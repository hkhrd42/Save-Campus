<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,staff'],
        ];

        // Add admin code validation for staff registration
        if ($request->role === 'staff') {
            $validationRules['admin_code'] = ['required', 'string'];
        }

        $request->validate($validationRules);

        // Verify admin code for staff registration
        if ($request->role === 'staff') {
            $adminCode = \DB::table('admin_codes')
                ->where('code', $request->admin_code)
                ->where('is_used', false)
                ->first();

            if (!$adminCode) {
                return back()->withErrors([
                    'admin_code' => 'Invalid or already used admin code.',
                ])->withInput();
            }

            // Mark the code as used
            \DB::table('admin_codes')
                ->where('id', $adminCode->id)
                ->update([
                    'is_used' => true,
                    'updated_at' => now(),
                ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Update the admin code with user reference
        if ($request->role === 'staff' && isset($adminCode)) {
            \DB::table('admin_codes')
                ->where('id', $adminCode->id)
                ->update(['used_by' => $user->id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
