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
let selectedMachineId = null;
let selectedClientFilterCol4 = '';
let sortable; // Declare the variable globally

// Función para aplicar los filtros en la columna 4
function aplicarFiltrosCol4() {
    const tableRows = document.querySelectorAll('#col4 tbody tr');
    tableRows.forEach(row => {
        const cliente = row.getAttribute('data-nombre-cliente').toLowerCase();
        const idMaquina = row.getAttribute('data-id-maquina');
        let display = true;

        if (selectedClientFilterCol4 && !cliente.includes(selectedClientFilterCol4)) {
            display = false;
        }

        if (selectedMachineId && idMaquina !== selectedMachineId) {
            display = false;
        }

        row.style.display = display ? '' : 'none';
    });
}
function filtrarPorClienteCol4(valor) {
    selectedClientFilterCol4 = valor.toLowerCase();
    aplicarFiltrosCol4();
}

// Función para filtrar procesos por máquina y actualizar el título de la columna
function filtrarProcesosPorMaquina(idMaquina, nombreMaquina) {
    selectedMachineId = idMaquina;

    // Actualizar el título con el nombre de la máquina seleccionada
    let titulo = document.getElementById('tituloProcesosEnMaquina');
    if (titulo) {
        titulo.textContent = `Procesos en ${nombreMaquina}`;
    }

    aplicarFiltrosCol4();
}

// Definir la función filtrarPorClienteCol4
function filtrarPorCliente(valor) {
    selectedClientFilterCol4 = valor.toLowerCase();
    aplicarFiltrosCol4();
}

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 en los campos de selección
    $('#searchInput').select2();
    $('#clienteFilter').select2();
    $('#medidasFilter').select2();
    $('#clienteFilterCol4').select2();
    seleccionarMaquinaGuardada();

    // Evento para buscar y filtrar procesos en col2
    $('#searchInput').on('change', function() {
        const searchTerm = $(this).val().toLowerCase();
        const tableRows = document.querySelectorAll('#col2 tbody tr');

        tableRows.forEach(row => {
            const proceso = row.getAttribute('data-nombre-proceso').toLowerCase();
            row.style.display = (proceso.includes(searchTerm) || searchTerm === '') ? '' : 'none';
        });
    });

    // Evento para buscar y filtrar Clientes en col2
    $('#clienteFilter').on('change', function() {
        const searchTerm = $(this).val().toLowerCase();
        const tableRows = document.querySelectorAll('#Tabla2 tbody tr');

        tableRows.forEach(row => {
            const cliente = row.getAttribute('data-nombre-cliente').toLowerCase();
            row.style.display = (cliente.includes(searchTerm) || searchTerm === '') ? '' : 'none';
        });
    });

    // Evento para buscar y filtrar Clientes en col4
    $('#clienteFilterCol4').on('change', function() {
        selectedClientFilterCol4 = $(this).val().toLowerCase();
        aplicarFiltrosCol4();
    });


