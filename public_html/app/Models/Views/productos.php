<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>

</head>
<body>

    <?php include('navegacion.php'); ?>

    <div style='height:20px;'></div>  
    <div style="padding: 10px">
        <?php echo $output->output; ?>
    </div>
    <div>
        <button id="add-producto-btn" data-toggle="modal" data-target="#addProductoModal">Añadir producto</button>
    </div>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

    <!-- Modal -->
    <div class="modal fade" id="addProductoModal" tabindex="-1" role="dialog" aria-labelledby="addProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductoModalLabel">Añadir producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-producto-form">
                        <div class="form-group">
                            <label for="nombre_producto">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                        </div>
                        <div class="form-group">
                            <label for="id_familia">ID de la familia</label>
                            <select class="form-control" id="id_familia" name="id_familia">
                                <option value="" disabled selected>Selecciona una familia</option>
                                <?php
                                foreach ($familia as $fam) {
                                    echo "<option value='{$fam->id_familia}'>{$fam->nombre}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="imagen">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" required>
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="text" class="form-control" id="precio" name="precio">
                        </div>
                        <div class="form-group">
                            <label for="id_activo">ID Activo</label>
                            <select class="form-control" id="id_activo" name="id_activo">
                                <?php
                                foreach ($booleano as $bool) {
                                    $selected = $bool->valor == 'Activo' ? 'selected' : '';
                                    echo "<option value='{$bool->id_activo}' $selected>{$bool->valor}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="save-producto-btn">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
    $(document).ready(function() {
        $('#save-producto-btn').click(function() {
            var data = $('#add-producto-form').serialize();

            $.ajax({
                url: '<?php echo site_url('productos/add_producto') ?>',
                type: 'POST',
                data: data,
                success: function() {
                    // Aquí puedes hacer algo cuando la solicitud sea exitosa, como cerrar el modal y recargar la página
                    $('#addProductoModal').modal('hide');
                    location.reload();
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#save-producto-btn').click(function(e) {
            e.preventDefault();

            var data = new FormData($('#add-producto-form')[0]);

            $.ajax({
                url: '<?php echo site_url('productos/add_producto') ?>',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
            })
            .done(function(response) {
                // Aquí puedes hacer algo cuando la solicitud sea exitosa, como cerrar el modal y recargar la página
                $('#addProductoModal').modal('hide');
                location.reload();

                // Mostrar un mensaje de éxito
                alert(response.message);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar los errores
                console.error("Error: ", textStatus, errorThrown);
                alert('Hubo un error al guardar el producto. Por favor, inténtalo de nuevo.');
            });
        });
    });
</script>
</html>