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
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="#">
                                    <span class="link-collapse">Profil Saya</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item {{ request()->is('/*') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-plus"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('cashier*') ? 'active' : '' }}">
                    <a href="{{ url('/cashier') }}">
                        <i class="fas fa-plus"></i>
                        <p>Kasir</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('product*') ? 'active' : '' }}">
                    <a href="{{ url('/product') }}">
                        <i class="fas fa-plus"></i>
                        <p>Product</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('billing*') ? 'active' : '' }}">
                    <a href="{{ url('/billing') }}">
                        <i class="fas fa-note"></i>
                        <p>Billing</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
