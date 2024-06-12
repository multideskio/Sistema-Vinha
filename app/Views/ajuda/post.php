<?= $this->extend('ajuda/template') ?>
<?= $this->section('main') ?>
<?php use CodeIgniter\I18n\Time; ?>
<h1 class="text-center"><?= $result['titulo'] ?></h1>
<div class="mt-5 shadow-lg p-5 mb-5 bg-body rounded">
    <style>
        h2{
            font-size: 22px;
            margin-bottom: 1%;
        }
    </style>
    <?= $result['conteudo'] ?>
</div>
<div class="text-muted mt-5">
    <?php
    if ($result['updated_at']) :
        $now = Time::now();
        $createdAt = Time::parse($result['updated_at']);
    ?>
        <small class="text-muted"><?= $createdAt->humanize($now) ?></small>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>