
<?= $this->extend('layouts/select')?>

<?= $this->section('content')?>
	<div class="pt-3 px-3">
                <? echo $output; ?>
            </div>
            <div style="text-align: right">
                        GESTIÓN COMERCIAL GARLIC &copy; 2023 - 
                        <a href="#" target="_blank">versión 6.3</a>  <a href="#" class="btn btn-warning btn-info">Buscar actualización</a>
                        <p></p>
            </div>
            <div style="text-align: right">
                        GESTIÓN CONTABLE ICAERP &copy; 2023 - 
                        <a href="#" target="_blank">versión 5.7</a>  <a href="#" class="btn btn-warning btn-info">Buscar actualización</a>
                        <p><br><br><br><br><br><br><br><br></p>
            </div>
<?= $this->endSection()?>