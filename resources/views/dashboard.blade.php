@extends('layouts.erp')

@section('title', 'Dashboard HR')

@section('content')
    @php
        $activeEmployeeCount = max($metrics['activeEmployees'], 1);
        $attendancePercent = round(($metrics['presentToday'] / $activeEmployeeCount) * 100);
        $trendMaximum = max(1, $attendanceTrend->flatMap(fn (array $day) => [$day['present'], $day['late'], $day['absent']])->max());
        $statusTotal = max(1, $employmentStatuses->sum('total'));
    @endphp

    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div><h1 class="text-2xl font-semibold text-gray-900">Dashboard HR</h1><p class="mt-1 text-sm text-gray-600">Ringkasan tenaga kerja dan absensi per {{ now()->translatedFormat('d F Y') }}.</p></div>
        <a href="{{ route('employees.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50">Kelola karyawan</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([['label' => 'Total karyawan', 'value' => $metrics['totalEmployees'], 'detail' => 'Seluruh data pegawai'], ['label' => 'Karyawan aktif', 'value' => $metrics['activeEmployees'], 'detail' => 'Status aktif saat ini'], ['label' => 'Hadir hari ini', 'value' => $metrics['presentToday'], 'detail' => $attendancePercent.'% dari karyawan aktif'], ['label' => 'Cuti menunggu', 'value' => $metrics['pendingLeaves'], 'detail' => 'Perlu tindak lanjut']] as $metric)
            <section class="rounded-xl border border-gray-200 bg-white p-5"><p class="text-sm text-gray-600">{{ $metric['label'] }}</p><p class="mt-2 text-3xl font-semibold tracking-tight text-gray-950">{{ $metric['value'] }}</p><p class="mt-2 text-xs text-gray-600">{{ $metric['detail'] }}</p></section>
        @endforeach
    </div>

    <div class="mt-5 grid gap-5 xl:grid-cols-12">
        <section class="rounded-xl border border-gray-200 bg-white p-5 xl:col-span-8" aria-labelledby="attendance-overview-title">
            <div class="flex flex-wrap items-start justify-between gap-3"><div><h2 id="attendance-overview-title" class="text-base font-semibold text-gray-900">Ringkasan absensi</h2><p class="mt-1 text-sm text-gray-600">Catatan hadir, terlambat, dan tidak hadir selama tujuh hari terakhir.</p></div><p class="rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-700">7 hari terakhir</p></div>
            <div class="mt-5 grid gap-3 sm:grid-cols-3"><div><p class="text-xs text-gray-600">Hadir</p><p class="mt-1 text-xl font-semibold text-gray-900">{{ $attendanceTrend->sum('present') }}</p></div><div><p class="text-xs text-gray-600">Terlambat</p><p class="mt-1 text-xl font-semibold text-gray-900">{{ $attendanceTrend->sum('late') }}</p></div><div><p class="text-xs text-gray-600">Tidak hadir</p><p class="mt-1 text-xl font-semibold text-gray-900">{{ $attendanceTrend->sum('absent') }}</p></div></div>
            <div class="mt-5 gap-3 border-b border-gray-200 pb-6 sm:gap-5" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); height: 12rem;" aria-label="Grafik absensi tujuh hari terakhir">
                @foreach($attendanceTrend as $day)
                    <div class="min-w-0" style="display: flex; flex-direction: column; height: 100%;"><div style="display: flex; flex: 1; align-items: end; justify-content: center; gap: 0.25rem;"><span title="Hadir: {{ $day['present'] }}" class="rounded-t" style="display: block; width: 0.5rem; min-height: 3px; height: {{ max(3, ($day['present'] / $trendMaximum) * 100) }}%; background-color: #465fff;"></span><span title="Terlambat: {{ $day['late'] }}" class="rounded-t" style="display: block; width: 0.5rem; min-height: 3px; height: {{ max(3, ($day['late'] / $trendMaximum) * 100) }}%; background-color: #8b5cf6;"></span><span title="Tidak hadir: {{ $day['absent'] }}" class="rounded-t" style="display: block; width: 0.5rem; min-height: 3px; height: {{ max(3, ($day['absent'] / $trendMaximum) * 100) }}%; background-color: #f87171;"></span></div><span class="mt-2 text-center text-xs text-gray-600">{{ $day['label'] }}</span></div>
                @endforeach
            </div>
            <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-gray-600"><span><span class="mr-1 inline-block size-2 rounded-sm bg-brand-500"></span>Hadir</span><span><span class="mr-1 inline-block size-2 rounded-sm bg-violet-500"></span>Terlambat</span><span><span class="mr-1 inline-block size-2 rounded-sm bg-red-400"></span>Tidak hadir</span></div>
        </section>

        <div class="grid gap-5 xl:col-span-4">
            <section class="rounded-xl border border-gray-200 bg-white p-5" aria-labelledby="attendance-rate-title"><h2 id="attendance-rate-title" class="text-base font-semibold text-gray-900">Kehadiran hari ini</h2><p class="mt-4 text-4xl font-semibold tracking-tight text-gray-950">{{ $attendancePercent }}%</p><p class="mt-2 text-sm text-gray-600">{{ $metrics['presentToday'] }} dari {{ $metrics['activeEmployees'] }} karyawan aktif tercatat hadir atau terlambat.</p></section>
            <section class="rounded-xl border border-gray-200 bg-white p-5" aria-labelledby="employment-status-title"><h2 id="employment-status-title" class="text-base font-semibold text-gray-900">Status kerja</h2><div class="mt-4 flex h-3 overflow-hidden rounded-sm bg-gray-100">@foreach($employmentStatuses as $index => $status)<span class="{{ ['bg-brand-500', 'bg-violet-500', 'bg-amber-400', 'bg-gray-400'][$index % 4] }}" style="width: {{ ($status->total / $statusTotal) * 100 }}%"></span>@endforeach</div><div class="mt-4 space-y-3">@forelse($employmentStatuses as $status)<div class="flex items-center justify-between gap-4 text-sm"><span class="text-gray-700">{{ $status->employment_status ?: 'Belum diisi' }}</span><span class="font-medium text-gray-900">{{ $status->total }} · {{ round(($status->total / $statusTotal) * 100) }}%</span></div>@empty<p class="text-sm text-gray-600">Status kerja belum tersedia.</p>@endforelse</div></section>
        </div>
    </div>

    <div class="mt-5 grid gap-5 xl:grid-cols-12">
        <section class="rounded-xl border border-gray-200 bg-white xl:col-span-8" aria-labelledby="employee-list-title"><div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-5 py-4"><h2 id="employee-list-title" class="text-base font-semibold text-gray-900">Karyawan terbaru</h2><a href="{{ route('employees.index') }}" class="inline-flex min-h-11 items-center text-sm font-medium text-brand-700 hover:text-brand-800">Lihat semua</a></div><div class="overflow-x-auto"><table class="min-w-full text-left text-sm"><thead class="bg-gray-50 text-xs text-gray-600 uppercase"><tr><th class="px-5 py-3 font-medium">Karyawan</th><th class="px-5 py-3 font-medium">Jabatan</th><th class="px-5 py-3 font-medium">Departemen</th><th class="px-5 py-3 font-medium">Atasan</th><th class="px-5 py-3 font-medium">Status</th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($recentEmployees as $employee)<tr><td class="whitespace-nowrap px-5 py-4"><a class="font-medium text-gray-900 hover:text-brand-700" href="{{ route('employees.show', $employee) }}">{{ $employee->name }}</a><p class="mt-1 text-xs text-gray-600">{{ $employee->employee_number }}</p></td><td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $employee->position }}</td><td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $employee->department }}</td><td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $employee->manager?->name ?: '-' }}</td><td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $employee->employment_status }}</td></tr>@empty<tr><td colspan="5" class="px-5 py-10 text-gray-600">Belum ada karyawan. Tambah data karyawan untuk menampilkan daftar di sini.</td></tr>@endforelse</tbody></table></div></section>
        <div class="grid gap-5 xl:col-span-4"><section class="rounded-xl border border-gray-200 bg-white p-5"><h2 class="text-base font-semibold text-gray-900">Dokumen mendekati berakhir</h2><div class="mt-4 space-y-4">@forelse($expiringDocuments as $document)<div><p class="text-sm font-medium text-gray-900">{{ $document->employee->name }}</p><p class="mt-1 text-xs text-gray-600">{{ $document->type }} · Berakhir {{ $document->expires_at->translatedFormat('d M Y') }}</p></div>@empty<p class="text-sm text-gray-600">Tidak ada dokumen berakhir dalam 30 hari.</p>@endforelse</div></section><section class="rounded-xl border border-gray-200 bg-white p-5"><h2 class="text-base font-semibold text-gray-900">Kontrak mendekati berakhir</h2><div class="mt-4 space-y-4">@forelse($expiringContracts as $contract)<div><p class="text-sm font-medium text-gray-900">{{ $contract->employee->name }}</p><p class="mt-1 text-xs text-gray-600">{{ $contract->number }} · Berakhir {{ $contract->ends_at->translatedFormat('d M Y') }}</p></div>@empty<p class="text-sm text-gray-600">Tidak ada kontrak berakhir dalam 30 hari.</p>@endforelse</div></section></div>
    </div>
@endsection
