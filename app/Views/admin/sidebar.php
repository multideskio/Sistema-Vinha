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
                    <a class="nav-link menu-link" href="<?= site_url('admin') ?>">
                        <i class="bi bi-speedometer"></i> <span>Dashboards</span>
                    </a>
                </li>
                <!-- end Dashboard Menu -->
                <li class="menu-title">
                    <span>CADASTROS</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/regiao') ?>">
                        <i class="bi bi-geo-alt"></i> <span>Região</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/gerentes') ?>">
                        <i class="bi bi-person-check"></i> <span>Gerentes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/supervisores') ?>">
                        <i class="bi bi-person-badge"></i> <span>Supervisores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/pastores') ?>">
                        <i class="bi bi-person-lines-fill"></i> <span>Pastores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/igrejas') ?>">
                        <i class="bi bi-building"></i> <span>Igrejas</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/usuarios') ?>">
                        <i class="ri-group-line"></i> <span>Usuarios</span>
                    </a>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Financeiro</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/recebimento') ?>">
                        <i class="ri-dashboard-2-line"></i> <span>Transações</span>
                    </a>
                </li>-->
                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/retorno') ?>">
                        <i class="ri-dashboard-2-line"></i> <span>Retorno</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/remessa') ?>">
                        <i class="ri-dashboard-2-line"></i> <span>Remessa</span>
                    </a>
                </li>-->
                <li class="menu-title">
                    <span>Configurações</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/config') ?>">
                        <i class="bi bi-display"></i> <span>Plataforma</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/gateways') ?>">
                        <i class="bi bi-plug"></i> <span>Gateways</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/admins') ?>">
                        <i class="bi bi-person-badge-fill"></i> <span>Administradores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('admin/ajuda') ?>">
                        <i class="bi bi-question-circle"></i> <span>Ajuda</span>
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