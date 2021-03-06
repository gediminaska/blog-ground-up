<nav class="navbar has-shadow">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item is-paddingless" href="{{route('welcome')}}">
                <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
            </a>
            @if (Request::segment(1) == "manage")
                <a class="navbar-item is-hidden-desktop" id="admin-slideout-button">
                    <span class="icon"><i class="fa fa-arrow-alt-circle-right"></i></span>
                </a>
            @endif

            <div class="navbar-burger burger" data-target="navbarExampleTransparentExample">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div id="navbarExampleTransparentExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item is-tab {{ Request::is('blog'.'*') ? 'is-active' : '' }}" href="{{ route('blog.index') }}">
                    Blog
                </a>
                <a class="navbar-item is-tab {{ Request::is('email') ? 'is-active' : '' }}" href="{{ route('email') }}">
                    Email
                </a>
                <a class="navbar-item is-tab {{ Request::is('contact') ? 'is-active' : '' }}" href="{{ route('contact') }}">
                    Contact
                </a>
                {{--<div class="navbar-item has-dropdown is-hoverable">--}}
                {{--<a class="navbar-link" href="/documentation/overview/start/">--}}
                {{--Docs--}}
                {{--</a>--}}
                {{--<div class="navbar-dropdown is-boxed">--}}
                {{--<a class="navbar-item" href="/documentation/overview/start/">--}}
                {{--Overview--}}
                {{--</a>--}}
                {{--<a class="navbar-item" href="https://bulma.io/documentation/modifiers/syntax/">--}}
                {{--Modifiers--}}
                {{--</a>--}}
                {{--<a class="navbar-item" href="https://bulma.io/documentation/columns/basics/">--}}
                {{--Columns--}}
                {{--</a>--}}
                {{--<a class="navbar-item" href="https://bulma.io/documentation/layout/container/">--}}
                {{--Layout--}}
                {{--</a>--}}
                {{--<a class="navbar-item" href="https://bulma.io/documentation/form/general/">--}}
                {{--Form--}}
                {{--</a>--}}
                {{--<hr class="navbar-divider">--}}
                {{--<a class="navbar-item" href="https://bulma.io/documentation/elements/box/">--}}
                {{--Elements--}}
                {{--</a>--}}
                {{--<a class="navbar-item is-active" href="https://bulma.io/documentation/components/breadcrumb/">--}}
                {{--Components--}}
                {{--</a>--}}
                {{--</div>--}}

            </div>

            @guest
                <div class="navbar-item">
                    <div class="field is-grouped">
                        <p class="control">
                            <a class="button" href="{{ route('register') }}">
                      <span class="icon">
                        <i class="fas fa-user-plus"></i>
                      </span>
                                <span>
                        Register
                      </span>
                            </a>
                        </p>
                        <p class="control">
                            <a class="button is-primary" href="{{ route('login') }}">
                      <span class="icon">
                        <i class="fas fa-sign-in-alt"></i>
                      </span>
                                <span>Login</span>
                            </a>
                        </p>
                    </div>
                </div>
            @else
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link has-dropdown is-hoverable">
                        Hi, {{ Auth::user()->name }}
                    </a>

                    <div class="navbar-dropdown is-right">
                        <a class="navbar-item"  href="{{ route('my.account') }}">
                            My account
                        </a>
                        <a class="navbar-item" href="{{ route('manage.dashboard') }}">
                            Manage
                        </a>
                        <hr class="navbar-divider">
                        <a class="navbar-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>

</nav>