<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <ul class="nav in" id="side-menu">
            <li class="nav-small-cap m-t-10">--- {{ __('Main Menu') }}</li>
            <li class="{{ request()->routeIs('admin') ? 'active' : '' }}">
                <a href="{{ route('admin') }}" class="waves-effect {{ request()->routeIs('admin') ? 'active' : '' }}">
                    <i class="icon-layers"></i><span class="hide-menu text-capitalize"> {{ __('Dashboard') }}</span>
                </a>
            </li>
            @if (Auth::user()->user_role == 1)
                <li
                    class="{{ request()->routeIs('poll') || request()->routeIs('poll.createForm') || request()->routeIs('category') ? 'active' : '' }}">
                    <a href="javascript:void(0)"
                        class="waves-effect {{ request()->routeIs('poll') || request()->routeIs('poll.createForm') || request()->routeIs('category') ? 'active' : '' }}">
                        <i class="ti-bar-chart"></i><span class="hide-menu text-capitalize">
                            {{ __('polls') }} <span class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ route('poll') }}"
                                class="{{ request()->routeIs('poll') ? 'active' : '' }}">{{ __('All Polls') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('poll.createForm') }}"
                                class="{{ request()->routeIs('poll.createForm') ? 'active' : '' }}">{{ __('Add New') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('category') }}"
                                class="{{ request()->routeIs('category') ? 'active' : '' }}">{{ __('Categories') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('codeblock') ? 'active' : '' }}">
                    <a href="{{ route('codeblock') }}"
                        class="waves-effect {{ request()->routeIs('codeblock') ? 'active' : '' }}">
                        <i class="fa fa-code"></i><span class="hide-menu text-capitalize">
                            {{ __('insert code block') }} </span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('setting') ? 'active' : '' }}">
                    <a href="{{ route('setting') }}"
                        class="waves-effect {{ request()->routeIs('setting') ? 'active' : '' }}">
                        <i class="ti-panel"></i><span class="hide-menu text-capitalize">
                            {{ __('settings') }}</span>
                    </a>
                </li>
                {{-- <li class="{{ request()->routeIs('user') ? 'active' : '' }}">
                    <a href="{{ route('user') }}"
                        class="waves-effect {{ request()->routeIs('user') ? 'active' : '' }}">
                        <i class="icon-people"></i><span class="hide-menu text-capitalize"> {{ __('Users') }}</span>
                    </a>
                </li> --}}
            @elseif (Auth::user()->user_role == 2)
                <li class="{{ request()->routeIs('offweek') ? 'active' : '' }}">
                    <a href="{{ route('offweek') }}"
                        class="waves-effect {{ request()->routeIs('offweek') ? 'active' : '' }}">
                        <i class=" ti-na"></i><span class="hide-menu text-capitalize">
                            {{ __('Off Weeks') }}</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('logout') }}" class="waves-effect"
                    onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                    <i class="icon-logout fa-fw"></i><span class="hide-menu text-capitalize">
                        {{ __('Log out') }}</span>
                </a>
                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
