<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user !== null) {
            $destination = match ($user->role) {
                'admin' => 'admin.dashboard',
                'owner' => 'owner.dashboard',
                'sales' => 'sales.dashboard',
                'mechanic' => 'mechanic.dashboard',
                default => null,
            };

            if ($destination !== null) {
                return redirect()->route($destination);
            }

            Auth::guard('web')->logout();
        }

        return view('admin.auth.login');
    }

    public function store(AdminLoginRequest $request): RedirectResponse
    {
        if (! Auth::guard('web')->attempt($request->validated())) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::guard('web')->user();

        if ($user === null || ! $user->isStaff()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $destination = match ($user->role) {
            'admin' => 'admin.dashboard',
            'owner' => 'owner.dashboard',
            'sales' => 'sales.dashboard',
            'mechanic' => 'mechanic.dashboard',
            default => null,
        };

        if ($destination === null) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route($destination);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
