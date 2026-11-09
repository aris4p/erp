@extends('layouts.erp')

@section('title', 'Slip Gaji Saya')

@section('content')
    <div class="mb-6">
        <a class="text-sm font-medium text-brand-600 hover:text-brand-700" href="{{ route('my.profile') }}">Kembali ke portal</a>
        <h1 class="mt-3 text-2xl font-semibold text-gray-900">Slip gaji saya</h1>
        <p class="mt-1 text-sm text-gray-600">Daftar penggajian yang diterbitkan untuk akun Anda.</p>
    </div>

    <section class="border border-gray-200 bg-white" aria-labelledby="payslip-list-title">
        <div class="border-b border-gray-200 px-5 py-4 md:px-6"><h2 id="payslip-list-title" class="font-semibold text-gray-900">Daftar slip gaji</h2></div>
        @if($payrollRecords->isEmpty())
            <div class="px-5 py-10 text-sm text-gray-600 md:px-6">Belum ada slip gaji yang diterbitkan untuk Anda.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-600 uppercase"><tr><th class="px-5 py-3 font-medium md:px-6">Periode</th><th class="px-5 py-3 font-medium">Tanggal bayar</th><th class="px-5 py-3 font-medium">Gaji bersih</th><th class="px-5 py-3 font-medium md:px-6"><span class="sr-only">Aksi</span></th></tr></thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($payrollRecords as $payrollRecord)
                            <tr><td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900 md:px-6">{{ $payrollRecord->period->translatedFormat('F Y') }}</td><td class="whitespace-nowrap px-5 py-4">{{ $payrollRecord->paid_at?->translatedFormat('d M Y') ?: 'Belum dibayar' }}</td><td class="whitespace-nowrap px-5 py-4">Rp {{ number_format((float) $payrollRecord->net_pay, 0, ',', '.') }}</td><td class="px-5 py-4 text-right md:px-6"><a class="inline-flex min-h-11 items-center rounded-lg border border-gray-300 px-4 font-medium text-brand-700 hover:bg-gray-50" href="{{ route('my.payslips.show', $payrollRecord) }}">Lihat slip</a></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-200 px-5 py-4 md:px-6">{{ $payrollRecords->links() }}</div>
        @endif
    </section>
@endsection
