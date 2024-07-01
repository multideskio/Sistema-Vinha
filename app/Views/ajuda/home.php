<?= $this->extend('ajuda/template') ?>


<?= $this->section('main') ?>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
    <?php
    use CodeIgniter\I18n\Time;
    foreach ($rows as $row) : ?>
        <div class="col list-blog"> 
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h4><?= $row['titulo'] ?></h4>
                    <p class="card-text"><?= character_limiter(strip_tags($row['conteudo']), 300, '...') ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="/ajuda/<?= $row['slug'] ?>" class="btn btn-outline-secondary">Ler tudo</a>
                        </div>
                        <?php
                        if ($row['updated_at']) :
                            $now = Time::now();
                            $createdAt = Time::parse($row['updated_at']);
                        ?>
                            <small class="text-muted"><?= $createdAt->humanize($now) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>