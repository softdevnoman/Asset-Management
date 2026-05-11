<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo ">
        <a href="javascript:void(0);" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/icons/brands/logo.png') }}" alt="EQTRAK Logo" width="32">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">EQTRAK</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div>E-Commerce</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div> Category</div>
                    </a>
                </li>
            </ul>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div> Accounts</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
@push('scripts')
    <script>
        $(document).ready(function(){
            // console.log('this us ahrfnaiy');
            const menuItem = document.querySelectorAll('.menu-item');
            menuItem.forEach(function(item){
                item.addEventListener('click', function(){
                    item.classList.toggle('active');
                });
            });
        });
    </script>
@endpush
