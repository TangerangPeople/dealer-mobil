<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_gallery(): void
    {
        $this->get(route('admin.galleries.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_receives_forbidden_response_for_gallery(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.galleries.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_gallery_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.galleries.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.galleries.create'))
            ->assertOk();
    }

    public function test_admin_can_store_gallery(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.galleries.store'), [
                'title' => 'Showroom Exterior',
                'gallery_type' => 'showroom',
                'image' => UploadedFile::fake()->image('showroom.jpg', 1200, 800),
                'alt_text' => 'Foto showroom mobil',
                'caption' => 'Area pameran utama dealer.',
                'is_published' => true,
                'sort_order' => 2,
            ])
            ->assertRedirect(route('admin.galleries.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('galleries', [
            'title' => 'Showroom Exterior',
            'gallery_type' => 'showroom',
            'alt_text' => 'Foto showroom mobil',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $gallery = Gallery::query()->first();
        $this->assertNotNull($gallery);
        $this->assertStringStartsWith('galleries/', $gallery->image_path);
        Storage::disk('public')->assertExists($gallery->image_path);
    }

    public function test_gallery_form_validation_rejects_invalid_input(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.galleries.create'))
            ->post(route('admin.galleries.store'), [
                'title' => '',
                'gallery_type' => 'invalid-type',
                'alt_text' => '',
                'caption' => 'Diterima',
                'is_published' => 'maybe',
                'sort_order' => -1,
            ])
            ->assertRedirect(route('admin.galleries.create'))
            ->assertSessionHasErrors(['title', 'gallery_type', 'alt_text', 'sort_order', 'image']);

        $this->actingAs($admin)
            ->from(route('admin.galleries.create'))
            ->post(route('admin.galleries.store'), [
                'title' => 'Valid Title',
                'gallery_type' => 'vehicle',
                'image' => UploadedFile::fake()->create('bad.txt', 100, 'text/plain'),
                'alt_text' => 'Alt text aman',
                'is_published' => true,
                'sort_order' => 0,
            ])
            ->assertSessionHasErrors('image');
    }

    public function test_admin_can_update_gallery_metadata_and_replace_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $gallery = Gallery::factory()->create([
            'title' => 'Old Gallery',
            'gallery_type' => 'other',
            'image_path' => 'galleries/old.jpg',
            'alt_text' => 'Old alt',
            'caption' => 'Old caption',
            'is_published' => false,
            'sort_order' => 3,
        ]);

        Storage::disk('public')->put($gallery->image_path, 'old-content');

        $this->actingAs($admin)
            ->put(route('admin.galleries.update', $gallery), [
                'title' => 'Updated Gallery',
                'gallery_type' => 'event',
                'alt_text' => 'New alt text',
                'caption' => 'Updated caption',
                'is_published' => true,
                'sort_order' => 4,
            ])
            ->assertRedirect(route('admin.galleries.index'))
            ->assertSessionHas('success');

        $gallery->refresh();
        $this->assertSame('Updated Gallery', $gallery->title);
        $this->assertSame('event', $gallery->gallery_type);
        $this->assertSame('New alt text', $gallery->alt_text);
        $this->assertTrue($gallery->is_published);
        $this->assertSame(4, $gallery->sort_order);

        $newImage = UploadedFile::fake()->image('new-gallery.jpg', 1200, 800);

        $this->actingAs($admin)
            ->put(route('admin.galleries.update', $gallery), [
                'title' => 'Updated Gallery',
                'gallery_type' => 'event',
                'alt_text' => 'New alt text',
                'caption' => 'Updated caption',
                'is_published' => true,
                'sort_order' => 4,
                'image' => $newImage,
            ])
            ->assertRedirect(route('admin.galleries.index'))
            ->assertSessionHas('success');

        $gallery->refresh();
        $this->assertNotSame('galleries/old.jpg', $gallery->image_path);
        $this->assertStringStartsWith('galleries/', $gallery->image_path);
        Storage::disk('public')->assertMissing('galleries/old.jpg');
        Storage::disk('public')->assertExists($gallery->image_path);
    }

    public function test_admin_can_soft_delete_gallery_and_hidden_from_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $gallery = Gallery::factory()->create([
            'title' => 'Delete Me',
            'image_path' => 'galleries/delete-me.jpg',
            'alt_text' => 'Delete me',
            'is_published' => true,
        ]);

        Storage::fake('public');
        Storage::disk('public')->put($gallery->image_path, 'content');

        $this->actingAs($admin)
            ->delete(route('admin.galleries.destroy', $gallery))
            ->assertRedirect(route('admin.galleries.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('galleries', ['id' => $gallery->id]);

        $this->actingAs($admin)
            ->get(route('admin.galleries.index'))
            ->assertOk()
            ->assertDontSee('Delete Me');
    }

    public function test_gallery_index_search_and_filters_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Gallery::factory()->create([
            'title' => 'Mobil Baru',
            'gallery_type' => 'vehicle',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        Gallery::factory()->create([
            'title' => 'Showroom Dealer',
            'gallery_type' => 'showroom',
            'is_published' => false,
            'sort_order' => 2,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.galleries.index', ['search' => 'mobil']))
            ->assertOk()
            ->assertSee('Mobil Baru')
            ->assertDontSee('Showroom Dealer');

        $this->actingAs($admin)
            ->get(route('admin.galleries.index', ['gallery_type' => 'showroom']))
            ->assertOk()
            ->assertSee('Showroom Dealer')
            ->assertDontSee('Mobil Baru');

        $this->actingAs($admin)
            ->get(route('admin.galleries.index', ['status' => 'published']))
            ->assertOk()
            ->assertSee('Mobil Baru')
            ->assertDontSee('Showroom Dealer');

        $this->actingAs($admin)
            ->get(route('admin.galleries.index', ['status' => 'unpublished']))
            ->assertOk()
            ->assertSee('Showroom Dealer')
            ->assertDontSee('Mobil Baru');
    }

    public function test_gallery_pagination_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Gallery::factory()->count(15)->create();

        $this->actingAs($admin)
            ->get(route('admin.galleries.index'))
            ->assertOk()
            ->assertSee('2');
    }

    public function test_gallery_model_uses_expected_schema_conventions(): void
    {
        $gallery = new Gallery;

        $this->assertContains('title', $gallery->getFillable());
        $this->assertContains('gallery_type', $gallery->getFillable());
        $this->assertContains('image_path', $gallery->getFillable());
        $this->assertContains('alt_text', $gallery->getFillable());
        $this->assertContains('caption', $gallery->getFillable());
        $this->assertContains('is_published', $gallery->getFillable());
        $this->assertContains('sort_order', $gallery->getFillable());
        $this->assertTrue(method_exists($gallery, 'delete'));
    }
}
