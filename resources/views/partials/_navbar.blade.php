<nav class="navbar is-light">
    <div class="navbar-brand">
        <a class="navbar-item" href="/">
            <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
        </a>
        <div class="navbar-burger burger" data-target="navbarExampleTransparentExample">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbarExampleTransparentExample" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="{{ route('blog.index') }}">
                Blog
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
                        <i class="fa fa-user-plus"></i>
                      </span>
                            <span>
                        Register
                      </span>
                        </a>
                    </p>
                    <p class="control">
                        <a class="button is-primary" href="{{ route('login') }}">
                      <span class="icon">
                        <i class="fa fa-sign-in"></i>
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
                    <a class="navbar-item">
                        Overview
                    </a>
                    <a class="navbar-item" href="{{ route('posts.index') }}">
                       View all posts
                    </a>
                    <a class="navbar-item" href="{{ route('categories.index') }}">
                        View categories
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
</nav>