<div class="side-menu" id="admin-side-menu">
    <aside class="menu">
        <p class="menu-label">
            General
        </p>
        <ul class="menu-list">
            <li><a href="{{route('manage.dashboard')}}" class="{{Nav::isRoute('manage.dashboard')}}">Dashboard</a></li>
        </ul>
        <p class="menu-label">
            Content
        </p>
        <ul class="menu-list">
            <li><a href="{{route('posts.create')}}" class="{{Nav::isRoute('posts.create')}}"><span class="icon"><i class="fas fa-plus"></i></span><span> New post</span></a></li>
            <li><a href="{{route('posts.index')}}" class="{{Nav::isRoute(['posts.index', 'posts.edit', 'posts.show'])}}">Posts</a></li>
            <li><a href="{{route('categories.index')}}" class="{{Nav::isResource('categories')}}">Categories</a></li>
        </ul>
        <p class="menu-label">
            Administration
        </p>
        <ul class="menu-list">
            <li><a href="{{route('users.index')}}" class="{{Nav::isResource('users')}}">Manage users</a></li>
            <li><a class="has-submenu {{Nav::hasSegment(['roles', 'permissions'], 2)}}">Roles and permissions</a>
                <ul class="submenu">
                    <li><a href="{{route('roles.index')}}" class="{{Nav::isResource('roles')}} second-lvl">Roles</a></li>
                    <li><a href="{{route('permissions.index')}}" class="{{Nav::isResource('permissions')}} second-lvl">Permissions</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>