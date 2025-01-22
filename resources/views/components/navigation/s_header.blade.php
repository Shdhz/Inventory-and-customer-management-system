@role('admin|supervisor')
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">

                    {{-- Nav-home --}}
                    @role('admin')
                        <li class="nav-item {{ Request::is('dashboard-admin') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route('dashboardAdmin.index') }}">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Home
                                </span>
                            </a>
                        </li>
                    @endrole
                    
                    @role('supervisor')
                        <li class="nav-item {{ Request::is('dashboard-supervisor') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboardSupervisor.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Home
                                </span>
                            </a>
                        </li>
                    @endrole

                    {{-- Nav - data customer --}}
                    <li class="nav-item dropdown {{ Request::is('draft-customer*') || Request::is('order-customer*') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Data Customer
                            </span>
                        </a>
                        {{-- dropdown - data customer --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item @isActive('draft-customer*')" href="{{ route('draft-customer.index') }}">
                                        Draft Customer
                                    </a>
                                    <a class="dropdown-item @isActive('order-customer*')" href="{{ route('order-customer.index') }}">
                                        Order Customer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Nav - Manajemen Order --}}
                    <li class="nav-item dropdown {{ Request::is('transaksi-customer*') || Request::is('form-po*') || Request::is('kelola-invoice*') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Kelola Order
                            </span>
                        </a>
                        {{-- dropdown - data customer --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item @isActive('transaksi-customer*')" href="{{ route('transaksi-customer.index') }}">
                                        Transaksi Customer
                                    </a>
                                    <a class="dropdown-item @isActive('kelola-invoice*')" href="{{ route('kelola-invoice.index') }}">
                                        Kelola Invoice
                                    </a>
                                    <a class="dropdown-item @isActive('form-po*')" href="{{ route('form-po.index') }}">
                                        Kelola Form Po
                                    </a>
                                </div>
                            </div>
                        </div>                        
                    </li>

                    {{-- Nav - Kelola Produk --}}
                    <li class="nav-item dropdown {{ Request::is('stok-barang') || Request::is('barang-rusak') || Request::is('rencana-produksi') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-package"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Kelola Produk
                            </span>
                        </a>
                        {{-- dropdown - kelola produk --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item @isActive('rencana-produksi*')" href="{{ route('rencana-produksi.index') }}">
                                        Rencana Produksi
                                    </a>
                                    <a class="dropdown-item @isActive('stok-barang*')" href="{{ route('stok-barang.index') }}">
                                        Stok Produk
                                    </a>
                                    <a class="dropdown-item @isActive('barang-rusak*')" href="{{ route('barang-rusak.index') }}">
                                        Kondisi Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Nav - Kelola Laporan --}}
                    <li class="nav-item dropdown {{ Request::is('riwayat-transaksi*') || Request::is('laporan-penjualan*') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20h14" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Kelola Laporan
                            </span>
                        </a>
                        {{-- dropdown - kelola Laporan --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item  @isActive('laporan-penjualan*')" href="{{ route('laporan.penjualan') }}">
                                        Laporan Penjualan
                                    </a>
                                    <a class="dropdown-item @isActive('riwayat-transaksi*')" href="{{ route('riwayat.transaksi') }}">
                                        Riwayat Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    @role('supervisor')
                    {{-- Nav - Kelola data admin --}}
                    <li class="nav-item {{ Request::is('kelola-admin') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('kelola-admin.index') }}" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            </span>
                            <span class="nav-link-title">
                                kelola data Pengguna
                            </span>
                        </a>
                    </li>
                    @endrole

                    {{-- Nav - Kelola tugas harian --}}
                    {{-- <li class="nav-item">
                        <a class="btn btn-outline-primary border border-1 rounded-1 focus-ring focus-ring-primary " href="">
                            Kelola tugas
                        </a>
                    </li> --}}
                    
                </ul>
            </div>
        </div>
    </div>
</header>
@endrole

@role('produksi')
{{-- Header produksi --}}
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl my-auto">
                <ul class="navbar-nav">

                    {{-- Nav-home --}}
                    <li class="nav-item {{ Request::is('dashboard-produksi') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('dashboardProduksi.index') }}">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Home
                            </span>
                        </a>
                    </li>

                    {{-- Nav - data produk --}}
                    <li class="nav-item dropdown {{ Request::is('kategori-barang*') || Request::is('stok-barang*') || Request::is('barang-rusak*') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-package"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Kelola Produk
                            </span>
                        </a>
                        {{-- dropdown - data produk --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item @isActive('kategori-barang*')" href="{{ route('kategori-barang.index') }}">
                                        kategori produk
                                    </a>
                                    <a class="dropdown-item @isActive('stok-barang*')" href="{{ route('stok-barang.index') }}">
                                        Stok produk
                                    </a>
                                    <a class="dropdown-item @isActive('barang-rusak*')" href="{{ route('barang-rusak.index') }}">
                                        Barang rusak
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Nav - manajemen barang masuk/keluar --}}
                    <li class="nav-item dropdown {{ Request::is('barang-masuk*') || Request::is('barang-keluar*')  ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-package-export"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /><path d="M15 18h7" /><path d="M19 15l3 3l-3 3" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Transaksi
                            </span>
                        </a>
                        {{-- dropdown - data customer --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item @isActive('barang-masuk*')" href="{{ route('barang-masuk.index') }}">
                                        Barang masuk
                                    </a>
                                    <a class="dropdown-item @isActive('barang-keluar*')" href="{{ route('barang-keluar.index') }}">
                                        Barang keluar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Nav - Kelola Produksi --}}
                    <li class="nav-item {{ Request::is('rencana-produksi*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('rencana-produksi.index') }}" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M7 14h.013" /><path d="M10.01 14h.005" /><path d="M13.01 14h.005" /><path d="M16.015 14h.005" /><path d="M13.015 17h.005" /><path d="M7.01 17h.005" /><path d="M10.01 17h.005" /></svg>
                            </span>
                            <span class="nav-link-title">
                                kelola rencana produksi
                            </span>
                        </a>
                    </li>

                    {{-- Nav - Kelola tugas harian --}}
                    {{-- <li class="nav-item">
                        <a class="btn btn-outline-primary border border-1 rounded-1 focus-ring focus-ring-primary " href="">
                            Kelola tugas
                        </a>
                    </li> --}}
                    
                </ul>
            </div>
        </div>
    </div>
</header>
@endrole