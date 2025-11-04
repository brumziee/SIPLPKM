<aside class="navbar navbar-vertical navbar-expand-lg">
    <div class="container-fluid">
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('dashboard') }}" aria-label="{{ $websiteSetting?->website_name ?? 'SIPLPKM' }}">
                @if($websiteSetting?->logo)
                    <img src="{{ asset('storage/' . $websiteSetting->logo) }}" alt="{{ $websiteSetting->website_name ?? 'SIPLPKM' }}"
                        class="navbar-brand-image">
                @else
                    SIPLPKM
                @endif
            </a>
        </div>

        <div class="navbar-collapse collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-1">
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Dashboard </span>
                    </a>
                </li>

                <!-- Poin Pelanggan (separate item) -->
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

                <!-- Penukaran Poin (separate item) -->
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                                <path d="M12 12l8 -4.5"></path>
                                <path d="M12 12l0 9"></path>
                                <path d="M12 12l-8 -4.5"></path>
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