<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <div class="avatar-img rounded-circle" id="avatarUsersSidebar">
                        <img src="{{ asset('assets/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle">
                    </div>
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            <span>Administrator</span>
                            <span class="user-level">Admin</span>
                        </span>
                    </a>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item {{ request()->is('home*') ? 'active' : '' }}">
                    <a href="{{ url('/home') }}">
                        <i class="fas fa-tachometer-alt"></i> <!-- Ikon Dashboard -->
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('product*') ? 'active' : '' }}">
                    <a href="{{ url('/product') }}">
                        <i class="fas fa-box"></i> <!-- Ikon Produk -->
                        <p>Produk</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('billing*') ? 'active' : '' }}">
                    <a href="{{ url('/billing') }}">
                        <i class="fas fa-shopping-cart"></i> <!-- Ikon Pesanan -->
                        <p>Pesanan</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>