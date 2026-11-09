@extends('layouts.erp')

@section('title', 'Approval HR')

@section('content')
    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Approval HR</h1><p class="mt-1 text-sm text-gray-600">Pengajuan cuti yang telah disetujui atasan langsung.</p></div>
    <section class="border border-gray-200 bg-white" aria-labelledby="hr-approval-title">
        <div class="border-b border-gray-200 px-5 py-4 md:px-6"><h2 id="hr-approval-title" class="font-semibold text-gray-900">Menunggu persetujuan HR</h2></div>
        @forelse($leaveRequests as $leaveRequest)
            <div class="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 last:border-0 md:flex-row md:items-center md:justify-between md:px-6"><div><p class="font-medium text-gray-900">{{ $leaveRequest->employee->name }} · {{ $leaveRequest->type }}</p><p class="mt-1 text-sm text-gray-600">{{ $leaveRequest->start_date->translatedFormat('d M Y') }} sampai {{ $leaveRequest->end_date->translatedFormat('d M Y') }} · {{ $leaveRequest->days }} hari</p><p class="mt-1 text-sm text-gray-600">Atasan: {{ $leaveRequest->employee->manager?->name ?: 'Tidak ada' }}</p></div><form method="POST" action="{{ route('leave-requests.hr-approve', $leaveRequest) }}">@csrf @method('PATCH')<button type="submit" class="min-h-11 rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white hover:bg-brand-600">Setujui pengajuan</button></form></div>
        @empty
            <p class="px-5 py-10 text-sm text-gray-600 md:px-6">Tidak ada pengajuan yang menunggu persetujuan HR.</p>
        @endforelse
        @if($leaveRequests->hasPages())<div class="border-t border-gray-200 px-5 py-4 md:px-6">{{ $leaveRequests->links() }}</div>@endif
    </section>
@endsection
