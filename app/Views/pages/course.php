<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<?php
helper('template');
$content = extract_template_content('templete/course.html');
?>

<div class="main-content">
<?= $content['main'] ?>
</div>

<?= $content['modals'] ?>

<script src="<?= base_url('assets/js/course-management.js') ?>"></script>

<?= $this->endSection() ?>

