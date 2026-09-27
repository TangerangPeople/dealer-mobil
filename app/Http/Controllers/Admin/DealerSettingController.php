<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealerSettingRequest;
use App\Models\DealerSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DealerSettingController extends Controller
{
    public function index(): View
    {
        $setting = $this->ensureSingleton();

        return view('admin.dealer-settings.edit', compact('setting'));
    }

    public function update(DealerSettingRequest $request): RedirectResponse
    {
        $setting = $this->ensureSingleton();
        $data = $request->validated();

        $newLogoPath = null;
        if ($request->hasFile('logo')) {
            $newLogoPath = $this->storeImage($request->file('logo'), 'dealer/logo', $setting->logo_path);
            $data['logo_path'] = $newLogoPath;
        } elseif ($request->boolean('remove_logo')) {
            $data['logo_path'] = null;
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
        }

        $newHeroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $newHeroImagePath = $this->storeImage($request->file('hero_image'), 'dealer/hero', $setting->hero_image_path);
            $data['hero_image_path'] = $newHeroImagePath;
        } elseif ($request->boolean('remove_hero_image')) {
            $data['hero_image_path'] = null;
            if ($setting->hero_image_path && Storage::disk('public')->exists($setting->hero_image_path)) {
                Storage::disk('public')->delete($setting->hero_image_path);
            }
        }

        try {
            $setting->fill($data);
            $setting->save();
        } catch (\Throwable $exception) {
            if ($newLogoPath !== null && Storage::disk('public')->exists($newLogoPath)) {
                Storage::disk('public')->delete($newLogoPath);
            }

            if ($newHeroImagePath !== null && Storage::disk('public')->exists($newHeroImagePath)) {
                Storage::disk('public')->delete($newHeroImagePath);
            }

            throw $exception;
        }

        return redirect()->route('admin.dealer-settings.index')->with('success', 'Informasi dealer berhasil diperbarui.');
    }

    protected function ensureSingleton(): DealerSetting
    {
        return DealerSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'dealer_name' => 'Dealer Development',
                'address' => 'Alamat development belum dikonfigurasi.',
                'phone' => '620000000000',
                'email' => 'dealer@example.test',
                'whatsapp' => '620000000000',
            ]
        );
    }

    protected function storeImage(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $path = $file->storeAs($directory, Str::uuid()->toString().'.'.$file->getClientOriginalExtension(), 'public');

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $path;
    }
}
