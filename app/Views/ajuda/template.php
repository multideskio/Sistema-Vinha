<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Página de ajuda">
    <meta name="author" content="Multidesk.io">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <title>Ajuda</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        h1 {
            font-size: 28px;
            font-weight: 900;
        }

        h2 {
            font-size: 18px;
            font-weight: 700;
        }

        .list-blog h4 {
            text-transform: uppercase;
            text-align: center;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card-text {
            flex-grow: 1;
        }

        .card {
            transition: transform 0.5s;
            cursor: context-menu;
        }

        .card:hover {
            transform: scale(1.05);
        }
    </style>

</head>

<body>
    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="/ajuda" class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z" />
                    </svg>
                    <strong>Ajuda</strong>
                </a>
            </div>
        </div>
    </header>
    <main>
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">Como podemos ajudar hoje?</h1>
                    <?= form_open(site_url('ajuda'), 'method="GET"') ?>
                    <div class="mt-4 input-group">
                        <input type="text" class="form-control form-control-lg" placeholder="Buscar" name="search">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>
                    </div>
                    </form>
                    <?php
                    echo $textResult ;
                    ?>
                </div>
            </div>
        </section>
        <div class="album py-5 bg-light" id="ajuda">
            <div class="container">
                <?= $this->renderSection('main') ?>
            </div>
        </div>
    </main>
    <footer class="text-muted py-5 text-center">
        <div class="container">
            <p class="float-end mb-1">
                <a href="#">Voltar ao topo</a> - {elapsed_time}
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>