$(document).ready(function() {
    window.filtrarPorMedida = function(valor) {
    };
    $('#medidasFilter').on('change', function() {
        filtrarPorMedida(this.value);
    });
});

    function filtrarPorMedida(valor) {
        // Obtener las filas de la tabla que se van a ordenar
        var rows = $('#Tabla2 tbody tr').get();

        rows.sort(function(a, b) {
            var medA, medB;
            if (valor === "iniciales") {
                medA = parseFloat($(a).find('td:eq(3)').text().split(' - ')[0]) || 0;
                medB = parseFloat($(b).find('td:eq(3)').text().split(' - ')[0]) || 0;
            } else if (valor === "finales") {
                medA = parseFloat($(a).find('td:eq(3)').text().split(' - ')[1]) || 0;
                medB = parseFloat($(b).find('td:eq(3)').text().split(' - ')[1]) || 0;
            } else {
                return 0;
            }

            return medA - medB ; // Orden Ascendente
        });

        $.each(rows, function(index, row) {
            $('#Tabla2').children('tbody').append(row);
        });
    }

    // Evento para el botón Eliminar Filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        // Restablecer los campos de selección a la opción por defecto
        $('#searchInput').val('').trigger('change');
        $('#clienteFilter').val('').trigger('change');
        $('#clienteFilterCol4').val('').trigger('change');
        $('#medidasFilter').val('').trigger('change');
        if (sortable) {
            sortable.option("disabled", true);
        }
    });

    // Seleccionar botones por atributo de acción
    const buttons = document.querySelectorAll('button[data-action]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            if (action === 'move-right') {
                // Mover de columna 2 a columna 4
                if (!selectedMachineId) {
                    alert('¡Seleccione una máquina!');
                    return;
                }
                moverPedidos('input[type="checkbox"]:checked', '.column:nth-child(4) table tbody');
            } else if (action === 'move-left') {
                // Mover de columna 4 a columna 2
                moverPedidos('.column:nth-child(4) input[type="checkbox"]:checked', '.column:nth-child(2) table tbody');
            } else if (action === 'confirm') {
                confirmarProcesos();
            }
        });
    });

    // Seleccionar máquinas por clase y añadir evento de clic
    document.querySelectorAll('.maquina').forEach(maquina => {
    maquina.addEventListener('click', function() {
        selectedMachineId = this.getAttribute('data-id-maquina');
        let nombreMaquina = this.getAttribute('data-nombre');
    
        // Actualizar el título de la columna con el nombre de la máquina seleccionada
        let titulo = document.getElementById('tituloProcesosEnMaquina');
        if (titulo) {
            titulo.textContent = `Procesos en ${nombreMaquina}`;
        }
    
        aplicarFiltrosCol4();
        if (sortable) {
            sortable.option("disabled", false);
        }
    });
});

    // Evento para el botón Ver Todo
    document.getElementById('verTodo').addEventListener('click', function() {
        selectedMachineId = null;
        selectedClientFilterCol4 = '';
        $('#clienteFilterCol4').val('').trigger('change');
        mostrarTodasLasLineas1();
        // Actualizar el título al valor por defecto
        document.getElementById('tituloProcesosEnMaquina').textContent = 'Procesos en máquinas';
        if (sortable) {
            sortable.option("disabled", true);
        }
    });

// Inicializar el arrastre solo una vez y deshabilitarlo por defecto
var el = document.getElementById('sortableTable').getElementsByTagName('tbody')[0];
sortable = Sortable.create(el, {
    animation: 150,
    onEnd: function (evt) {
        var itemEl = evt.item; 
        console.log('Element moved', itemEl);

        // Capturar el estado actual de las filas después del movimiento
        const filas = document.querySelectorAll('#sortableTable tbody tr');
        let ordenes = [];

        // Filtrar las filas para incluir solo las que tienen el mismo id_maquina que la máquina seleccionada
        const filasFiltradas = Array.from(filas).filter(fila => {
            return fila.getAttribute('data-id-maquina') === selectedMachineId;
        });

        filasFiltradas.forEach((fila, index) => {
            const idLineaPedido = fila.querySelector('td:nth-child(2)').textContent.trim();
            const nombreProceso = fila.getAttribute('data-nombre-proceso').trim();
            const idMaquina = fila.getAttribute('data-id-maquina').trim();
            ordenes.push({
                id_linea_pedido: idLineaPedido,
                nombre_proceso: nombreProceso,
                orden: index + 1,
                id_maquina: idMaquina //Incluir la ID de la máquina
            });
        });

        // Enviar los datos al servidor
        fetch('<?php echo base_url('procesos_pedidos/actualizarOrdenProcesos'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
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
        .catch((error) => {
            console.error('Error:', error);
        });
    }
});

sortable.option("disabled", true);

});

