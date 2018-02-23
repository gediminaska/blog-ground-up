<div class="side-menu" id="admin-side-menu">
    <aside class="menu">
        <p class="menu-label">
            General
        </p>
        <ul class="menu-list">
            <li><a href="{{route('manage.dashboard')}}" class="{{Nav::isRoute('manage.dashboard')}}">Statistics</a></li>
        </ul>
        <p class="menu-label">
            Content
        </p>
        <ul class="menu-list">
            <li><a class="has-submenu {{Nav::hasSegment(['posts'], 2)}}">Posts</a>
                <ul class="submenu">
                    <li><a href="{{route('posts.create')}}" class="{{Nav::isRoute('posts.create')}}"><span class="icon"><i class="fas fa-plus"></i></span><span> New post</span></a></li>
                    <li><a href="{{route('posts.index')}}" class="{{Nav::isRoute(['posts.index', 'posts.edit', 'posts.show'])}}">{{Laratrust::can('publish-post') ? 'All posts' : 'My posts'}} & drafts</a></li>
                </ul>
            </li>
            <li><a {{Laratrust::can('read-category') ? "href=" . route('categories.index') : ''}} class="{{Nav::isResource('categories')}} {{Laratrust::can('read-category') ? "" : 'deactivated'}}">Categories</a></li>
        </ul>
        <p class="menu-label">
            Administration
        </p>
        <ul class="menu-list">
            <li><a {{Laratrust::can('read-users') ? "href=" . route('users.index') : ''}}  class="{{Nav::isResource('users')}} {{Laratrust::can('read-users') ? '' : 'deactivated'}}">Manage users</a></li>
            <li><a class="{{Laratrust::can('read-roles')||Laratrust::can('read-permission') ? 'has-submenu' : 'deactivated'}} {{Nav::hasSegment(['roles', 'permissions'], 2)}}">Roles and permissions</a>
                <ul class="submenu">
                    <li><a {{Laratrust::can('read-roles') ? "href=" . route('roles.index') : ''}} class="{{Nav::isResource('roles')}} second-lvl {{Laratrust::can('read-roles') ? '' : 'deactivated'}}">Roles</a></li>
                    <li><a {{Laratrust::can('read-permission') ? "href=" . route('permissions.index') : ''}} class="{{Nav::isResource('permissions')}} second-lvl {{Laratrust::can('read-permission') ? '' : 'deactivated'}}">Permissions</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>