<?php
//Recargo la página cada 15 segundos para salir de ausentes
echo "<script>function redireccionarPagina() {
  window.location ='" .base_url(). "';
}
setTimeout('redireccionarPagina()', 95000);
</script>";
?>