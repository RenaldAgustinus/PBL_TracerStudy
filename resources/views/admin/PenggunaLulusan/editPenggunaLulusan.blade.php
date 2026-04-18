@extends('layouts.template')

@section('title', 'Edit Pengguna Lulusan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Pengguna Lulusan</h4>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('penggunaLulusan.update', $penggunaLulusan->id_pengguna_lulusan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama_atasan">Nama Atasan</label>
                <input type="text" name="nama_atasan" id="nama_atasan" class="form-control" value="{{ old('nama_atasan', $penggunaLulusan->nama_atasan) }}" required>
            </div>
            <div class="form-group">
                <label for="jabatan_atasan">Jabatan Atasan</label>
                <input type="text" name="jabatan_atasan" id="jabatan_atasan" class="form-control" value="{{ old('jabatan_atasan', $penggunaLulusan->jabatan_atasan) }}" required>
            </div>
            <div class="form-group">
                <label for="email_atasan">Email Atasan</label>
                <input type="email" name="email_atasan" id="email_atasan" class="form-control" value="{{ old('email_atasan', $penggunaLulusan->email_atasan) }}">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('penggunaLulusan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection