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
        return view('usermanagement.createuser');
    }

    public function show(): View
    {
        return view('usermanagement.listusers', [
            'users' => User::where('role', '!=', 'superadmin')->get(),
        ]);
    }
    public function edit(User $user): View
    {
        return view('usermanagement.createuser', compact('user'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'contact' => ['required', 'string', 'max:15'],
            'role' => ['required', 'in:user,admin'],
            'address' => ['required', 'string', 'max:500'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->role === 'superadmin' && User::where('role', 'superadmin')->exists()) {
            return back()->withErrors(['role' => 'A superadmin already exists. Only one is allowed.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'contact' => $request->contact,
            'role' => $request->role,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return redirect()
            ->route('register')
            ->with('useradded', 'User created successfully!');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'contact' => 'required|string|max:15',
            'role' => 'required|in:user,admin',
            'address' => 'required|string|max:500',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'username', 'contact', 'role', 'address', 'email'));

        return redirect()->route('users.list')->with('success', 'User updated successfully!');
    }

    public function showChangePasswordForm(User $user)
    {
        return view('usermanagement.changepassword', compact('user'));
    }
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.list')
            ->with('useradded', 'Password updated successfully!');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Cannot delete superadmin.');
        }

        $user->delete();

        return redirect()->route('users.list')->with('success', 'User deleted successfully!');
    }


    public function toggleAdmin(User $user): RedirectResponse
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Cannot modify superadmin.');
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        return back()->with('success', 'User role updated successfully!');
    }
}