<?php
//Recargo la página cada hora para que se generen las rutinas periodicas
echo "<script>function redireccionarPaginaHora() {
  window.location ='" .base_url(). "';
}
setTimeout('redireccionarPaginaHora()', 360000);
</script>";
?>