<?php
// filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\app\Http\Controllers\AdminController.php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::user();
        
        if (!$user || !$user->canManageAdmins()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        // Query builder untuk admin
        $query = Admin::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'LIKE', "%{$search}%");
        }

        // Pagination dengan 10 data per halaman
        $admins = $query->orderBy('nama', 'asc')->paginate(10);

        return view('admin.Admin.indexAdmin', compact('admins'));
    }

    public function create()
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::user();
        
        if (!$user || !$user->canManageAdmins()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        return view('admin.Admin.createAdmin');
    }

    public function edit($id)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::user();
        
        if (!$user || !$user->canManageAdmins()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $admin = Admin::findOrFail($id);
        return view('admin.Admin.editAdmin', compact('admin'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::user();
        
        if (!$user || !$user->canManageAdmins()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:admin,username',
            'email' => 'required|email|max:255|unique:admin,email',
            'password' => 'required|string|min:8|confirmed',
            'nama' => 'required|string|max:100',
            'role' => 'required|in:admin,super_admin'
        ]);

        Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nama' => $request->nama,
            'role' => $request->role
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::user();
        
        if (!$user || !$user->canManageAdmins()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $admin = Admin::findOrFail($id);
        
        $request->validate([
            'username' => 'required|string|max:50|unique:admin,username,' . $id . ',id_admin',
            'email' => 'required|email|max:255|unique:admin,email,' . $id . ',id_admin',
            'password' => 'nullable|string|min:8|confirmed',
            'nama' => 'required|string|max:100',
            'role' => 'required|in:admin,super_admin'
        ]);

        $updateData = [
            'username' => $request->username,
            'email' => $request->email,
            'nama' => $request->nama,
            'role' => $request->role
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::user();
        
        if (!$user || !$user->canManageAdmins()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $adminToDelete = Admin::findOrFail($id);
        
        // Prevent deleting own account
        if ($adminToDelete->id_admin == Auth::user()->id_admin) {
            return redirect()->route('admin.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $adminToDelete->delete();
        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}