<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesos</title>
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Incluir Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Incluir jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<style>
    .modal-backdrop.show {
        background-color: #fff3cd;
    }
    .placeholder {
        user-select: none;
        pointer-events: none;
    }
    li.ui-state-default {
        list-style-type: none;
    }
    /* Estilo para las cartas */
    .ui-state-default {
        border: 1px solid #ddd;
        background-color: #f8f9fa;
        padding: 8px 16px;
        border-radius: 4px;
        margin-bottom: 4px;
        box-shadow: 0 2px 1px rgba(0,0,0,0.05);
        cursor: pointer; /* Cambio para mejorar la indicación de que se puede arrastrar */
    }
    /* Estilo cuando se arrastra */
    .ui-state-default.ui-sortable-helper {
        box-shadow: 0 4px 2px rgba(0,0,0,0.1);
    }
    /* Estilo para los contenedores conectados */
    .connectedSortable {
        min-height: 50px; /* Asegura que el contenedor siempre es visible y arrastrable */
        padding: 10px;
        border: 1px dashed #ccc;
    }
</style>
    <!-- Modal -->
    <div class="modal fade" id="procesosModal" tabindex="-1" role="dialog" aria-labelledby="procesosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="procesosModalLabel">Producto: <?= $producto->nombre_producto ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Todos los Procesos</h6>
                            <?php if(!empty($allProcesses)): ?>
                                <ul id="sortable" class="connectedSortable">
                                    <?php foreach($allProcesses as $proceso): ?>
                                        <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>"><?= $proceso->nombre_proceso ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No hay procesos disponibles.</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h6>Ordenar Procesos</h6>
                            <ul class="connectedSortable" style="border: 1px solid #000; margin: 10px; padding: 10px; min-height: 50px;" id="orderList">
                                <!-- Aquí se moverán los procesos para ordenar -->
                            </ul>
                            <input type="hidden" id="order" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-button" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary save-button" id="saveOrder">Guardar Orden</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Script para abrir el modal automáticamente y hacer la lista ordenable -->
    <script>
        //lleva al usuario a la página anterior
        $('.close-button').click(function() {
            window.history.back();
        });
        $('#procesosModal').on('hidden.bs.modal', function (e) {
            window.history.back();
        });
        $(document).ready(function() {
            // Mostrar el modal al cargar la página
            $('#procesosModal').modal('show');
        
            // Hacer que los elementos sean ordenables
            $("#sortable, .connectedSortable").sortable({
                connectWith: ".connectedSortable",
                items: "li:not(.placeholder)",
                update: function(event, ui) {
                    var order = [];
                    // Para cada elemento, guardar su id y su posición en el array 'order'
                    $('#orderList').children('li').each(function(index) {
                        var id = $(this).data('id');
                        if (id) {
                            order.push([id, index + 1]);
                        }
                    });
                    // Guardar el orden en el input oculto
                    $('#order').val(JSON.stringify(order));
                }
            }).disableSelection();
        
            // Mostrar u ocultar el placeholder dependiendo de si hay elementos
            $(".connectedSortable").on("sortreceive sortremove", function(event, ui) {
                var hasChildren = $(this).children('li').length > 0;
                $(this).find(".placeholder").toggle(!hasChildren);
            });
        
            // Al hacer click en 'Guardar Orden'
            $('#saveOrder').click(function() {
                // Obtener el orden desde el input oculto
                var order = JSON.parse($('#order').val());
                // Obtener el id_producto desde la URL
                var url = window.location.pathname;
                var id_producto = url.substring(url.lastIndexOf('/') + 1);
                var data = [];
                // Crear un array de objetos con id_producto, id_proceso y orden
                for (var i = 0; i < order.length; i++) {
                    data.push({
                        id_producto: id_producto,
                        id_proceso: order[i][0],
                        orden: order[i][1]
                    });
                }
                // Enviar los datos al servidor
                $.post('updateOrder', { data: JSON.stringify(data) })
                    .done(function() {
                        alert('Procesos guardados');
                    })
                    .fail(function() {
                        alert('Ha habido un error');
                    });
            });
        });
    </script>
</body>
</html>
