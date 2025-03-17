<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="../assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                </div>

            </div>
            <ul class="nav nav-primary">
                <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                    <a href="{{ url('/user') }}">
                        <i class="fas fa-plus"></i>
                        <p>User</p>
                    </a>
                </li>
            </ul>

            <ul class="nav nav-primary">
                <li class="nav-item {{ request()->is('product*') ? 'active' : '' }}">
                    <a href="{{ url('/product') }}">
                        <i class="fas fa-plus"></i>
                        <p>Product</p>
                    </a>
                </li>
            </ul>


        </div>
    </div>
</div>
