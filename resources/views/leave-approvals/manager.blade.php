@extends('layouts.erp')

@section('title', 'Approval Atasan')

@section('content')
    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Approval atasan</h1><p class="mt-1 text-sm text-gray-600">Pengajuan cuti dari karyawan yang melapor langsung kepada Anda.</p></div>
    <section class="border border-gray-200 bg-white" aria-labelledby="manager-approval-title">
        <div class="border-b border-gray-200 px-5 py-4 md:px-6"><h2 id="manager-approval-title" class="font-semibold text-gray-900">Menunggu persetujuan</h2></div>
        @forelse($leaveRequests as $leaveRequest)
            <div class="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 last:border-0 md:flex-row md:items-center md:justify-between md:px-6"><div><p class="font-medium text-gray-900">{{ $leaveRequest->employee->name }} · {{ $leaveRequest->type }}</p><p class="mt-1 text-sm text-gray-600">{{ $leaveRequest->start_date->translatedFormat('d M Y') }} sampai {{ $leaveRequest->end_date->translatedFormat('d M Y') }} · {{ $leaveRequest->days }} hari</p>@if($leaveRequest->reason)<p class="mt-1 text-sm text-gray-600">{{ $leaveRequest->reason }}</p>@endif</div><form method="POST" action="{{ route('leave-requests.manager-approve', $leaveRequest) }}">@csrf @method('PATCH')<button type="submit" class="min-h-11 rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white hover:bg-brand-600">Setujui dan teruskan ke HR</button></form></div>
        @empty
            <p class="px-5 py-10 text-sm text-gray-600 md:px-6">Tidak ada pengajuan yang menunggu persetujuan atasan.</p>
        @endforelse
        @if($leaveRequests->hasPages())<div class="border-t border-gray-200 px-5 py-4 md:px-6">{{ $leaveRequests->links() }}</div>@endif
    </section>
@endsection
