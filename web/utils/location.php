<?php
function almacenarUbicacion($sender,$latitud,$longitud)
{
	$file = 'utils/jsonUbicacion.json';
	$ubicacion;

	$ubicaciones = file_get_contents($file);
	$json_ubicaciones=json_decode($ubicaciones,true);
	$elementos=count($json_ubicaciones["ubicaciones"]);
	if($elementos>0)
	{
		$ubicacion=',"'.$sender.'":{
				"latitud":'.$latitud.',
				"longitud":'.$longitud.'
			}';
	}
	else
	{
		$ubicacion='"'.$sender.'":{
				"latitud":'.$latitud.',
				"longitud":'.$longitud.'
			}';
	}
	$ubicaciones=substr($ubicaciones,0,-2);
	$ubicaciones.=$ubicacion.'}}';

	$json=json_decode($ubicaciones,true);
	$jsonAlmacenar=json_encode($json);
	file_put_contents($file,$jsonAlmacenar);
}
function leerUbicacion($sender)
{
	$info;
	$file = 'utils/jsonUbicacion.json';
	$ubicaciones = file_get_contents($file);
	$json_ubicaciones = json_decode($ubicaciones,true);
	$info=$json_ubicaciones["ubicaciones"][''.$sender.''];
	
	unset($json_ubicaciones["ubicaciones"][''.$sender.'']);
	$jsonAlmacenar=json_encode($json_ubicaciones);
	file_put_contents($file,$jsonAlmacenar);
	return $info;
}
?>