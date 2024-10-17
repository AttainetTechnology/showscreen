<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2 class="titlepedidosadd">Añadir Pedido</h2>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<style>
    /* Aplicar estilo al select de Select2 */
.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da; /* Mismo borde que otros campos */
    border-radius: 4px; /* Ajustar el borde redondeado */
   height: 35px;
}

/* Ajustar el texto del placeholder */
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6c757d; /* Color similar al placeholder en otros campos */
}

/* Quitar el borde azul que aparece al hacer clic */
.select2-container--default .select2-selection--single:focus {
    outline: none;
    box-shadow: none;
}

/* Estilo de la flecha desplegable de Select2 */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
}

</style>
<div class="container mt-4 addpedido">
    <form action="<?= base_url('pedidos/save') ?>" method="post">
        <div class="form-group">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control select2" required>
                <option value="" selected disabled hidden>Seleccione empresa</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>"><?= $cliente['nombre_cliente'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control">
        </div>
        <br>
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <br>
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
        </div>
        <br>
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
        </div>
        <br>
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?= esc($usuario_sesion['id_user']); ?>">
        <br>
        <div class="buttonsEditProductProveedAbajo">
            <button type="submit" class="btn btn-primary">Guardar Pedido</button>
            <button type="button" class="btn mb-3 volverButton" id="volverButton" style="margin-top:15px;">Volver</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        // Iniciar Select2 en el campo select
        $('#id_cliente').select2({
            placeholder: 'Seleccione empresa', // Placeholder para una guía intuitiva
            width: '100%' // Hacer que el select ocupe todo el ancho del contenedor
        });

        // Botón de volver
        document.getElementById('volverButton').addEventListener('click', function() {
            window.location.href = '<?= base_url('pedidos/enmarcha') ?>';
        });
    });
</script>

</script>

<?= $this->endSection() ?>