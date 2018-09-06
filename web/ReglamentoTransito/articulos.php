<?php
	require_once('reglamento.php');

	$articulo= $_GET["articulo"];
	$multa=Reglamento($articulo,null,'multa');
	$punto_licencia=Reglamento($articulo,null,'puntos_licencia');
	$corralon=Reglamento($articulo,null,'corralon');
	$artLect=Reglamento($articulo,'art_completo',null);
	$resumen=Reglamento($articulo,'resumen_art',null);
	echo '<h1>Articulo '.$articulo.'</h1>';
	echo '<p>'.$resumen.'</p>';
	echo "<h2>Multa</h2>";
	echo '<p>'.$multa.'</p>';
	echo "<h2>Remision del vehiculo</h2>";
	echo '<p>'.$corralon.'</p>';
	echo "<h2>Puntos de penalizacion a la licencia</h2>";
	echo '<p>'.$punto_licencia.'</p>';
	echo '<h2>Articulo '.$articulo.'</h2>';
	echo '<p>'.$artLect.'</p>';
?>