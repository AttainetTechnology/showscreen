
<?php
		//Cargo los permisos de acceso de los usuarios
		$session = session();
		$session_data = $session->get('logged_in');		
		$nivel=$session_data['nivel_acceso'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ATTAINET - INTRANET</title>
	<? $ahora= time(); ?> 

<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url("vendor/grocery-crud/css/bootstrap-v5/css/bootstrap/bootstrap.min.css"); ?>" rel="stylesheet">

    <!-- Attainet CSS -->

 <link href="<?php echo base_url("public/assets/css/pedidos.css?v='$ahora'"); ?>" rel="stylesheet">  
<link href="<?php echo base_url("public/assets/css/attainet.css?v='$ahora'"); ?>" rel="stylesheet">
<? 
	//Oculto los botones de edición para los usarios sin permisos
	if ($nivel<'4'){?>
	<link href="<?php echo base_url("public/assets/css/ocultar_botones.css?v='$ahora'"); ?>" rel="stylesheet">
<? }?>

<link href="<?php echo base_url("public/assets/css/custom.css"); ?>" rel="stylesheet" type="text/css">
<?php foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
 
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
 
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

  <script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

</head>

<body>

  <div id="wrapper">

 
