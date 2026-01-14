<div class="sidebar bg-gray-800 text-white h-screen w-64 p-4">
    <h2 class="text-xl font-bold mb-6">SysAdmin Panel</h2>

    <ul>
        <li>
            <a href="{{ route('dashboard') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('servers.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                <i class="fas fa-server mr-2"></i> Servers
            </a>
        </li>

        <li>
            <a href="{{ route('users.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                <i class="fas fa-users mr-2"></i> Users
            </a>
        </li>

        @if($role === 'admin')
            <li>
                <a href="{{ route('settings') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                    <i class="fas fa-cogs mr-2"></i> Settings
                </a>
            </li>
        @endif
    </ul>
</div>