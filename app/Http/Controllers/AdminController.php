<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin (kelola akun pengguna).
     */
    public function index(Request $request): View|JsonResponse
    {
        $users = User::query()->latest()->paginate(20);

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        return view('admin.index', ['users' => $users]);
    }

    /**
     * Tambah akun pengguna baru (khusus admin).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if ($request->expectsJson()) {
            return response()->json($user, 201);
        }

        return back()->with('status', 'Akun pengguna berhasil ditambahkan.');
    }

    /**
     * Hapus akun pengguna (khusus admin).
     */
    public function destroy(Request $request, User $user): RedirectResponse|JsonResponse
    {
        if ($request->user()?->is($user)) {
            abort(403, 'Admin tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Akun pengguna berhasil dihapus.']);
        }

        return back()->with('status', 'Akun pengguna berhasil dihapus.');
    }
}
