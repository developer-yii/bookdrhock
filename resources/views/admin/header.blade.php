<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
            data-target=".navbar-collapse"><i class="ti-menu"></i></a>
        <div class="top-left-part">
            <a class="logo d-flex align-items-center" href="{{ route('home') }}">
                <b><img src="{{ asset('assets/images/logo.png') }}" width="40px" alt="home" /></b>
                <span class="hidden-xs">
                    <img src="{{ asset('assets/images/text.png') }}" width="135px" alt="home" />
                </span>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-right pull-right">
            <li class="dropdown">
                <a class="dropdown-toggle profile-pic text-capitalize font-weight-bold" data-toggle="dropdown"
                    href="#">
                    {{ Auth::user()->name }}</b> </a>
                <ul class="dropdown-menu dropdown-user animated flipInY">
                    <li><a href="{{ route('userProfile') }}"><i class="ti-user"></i> {{ __('My Profile') }}</a></li>
                    <li><a href="{{ route('userProfile.password') }}"><i class="ti-key"></i>
                            {{ __('Change Password') }}</a></li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
