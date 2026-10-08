<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseApplicationRequest;
use App\Models\DealerSetting;
use App\Models\PurchaseApplication;
use App\Models\PurchaseApplicationDocument;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PurchaseApplicationController extends Controller
{
    public function create(Vehicle $vehicle): View
    {
        $customer = auth('customer')->user();

        return view('public.purchase-applications.create', [
            'vehicle' => $vehicle,
            'customer' => $customer,
            'settings' => DealerSetting::query()->first(),
        ]);
    }

    public function store(StorePurchaseApplicationRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $customer = $request->user('customer');
        $validated = $request->validated();
        $storedPaths = [];

        try {
            $application = DB::transaction(function () use ($request, $vehicle, $customer, $validated, &$storedPaths): PurchaseApplication {
                $application = PurchaseApplication::query()->create([
                    'application_number' => 'PA-'.now()->format('ymd').'-'.Str::upper(Str::random(8)),
                    'user_id' => $customer->getKey(),
                    'vehicle_id' => $vehicle->getKey(),
                    'vehicle_brand' => $vehicle->brand,
                    'vehicle_name' => $vehicle->name,
                    'vehicle_type' => $vehicle->type,
                    'vehicle_year' => $vehicle->year,
                    'vehicle_price' => $vehicle->price,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_whatsapp' => $validated['customer_whatsapp'],
                    'customer_address' => $validated['customer_address'],
                    'purchase_method' => $validated['purchase_method'],
                    'financing_preference' => $validated['financing_preference'] ?? null,
                    'customer_note' => $validated['customer_note'] ?? null,
                    'status' => 'pending',
                ]);

                foreach ($request->file('documents', []) as $index => $documentInput) {
                    $file = $documentInput['file'] ?? null;
                    if (! $file) {
                        continue;
                    }

                    $path = $file->store('purchase-applications/'.$application->getKey(), 'local');
                    if (! is_string($path)) {
                        throw new RuntimeException('Dokumen tidak dapat disimpan.');
                    }
                    $storedPaths[] = $path;

                    PurchaseApplicationDocument::query()->create([
                        'purchase_application_id' => $application->getKey(),
                        'document_type' => $validated['documents'][$index]['document_type'],
                        'document_label' => $validated['documents'][$index]['document_label'],
                        'file_path' => $path,
                        'original_name' => $this->safeOriginalName($file->getClientOriginalName()),
                        'mime_type' => (string) $file->getMimeType(),
                        'file_size' => (int) $file->getSize(),
                        'uploaded_at' => now(),
                    ]);
                }

                return $application;
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($storedPaths);

            throw $exception;
        }

        return to_route('purchase.show', $application)->with('success', 'Pengajuan pembelian berhasil dikirim. Tim dealer akan meninjau pengajuan Anda.');
    }

    public function whatsapp(PurchaseApplication $application): RedirectResponse
    {
        abort_unless((int) $application->user_id === (int) auth('customer')->id(), 404);

        $dealerSettings = DealerSetting::query()->first();
        $phone = preg_replace('/\D+/', '', (string) ($dealerSettings?->whatsapp ?? '')) ?? '';

        if ($phone === '') {
            return to_route('purchase.show', $application)->with('error', 'Nomor WhatsApp dealer belum tersedia.');
        }

        $message = sprintf(
            'Halo, saya %s ingin konsultasi pengajuan %s untuk %s %s. Status saat ini: %s.',
            $application->customer_name,
            $application->application_number,
            $application->vehicle_brand,
            $application->vehicle_name,
            $application->status,
        );

        return redirect()->away('https://wa.me/'.$phone.'?text='.rawurlencode($message));
    }

    private function safeOriginalName(string $name): string
    {
        $name = basename(str_replace('\\', '/', $name));
        $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name) ?? '';

        return mb_substr($name !== '' ? $name : 'dokumen', 0, 255);
    }
}
