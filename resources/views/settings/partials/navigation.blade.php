<div class="w-full md:w-64 shrink-0 border-r border-gray-200 pr-4">
    <nav class="bg-gray-50 rounded-lg overflow-hidden">
        <ul class="divide-y divide-gray-200">
            <li>
                <a href="{{ route('settings.profile.edit') }}" @class([
                    'bg-gray-100 block px-4 py-3 text-gray-700 hover:bg-white' => !request()->routeIs(
                        'settings.profile.*'),
                    'bg-white block px-4 py-3  text-gray-900 font-medium' => request()->routeIs(
                        'settings.profile.*'),
                ])>
                    Profil
                </a>
            </li>
            <li>
                <a href="{{ route('settings.password.edit') }}" @class([
                    'bg-gray-100 block px-4 py-3 text-gray-700 hover:bg-white' => !request()->routeIs(
                        'settings.password.*'),
                    'bg-white  block px-4 py-3 text-gray-900 font-medium' => request()->routeIs(
                        'settings.password.*'),
                ])>
                    Password
                </a>
            </li>
            <li>
                <a href="{{ route('settings.appearance.edit') }}" @class([
                    'bg-gray-100 block px-4 py-3 text-gray-700 hover:bg-white' => !request()->routeIs(
                        'settings.appearance.*'),
                    'bg-white block px-4 py-3  text-gray-900 font-medium' => request()->routeIs(
                        'settings.appearance.*'),
                ])>
                    Tampilan
                </a>
            </li>
        </ul>
    </nav>
</div>
