<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo ">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo"
                style="flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('assets/img/icons/brands/logo.png') }}" alt="Logo"
                    style="height: 30px; width: auto; max-width: 100%; object-fit: contain; flex-shrink: 0;">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">{{ config('app.name', 'Asset Management') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @if (auth()->user()->role === 'admin')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-smart-home"></i>
                    <div>Inventory Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('assets') }}" class="menu-link">
                            <div> Assets </div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        <!-- Accounts -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div>Accounts Management</div>
            </a>
            @if (auth()->user()->role === 'super_admin')
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('accounts') }}" class="menu-link">
                            <div> Accounts</div>
                        </a>
                    </li>
                </ul>
            @endif
            @if (auth()->user()->role === 'admin')
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('employees') }}" class="menu-link">
                            <div> Employee </div>
                        </a>
                    </li>
                </ul>
            @endif
        </li>
    </ul>
</aside>
@push('scripts')
    <script>
        $(document).ready(function() {
            // console.log('this us ahrfnaiy');
            const menuItem = document.querySelectorAll('.menu-item');
            menuItem.forEach(function(item) {
                item.addEventListener('click', function() {
                    item.classList.toggle('active');
                });
            });
        });
    </script>
@endpush
