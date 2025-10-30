<style>
    .header {
        background-color: #f2f3f5 !important; 
        border-bottom: 1px solid #f2f3f5;
        padding: 0 1rem;
        z-index: 1030; 
        /* Fixed navbar positioning to ensure it stays at top */
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
    }

    body {
        padding-top: 70px !important;
    }

    .navbar-brand-image {
        height: 2rem;
    }

    .navbar-nav .nav-link {
        color: #495567;
        font-weight: 500;
        padding: 0.75rem 1rem;
        margin: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .navbar-nav .nav-link:hover {
        color: #1d273b;
        background-color: #f2f3f5;
    }

    .navbar-nav .nav-link.active {
        background-color: transparent !important;
        color: #f2f3f5;
        border-bottom: 3px solid #f2f3f5;
        border-radius: 0;
    }

    .navbar-nav .nav-link .nav-link-icon {
        font-size: 1.1rem;
    }

    .dropdown-menu {
        border-radius: 8px;
        border: 1px solid #f2f3f5;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        padding: 0.5rem 0;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
    }

    .dropdown-item .icon {
        width: 20px;
        text-align: center;
    }

    .dropdown-item.active,
    .dropdown-item:active,
    .navbar-nav .dropdown-menu .dropdown-item.active,
    .navbar-nav .dropdown-menu .dropdown-item:active {
        background-color: #206bc4 !important;
        color: white !important;
    }

    .navbar-nav .dropdown-toggle::after {
        margin-left: 0.5rem;
    }

    .navbar-nav .nav-item.dropdown .dropdown-menu {
        margin-top: 0.5rem;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #206bc4;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        border: 2px solid rgba(255,255,255,0.9); 
        box-shadow: 0 2px 6px rgba(0,0,0,0.15); 
        transition: all 0.3s ease; 
    }

    .avatar:hover {
        transform: scale(1.05); 
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    .navbar-brand a {
        color: white !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .navbar-brand svg {
        color: white !important;
        stroke: white !important;
    }

    /* RESPONSIVE */
    @media (max-width: 767.98px) {
        .navbar-collapse {
            padding: 1rem 0;
        }

        .navbar-nav .nav-link {
            padding: 0.75rem;
            margin: 0.25rem 0;
        }

        .navbar-nav .nav-link.active {
            border-bottom: none;
            border-radius: 8px;
            background-color: #e9ecef !important;
            color: #1d273b;
        }

        .navbar-nav .nav-item.dropdown .dropdown-menu {
            box-shadow: none;
            border: none;
            margin-top: 0;
            padding-top: 0;
        }
    }
</style>
<header class="navbar navbar-expand-md navbar-light d-print-none header">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark pe-0 pe-md-3">
            <a href="/dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" class="navbar-brand-image" width="24" height="24"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 21l18 0" />
                    <path d="M5 21v-14l8 -4v18" />
                    <path d="M19 21v-10l-6 -4" />
                    <path d="M9 9l0 .01" />
                    <path d="M9 12l0 .01" />
                    <path d="M9 15l0 .01" />
                    <path d="M9 18l0 .01" />
                </svg>
                <span class="ms-2 d-none d-sm-inline-block"> GERAI UMKM MART </span>
            </a>
        </h1>

        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
                    <div class="d-none d-md-block ps-2">
                        <div>{{ auth()->user()->nama }}</div>
                        <div class="mt-1 small text-muted">{{ auth()->user()->role }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right icon"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
                            <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>
                    @if(auth()->check() && (auth()->user()->isKasir() || auth()->user()->isAdmin()))
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('dashboard/cashier*') ? 'active' : '' }}"
                               href="/dashboard/cashier">
                                <span class="nav-link-icon"><i class="bi bi-calculator"></i></span>
                                <span class="nav-link-title">Kasir</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->check() && (auth()->user()->isKasir() || auth()->user()->isAdmin()))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ Request::is('dashboard/goods*') || Request::is('dashboard/categories*') || Request::is('dashboard/returns*') || Request::is('dashboard/restock*') || Request::is('dashboard/biayaoperasional*') ? 'active' : '' }}"
                               href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon"><i class="bi bi-box-seam"></i></span>
                                <span class="nav-link-title">Data Manajemen</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ Request::is('dashboard/goods*') ? 'active' : '' }}"
                                       href="/dashboard/goods"><i class="bi bi-box icon"></i> Barang</a></li>
                                <li><a class="dropdown-item {{ Request::is('dashboard/categories*') ? 'active' : '' }}"
                                       href="/dashboard/categories"><i class="bi bi-building icon"></i> Mitra Binaan</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item {{ Request::is('dashboard/returns*') ? 'active' : '' }}"
                                       href="/dashboard/returns"><i class="bi bi-arrow-return-left icon"></i> Return
                                        Barang</a></li>
                                <li><a class="dropdown-item {{ Request::is('dashboard/restock*') ? 'active' : '' }}"
                                       href="/dashboard/restock"><i class="bi bi-arrow-up-circle icon"></i> Restock
                                        Barang</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item {{ Request::is('dashboard/biayaoperasional*') ? 'active' : '' }}"
                                       href="/dashboard/biayaoperasional"><i class="bi bi-wallet2 icon"></i> Biaya
                                        Operasional</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard/rekapitulasi*') ? 'active' : '' }}"
                           href="/dashboard/rekapitulasi">
                            <span class="nav-link-icon"><i class="bi bi-file-earmark-text"></i></span>
                            <span class="nav-link-title">Rekapitulasi</span>
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->isAdminOrManajer())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ Request::is('dashboard/users*') || Request::is('dashboard/transactions*') ? 'active' : '' }}"
                               href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon"><i class="bi bi-gear"></i></span>
                                <span class="nav-link-title">
                                    @if(auth()->user()->isAdmin())
                                        Admin
                                    @else
                                        Manajer
                                    @endif
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ Request::is('dashboard/transactions*') ? 'active' : '' }}"
                                       href="/dashboard/transactions"><i class="bi bi-receipt icon"></i> Data Transaksi</a>
                                </li>
                                <li><a class="dropdown-item {{ Request::is('dashboard/users*') ? 'active' : '' }}"
                                        href="/dashboard/users" class="dropdown-item"><i class="bi bi-people icon"></i> Data Pengguna</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>
