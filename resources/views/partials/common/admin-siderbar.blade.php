<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-lightblue">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('AdminLTE/dist/img/logo-head.png')}}" alt="BPO Tech" class="brand-image"
             style="opacity: .8">
        <span class="brand-text font-weight-bold text-lightblue"
              style="font-family: 'Nerko One', cursive; opacity: 0">BPO Tech</span>
    </a>

    <!-- Sidebar -->
    <div
            class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
        <!-- Sidebar user (optional) -->
        <!-- Sidebar Menu -->
        <nav class="mt-5">
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link font-weight-light {{$active===1 ? 'active': ''}}">
                        <i class="nav-icon fas fa-briefcase text-info"></i>
                        <p>
                            {{__('Jobs')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customers.index')}}"
                       class="nav-link font-weight-light {{$active===2 ? 'active': ''}}">
                        <i class="nav-icon fas fa-users text-purple position-relative">
                            @if(isset($unpaidCount) && $unpaidCount > 0)
                                <span class="badge badge-danger badge-pill badge-sidebar position-absolute">{{$unpaidCount}}</span>
                            @endif
                        </i>
                        <p>
                            {{__('Customers')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('jmethods.index')}}"
                       class="nav-link font-weight-light {{$active===3 ? 'active': ''}}">
                        <i class="nav-icon fas fa-cogs text-lime"></i>
                        <p>
                            {{__('Methods')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('jtypes.index')}}"
                       class="nav-link font-weight-light {{$active===4 ? 'active': ''}}">
                        <i class="nav-icon fas fa-folder-open text-orange"></i>
                        <p>
                            {{__('Types')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index')}}"
                       class="nav-link font-weight-light {{$active===5 ? 'active': ''}}">
                        <i class="nav-icon fas fa-newspaper text-success"></i>
                        <p>
                            {{__('Report')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('reports.chart')}}"
                       class="nav-link font-weight-light {{$active===6 ? 'active': ''}}">
                        <i class="nav-icon fas fa-chart-pie text-pink"></i>
                        <p>
                            {{__('Chart Report')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('backup.index')}}"
                       class="nav-link font-weight-light {{$active===7 ? 'active': ''}}">
                        <i class="nav-icon fas fa-wrench text-danger"></i>
                        <p>
                            {{__('Backup File')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('users.index')}}"
                       class="nav-link font-weight-light {{$active=== 8? 'active': ''}}">
                        <i class="nav-icon fas fa-user text-success"></i>
                        <p>
                            {{__('User')}}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>