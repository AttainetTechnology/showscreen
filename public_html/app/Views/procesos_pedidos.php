<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORGANIZADOR</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/organizador.css') ?>?v=<?= time() ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <!-- Cargamos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Iconos Bootstrap --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div id="organizador">
        <div class="column" id="col1">
            <h4>Máquinas</h4>
            <button id="verTodo" class="btn btn-warning btn-sm">Ver Todo</button><br><br>
            <?php foreach ($maquinas as $maquina): ?>
                <div class="boton-maquina">
                <button class="btn maquina btn-sm" data-id-maquina="<?php echo $maquina['id_maquina']; ?>" data-nombre="<?php echo $maquina['nombre']; ?>" onclick="filtrarProcesosPorMaquina('<?php echo $maquina['id_maquina']; ?>', '<?php echo $maquina['nombre']; ?>')"><?php echo $maquina['nombre']; ?></button>
                </div>
                <?php endforeach; ?>
    
        </div>
        <div class="column" id="col2">
            <div class="cabecera">
                <h4>Procesos listos para producir</h4>
                <div id="searchContainer">              
                    <select id="searchInput" style="width: 60%">
                        <option value="">Seleccione un proceso...</option>
                        <?php if(isset($procesos)): ?>
                            <?php foreach ($procesos as $proceso): ?>
                                <option value="<?= esc($proceso['nombre_proceso']) ?>"><?= esc($proceso['nombre_proceso']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button id="clearFilters" class="btn btn-sm btn-light">Eliminar Filtros</button>
                </div>
            </div>
            <div class="resultados">
            <table id="Tabla2" class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>id</th>
                        <th>
                            Cliente
                            <select id="clienteFilter" style="width: 100%;" onchange="filtrarPorCliente(this.value);">
                                <option value="">Todos</option>
                                <?php if(isset($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= esc($cliente['nombre_cliente']) ?>"><?= esc($cliente['nombre_cliente']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </th>
                        <th>
                        Medidas
                        <select id="medidasFilter" style="width: 100%;" onchange="filtrarPorMedida(this.value);">
                            <option value="">Orden ascendente</option>
                            <option value="iniciales">Medidas Iniciales</option>
                            <option value="finales">Medidas Finales</option>
                        </select>
                         </th>
                        <th>Fecha Entrega</th>
                        <th>Producto</th>
                        <th>Nº Piezas</th>
                        <th>Proceso</th>
                        <th>Base</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lineas as $linea): ?>
                    <tr class="linea" 
                        data-nombre-cliente="<?= esc($linea['cliente']); ?>" 
                        data-nombre-proceso="<?= esc($linea['proceso']); ?>" 
                        data-med-inicial="<?= isset($linea['med_inicial']) ? esc($linea['med_inicial']) : '0'; ?>" 
                        data-med-final="<?= isset($linea['med_final']) ? esc($linea['med_final']) : '0'; ?>">
                        <td><input type="checkbox" name="selectedLine[]"></td>
                        <td><?= $linea['id_linea_pedido']; ?></td>
                        <td><?= $linea['cliente'] ?></td>
                        <td><?= $linea['medidas'] ?></td>
                        <td><?= $linea['fecha'] ?></td>
                        <td><?= $linea['producto'] ?></td>
                        <td><?= $linea['n_piezas'] ?></td>
                        <td><?= $linea['proceso'] ?></td>
                        <td><?= $linea['base'] ?></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
        <div class="column" id="col3">
            <button data-action="move-left" class="btn btn-md btn-primary"><i class="bi bi-arrow-left"></i></button><br>
            <button data-action="move-right" class="btn btn-md btn-primary"><i class="bi bi-arrow-right"></i></button><br>
            <button data-action="confirm" class="btn btn-md btn-info"><i class="bi bi-floppy"></i></button><br>
            <button data-action="btn-terminado" class="btn btn-md btn-success"><i class="bi bi-clipboard2-check"></i></button><br>
            <button data-action="btn-imprimir"  onclick="printDiv('printableArea')" class="btn btn-secondary btn-md"><i class='bi bi-printer'></i></button><br>
            <button data-action="cancelar" onclick="window.location.reload();"  class="btn btn-md btn-warning"><i class="bi bi-arrow-clockwise"></i></button><br>
        </div>
        <div class="column" id="col4">
            <div class="cabecera">
            <h4 id="tituloProcesosEnMaquina">Procesos en máquina</h4>
            </div>
            <div class="resultados">
            <table id="sortableTable" class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>id</th>
                        <th>
                            Cliente
                            <select id="clienteFilterCol4" style="width: 100%;" onchange="filtrarPorClienteCol4(this.value);">
                                <option value="">Todos</option>
                                <?php if(isset($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= esc($cliente['nombre_cliente']) ?>"><?= esc($cliente['nombre_cliente']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </th>
                        <th>Medidas</th>
                        <th>Fecha Entrega</th>
                        <th>Producto</th>
                        <th>Nº Piezas</th>
                        <th>Proceso</th>
                        <th>Base</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lineasEstado3 as $linea): ?>
                <tr class="linea" 
                    data-nombre-cliente="<?= esc($linea['cliente']) ?>" 
                    data-nombre-proceso="<?= esc($linea['proceso']); ?>" 
                    data-id-maquina="<?= $linea['id_maquina']; ?>" 
                    data-estado="<?= esc($linea['guardado']); ?>"> 
                    <td><input type="checkbox" name="selectedLine[]"></td>
                    <td><?= $linea['id_linea_pedido']; ?></td>
                    <td><?= $linea['cliente'] ?></td>
                    <td><?= $linea['medidas'] ?></td>
                    <td><?= $linea['fecha'] ?></td>
                    <td><?= $linea['producto'] ?></td>
                    <td><?= $linea['n_piezas'] ?></td>
                    <td><?= $linea['proceso'] ?></td>
                    <td><?= $linea['base'] ?></td>
                </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            </div>
        </div>
        <div id="printableArea" style="display: none;">
            <div id="fondo">
                <div id="printableContent">
                    <h1>Informe de Procesos en Máquinas</h1>
                    <?php foreach ($maquinas as $maquina): ?>
                        <div>
                            <h2>Máquina: <?= esc($maquina['nombre']); ?></h2>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Línea Pedido</th>
                                        <th>Cliente</th>
                                        <th>Medidas</th>
                                        <th>Fecha Entrega</th>
                                        <th>Producto</th>
                                        <th>Nº Piezas</th>
                                        <th>Proceso</th>
                                        <th>Base</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lineasEstado3 as $linea): ?>
                                        <?php if ($linea['id_maquina'] == $maquina['id_maquina']): ?>
                                            <tr>
                                                <td><?= esc($linea['id_linea_pedido']); ?></td>
                                                <td><?= esc($linea['cliente']); ?></td>
                                                <td><?= esc($linea['medidas']); ?></td>
                                                <td><?= esc($linea['fecha']); ?></td>
                                                <td><?= esc($linea['producto']); ?></td>
                                                <td><?= esc($linea['n_piezas']); ?></td>
                                                <td><?= esc($linea['proceso']); ?></td>
                                                <td><?= esc($linea['base']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
       function printDiv(divId) {
    // Verificar si hay una máquina seleccionada
    if (!selectedMachineId) {
        alert('¡Seleccione una máquina antes de imprimir!');
        return;
    }

    // Generar contenido imprimible solo para la máquina seleccionada
    generarContenidoImprimible();

    var printContents = document.getElementById(divId).innerHTML;

    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Impresión</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
    printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

        document.addEventListener('DOMContentLoaded', function() {
            actualizarColores();
            generarContenidoImprimible();
            seleccionarMaquinaGuardada();

        });

        function generarContenidoImprimible() {
            let printableArea = document.getElementById('printableArea');
    let maquinas = <?php echo json_encode($maquinas); ?>;
    let lineasEstado3 = <?php echo json_encode($lineasEstado3); ?>;

    // Obtener la fecha actual
    let fechaActual = new Date();
    // Formatear la fecha (p.ej., "DD/MM/YYYY")
    let fechaFormateada = fechaActual.getDate() + '/' + (fechaActual.getMonth() + 1) + '/' + fechaActual.getFullYear();

    let content = document.getElementById('printableContent');
    // Añadir la fecha al contenido imprimible
    content.innerHTML = `<h1>Informe de Procesos en Máquinas -  ${fechaFormateada}</h1>`;

    // Filtrar las máquinas para encontrar la seleccionada
    let maquinaSeleccionada = maquinas.find(maquina => maquina.id_maquina === selectedMachineId);
    if (maquinaSeleccionada) {
        let lineasMaquina = lineasEstado3.filter(linea => linea.id_maquina === selectedMachineId);

        if (lineasMaquina.length > 0) {
            let maquinaDiv = document.createElement('div');
            maquinaDiv.innerHTML = `<h2>Máquina: ${maquinaSeleccionada.nombre}</h2>`;

            let table = document.createElement('table');
            table.className = 'table table-sm table-hover';
            table.innerHTML = `
                <thead>
                    <tr>
                        <th>ID Línea Pedido</th>
                        <th>Cliente</th>
                        <th>Medidas</th>
                        <th>Fecha Entrega</th>
                        <th>Producto</th>
                        <th>Nº Piezas</th>
                        <th>Proceso</th>
                        <th>Base</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;
            let tbody = table.querySelector('tbody');

            lineasMaquina.forEach(linea => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${linea.id_linea_pedido}</td>
                    <td>${linea.cliente}</td>
                    <td>${linea.medidas}</td>
                    <td>${linea.fecha}</td>
                    <td>${linea.producto}</td>
                    <td>${linea.n_piezas}</td>
                    <td>${linea.proceso}</td>
                    <td>${linea.base}</td>
                `;
                tbody.appendChild(row);
            });

maquinaDiv.appendChild(table);
content.appendChild(maquinaDiv);
}
}
}
    </script>
</body>

</html>
<script>
// Variables globales
let selectedMachineId = null;
let selectedClientFilterCol2 = '';
let selectedProcesoFilterCol2 = '';
let selectedClientFilterCol4 = '';
let selectedProcesoFilterCol4 = '';
let sortable;

// Funciones de filtrado
function aplicarFiltros(columna) {
    const tableRows = document.querySelectorAll(`#col${columna} tbody tr`);
    const clientFilter = columna === 2 ? selectedClientFilterCol2 : selectedClientFilterCol4;
    const procesoFilter = columna === 2 ? selectedProcesoFilterCol2 : selectedProcesoFilterCol4;

    tableRows.forEach(row => {
        const cliente = row.getAttribute('data-nombre-cliente').toLowerCase();
        const proceso = row.getAttribute('data-nombre-proceso').toLowerCase();
        const idMaquina = row.getAttribute('data-id-maquina');
        let display = true;

        if (clientFilter && !cliente.includes(clientFilter)) {
            display = false;
        }
        if (procesoFilter && !proceso.includes(procesoFilter)) {
            display = false;
        }
        if (columna === 4 && selectedMachineId && idMaquina !== selectedMachineId) {
            display = false;
        }

        row.style.display = display ? '' : 'none';
    });
}

function filtrarPorCliente(valor, columna) {
    if (columna === 2) {
        selectedClientFilterCol2 = valor.toLowerCase();
    } else {
        selectedClientFilterCol4 = valor.toLowerCase();
    }
    aplicarFiltros(columna);
}

function filtrarPorProceso(valor, columna) {
    if (columna === 2) {
        selectedProcesoFilterCol2 = valor.toLowerCase();
    } else {
        selectedProcesoFilterCol4 = valor.toLowerCase();
    }
    aplicarFiltros(columna);
}

function filtrarProcesosPorMaquina(idMaquina, nombreMaquina) {
    selectedMachineId = idMaquina;
    document.getElementById('tituloProcesosEnMaquina').textContent = `Procesos en ${nombreMaquina}`;
    aplicarFiltros(4);
}

// Funciones de movimiento y confirmación
function moverPedidos(selectorCheckbox, selectorTablaDestino) {
    document.querySelectorAll(selectorCheckbox).forEach(checkbox => {
        const filaOriginal = checkbox.closest('tr');
        const tablaDestino = document.querySelector(selectorTablaDestino);
        const nuevaFila = crearNuevaFila(filaOriginal);
        tablaDestino.appendChild(nuevaFila);
        filaOriginal.remove();
    });
    actualizarColores();
}

function crearNuevaFila(filaOriginal) {
    const nuevaFila = document.createElement('tr');
    nuevaFila.className = 'linea';
    nuevaFila.setAttribute('data-id-maquina', selectedMachineId);
    nuevaFila.setAttribute('data-nombre-proceso', filaOriginal.getAttribute('data-nombre-proceso'));
    nuevaFila.setAttribute('data-guardado', 'guardado');

    const tdCheckbox = document.createElement('td');
    const nuevoCheckbox = document.createElement('input');
    nuevoCheckbox.type = 'checkbox';
    tdCheckbox.appendChild(nuevoCheckbox);
    nuevaFila.appendChild(tdCheckbox);

    Array.from(filaOriginal.children).slice(1).forEach(td => {
        nuevaFila.appendChild(td.cloneNode(true));
    });

    return nuevaFila;
}

function confirmarProcesos() {
    const procesosActualizar = obtenerProcesos('.column:nth-child(4) table tbody tr', true);
    const procesosRevertir = obtenerProcesos('.column:nth-child(2) table tbody tr', false);

    if (selectedMachineId) {
        localStorage.setItem('selectedMachineId', selectedMachineId);
    }

    if (procesosActualizar.length > 0) {
        actualizarProcesos(procesosActualizar);
    }

    if (procesosRevertir.length > 0) {
        revertirProcesos(procesosRevertir);
    }

    actualizarColores();
}

function obtenerProcesos(selector, conOrden) {
    return Array.from(document.querySelectorAll(selector)).map((fila, index) => ({
        nombre_proceso: fila.getAttribute('data-nombre-proceso'),
        id_linea_pedido: fila.querySelector('td:nth-child(2)').textContent.trim(),
        id_maquina: conOrden ? selectedMachineId : null,
        orden: conOrden ? index + 1 : 0
    }));
}

function actualizarProcesos(procesos) {
    realizarPeticionAjax('<?php echo base_url('procesos_pedidos/actualizarEstadoProcesos'); ?>', procesos, () => {
        actualizarEstadoLineaPedido();
    });
}

function revertirProcesos(procesos) {
    realizarPeticionAjax('<?php echo base_url('procesos_pedidos/revertirEstadoProcesos'); ?>', procesos, () => {
        localStorage.setItem('reloadedFromConfirm', 'true');
        window.location.reload();
    });
}

function actualizarEstadoLineaPedido() {
    fetch('<?php echo base_url('procesos_pedidos/actualizarEstadoLineaPedido'); ?>', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                localStorage.setItem('reloadedFromConfirm', 'true');
                window.location.reload();
            } else {
                alert('Error al actualizar los estados de las líneas de pedido.');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Funciones de utilidad
function realizarPeticionAjax(url, procesos, callback) {
    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ procesos: procesos }),
        success: function(response) {
            if (response.success) {
                if (callback) callback();
            } else {
                alert('Error al actualizar los procesos.');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Error en la solicitud AJAX. Revisa la consola para más detalles.');
        }
    });
}

function mostrarTodasLasLineas() {
    document.querySelectorAll('#col4 .linea').forEach(linea => {
        linea.style.display = '';
    });
}

function actualizarColores() {
    document.querySelectorAll('#col4 .linea').forEach(fila => {
        fila.classList.toggle('sin-color', fila.getAttribute('data-guardado') === 'guardado');
        fila.classList.toggle('verde-tenue', fila.getAttribute('data-guardado') !== 'guardado');
    });
}

function seleccionarMaquinaGuardada() {
    if (localStorage.getItem('reloadedFromConfirm') === 'true') {
        const savedMachineId = localStorage.getItem('selectedMachineId');
        if (savedMachineId) {
            const maquina = document.querySelector(`.maquina[data-id-maquina="${savedMachineId}"]`);
            if (maquina) maquina.click();
        }
        localStorage.removeItem('selectedMachineId');
        localStorage.removeItem('reloadedFromConfirm');
    }
}

// Inicialización y eventos
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2
    ['#searchInput', '#clienteFilter', '#medidasFilter', '#clienteFilterCol4', '#searchInputCol4'].forEach(selector => {
        $(selector).select2();
    });

    seleccionarMaquinaGuardada();

    // Eventos de filtrado
    $('#searchInput').on('change', e => filtrarPorProceso(e.target.value, 2));
    $('#clienteFilter').on('change', e => filtrarPorCliente(e.target.value, 2));
    $('#clienteFilterCol4').on('change', e => filtrarPorCliente(e.target.value, 4));
    $('#searchInputCol4').on('change', e => filtrarPorProceso(e.target.value, 4));
    $('#medidasFilter').on('change', e => filtrarPorMedida(e.target.value));

    // Evento para limpiar filtros
    $('#clearFilters').on('click', () => {
        ['#searchInput', '#clienteFilter', '#clienteFilterCol4', '#medidasFilter'].forEach(selector => {
            $(selector).val('').trigger('change');
        });
        if (sortable) sortable.option("disabled", true);
    });

    // Eventos de botones
    document.querySelectorAll('button[data-action]').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            if (action === 'move-right') {
                if (!selectedMachineId) {
                    alert('¡Seleccione una máquina!');
                    return;
                }
                moverPedidos('input[type="checkbox"]:checked', '.column:nth-child(4) table tbody');
            } else if (action === 'move-left') {
                moverPedidos('.column:nth-child(4) input[type="checkbox"]:checked', '.column:nth-child(2) table tbody');
            } else if (action === 'confirm') {
                confirmarProcesos();
            }
        });
    });

    // Eventos de máquinas
    document.querySelectorAll('.maquina').forEach(maquina => {
        maquina.addEventListener('click', function() {
            selectedMachineId = this.getAttribute('data-id-maquina');
            filtrarProcesosPorMaquina(selectedMachineId, this.getAttribute('data-nombre'));
            if (sortable) sortable.option("disabled", false);
        });
    });

    // Evento para ver todo
    $('#verTodo').on('click', () => {
        selectedMachineId = null;
        selectedClientFilterCol4 = '';
        selectedProcesoFilterCol4 = '';
        $('#clienteFilterCol4, #searchInputCol4').val('').trigger('change');
        mostrarTodasLasLineas();
        document.getElementById('tituloProcesosEnMaquina').textContent = 'Procesos en máquinas';
        if (sortable) sortable.option("disabled", true);
    });

    // Inicializar Sortable
    var el = document.getElementById('sortableTable').getElementsByTagName('tbody')[0];
    sortable = Sortable.create(el, {
        animation: 150,
        onEnd: function(evt) {
            actualizarOrdenProcesos();
        }
    });
    sortable.option("disabled", true);
});

