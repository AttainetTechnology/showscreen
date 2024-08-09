<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Proceso: <?= $proceso_principal['nombre_proceso'] ?></h2>


    <form action="<?= base_url('procesos/restriccion/' . $proceso_principal['id_proceso']); ?>" method="post">
        <div class="form-group">
            <label for="nombre_proceso">Nombre del Proceso</label>
            <input type="text" class="form-control" id="nombre_proceso" name="nombre_proceso" value="<?= esc($proceso_principal['nombre_proceso']); ?>" required>
        </div>
        <div class="form-group">
            <label for="estado_proceso">Estado del Proceso</label>
            <select class="form-control" id="estado_proceso" name="estado_proceso" required>
                <option value="activo" <?= $proceso_principal['estado_proceso'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?= $proceso_principal['estado_proceso'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <br>
        <h3 class="text-center mb-4">Editar Restricciones</h3>
        
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
        <button type="submit" class="btn btn-success float-end mt-3">Guardar Cambios</button>
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
