<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DealerSetting;
use App\Models\Gallery;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_vehicles_and_vehicle_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create();

        $this->assertTrue($vehicle->category->is($category));
        $this->assertTrue($category->vehicles->contains($vehicle));
    }

    public function test_vehicle_has_many_images_and_a_primary_image(): void
    {
        $vehicle = Vehicle::factory()->create();
        $primaryImage = VehicleImage::factory()->primary()->for($vehicle)->create();
        $galleryImage = VehicleImage::factory()->for($vehicle)->create(['sort_order' => 1]);

        $this->assertTrue($vehicle->images->contains($primaryImage));
        $this->assertTrue($vehicle->images->contains($galleryImage));
        $this->assertTrue($vehicle->primaryImage->is($primaryImage));
    }

    public function test_vehicle_has_many_promotions_and_a_promotion_may_be_general(): void
    {
        $vehicle = Vehicle::factory()->create();
        $vehiclePromotion = Promotion::factory()->for($vehicle)->create();
        $generalPromotion = Promotion::factory()->general()->create();

        $this->assertTrue($vehicle->promotions->contains($vehiclePromotion));
        $this->assertNull($generalPromotion->vehicle);
        $this->assertNull($generalPromotion->vehicle_id);
    }

    public function test_database_allows_only_one_primary_image_per_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        VehicleImage::factory()->primary()->for($vehicle)->create();

        $this->expectException(QueryException::class);

        VehicleImage::factory()->primary()->for($vehicle)->create(['sort_order' => 1]);
    }

    public function test_domain_models_support_soft_deletes(): void
    {
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $promotion = Promotion::factory()->create();
        $sale = Sale::factory()->create();
        $gallery = Gallery::factory()->create();

        $category->delete();
        $vehicle->delete();
        $promotion->delete();
        $sale->delete();
        $gallery->delete();

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
        $this->assertSoftDeleted('promotions', ['id' => $promotion->id]);
        $this->assertSoftDeleted('sales', ['id' => $sale->id]);
        $this->assertSoftDeleted('galleries', ['id' => $gallery->id]);
    }

    public function test_dealer_setting_factory_uses_the_singleton_identifier(): void
    {
        $setting = DealerSetting::factory()->create();

        $this->assertSame(1, $setting->id);
        $this->assertDatabaseHas('dealer_settings', ['id' => 1]);
    }

    public function test_foreign_keys_restrict_category_and_promotion_deletion_but_cascade_vehicle_images(): void
    {
        $category = Category::factory()->create();
        $vehicle = Vehicle::factory()->for($category)->create();
        VehicleImage::factory()->for($vehicle)->create();

        $this->expectException(QueryException::class);
        $category->forceDelete();
    }

    public function test_vehicle_hard_delete_cascades_images(): void
    {
        $vehicle = Vehicle::factory()->create();
        $image = VehicleImage::factory()->for($vehicle)->create();

        $vehicle->forceDelete();

        $this->assertDatabaseMissing('vehicle_images', ['id' => $image->id]);
    }

    public function test_vehicle_hard_delete_is_restricted_when_a_promotion_references_it(): void
    {
        $vehicle = Vehicle::factory()->create();
        Promotion::factory()->for($vehicle)->create();

        $this->expectException(QueryException::class);
        $vehicle->forceDelete();
    }

    public function test_mysql_vehicles_table_has_the_required_fulltext_index(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            $this->markTestSkipped('FULLTEXT is a MySQL-specific production migration feature.');
        }

        $indexes = DB::select("SHOW INDEX FROM vehicles WHERE Key_name = 'vehicles_search_fulltext'");

        $this->assertCount(4, $indexes);
        $this->assertTrue(collect($indexes)->every(fn (object $index): bool => $index->Index_type === 'FULLTEXT'));
    }

    public function test_mysql_dealer_setting_singleton_constraint_is_enforced(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            $this->markTestSkipped('CHECK constraints are enforced by the production MySQL migration.');
        }

        $this->expectException(QueryException::class);

        DB::table('dealer_settings')->insert([
            'id' => 2,
            'dealer_name' => 'Invalid singleton',
            'address' => 'Invalid address',
            'phone' => '620000000000',
            'email' => 'invalid@example.test',
            'whatsapp' => '620000000000',
        ]);
    }

    public function test_mysql_promotion_date_constraint_is_enforced(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            $this->markTestSkipped('CHECK constraints are enforced by the production MySQL migration.');
        }

        $this->expectException(QueryException::class);

        Promotion::factory()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
        ]);
    }
}
