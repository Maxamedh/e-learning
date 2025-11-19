<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<?php
helper('template');
$content = extract_template_content('templete/fees.html');
?>

<div class="main-content">
<?= $content['main'] ?>
</div>

<?= $content['modals'] ?>

<?= $this->endSection() ?>