document.addEventListener('click', function(event) {
    const target = event.target;
    
    if (target.matches('button[data-action="confirm"]')) {
        // Capturar el estado actual de las líneas
        const lineas = document.querySelectorAll('.linea');
        const datosLineas = Array.from(lineas).map(linea => {
            return {
                id: linea.dataset.id,
                nuevaUbicacion: linea.parentElement.id
            };
        });
        // Enviar los datos al servidor
        fetch('procesos_pedidos.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({lineas: datosLineas})
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            // Recargar la página solo después de que los datos se hayan guardado correctamente
            window.location.reload();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    document.addEventListener('click', function(event) {
    const target = event.target.closest('button[data-action="btn-terminado"]');
    if (target) {
        event.preventDefault(); // Prevenir cualquier acción por defecto

        console.log("Botón terminado clicado"); // Debug

        const selectedLines = document.querySelectorAll('input[name="selectedLine[]"]:checked');
        let lineItems = [];
        selectedLines.forEach(line => {
            const row = line.closest('tr');
            const idLineaPedido = row.querySelector('td:nth-child(2)').textContent.trim();
            const nombreProceso = row.querySelector('td:nth-child(8)').textContent.trim();
            lineItems.push({ idLineaPedido: idLineaPedido, nombreProceso: nombreProceso });
        });

        console.log("Elementos seleccionados:", lineItems); // Debug

        if (lineItems.length > 0) {
            // Deshabilitar el botón para evitar múltiples clics
            target.disabled = true;

            fetch('<?php echo base_url('procesos_pedidos/marcarTerminado'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ lineItems: lineItems })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Respuesta del servidor:", data); // Debug
                if (data.success) {
                    window.location.reload();
                } else {
                    console.error('Error en la respuesta del servidor:', data); // Debug
                    alert('Error al actualizar los estados.');
                }
            })
            .catch(error => {
                console.error("Error en la solicitud:", error); // Debug
                alert('Error al actualizar los estados.');
            })
            .finally(() => {
                // Rehabilitar el botón independientemente del resultado
                target.disabled = false;
            });
        } else {
            alert('No se ha seleccionado ninguna línea.');
        }
    }
});


    if (target.matches('button[data-action="confirm"]')) {
        const filas = document.querySelectorAll('#sortableTable tbody tr');
        let ordenes = [];

        filas.forEach((fila, index) => {
            const idLineaPedido = fila.querySelector('td:nth-child(2)').textContent.trim();
            const nombreProceso = fila.getAttribute('data-nombre-proceso').trim();
            ordenes.push({
                id_linea_pedido: idLineaPedido,
                nombre_proceso: nombreProceso,
                orden: index + 1 // Asignar el nuevo orden basado en la posición
            });
        });

        fetch('<?php echo base_url('procesos_pedidos/actualizarOrdenProcesos'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ordenes: ordenes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error al actualizar el orden.');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
});


// Función para mover pedidos entre columnas
function moverPedidos(selectorCheckbox, selectorTablaDestino) {
    document.querySelectorAll(selectorCheckbox).forEach(checkbox => {
        const filaOriginal = checkbox.closest('tr');
        const tablaDestino = document.querySelector(selectorTablaDestino);
        const nuevaFila = document.createElement('tr');
        nuevaFila.classList.add('linea');
        nuevaFila.setAttribute('data-id-maquina', selectedMachineId);
        nuevaFila.setAttribute('data-nombre-proceso', filaOriginal.getAttribute('data-nombre-proceso'));
        nuevaFila.setAttribute('data-guardado', 'guardado'); // Actualiza el estado a 'guardado'

        // Crear y añadir un nuevo td con un checkbox sin marcar al inicio de la nueva fila
        const tdCheckbox = document.createElement('td');
        const nuevoCheckbox = document.createElement('input');
        nuevoCheckbox.type = 'checkbox';
        tdCheckbox.appendChild(nuevoCheckbox);
        nuevaFila.appendChild(tdCheckbox);

        // Copiar cada celda de la fila original a la nueva fila, excepto el primer td (el del checkbox original)
        Array.from(filaOriginal.children).forEach((td, index) => {
            if (index > 0) {
                const nuevoTd = td.cloneNode(true);
                nuevaFila.appendChild(nuevoTd);
            }
        });

        // Añadir la nueva fila a la tabla destino
        tablaDestino.appendChild(nuevaFila);

        // Eliminar la fila original
        filaOriginal.remove();
    });

    // Actualizar colores después de mover los pedidos
    actualizarColores();
}

// Función para confirmar los procesos movidos
function confirmarProcesos() {
    let procesosActualizar = [];
    let procesosRevertir = [];

    // Seleccionar todas las filas de las columnas 2 y 4
    const filasColumna2 = document.querySelectorAll('.column:nth-child(2) table tbody tr');
    const filasColumna4 = document.querySelectorAll('.column:nth-child(4) table tbody tr');

    // Agregar procesos de la columna 4 (Procesos en máquina)
    filasColumna4.forEach(fila => {
        const idLineaPedido = fila.querySelector('td:nth-child(2)').textContent.trim();
        const nombreProceso = fila.getAttribute('data-nombre-proceso');
        const idMaquina = selectedMachineId;
        if (idLineaPedido && nombreProceso && idMaquina) {
            procesosActualizar.push({
                nombre_proceso: nombreProceso,
                id_linea_pedido: idLineaPedido,
                id_maquina: idMaquina
            });
        }
    });

    // Agregar procesos de la columna 2 (Procesos listos para producir)
    filasColumna2.forEach(fila => {
        const idLineaPedido = fila.querySelector('td:nth-child(2)').textContent.trim();
        const nombreProceso = fila.getAttribute('data-nombre-proceso');
        if (idLineaPedido && nombreProceso) {
            procesosRevertir.push({
                nombre_proceso: nombreProceso,
                id_linea_pedido: idLineaPedido,
                id_maquina: null
            });
        }
    });

    // Guardar el ID de la máquina seleccionada en localStorage
    if (selectedMachineId) {
        localStorage.setItem('selectedMachineId', selectedMachineId);
    }

    // Realizar la llamada AJAX para actualizar
    if (procesosActualizar.length > 0) {
        realizarPeticionAjax('<?php echo base_url('procesos_pedidos/actualizarEstadoProcesos'); ?>', procesosActualizar, function() {
            fetch('<?php echo base_url('procesos_pedidos/actualizarEstadoLineaPedido'); ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('reloadedFromConfirm', 'true');
                    window.location.reload();
                } else {
                    alert('Error al actualizar los estados de las líneas de pedido.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    }

    // Realizar la llamada AJAX para revertir
    if (procesosRevertir.length > 0) {
        realizarPeticionAjax('<?php echo base_url('procesos_pedidos/revertirEstadoProcesos'); ?>', procesosRevertir, function() {
            localStorage.setItem('reloadedFromConfirm', 'true');
            window.location.reload();
        });
    }

    // Llamar a actualizarColores después de confirmar los procesos
    actualizarColores();
}

// Función para seleccionar la máquina guardada
function seleccionarMaquinaGuardada() {
    const reloadedFromConfirm = localStorage.getItem('reloadedFromConfirm');
    if (reloadedFromConfirm === 'true') {
        const savedMachineId = localStorage.getItem('selectedMachineId');
        if (savedMachineId) {
            const maquina = document.querySelector(`.maquina[data-id-maquina="${savedMachineId}"]`);
            if (maquina) {
                maquina.click(); // Simula un clic en la máquina
            }
        }
        // Limpiar localStorage después de usar
        localStorage.removeItem('selectedMachineId');
        localStorage.removeItem('reloadedFromConfirm');
    }
}
// Función para realizar las peticiones AJAX
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

// // Función para realizar las peticiones AJAX
// function realizarPeticionAjax(url, procesos, callback) {
//     $.ajax({
//         url: url,
//         type: 'POST',
//         contentType: 'application/json',
//         data: JSON.stringify({ procesos: procesos }),
//         success: function(response) {
//             if (response.success) {
//                 if (callback) callback();
//             } else {
//                 alert('Error al actualizar los procesos.');
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error(xhr.responseText);
//             alert('Error en la solicitud AJAX. Revisa la consola para más detalles.');
//         }
//     });
// }

// Función para mostrar todas las líneas de pedido en la columna 4

function mostrarTodasLasLineas1() {
    document.querySelectorAll('#col4 .linea').forEach(linea => {
        linea.style.display = '';
    });
}
function actualizarColores() {
    document.querySelectorAll('#col4 .linea').forEach(fila => {
        if (fila.getAttribute('data-guardado') === 'guardado') {
            fila.classList.add('sin-color');
            fila.classList.remove('verde-tenue');
        } else {
            fila.classList.add('verde-tenue');
            fila.classList.remove('sin-color');
        }
    });
}

</script>