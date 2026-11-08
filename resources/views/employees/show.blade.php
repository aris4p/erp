@extends('layouts.erp')
@section('title', 'Detail Pegawai')
@section('content')
<div class="mb-6 flex justify-between"><h1 class="text-2xl font-semibold">Detail Pegawai</h1><a class="text-brand-500" href="{{ route('employees.index') }}">Kembali</a></div>
<div class="rounded-2xl border border-gray-200 bg-white p-6"><dl class="grid gap-5 md:grid-cols-2">
@foreach(['NIK Pegawai' => $employee->employee_number, 'Nama' => $employee->name, 'Jenis kelamin' => $employee->gender, 'Tanggal lahir' => $employee->birth_date->format('d-m-Y'), 'Alamat' => $employee->address, 'Nomor telepon' => $employee->phone, 'Email' => $employee->email, 'Jabatan' => $employee->position, 'Departemen' => $employee->department, 'Tanggal masuk' => $employee->joined_at->format('d-m-Y'), 'Status' => $employee->is_active ? 'Aktif' : 'Tidak aktif'] as $label => $value)<div><dt class="text-sm text-gray-500">{{ $label }}</dt><dd class="mt-1 font-medium">{{ $value }}</dd></div>@endforeach
</dl>@can('manage-employees')<a class="mt-6 inline-block rounded-lg bg-brand-500 px-5 py-3 text-white" href="{{ route('employees.edit', $employee) }}">Ubah</a>@endcan</div>
@endsection
