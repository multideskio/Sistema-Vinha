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
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
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
                    <a class="nav-link menu-link" href="<?= site_url('igreja') ?>">
                        <i class="bi bi-speedometer"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">
                    <i class="ri-more-fill"></i> <span>Pagamentos</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('igreja/pix') ?>">
                        <i class="bi bi-cash"></i> <span>PIX</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('igreja/boleto') ?>">
                        <i class="bi bi-receipt"></i> <span>Boleto</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('igreja/credito') ?>">
                        <i class="bi bi-credit-card"></i> <span>Cartão de crédito</span>
                    </a>
                </li>
                <!--
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('igreja/debito') ?>">
                        <i class="bi bi-wallet"></i> <span>Cartão de débito</span>
                    </a>
                </li> -->



                <li class="menu-title">
                    <i class="ri-more-fill"></i> <span>Financeiro</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= site_url('igreja/transacoes') ?>">
                        <i class="bi bi-arrow-left-right"></i><span>Transações</span>
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