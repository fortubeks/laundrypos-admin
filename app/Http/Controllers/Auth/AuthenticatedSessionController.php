<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $status = session('status');
        return view('material.auth.login')->with(compact('status'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $candidateUser = User::where('email', $request->email)->first();

        if (! $candidateUser || ! $this->canAccessPortal($candidateUser)) {
            return back()->with('status', 'Unauthorized');
        }

        $request->authenticate();

        if (! Auth::user() || ! $this->canAccessPortal(Auth::user())) {
            Auth::guard('web')->logout();
            return back()->with('status', 'Unauthorized');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function canAccessPortal(User $user): bool
    {
        if (Schema::hasColumn('users', 'role')) {
            return in_array((string) $user->role, ['Super Admin', 'Admin'], true);
        }

        $rawEmails     = (string) env('ADMIN_PORTAL_EMAIL', 'admin@mylaundrypos.com');
        $allowedEmails = array_values(array_filter(array_map('trim', explode(',', $rawEmails))));

        return in_array((string) $user->email, $allowedEmails, true);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
