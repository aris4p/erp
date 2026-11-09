@extends('layouts.erp')

@section('title', 'Absensi Saya')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <a class="text-sm font-medium text-brand-600 hover:text-brand-700" href="{{ route('my.profile') }}">Kembali ke portal</a>
            <h1 class="mt-3 text-2xl font-semibold text-gray-900">Absensi saya</h1>
            <p class="mt-1 text-sm text-gray-600">Gunakan waktu server untuk check-in dan check-out hari ini.</p>
        </div>
        <p class="text-sm font-medium text-gray-700">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <section class="border border-gray-200 bg-white p-5 md:p-6" aria-labelledby="attendance-today-title">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 id="attendance-today-title" class="text-lg font-semibold text-gray-900">Absensi hari ini</h2>
                @if($todayAttendance?->checked_out_at)
                    <p class="mt-1 text-sm text-gray-600">Check-in {{ $todayAttendance->checked_in_at }}. Check-out {{ $todayAttendance->checked_out_at }}.</p>
                @elseif($todayAttendance?->checked_in_at)
                    <p class="mt-1 text-sm text-gray-600">Check-in tercatat pukul {{ $todayAttendance->checked_in_at }}. Lanjutkan check-out saat selesai bekerja.</p>
                @else
                    <p class="mt-1 text-sm text-gray-600">Belum ada check-in untuk hari ini.</p>
                @endif
            </div>
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('my.attendance.check-in') }}">
                    @csrf
                    <button type="submit" @disabled($todayAttendance?->checked_in_at) class="min-h-11 rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white hover:bg-brand-600 disabled:cursor-not-allowed disabled:bg-gray-300">Check-in</button>
                </form>
                <form method="POST" action="{{ route('my.attendance.check-out') }}">
                    @csrf
                    <button type="submit" @disabled(! $todayAttendance?->checked_in_at || $todayAttendance?->checked_out_at) class="min-h-11 rounded-lg border border-gray-300 px-5 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400">Check-out</button>
                </form>
            </div>
        </div>
    </section>

    <section class="mt-6 border border-gray-200 bg-white" aria-labelledby="attendance-history-title">
        <div class="border-b border-gray-200 px-5 py-4 md:px-6">
            <h2 id="attendance-history-title" class="font-semibold text-gray-900">Riwayat absensi</h2>
        </div>
        @if($attendanceRecords->isEmpty())
            <div class="px-5 py-10 text-sm text-gray-600 md:px-6">Belum ada riwayat absensi. Check-in hari ini untuk membuat catatan pertama.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-600 uppercase">
                        <tr><th class="px-5 py-3 font-medium md:px-6">Tanggal</th><th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Check-in</th><th class="px-5 py-3 font-medium">Check-out</th><th class="px-5 py-3 font-medium md:px-6">Lembur</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($attendanceRecords as $attendance)
                            <tr><td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900 md:px-6">{{ $attendance->attendance_date->translatedFormat('d M Y') }}</td><td class="whitespace-nowrap px-5 py-4">{{ $attendance->status }}</td><td class="whitespace-nowrap px-5 py-4">{{ $attendance->checked_in_at ?: '-' }}</td><td class="whitespace-nowrap px-5 py-4">{{ $attendance->checked_out_at ?: '-' }}</td><td class="whitespace-nowrap px-5 py-4 md:px-6">{{ $attendance->overtime_minutes }} menit</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-200 px-5 py-4 md:px-6">{{ $attendanceRecords->links() }}</div>
        @endif
    </section>
@endsection
