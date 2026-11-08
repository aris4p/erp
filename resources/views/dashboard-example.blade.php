@extends('layouts.erp')
@section('title', 'Dashboard')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div><h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1><p class="mt-1 text-sm text-gray-500">Ringkasan aktivitas dan informasi kepegawaian.</p></div>
    <span class="rounded-full bg-brand-50 px-3 py-1 text-sm font-medium text-brand-500">Data contoh</span>
</div>
<div class="mb-6 rounded-xl border border-brand-100 bg-brand-50 p-4 text-sm text-brand-700">Seluruh angka dan aktivitas pada dashboard ini adalah data dummy, bukan data pegawai sebenarnya.</div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
    @foreach([
        ['Total Pegawai', '248', '+12 bulan ini', 'M4 19v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z'],
        ['Pegawai Aktif', '232', '93,5% dari total', 'm5 12 4 4L19 6'],
        ['Departemen', '8', 'Di seluruh perusahaan', 'M4 21V5l8-3 8 3v16M2 21h20M9 9h.01M15 9h.01M9 13h.01M15 13h.01'],
        ['Pegawai Baru', '12', 'Dalam bulan ini', 'M12 5v14M5 12h14'],
    ] as [$label, $value, $description, $icon])
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800"><svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg></div>
        <div class="mt-5"><span class="text-sm text-gray-500">{{ $label }}</span><div class="mt-2 flex flex-wrap items-end justify-between gap-2"><h2 class="text-title-sm font-bold text-gray-800">{{ $value }}</h2><span class="rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-600">{{ $description }}</span></div></div>
    </div>
    @endforeach
</div>
<div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 xl:col-span-2 md:p-6">
        <div class="mb-6 flex items-start justify-between gap-3"><div><h2 class="text-lg font-semibold text-gray-800">Distribusi Departemen</h2><p class="mt-1 text-sm text-gray-500">Contoh jumlah pegawai berdasarkan departemen</p></div><span class="text-sm text-gray-400">Contoh</span></div>
        <div class="space-y-5">
            @foreach([['Operasional', 72, 'w-3/4'], ['Penjualan', 54, 'w-1/2'], ['Teknologi Informasi', 42, 'w-2/5'], ['Keuangan', 36, 'w-1/3'], ['Sumber Daya Manusia', 24, 'w-1/4']] as [$department, $total, $width])
            <div><div class="mb-2 flex justify-between text-sm"><span class="font-medium text-gray-700">{{ $department }}</span><span class="text-gray-500">{{ $total }} pegawai</span></div><div class="h-2 overflow-hidden rounded-full bg-gray-100"><div class="{{ $width }} h-full rounded-full bg-brand-500"></div></div></div>
            @endforeach
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6"><h2 class="text-lg font-semibold text-gray-800">Status Pegawai</h2><p class="mt-1 text-sm text-gray-500">Ringkasan status contoh</p><div class="mt-8 space-y-5"><div class="flex items-center justify-between rounded-xl bg-gray-50 p-4"><div><p class="text-sm text-gray-500">Aktif</p><p class="mt-1 text-2xl font-semibold">232</p></div><span class="rounded-full bg-green-50 px-3 py-1 text-sm text-green-600">93,5%</span></div><div class="flex items-center justify-between rounded-xl bg-gray-50 p-4"><div><p class="text-sm text-gray-500">Tidak aktif</p><p class="mt-1 text-2xl font-semibold">16</p></div><span class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-600">6,5%</span></div></div></div>
</div>
<div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 md:p-6"><div class="mb-5 flex flex-wrap items-center justify-between gap-3"><div><h2 class="text-lg font-semibold text-gray-800">Pegawai Terbaru</h2><p class="mt-1 text-sm text-gray-500">Daftar berikut hanya contoh tampilan</p></div><a href="{{ route('employees.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Lihat data pegawai</a></div><div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="border-b border-gray-200 text-gray-500"><tr><th class="p-3">NIK</th><th class="p-3">Nama</th><th class="p-3">Departemen</th><th class="p-3">Jabatan</th><th class="p-3">Status</th></tr></thead><tbody>@foreach([['EMP-001', 'Andi Pratama', 'Operasional', 'Staf Operasional'], ['EMP-002', 'Sinta Dewi', 'Keuangan', 'Analis Keuangan'], ['EMP-003', 'Budi Santoso', 'Teknologi Informasi', 'Pengembang']] as [$number, $name, $department, $position])<tr class="border-b border-gray-100"><td class="p-3 text-gray-500">{{ $number }}</td><td class="p-3 font-medium text-gray-800">{{ $name }}</td><td class="p-3 text-gray-500">{{ $department }}</td><td class="p-3 text-gray-500">{{ $position }}</td><td class="p-3"><span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-600">Aktif</span></td></tr>@endforeach</tbody></table></div></div>
@endsection
