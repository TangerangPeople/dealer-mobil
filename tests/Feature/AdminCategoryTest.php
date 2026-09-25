<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_categories(): void
    {
        $this->get(route('admin.categories.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_receives_forbidden_response_for_categories(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.categories.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_category_index_and_create_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSeeText('Kategori kendaraan');

        $this->actingAs($admin)
            ->get(route('admin.categories.create'))
            ->assertOk()
            ->assertSeeText('Tambah kategori');
    }

    public function test_admin_can_create_category_with_a_slug_generated_from_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Mobil Keluarga',
                'description' => 'Kategori kendaraan untuk keluarga.',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Mobil Keluarga',
            'slug' => 'mobil-keluarga',
            'is_active' => true,
        ]);
    }

    public function test_category_name_is_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), ['name' => ''])
            ->assertRedirect(route('admin.categories.create'))
            ->assertSessionHasErrors('name');
    }

    public function test_category_slug_must_be_unique_even_when_it_is_provided_manually(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->create(['slug' => 'kendaraan-keluarga']);

        $this->actingAs($admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), [
                'name' => 'Kategori Lain',
                'slug' => 'Kendaraan Keluarga',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.categories.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_category_without_failing_its_own_unique_validation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create([
            'name' => 'MPV',
            'slug' => 'mpv',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'MPV Premium',
                'slug' => 'mpv',
                'description' => 'Kategori MPV premium.',
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'MPV Premium',
            'slug' => 'mpv',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_soft_delete_an_unused_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_category_with_vehicles_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Vehicle::factory()->for($category)->create();

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'deleted_at' => null]);
    }

    public function test_category_search_and_status_filter_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $active = Category::factory()->create([
            'name' => 'Crossover Pilihan',
            'slug' => 'crossover-pilihan',
            'is_active' => true,
        ]);
        $inactive = Category::factory()->create([
            'name' => 'Roadster Arsip',
            'slug' => 'roadster-arsip',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.categories.index', ['search' => 'crossover']))
            ->assertOk()
            ->assertSeeText($active->name)
            ->assertDontSeeText($inactive->name);

        $this->actingAs($admin)
            ->get(route('admin.categories.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSeeText($inactive->name)
            ->assertDontSeeText($active->name);
    }

    public function test_category_pagination_does_not_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->count(11)->create();

        $this->actingAs($admin)
            ->get(route('admin.categories.index', ['page' => 2]))
            ->assertOk()
            ->assertSeeText('Berikutnya');
    }
}
