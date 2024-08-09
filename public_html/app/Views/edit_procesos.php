<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Restricciones del Proceso: <?= $proceso_principal['nombre_proceso'] ?></h2>
    <br>
    <form action="<?= base_url('procesos/guardarRestriccion') ?>" method="post">
        <input type="hidden" name="primaryKey" value="<?= $primaryKey ?>">

        <?php 
            $restricciones_actuales = explode(',', $proceso_principal['restriccion']);
        ?>

        <div class="row">
            <?php foreach ($procesos as $proceso): ?>
                <?php 
                    // Verificamos si el proceso actual está en las restricciones
                    $is_restricted = in_array($proceso['id_proceso'], $restricciones_actuales); 
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card proceso-box <?= $is_restricted ? 'selected border-primary shadow' : '' ?>" data-id="<?= $proceso['id_proceso'] ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $proceso['nombre_proceso'] ?></h5>
                        </div>
                    </div>
                    <input type="checkbox" name="restricciones[]" value="<?= $proceso['id_proceso'] ?>" class="d-none" <?= $is_restricted ? 'checked' : '' ?>>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary float-end mt-3">Guardar Restricciones</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.proceso-box').forEach(function(box) {
            box.addEventListener('click', function() {
                this.classList.toggle('selected');
                this.classList.toggle('border-primary');
                this.classList.toggle('shadow');
                var checkbox = this.nextElementSibling;
                checkbox.checked = !checkbox.checked;
            });
        });
    });
</script>

<style>
    .proceso-box {
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }
    .proceso-box.selected {
        background-color: #f0f8ff;
    }
    .proceso-box:hover {
        box-shadow: 0 0 11px rgba(33,33,33,.2); 
    }
</style>
<?= $this->endSection() ?>
