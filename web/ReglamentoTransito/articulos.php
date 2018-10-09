<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Reglamento CDMX</title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
	<header>
		<img src="http://ancient-brushlands-87186.herokuapp.com/ReglamentoTransito/imagenes/ico_reglamento.png">
		<h1 class="titulo"> REGLAMENTO CDMX</h1>
	</header>

	<section>

		<?php
			require_once('reglamento.php');

			$articulo= $_GET["articulo"];
			$resumen=Reglamento($articulo,'resumen_art',null);

			echo '<h2>Artículo '.$articulo.'</h2>';
			echo '<p>'.$resumen.'</p>';
		?>

		<img src="http://ancient-brushlands-87186.herokuapp.com/ReglamentoTransito/imagenes/ico_multa.png">
		<h3>Multa</h3>
		<div>
			<?php
				require_once('reglamento.php');
				$multa=Reglamento($articulo,null,'multa');
				echo '<p>'.$multa.'</p>';
			?>
		</div>

		<img src="http://ancient-brushlands-87186.herokuapp.com/ReglamentoTransito/imagenes/ico_corralon.png">
		<h3>Remisión del vehículo</h3>
		<div>
			<?php
				require_once('reglamento.php');
				$corralon=Reglamento($articulo,null,'corralon');
				echo '<p>'.$corralon.'</p>';
			?>
		</div>

		<img src="http://ancient-brushlands-87186.herokuapp.com/ReglamentoTransito/imagenes/ico_puntos.png">
		<h3>Puntos de penalización a la licencia</h3>
		<div>
			<?php
				require_once('reglamento.php');
				$punto_licencia=Reglamento($articulo,null,'puntos_licencia');
				echo '<p>'.$punto_licencia.'</p>';
			?>
		</div>

		<h3 id="art_completo">Articulo Completo</h3>
		<div>
			<?php
				require_once('reglamento.php');
				$artLect=Reglamento($articulo,'art_completo',null);
				echo '<p>'.$artLect.'</p>';
			?>
		</div>

	</section>

	<footer>
		<img src="http://ancient-brushlands-87186.herokuapp.com/Corralon/imagenes/footer_Smartweb.png">
	</footer>

</body>
</html>