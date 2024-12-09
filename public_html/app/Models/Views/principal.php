<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de la aplicación</title>

    <link href="<?php echo base_url()?>css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo base_url()?>js/bootstrap.bundle.min.js" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<style>
    li > ul {
        display: none;
    }
</style>
<body>
    <h1>Bienvenido a mi aplicación</h1>
    <nav>
        <ul>
            <li><a href="<?= base_url('/usuarios') ?>">Usuarios</a></li>
            <li>
                <a href="#">Productos</a>
                <ul>
                    <li><a href="<?= base_url('/productos') ?>">Productos</a></li>
                    <li><a href="<?= base_url('/familia_productos') ?>">Familias</a></li>
                    <li><a href="<?= base_url('/procesos') ?>">Procesos</a></li>
                    <li><a href="<?= base_url('/maquinas') ?>">Maquinas</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <script>
        $(document).ready(function(){
            $("li:has(ul)").click(function(){
                $("ul",this).toggle('slow');
            });
        });
    </script>


</body>
</html>