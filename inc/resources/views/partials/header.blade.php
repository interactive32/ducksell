<header class="main-header">
    <a href="{{ url('/') }}" class="logo"><b>{{ trans('app.ducksell') }}</b></a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('app.toggle_navigation') }}</span>
        </a>

        <a href="{{ url('/') }}" class="logo-mob"><b>{{ trans('app.ducksell') }}</b></a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @include('partials.notifications')
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img class="user-image" alt="User Image" src="{{ getGravatar(Auth::User()->email) }}">
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ getGravatar(Auth::User()->email) }}" class="img-circle" alt="User Image" />
                            <p>
                                {{ Auth::user()->name }}
                                <small>{{ trans('app.admin') }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('/profile') }}" class="btn btn-default btn-flat">{{ trans('app.profile') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/auth/logout') }}" class="btn btn-default btn-flat">{{ trans('app.sign_out') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>