@extends('layouts.erp')
@section('title', 'Masuk')
@section('content')
<div class="relative flex min-h-screen w-full flex-col bg-white lg:flex-row">
    <div class="flex w-full flex-1 flex-col justify-center px-6 py-12 lg:w-1/2">
        <div class="mx-auto w-full max-w-md">
            <img class="mb-10 h-10 w-37.5" src="{{ asset('tailadmin/images/logo/logo.svg') }}" alt="TailAdmin">
            <h1 class="mb-2 text-title-sm font-semibold text-gray-800">Masuk ke ERP</h1>
            <p class="mb-8 text-sm text-gray-500">Masukkan email dan kata sandi akun Anda.</p>
            <form method="POST" action="{{ route('login.store') }}" class="space-y-6">@csrf
                <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span><input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@perusahaan.com" class="mt-2 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden"></label>
                <label class="block text-sm font-medium text-gray-700">Kata sandi <span class="text-red-500">*</span><input type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" class="mt-2 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden"></label>
                <button class="flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Masuk</button>
            </form>
        </div>
    </div>
    <div class="relative hidden w-full bg-brand-950 xl:block lg:w-1/2">
        <div class="flex min-h-screen items-center justify-center"><div class="mx-auto max-w-xs text-center"><img class="mx-auto mb-6" src="{{ asset('tailadmin/images/logo/logo-dark.svg') }}" alt="TailAdmin"><p class="text-gray-400">Kelola data pegawai perusahaan dengan lebih mudah dan terpusat.</p></div></div>
    </div>
</div>
@endsection
