<div class="side-menu">
    <aside class="menu">
        <p class="menu-label">
            General
        </p>
        <ul class="menu-list">
            <li><a href="{{route('manage.dashboard')}}">Dashboard</a></li>
        </ul>
        <p class="menu-label">
            Administration
        </p>
        <ul class="menu-list">
            <li><a href={{route('users.index')}}>Manage users</a></li>
            <li><a href={{route('permissions.index')}}>Roles and permissions</a></li>
        </ul>
    </aside>
</div>