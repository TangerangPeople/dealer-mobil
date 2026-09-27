<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_sales(): void
    {
        $this->get(route('admin.sales.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_receives_forbidden_response_for_sales(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.sales.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_sales_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.sales.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.sales.create'))
            ->assertOk();
    }

    public function test_admin_can_store_sale(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.sales.store'), [
                'name' => 'Ayu Pratiwi',
                'phone' => '081234567890',
                'whatsapp' => '081234567890',
                'position' => 'Sales Consultant',
                'is_active' => true,
                'sort_order' => 2,
            ])
            ->assertRedirect(route('admin.sales.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('sales', [
            'name' => 'Ayu Pratiwi',
            'phone' => '6281234567890',
            'whatsapp' => '6281234567890',
            'position' => 'Sales Consultant',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }

    public function test_sale_name_and_phone_validation_are_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.sales.create'))
            ->post(route('admin.sales.store'), [
                'name' => '',
                'phone' => '',
                'whatsapp' => '',
                'position' => null,
                'is_active' => true,
                'sort_order' => -1,
            ])
            ->assertRedirect(route('admin.sales.create'))
            ->assertSessionHasErrors(['name', 'phone', 'whatsapp', 'sort_order']);
    }

    public function test_whatsapp_number_is_normalized(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.sales.store'), [
                'name' => 'Dina Putri',
                'phone' => '0812-3456-7890',
                'whatsapp' => '+62 812-3456-7890',
                'position' => 'Senior Sales',
                'is_active' => true,
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.sales.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('sales', [
            'name' => 'Dina Putri',
            'phone' => '6281234567890',
            'whatsapp' => '6281234567890',
        ]);
    }

    public function test_sale_index_filters_and_search_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Sale::factory()->create([
            'name' => 'Rina Sales',
            'phone' => '6281111111111',
            'whatsapp' => '6281111111111',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Sale::factory()->create([
            'name' => 'Budi Sales',
            'phone' => '6282222222222',
            'whatsapp' => '6282222222222',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.sales.index', ['search' => 'rina']))
            ->assertOk()
            ->assertSee('Rina Sales')
            ->assertDontSee('Budi Sales');

        $this->actingAs($admin)
            ->get(route('admin.sales.index', ['search' => '6282222222222']))
            ->assertOk()
            ->assertSee('Budi Sales')
            ->assertDontSee('Rina Sales');

        $this->actingAs($admin)
            ->get(route('admin.sales.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Inactive')
            ->assertSee('Budi Sales');
    }

    public function test_admin_can_update_sale(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sale = Sale::factory()->create([
            'name' => 'Old Name',
            'phone' => '6289999999999',
            'whatsapp' => '6289999999999',
            'is_active' => true,
            'position' => 'Junior Sales',
            'sort_order' => 9,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.sales.update', $sale), [
                'name' => 'Updated Name',
                'phone' => '081234567890',
                'whatsapp' => '081234567890',
                'position' => 'Senior Sales',
                'is_active' => false,
                'sort_order' => 5,
            ])
            ->assertRedirect(route('admin.sales.index'))
            ->assertSessionHas('success');

        $sale->refresh();

        $this->assertSame('Updated Name', $sale->name);
        $this->assertSame('6281234567890', $sale->phone);
        $this->assertSame('6281234567890', $sale->whatsapp);
        $this->assertFalse($sale->is_active);
        $this->assertSame(5, $sale->sort_order);
    }

    public function test_admin_can_soft_delete_sale_and_deleted_sales_do_not_appear_in_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sale = Sale::factory()->create([
            'name' => 'To Delete',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.sales.destroy', $sale))
            ->assertRedirect(route('admin.sales.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('sales', ['id' => $sale->id]);

        $this->actingAs($admin)
            ->get(route('admin.sales.index'))
            ->assertOk()
            ->assertDontSee('To Delete');
    }

    public function test_sale_model_uses_required_schema_conventions(): void
    {
        $sale = new Sale;

        $this->assertContains('name', $sale->getFillable());
        $this->assertContains('phone', $sale->getFillable());
        $this->assertContains('whatsapp', $sale->getFillable());
        $this->assertContains('position', $sale->getFillable());
        $this->assertContains('is_active', $sale->getFillable());
        $this->assertContains('sort_order', $sale->getFillable());
        $this->assertTrue(method_exists($sale, 'delete'));
    }
}
