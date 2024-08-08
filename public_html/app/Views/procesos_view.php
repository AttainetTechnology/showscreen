<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
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
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        /* Cambio para mejorar la indicación de que se puede arrastrar */
    }

    /* Estilo cuando se arrastra */
    .ui-state-default.ui-sortable-helper {
        box-shadow: 0 4px 2px rgba(0, 0, 0, 0.1);
    }

    /* Estilo para los contenedores conectados */
    .connectedSortable {
        min-height: 50px;
        /* Asegura que el contenedor siempre es visible y arrastrable */
        padding: 10px;
        border: 1px dashed #ccc;
    }
</style>

<div class="modal fade" id="procesosModal" tabindex="-1" role="dialog" aria-labelledby="procesosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="procesosModalLabel">Producto: <?= $producto->nombre_producto ?></h5>
                <button type="button" class="btn-close-custom" aria-label="Close" onclick="window.history.back();">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Todos los Procesos</h6>
                        <?php if (!empty($allProcesses)) : ?>
                            <ul id="sortable" class="connectedSortable">
                                <?php foreach ($allProcesses as $proceso) : ?>
                                    <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>">
                                        <?= $proceso->nombre_proceso ?>
                                        <i class="fas <?= $proceso->restriccion ? 'fa-lock' : 'fa-lock-open' ?> candado" style="color: <?= $proceso->restriccion ? 'red' : 'gray' ?>;" data-id="<?= $proceso->id_proceso ?>" data-restriccion="<?= $proceso->restriccion ?>">
                                        </i>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>No hay procesos disponibles.</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h6>Ordenar Procesos</h6>
                        <ul class="connectedSortable" style="border: 1px solid #000; margin: 10px; padding: 10px; min-height: 50px;" id="orderList">
                            <?php if (!empty($procesos)) : ?>
                                <?php foreach ($procesos as $proceso) : ?>
                                    <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>"><?= $proceso->nombre_proceso ?></li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li class="placeholder">No hay procesos asociados.</li>
                            <?php endif; ?>
                        </ul>
                        <input type="hidden" id="order" value="">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-button" data-dismiss="modal" onclick="window.location.href='<?= base_url('productos') ?>';">Cerrar</button>

                <button type="button" class="btn btn-primary save-button" id="saveOrder">Guardar Orden</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
    $(document).ready(function() {
        $('#procesosModal').modal('show');

        $("#sortable, .connectedSortable").sortable({
            connectWith: ".connectedSortable",
            items: "li:not(.placeholder)",
            update: function(event, ui) {
                var order = [];
                $('#orderList').children('li').each(function(index) {
                    var id = $(this).data('id');
                    if (id) {
                        order.push([id, index + 1]);
                    }
                });
                $('#order').val(JSON.stringify(order));
            }
        }).disableSelection();

        $(".connectedSortable").on("sortreceive sortremove", function(event, ui) {
            var hasChildren = $(this).children('li').length > 0;
            $(this).find(".placeholder").toggle(!hasChildren);
        });

        $('.candado').click(function() {
            var icon = $(this);
            var idProceso = icon.data('id');
            var restriccion = icon.data('restriccion');
            var nuevaRestriccion = restriccion ? 0 : 1;

            $.post('<?= base_url('procesos/updateRestriction') ?>', {
                id_proceso: idProceso,
                restriccion: nuevaRestriccion
            }).done(function() {
                icon.toggleClass('fa-lock fa-lock-open');
                icon.css('color', nuevaRestriccion ? 'red' : 'gray');
                icon.data('restriccion', nuevaRestriccion);
            }).fail(function() {
                alert('Error al actualizar la restricción');
            });
        });

        $('#saveOrder').click(function() {
            var order = JSON.parse($('#order').val());
            var url = window.location.pathname;
            var id_producto = url.substring(url.lastIndexOf('/') + 1);
            var data = [];
            for (var i = 0; i < order.length; i++) {
                data.push({
                    id_producto: id_producto,
                    id_proceso: order[i][0],
                    orden: order[i][1]
                });
            }
            $.post('updateOrder', {
                    data: JSON.stringify(data)
                })
                .done(function() {
                    alert('Procesos guardados');
                    window.location.href = '<?= base_url('productos') ?>';
                })
                .fail(function() {
                    alert('Ha habido un error');
                });
        });

        $('#procesosModal').on('hidden.bs.modal', function() {
            window.history.back();
        });
    });
</script>

<?= $this->endSection() ?>