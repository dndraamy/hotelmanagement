<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Regenerate session ID setelah login (mencegah session fixation attack)
        $request->session()->regenerate();

        $roleName = $request->user()->roles->first()?->name;
        $redirectRoute = match ($roleName) {
            'Manajer Hotel' => 'dashboard.manager',
            'Resepsionis' => 'dashboard.receptionist',
            'Staf Keuangan' => 'dashboard.finance',
            'Petugas Restoran' => 'dashboard.restaurant',
            'Petugas Kebersihan' => 'dashboard.cleaning',
            'Staf HRD' => 'dashboard.hrd',
            'Karyawan' => 'dashboard.employee',
            'Staf Gudang' => 'dashboard.warehouse',
            'Super Admin' => 'dashboard.admin',
            default => 'dashboard',
        };
        return redirect()->intended(route($redirectRoute, absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Hapus semua data session
        $request->session()->invalidate();

        // Buat CSRF token baru
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
