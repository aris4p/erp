@extends('layouts.erp')
@section('title', $employee->exists ? 'Ubah Pegawai' : 'Tambah Pegawai')
@section('content')
<h1 class="mb-6 text-2xl font-semibold">{{ $employee->exists ? 'Ubah Pegawai' : 'Tambah Pegawai' }}</h1>
<form method="POST" action="{{ $employee->exists ? route('employees.update', $employee) : route('employees.store') }}" class="rounded-2xl border border-gray-200 bg-white p-6">@csrf @if($employee->exists) @method('PUT') @endif
<div class="grid gap-5 md:grid-cols-2">
@foreach(['employee_number' => 'NIK Pegawai', 'name' => 'Nama', 'phone' => 'Nomor telepon', 'email' => 'Email', 'position' => 'Jabatan', 'department' => 'Departemen'] as $field => $label)
<label class="block">{{ $label }}<input name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}" value="{{ old($field, $employee->$field) }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"></label>
@endforeach
<label>Jenis kelamin<select name="gender" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"><option value="">Pilih</option>@foreach(['Laki-laki', 'Perempuan'] as $gender)<option value="{{ $gender }}" @selected(old('gender', $employee->gender) === $gender)>{{ $gender }}</option>@endforeach</select></label>
<label>Tanggal lahir<input type="date" name="birth_date" value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"></label>
<label>Tanggal masuk<input type="date" name="joined_at" value="{{ old('joined_at', $employee->joined_at?->format('Y-m-d')) }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"></label>
<label>Status<select name="is_active" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"><option value="1" @selected((string) old('is_active', $employee->is_active ?? 1) === '1')>Aktif</option><option value="0" @selected((string) old('is_active', $employee->is_active ?? 1) === '0')>Tidak aktif</option></select></label>
<label class="md:col-span-2">Alamat<textarea name="address" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3">{{ old('address', $employee->address) }}</textarea></label>
</div><div class="mt-6 flex gap-4"><button class="rounded-lg bg-brand-500 px-5 py-3 text-white">Simpan</button><a class="rounded-lg border border-gray-300 px-5 py-3" href="{{ route('employees.index') }}">Batal</a></div></form>
@endsection
