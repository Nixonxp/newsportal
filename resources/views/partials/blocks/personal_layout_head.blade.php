<div class="container">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar sidebar-personal">
            <div class="position-sticky pt-3 pb-5">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link @routeactive('personal.index')" aria-current="page" href="{{ route('personal.index') }}">
                            @lang('main.personal_area')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @routeactive('personal.authors')" aria-current="page" href="{{ route('personal.authors') }}">
                            @lang('main.subscribe_authors')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('logout') }}">
                            @lang('admin.logout')
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="row pt-3 pb-5 mb-3 border-bottom">

                <h1 class="h2 mb-5 h2-personal ml-3">@yield('h1')</h1><br>
                <div class="btn-toolbar mb-2 pb-5">
