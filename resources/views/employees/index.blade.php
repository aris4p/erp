@extends('layouts.erp')
@section('title', 'Pegawai')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4"><div><h1 class="text-2xl font-semibold">Data Pegawai</h1><p class="text-gray-500">Kelola informasi pegawai perusahaan.</p></div>@can('manage-employees')<a class="rounded-lg bg-brand-500 px-4 py-3 text-white" href="{{ route('employees.create') }}">+ Tambah Pegawai</a>@endcan</div>
<div class="rounded-2xl border border-gray-200 bg-white p-5">
    <form method="GET" class="mb-5 flex gap-2"><input name="search" value="{{ $search }}" placeholder="Cari nama, NIK, atau departemen" class="w-full max-w-sm rounded-lg border border-gray-300 px-4 py-2"><button class="rounded-lg bg-gray-800 px-4 py-2 text-white">Cari</button></form>
    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="border-b border-gray-200 text-gray-500"><tr><th class="p-3">NIK</th><th class="p-3">Nama</th><th class="p-3">Departemen</th><th class="p-3">Jabatan</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead><tbody>
    @forelse($employees as $employee)<tr class="border-b border-gray-100"><td class="p-3">{{ $employee->employee_number }}</td><td class="p-3">{{ $employee->name }}</td><td class="p-3">{{ $employee->department }}</td><td class="p-3">{{ $employee->position }}</td><td class="p-3">{{ $employee->is_active ? 'Aktif' : 'Tidak aktif' }}</td><td class="p-3"><div class="flex gap-3"><a class="text-brand-500" href="{{ route('employees.show', $employee) }}">Detail</a>@can('manage-employees')<a class="text-brand-500" href="{{ route('employees.edit', $employee) }}">Ubah</a><form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Hapus pegawai ini?')">@csrf @method('DELETE')<button class="text-red-500">Hapus</button></form>@endcan</div></td></tr>@empty<tr><td colspan="6" class="p-6 text-center text-gray-500">Tidak ada data pegawai.</td></tr>@endforelse
    </tbody></table></div><div class="mt-5">{{ $employees->links() }}</div>
</div>
@endsection
