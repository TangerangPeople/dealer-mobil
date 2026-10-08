<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerLoginRequest;
use App\Http\Requests\CustomerRegistrationRequest;
use App\Models\DealerSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('customer')->check()) {
            return to_route('customer.account');
        }

        return view('customer.auth.login', ['settings' => DealerSetting::query()->first()]);
    }

    public function register(): View|RedirectResponse
    {
        if (Auth::guard('customer')->check()) {
            return to_route('customer.account');
        }

        return view('customer.auth.register', ['settings' => DealerSetting::query()->first()]);
    }

    public function storeRegistration(CustomerRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $customer = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'],
            'password' => $validated['password'],
            'role' => 'user',
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        $redirectTo = $this->purchaseRedirectTarget($request);

        return redirect()->intended($redirectTo)->with('success', 'Akun berhasil dibuat.');
    }

    public function storeLogin(CustomerLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::guard('customer')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => 'user',
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak valid.',
            ]);
        }

        $request->session()->regenerate();

        $redirectTo = $this->purchaseRedirectTarget($request);

        return redirect()->intended($redirectTo)->with('success', 'Login berhasil.');
    }

    protected function purchaseRedirectTarget(Request $request): string
    {
        $vehicle = $request->query('vehicle')
            ?: session('selected_vehicle_slug')
            ?: ($request->route('vehicle') instanceof \Illuminate\Database\Eloquent\Model ? $request->route('vehicle')->slug : $request->route('vehicle'));

        if (is_string($vehicle) && trim($vehicle) !== '') {
            return route('purchase.create', ['vehicle' => $vehicle]);
        }

        return route('customer.account');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
