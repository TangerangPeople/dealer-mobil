<?php

namespace App\Models;

use Database\Factories\ServiceBookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceBooking extends Model
{
    /** @use HasFactory<ServiceBookingFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'phone',
        'whatsapp',
        'vehicle_id',
        'vehicle_plate',
        'service_package_id',
        'service_type',
        'preferred_date',
        'preferred_time',
        'complaint',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_id' => 'integer',
            'service_package_id' => 'integer',
            'mechanic_id' => 'integer',
            'preferred_date' => 'date',
        ];
    }

    /**
     * Kendaraan yang terkait dengan booking.
     *
     * @return BelongsTo<Vehicle, $this>
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Paket servis yang dipilih pelanggan.
     *
     * @return BelongsTo<ServicePackage, $this>
     */
    public function servicePackage(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class);
    }

    /**
     * Mekanik yang ditugaskan mengerjakan booking.
     *
     * @return BelongsTo<User, $this>
     */
    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    /**
     * Daftar pemakaian sparepart untuk booking ini.
     *
     * @return HasMany<ServiceBookingSparePart, $this>
     */
    public function sparePartUsages(): HasMany
    {
        return $this->hasMany(
            ServiceBookingSparePart::class,
            'service_booking_id'
        );
    }

    /**
     * Membuat tautan WhatsApp untuk konfirmasi booking.
     */
    public function whatsappLink(): string
    {
        $dealer = DealerSetting::query()->first();

        $number = preg_replace(
            '/\D+/',
            '',
            (string) ($dealer?->whatsapp ?? $dealer?->phone ?? '')
        );

        if ($number === '') {
            return '#';
        }

        $message = sprintf(
            'Halo Dealer,%sSaya ingin konfirmasi booking service:%sNama: %s%sNomor polisi: %s%sTanggal: %s%sJam: %s%sJenis servis: %s',
            PHP_EOL,
            PHP_EOL,
            $this->customer_name,
            PHP_EOL,
            $this->vehicle_plate,
            PHP_EOL,
            $this->preferred_date->format('d M Y'),
            PHP_EOL,
            $this->preferred_time,
            PHP_EOL,
            $this->service_type,
        );

        return 'https://wa.me/'.$number.'?text='.urlencode($message);
    }
}
