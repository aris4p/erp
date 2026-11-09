<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ERP') | ERP</title>
    <link rel="icon" href="{{ asset('tailadmin/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tailadmin/style.css') }}">
</head>
<body x-data="{ page: '{{ request()->routeIs('users.*') ? 'users' : 'employees' }}', sidebarToggle: false }" class="bg-gray-50 font-outfit text-gray-800">
@auth
<div class="flex h-screen overflow-hidden">
    <div x-cloak x-show="sidebarToggle" @click="sidebarToggle = false" x-transition.opacity class="fixed inset-0 z-99 bg-gray-900/50 backdrop-blur-xs xl:hidden"></div>
    <aside :class="sidebarToggle ? 'translate-x-0 xl:w-22.5' : '-translate-x-full'" @click.outside="if (window.innerWidth < 1280) sidebarToggle = false" class="sidebar fixed top-0 left-0 z-9999 flex h-screen w-72.5 flex-col border-r border-gray-200 bg-white px-5 transition-all duration-300 xl:static xl:translate-x-0" aria-label="Navigasi utama">
        <div :class="sidebarToggle ? 'xl:justify-center' : 'justify-between'" class="sidebar-header flex items-center gap-2 py-8">
            <a href="{{ auth()->user()->can('manage-employees') ? route('dashboard') : route('my.profile') }}"><img :class="sidebarToggle ? 'xl:hidden' : ''" class="h-10 w-37.5" src="{{ asset('tailadmin/images/logo/logo.svg') }}" alt="TailAdmin"><img :class="sidebarToggle ? 'xl:block' : 'hidden'" class="hidden" src="{{ asset('tailadmin/images/logo/logo-icon.svg') }}" alt="TailAdmin"></a>
        </div>
        <div class="no-scrollbar flex flex-col overflow-y-auto">
            <nav>
                <h2 class="mb-4 flex text-xs leading-5 text-gray-400 uppercase"><span :class="sidebarToggle ? 'xl:hidden' : ''">Menu</span></h2>
                <ul class="mb-6 flex flex-col gap-4">
                    @can('manage-employees')
                    <li><a href="{{ route('dashboard') }}" class="menu-item group {{ request()->routeIs('dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}" :class="sidebarToggle ? 'xl:justify-center' : ''"><svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h7.5v7.5h-7.5zM12.75 3.75h7.5v7.5h-7.5zM3.75 12.75h7.5v7.5h-7.5zM12.75 12.75h7.5v7.5h-7.5z" /></svg><span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Dashboard</span></a></li>
                    <li><a href="{{ route('employees.index') }}" class="menu-item group {{ request()->routeIs('employees.*') ? 'menu-item-active' : 'menu-item-inactive' }}" :class="sidebarToggle ? 'xl:justify-center' : ''">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20.25v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3A4.5 4.5 0 0 0 5 18.75v1.5M15 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM19 9.75a3 3 0 0 1 0 5.5m0 0a4.5 4.5 0 0 1 2 3.75v1.25" /></svg>
                        <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Pegawai</span></a></li>
                    @endcan
                    @if(auth()->user()->employee)<li><a href="{{ route('my.profile') }}" class="menu-item group {{ request()->routeIs('my.*') ? 'menu-item-active' : 'menu-item-inactive' }}" :class="sidebarToggle ? 'xl:justify-center' : ''"><span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Portal Saya</span></a></li>@endif
                    @if(auth()->user()->hasRole('manager', 'admin'))<li><a href="{{ route('leave-approvals.manager') }}" class="menu-item group {{ request()->routeIs('leave-approvals.manager') ? 'menu-item-active' : 'menu-item-inactive' }}" :class="sidebarToggle ? 'xl:justify-center' : ''"><span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Approval Atasan</span></a></li>@endif
                    @if(auth()->user()->hasRole('hr', 'admin'))<li><a href="{{ route('leave-approvals.hr') }}" class="menu-item group {{ request()->routeIs('leave-approvals.hr') ? 'menu-item-active' : 'menu-item-inactive' }}" :class="sidebarToggle ? 'xl:justify-center' : ''"><span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Approval HR</span></a></li>@endif
                    @can('manage-employees')<li><a href="{{ route('users.index') }}" class="menu-item group {{ request()->routeIs('users.*') ? 'menu-item-active' : 'menu-item-inactive' }}" :class="sidebarToggle ? 'xl:justify-center' : ''">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25a3.75 3.75 0 1 0-7.5 0V9M5.25 9h13.5v11.25H5.25z" /></svg>
                        <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Akun Pengguna</span></a></li>@endcan
                </ul>
            </nav>
        </div>
    </aside>
    <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
        <header class="sticky top-0 z-50 flex w-full shrink-0 border-b border-gray-200 bg-white">
            <div class="flex w-full items-center justify-between gap-4 px-4 py-4 md:px-6">
                <div class="flex items-center gap-4"><button type="button" @click.stop="sidebarToggle = !sidebarToggle" aria-label="Buka atau tutup menu" class="inline-flex size-11 items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg></button><span class="hidden text-sm text-gray-500 sm:block">Sistem Informasi Pegawai</span></div>
                <div class="flex items-center gap-4"><div class="text-right"><div class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</div><div class="text-xs text-gray-500">{{ ['admin' => 'Administrator', 'hr' => 'HR', 'manager' => 'Atasan', 'payroll' => 'Payroll', 'employee' => 'Karyawan'][auth()->user()->role] ?? 'Pengguna' }}</div></div><form method="POST" action="{{ route('logout') }}">@csrf <button class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" type="submit">Keluar</button></form></div>
            </div>
        </header>
        <main id="main-content" class="mx-auto w-full max-w-(--breakpoint-2xl) p-4 md:p-6">
@else
<main class="min-h-screen">
@endauth
            @if(session('success'))<div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
@auth
    </div>
</div>
@endauth
<script defer src="{{ asset('tailadmin/bundle.js') }}"></script>
</body>
</html>