// Eventos adicionales
document.addEventListener('click', function(event) {
    if (event.target.matches('button[data-action="confirm"]')) {
        actualizarOrdenProcesos();
    }

    const targetTerminado = event.target.closest('button[data-action="btn-terminado"]');
    if (targetTerminado) {
        event.preventDefault();
        marcarComoTerminado(targetTerminado);
    }
});

function actualizarOrdenProcesos() {
    const filas = document.querySelectorAll('#sortableTable tbody tr');
    let ordenes = Array.from(filas)
        .filter(fila => fila.getAttribute('data-id-maquina') === selectedMachineId)
        .map((fila, index) => ({
            id_linea_pedido: fila.querySelector('td:nth-child(2)').textContent.trim(),
            nombre_proceso: fila.getAttribute('data-nombre-proceso').trim(),
            orden: index + 1,
            id_maquina: fila.getAttribute('data-id-maquina').trim()
        }));

    fetch('<?php echo base_url('procesos_pedidos/actualizarOrdenProcesos'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ordenes: ordenes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Orden actualizado correctamente.');
        } else {
            alert('Error al actualizar el orden.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function marcarComoTerminado(button) {
    const selectedLines = document.querySelectorAll('input[name="selectedLine[]"]:checked');
    let lineItems = Array.from(selectedLines).map(line => {
        const row = line.closest('tr');
        return {
            idLineaPedido: row.querySelector('td:nth-child(2)').textContent.trim(),
            nombreProceso: row.querySelector('td:nth-child(8)').textContent.trim()
        };
    });

    if (lineItems.length > 0) {
        button.disabled = true;
        fetch('<?php echo base_url('procesos_pedidos/marcarTerminado'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ lineItems: lineItems })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error al actualizar los estados.');
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            alert('Error al actualizar los estados.');
        })
        .finally(() => {
            button.disabled = false;
        });
    }
}
</script>
