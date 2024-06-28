<?= $this->include('partials/main') ?>

<head>
    <?php echo view('partials/title-meta', array('title' => 'Vinha Ministérios')); ?>
    <?= $this->include('partials/head-css') ?>
    <!--datatable css-->
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
    <!-- Sweet Alert css-->
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <?= $this->renderSection('css') ?>
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?= $this->include('igrejas/menu') ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php echo view('partials/page-title', array('pagetitle' => 'Vinha', 'title' => $titlePage)); ?>
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1"><?= saudacao() . ', ' . session('data')['name'] ?>!</h4>
                        </div>
                    </div>
                    <?= $this->renderSection('page') ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?= $this->include('partials/footer') ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <?= $this->include('partials/customizer') ?>
    <?= $this->include('partials/vendor-scripts') ?>
    <!-- App js -->
    <script src="/assets/js/app.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Plugin adicionais -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7"></script>

    <!-- cleave.js -->
    <script src="/assets/libs/cleave.js/cleave.min.js"></script>

    <!-- form masks init -->
    <script src="/assets/js/pages/form-masks.init.js"></script>

    <?= $this->renderSection('js') ?>

    <script>
        function recursoindisponivel() {
            Swal.fire({
                title: 'Recurso indisponivél no momento',
                type: 'error',
                confirmButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
            });
        }

        $(document).ready(function() {
            $('img').each(function() {
                var img = $(this);
                img.on('error', function() {
                    // Verifique se a imagem de substituição também está acessível
                    var placeholderImg = new Image();
                    placeholderImg.src = 'https://placehold.co/50/00000/FFF?text=V';
                    placeholderImg.onload = function() {
                        img.attr('src', placeholderImg.src);
                    };
                    placeholderImg.onerror = function() {
                        console.error('Placeholder image could not be loaded.');
                    };
                });

                // Forçar disparo do evento de erro para imagens já quebradas
                if (img[0].complete && img[0].naturalHeight === 0) {
                    img.trigger('error');
                }
            });
        });
    </script>

</body>

</html>