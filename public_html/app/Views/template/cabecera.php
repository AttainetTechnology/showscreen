<!DOCTYPE html>
<?php date_default_timezone_set('Europe/Madrid');?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Control de presencia</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Cargo FAVICON  -->
	<?
	helper('controlacceso');
	$data=datos_user();
	$favicon= $data['favicon'];
	?>
	
	<link rel="icon" href="<?=base_url('public')?>/assets/uploads/favicon<? echo $favicon; ?>" type="image/gif">
	
	<link rel="stylesheet" type="text/css" href="<?= base_url('/assets/css/fichajes.css') ?>?v=<?= time() ?>">
	<!-- BOOTSTRAP -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <!-- BOOTSTRAP ICONS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
   <!-- CSS ATTAINET -->
   <link rel="stylesheet" href="<?=base_url('public/resources/css/fichajes.css')?>">
<script type="text/javascript">
	 window.GUMLET_CONFIG = {
		 hosts: [{
			 current: "crm.attainet.es",
			 gumlet: "attainet.gumlet.io"
		 }],
		 lazy_load: true
	 };
	 (function(){d=document;s=d.createElement("script");s.src="https://cdn.gumlet.com/gumlet.js/2.1/gumlet.min.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
   </script>
</head>
