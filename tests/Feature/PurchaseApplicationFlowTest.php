<?php

namespace Tests\Feature;

use App\Models\PurchaseApplication;
use App\Models\PurchaseApplicationDocument;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PurchaseApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_submit_a_vehicle_application_and_track_admin_status_update(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $vehicle = Vehicle::factory()->create([
            'brand' => 'Toyota',
            'name' => 'Fortuner',
            'slug' => 'fortuner',
            'year' => 2024,
            'price' => 650000000,
        ]);

        $this->get(route('vehicles.show', ['vehicle' => $vehicle->slug]))
            ->assertOk()
            ->assertSee('Ajukan Pembelian')
            ->assertSee(route('purchase.create', ['vehicle' => $vehicle->slug]), false)
            ->assertDontSee('Beli Sekarang');

        $this->get(route('purchase.create', ['vehicle' => $vehicle->slug]))
            ->assertRedirect(route('customer.login', ['vehicle' => $vehicle->slug]));

        $loginPage = $this->get(route('customer.login', ['vehicle' => $vehicle->slug]))->assertOk();
        $loginPage->assertSee(route('customer.login.store'), false)->assertSee('name="_token"', false);

        $registerPage = $this->get(route('customer.register'))->assertOk();
        $registerPage->assertSee(route('customer.register.store'), false)->assertSee('name="_token"', false);

        $password = 'CustomerPass123';
        $this->post(route('customer.register.store'), [
            'name' => 'Dina Pelanggan',
            'email' => 'dina@example.test',
            'phone' => '081234567890',
            'whatsapp' => '081234567890',
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertRedirect(route('purchase.create', ['vehicle' => $vehicle->slug]));

        $customer = User::query()->where('email', 'dina@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($customer, 'customer');
        $this->assertNotSame($password, $customer->password);
        $this->assertTrue(Hash::check($password, $customer->password));

        $this->post(route('customer.logout'))->assertRedirect(route('home'));
        $this->get(route('purchase.create', ['vehicle' => $vehicle->slug]))
            ->assertRedirect(route('customer.login', ['vehicle' => $vehicle->slug]));

        $this->post(route('customer.login.store'), [
            'email' => $customer->email,
            'password' => $password,
        ])->assertRedirect(route('purchase.create', ['vehicle' => $vehicle->slug]));

        $form = $this->get(route('purchase.create', ['vehicle' => $vehicle->slug]))->assertOk();
        $form->assertSee('data-vehicle-id="'.$vehicle->getKey().'"', false)
            ->assertSee('Toyota Fortuner')
            ->assertSee(route('purchase.store', ['vehicle' => $vehicle->slug]), false)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="_token"', false);

        $response = $this->post(route('purchase.store', ['vehicle' => $vehicle->slug]), [
            'customer_name' => 'Dina Pelanggan',
            'customer_email' => 'dina@example.test',
            'customer_phone' => '081234567890',
            'customer_whatsapp' => '081234567890',
            'customer_address' => 'Jl. Merdeka No. 10, Bandung',
            'purchase_method' => 'consultation',
            'customer_note' => 'Mohon hubungi sore hari.',
            'documents' => [
                [
                    'document_type' => 'identity',
                    'document_label' => 'Identitas pelanggan',
                    'file' => UploadedFile::fake()->create('identitas.pdf', 40, 'application/pdf'),
                ],
            ],
        ]);

        $application = PurchaseApplication::query()->where('user_id', $customer->getKey())->firstOrFail();
        $response->assertRedirect(route('purchase.show', $application));

        $this->assertDatabaseHas('purchase_applications', [
            'id' => $application->getKey(),
            'user_id' => $customer->getKey(),
            'vehicle_id' => $vehicle->getKey(),
            'vehicle_brand' => 'Toyota',
            'vehicle_name' => 'Fortuner',
            'vehicle_year' => 2024,
            'vehicle_price' => 650000000,
            'status' => 'pending',
        ]);

        $document = PurchaseApplicationDocument::query()
            ->where('purchase_application_id', $application->getKey())
            ->firstOrFail();
        Storage::disk('local')->assertExists($document->file_path);
        Storage::disk('public')->assertMissing($document->file_path);
        $this->get('/storage/'.$document->file_path)->assertForbidden();
        $this->assertStringStartsWith('purchase-applications/'.$application->getKey().'/', $document->file_path);
        $this->assertSame('identitas.pdf', $document->original_name);

        $this->get(route('purchase.index'))
            ->assertOk()
            ->assertSee($application->application_number)
            ->assertSee('Toyota Fortuner');
        $this->get(route('purchase.show', $application))
            ->assertOk()
            ->assertSee('Pending')
            ->assertSee(route('purchase.documents.download', [$application, $document]), false);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('admin.purchase-applications.index'))
            ->assertOk()
            ->assertSee($application->application_number);
        $this->actingAs($admin)
            ->get(route('admin.purchase-applications.show', $application))
            ->assertOk()
            ->assertSee('Identitas pelanggan')
            ->assertSee(route('admin.purchase-applications.documents.download', [$application, $document]), false);
        $this->actingAs($admin)
            ->put(route('admin.purchase-applications.status', $application), ['status' => 'reviewing'])
            ->assertRedirect(route('admin.purchase-applications.show', $application));

        $this->assertDatabaseHas('purchase_applications', [
            'id' => $application->getKey(),
            'status' => 'reviewing',
        ]);

        $this->actingAs($customer, 'customer')
            ->get(route('purchase.show', $application))
            ->assertOk()
            ->assertSee('Reviewing');
    }

    public function test_customer_cannot_view_or_download_another_customers_application_document(): void
    {
        Storage::fake('local');

        $owner = User::factory()->create(['role' => 'user']);
        $otherCustomer = User::factory()->create(['role' => 'user']);
        $application = PurchaseApplication::factory()->create(['user_id' => $otherCustomer->getKey()]);
        $document = PurchaseApplicationDocument::factory()->create([
            'purchase_application_id' => $application->getKey(),
            'file_path' => 'purchase-applications/'.$application->getKey().'/private.pdf',
        ]);
        Storage::disk('local')->put($document->file_path, 'private document');

        $this->actingAs($owner, 'customer')
            ->get(route('purchase.show', $application))
            ->assertNotFound();

        $this->actingAs($owner, 'customer')
            ->get(route('purchase.index'))
            ->assertOk()
            ->assertDontSee($application->application_number);

        $this->actingAs($owner, 'customer')
            ->get(route('purchase.documents.download', [$application, $document]))
            ->assertNotFound();

        $this->actingAs($otherCustomer, 'customer')
            ->get(route('purchase.documents.download', [$application, $document]))
            ->assertOk()
            ->assertHeader('content-disposition');
    }

    public function test_purchase_application_rejects_invalid_document_uploads(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($customer, 'customer')
            ->post(route('purchase.store', ['vehicle' => $vehicle->slug]), [
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => '081234567890',
                'customer_whatsapp' => '081234567890',
                'customer_address' => 'Jl. Merdeka No. 10',
                'purchase_method' => 'consultation',
                'documents' => [
                    [
                        'document_type' => 'identity',
                        'document_label' => 'Berkas tidak valid',
                        'file' => UploadedFile::fake()->create('malware.exe', 10, 'application/octet-stream'),
                    ],
                ],
            ])
            ->assertSessionHasErrors('documents.0.file');

        $this->assertDatabaseCount('purchase_applications', 0);
        $this->assertDatabaseCount('purchase_application_documents', 0);
    }

    public function test_non_admin_customer_cannot_view_admin_purchase_applications(): void
    {
        $customer = User::factory()->create(['role' => 'user']);

        $this->actingAs($customer)
            ->get(route('admin.purchase-applications.index'))
            ->assertForbidden();
    }

    public function test_admin_credentials_cannot_authenticate_through_the_customer_login_route(): void
    {
        $password = 'AdministratorPass123';
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->post(route('customer.login.store'), [
            'email' => $admin->email,
            'password' => $password,
        ])->assertSessionHasErrors('email');

        $this->assertGuest('customer');
    }

    public function test_customer_write_routes_keep_laravels_csrf_middleware(): void
    {
        foreach (['customer.register.store', 'customer.login.store', 'purchase.store', 'customer.logout'] as $routeName) {
            $route = app('router')->getRoutes()->getByName($routeName);

            $this->assertNotNull($route);
            $this->assertContains('web', $route->gatherMiddleware());
        }

        $this->assertContains(
            ValidateCsrfToken::class,
            app(Kernel::class)->getMiddlewareGroups()['web']
        );
    }
}
