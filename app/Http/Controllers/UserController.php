<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', ['users' => User::orderBy('name')->paginate(15)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', 'unique:users,email'], 'password' => ['required', 'string', 'min:8', 'confirmed'], 'role' => ['required', Rule::in(['admin', 'user'])]]);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user()) || ($user->isAdmin() && User::where('role', 'admin')->count() <= 1)) {
            return back()->withErrors(['user' => 'Akun sendiri atau admin terakhir tidak dapat dihapus.']);
        }
        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
