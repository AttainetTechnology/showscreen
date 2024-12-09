<? 
$nivel=control_login();
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

	<?php $ahora= time(); ?> 

<? 
	//Oculto los botones de edición para los usarios sin permisos
	if ($nivel<'4'){?>
	<link href="<?php echo base_url("public/assets/css/ocultar_botones.css?v='$ahora'"); ?>" rel="stylesheet">
<? }?>
   
<link href="<?php echo base_url("public/assets/css/attainet.css?v='$ahora'"); ?>" rel="stylesheet">
<link href="<?php echo base_url("public/assets/css/pedidos_lista1.css?v='$ahora'"); ?>" rel="stylesheet">
<?php 
if(!empty($css_files)){
    foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; }?>

</head>
<body>
    <div>
		<?php echo $output; ?>
    </div>
    <script>
    $(function(){
        $('.0').closest('tr').css('background-color','#FFFFFF');
        $('.1').closest('tr').css('background-color','#99ffff');
        $('.2').closest('tr').css('background-color','#ffcccc');
        $('.3').closest('tr').css('background-color','#ff9933');
        $('.4').closest('tr').css('background-color','#ccff99');
        $('.5').closest('tr').css('background-color','#66cc33');
    });

	</script> 
<?php 
    if(!empty($js_files)){
        foreach($js_files as $file): ?>
    
        <script src="<?php echo $file; ?>"></script>
     
    <?php endforeach; }?>
</body>
</html>
