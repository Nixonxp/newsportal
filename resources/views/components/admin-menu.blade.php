<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @forelse($menu as $keySectionName => $menuItem)
            @if (!empty($menuItem))
                <li class="nav-header">{{__($keySectionName)}}</li>
            @endif
            @forelse($menuItem as $menuItemLv1)
                <li class="nav-item {{ $menuItemLv1['active'] ? 'menu-open' : '' }}">
                    <a href="{{ $menuItemLv1['link'] }}" class="nav-link {{ $menuItemLv1['active'] ? 'active' : '' }}">
                        <i class="nav-icon {{ $menuItemLv1['icon'] }}"></i>
                        <p>
                            {{ $menuItemLv1['text'] }}
                            @if($menuItemLv1['angle_left'])
                                <i class="right fas fa-angle-left"></i>
                            @endif
                        </p>
                    </a>
                    @isset($menuItemLv1['child'])
                        <ul class="nav nav-treeview">
                            @forelse($menuItemLv1['child'] as $menuItemLv2)
                                <li class="nav-item">
                                    <a
                                        href="{{ $menuItemLv2['link'] }}"
                                        class="nav-link {{ $menuItemLv2['active'] ? 'active' : '' }}">
                                        <i class="{{ $menuItemLv2['icon'] }} nav-icon"></i>
                                        <p>{{ $menuItemLv2['text'] }}</p>
                                    </a>
                                </li>
                            @empty
                            @endforelse
                        </ul>
                    @endisset
                </li>
            @empty
            @endforelse
        @empty
        @endforelse
    </ul>
</nav>
