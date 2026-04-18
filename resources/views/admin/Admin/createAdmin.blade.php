{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\Admin\createAdmin.blade.php --}}
@extends('layouts.template')

@section('title', 'Tambah Admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Admin</h4>
    </div>
    <div class="card-body">
        <form id="adminForm" action="{{ route('admin.store') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" 
                       value="{{ old('username') }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="nama">Nama Admin</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                       value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('adminForm').addEventListener('submit', function (e) {
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const password = passwordInput.value;
        const passwordConfirm = passwordConfirmInput.value;

        // Hapus pesan error sebelumnya
        const errorElement = document.querySelector('.password-error');
        if (errorElement) {
            errorElement.remove();
        }

        // Validasi panjang password
        if (password.length > 0 && password.length < 8) {
            e.preventDefault();

            const errorMessage = document.createElement('div');
            errorMessage.classList.add('text-danger', 'password-error', 'mt-1');
            errorMessage.textContent = 'Password harus memiliki minimal 8 karakter.';
            passwordInput.parentNode.appendChild(errorMessage);
            passwordInput.focus();
            return;
        }

        // Validasi konfirmasi password
        if (password !== passwordConfirm) {
            e.preventDefault();

            const errorMessage = document.createElement('div');
            errorMessage.classList.add('text-danger', 'password-error', 'mt-1');
            errorMessage.textContent = 'Konfirmasi password tidak cocok.';
            passwordConfirmInput.parentNode.appendChild(errorMessage);
            passwordConfirmInput.focus();
            return;
        }
    });
</script>
@endsection