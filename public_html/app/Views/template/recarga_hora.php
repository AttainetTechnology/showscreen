<?php
//Recargo la página cada hora para que se generen las rutinas periodicas
echo "<script>
function redireccionarPaginaHora() {
    window.location ='" .base_url('presentes'). "';
}
// setTimeout(redireccionarPaginaHora, 3600000); // 1 hora = 3600000 milisegundos
//setTimeout(redireccionarPaginaHora, 120000); // 2 minutos = 120000 milisegundos
setTimeout(redireccionarPaginaHora, 1800000); // 30 minutos = 1800000 milisegundos
</script>";
?>
