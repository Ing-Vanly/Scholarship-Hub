    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        {{-- <a href="index3.html" class="brand-link">
            <img src="AdminLTE/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </a> --}}
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="AdminLTE/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Alexander Pierce</a>
                </div>
            </div>
            {{-- <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div> --}}
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li
                        class="nav-item {{ request()->routeIs('admin.user.*') || request()->routeIs('admin.role.*') || request()->routeIs('admin.permission.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('admin.user.*') || request()->routeIs('admin.role.*') || request()->routeIs('admin.permission.*') ? 'active' : '' }}">
                            @include('backends.admin.svg.user')
                            <p class="nav-item-p-text">
                                {{ __('User Management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.user.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.user.index') || request()->routeIs('admin.user.create') || request()->routeIs('admin.user.edit') || request()->routeIs('admin.user.show') ? 'active' : '' }}">
                                    <p class="nav-item-p-text ml-2">{{ __('User List') }}</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('admin.permission.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.permission.index') || request()->routeIs('admin.permission.create') || request()->routeIs('admin.permission.edit') || request()->routeIs('admin.permission.show') ? 'active' : '' }}">
                                    <p class="nav-item-p-text ml-2">{{ __('Permission') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.role.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.role.index') || request()->routeIs('admin.role.create') || request()->routeIs('admin.role.edit') || request()->routeIs('admin.role.show') ? 'active' : '' }}">
                                    <p class="nav-item-p-text ml-2">{{ __('Role List') }}</p>
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
