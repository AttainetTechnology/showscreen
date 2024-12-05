
<?= $this->extend('layouts/main')?>

<?= $this->section('content')?>
	<div class="">
        <?php if (isset($mensaje)): ?>
        <?= $mensaje ?>
        <?php endif; ?>
                <? echo $output; ?>
            </div>
<?= $this->endSection()?>