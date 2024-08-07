<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title">
                    <span>Menu</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('gerente') ?>">
                        <i class="bi bi-speedometer"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('gerente/pagamentos') ?>">
                        <i class="bi bi-wallet"></i> <span>Pagamentos</span>
                    </a>
                </li>
                <li class="menu-title">
                    <i class="ri-more-fill"></i> <span>Genrencia</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('gerente/supervisores') ?>">
                    <i class="bi bi-person-badge"></i> <span>Supervisores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('gerente/pastores') ?>">
                        <i class="bi bi-person-lines-fill"></i> <span>Meus pastores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('gerente/igrejas') ?>">
                        <i class="bi bi-building"></i> <span>Minhas igrejas</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>