{{--<nav class="navbar is-light">--}}
    {{--<div class="container">--}}
        {{--<div class="navbar-header">--}}

            {{--<!-- Collapsed Hamburger -->--}}
            {{--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">--}}
                {{--<span class="sr-only">Toggle Navigation</span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
            {{--</button>--}}

            {{--<!-- Branding Image -->--}}
            {{--<a class="navbar-brand" href="{{ url('/') }}">--}}
                {{--{{ config('app.name', 'Laravel') }}--}}
            {{--</a>--}}
        {{--</div>--}}

        {{--<div class="collapse navbar-collapse" id="app-navbar-collapse">--}}
            {{--<!-- Left Side Of Navbar -->--}}
            {{--<ul class="nav navbar-nav">--}}
                {{--&nbsp;--}}
            {{--</ul>--}}

            {{--<!-- Right Side Of Navbar -->--}}
            {{--<ul class="nav navbar-nav navbar-right">--}}
                {{--<!-- Authentication Links -->--}}
                {{--@guest--}}
                    {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                    {{--<li><a href="{{ route('register') }}">Register</a></li>--}}
                    {{--@else--}}
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">--}}
                                {{--{{ Auth::user()->name }} <span class="caret"></span>--}}
                            {{--</a>--}}

                            {{--<ul class="dropdown-menu">--}}

                                {{--<li>--}}
                                    {{--{{ Html::linkRoute('categories.index', 'View categories') }}--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--{{ Html::linkRoute('posts.index', 'View all posts') }}--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="{{ route('logout') }}"--}}
                                       {{--onclick="event.preventDefault();--}}
                                                     {{--document.getElementById('logout-form').submit();">--}}
                                        {{--Logout--}}
                                    {{--</a>--}}

                                    {{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
                                        {{--{{ csrf_field() }}--}}
                                    {{--</form>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--@endguest--}}
            {{--</ul>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</nav>--}}

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
        </div>



        <div class="navbar-end">


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
                    </a>
                </div>

                        @endguest
                </div>

        </div>
    </div>
</nav>