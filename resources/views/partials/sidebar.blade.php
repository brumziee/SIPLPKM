<aside class="navbar navbar-vertical navbar-expand-lg">
    <div class="container-fluid">

        <!-- BAR ATAS: toggle kiri, SIPLPKM tengah, profil kanan mobile -->
        <div class="d-flex align-items-center w-100 mb-3 position-relative">
            <!-- tombol toggle di kiri (hanya mobile) -->
            <button class="navbar-toggler collapsed d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- SIPLPKM di tengah -->
            <a href="{{ route('dashboard') }}" 
               class="fw-bold text-2xl text-red-600 hover:text-red-800 text-decoration-none text-center w-100">
                SIPLPKM
            </a>

            <!-- PROFIL di kanan (mobile) -->
            <div class="d-lg-none position-absolute end-0">
                <div class="nav-item dropdown">
                    <a href="#" class="p-0 nav-link d-flex align-items-center" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url('{{ asset('static/avatars/userprofile.png') }}')"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="dropdown-item-text fw-semibold text-center">
                            {{ auth()->user() ? auth()->user()->name : '' }}
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" class="dropdown-item text-danger fw-semibold"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- tetap gunakan bagian menu yg sama seperti semula -->
        <div class="navbar-collapse collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Dashboard </span>
                    </a>
                </li>

                <!-- Poin Pelanggan -->
                <li class="nav-item {{ request()->is('pelanggan*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pelanggan.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M3 3h18l-1 13H4L3 3z"></path>
                                <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0"></path>
                                <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Poin Pelanggan </span>
                    </a>
                </li>

                <!-- Penukaran Poin -->
                <li class="nav-item {{ request()->is('penukaran-poin*') || request()->is('penukaran*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('penukaran-poin.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M3 3h18l-1 13H4L3 3z"></path>
                                <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0"></path>
                                <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0"></path>
                                <path d="M8.5 4.5l.5 7h6l.5 -7"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Penukaran Poin </span>
                    </a>
                </li>

                <!-- Reward -->
                <li class="nav-item {{ request()->is('reward*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('reward.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <path d="M3 9h18"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Reward </span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('csv-logs*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('csv-logs.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M12 20l4 -9l-4 -3l-4 3z"></path>
                                <path d="M12 4l0 .01"></path>
                                <path d="M3 12l.01 0"></path>
                                <path d="M21 12l.01 0"></path>
                                <path d="M5.6 5.6l.01 0"></path>
                                <path d="M18.4 5.6l.01 0"></path>
                                <path d="M5.6 18.4l.01 0"></path>
                                <path d="M18.4 18.4l.01 0"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Log Upload CSV </span>
                    </a>
                </li>

                <!-- User -->
                @can('user.view')
                <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <circle cx="12" cy="7" r="4"></circle>
                                <path d="M5.5 21a7 7 0 0 1 13 0z"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> User </span>
                    </a>
                </li>
                @endcan

                <!-- Hak Akses -->
                @can('role.view')
                <li class="nav-item {{ request()->is('role*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('role.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M12 3l8 4.5v9l-8 4.5l-8-4.5v-9l8-4.5"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Hak Akses </span>
                    </a>
                </li>
                @endcan

                
            </ul>
        </div>
    </div>
</aside>
