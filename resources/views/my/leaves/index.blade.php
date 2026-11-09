@extends('layouts.erp')
@section('title', 'Cuti Saya')
@section('content')
<div class="mb-6 flex flex-wrap justify-between gap-4"><div><h1 class="text-2xl font-semibold">Cuti dan izin saya</h1><p class="mt-1 text-sm text-gray-500">Saldo cuti tahunan: {{ $employee->annual_leave_balance }} hari</p></div><a href="{{ route('my.leaves.create') }}" class="inline-flex min-h-11 items-center rounded-lg bg-brand-500 px-4 text-sm font-semibold text-white">Ajukan cuti</a></div><section class="rounded-xl border border-gray-200 bg-white p-5"><div class="divide-y divide-gray-100">@forelse($employee->leaveRequests as $leave)<div class="py-4 first:pt-0"><p class="font-medium">{{ $leave->type }} · {{ $leave->days }} hari</p><p class="mt-1 text-sm text-gray-500">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }} · {{ $leave->status }}</p></div>@empty<p class="text-sm text-gray-500">Belum ada pengajuan.</p>@endforelse</div></section>
@endsection
