<?php
//require_once('SAC/jsonFormularioSac.json');
function almacenaDenuncia($sender,$descripcion,$correo,$nombre)
{
	$file = 'SAC/jsonFormularioSac.json';
	$denunciaSac;

	$denunciasSac = file_get_contents($file);
	$json_denunciasSac=json_decode($denunciasSac,true);
	$elementos=count($json_denunciasSac["denuncias"]);
	if($elementos>0)
	{
		$denunciaSac=',"'.$sender.'":{
				"modulo":"sac",
				"descripcion":"'.$descripcion.'",
				"correo":"'.$correo.'",
				"nombre":"'.$nombre.'"
			}';
	}
	else
	{
		$denunciaSac='"'.$sender.'":{
				"modulo":"sac",
				"descripcion":"'.$descripcion.'",
				"correo":"'.$correo.'",
				"nombre":"'.$nombre.'"
			}';
	}
	$denunciasSac=substr($denunciasSac,0,-2);
	$denunciasSac.=$denunciaSac.'}}';

	$json=json_decode($denunciasSac,true);
	$jsonAlmacenar=json_encode($json);
	file_put_contents($file,$jsonAlmacenar);
}
function consultarDenuncia($sender)
{
	$info;
	$file = 'SAC/jsonFormularioSac.json';
	$denuncias = file_get_contents($file);
	$json_denuncias = json_decode($denuncias,true);

	if(isset($json_denuncias["denuncias"][''.$sender.'']))
	{
		$info=$json_denuncias["denuncias"][''.$sender.''];
		unset($json_denuncias["denuncias"][''.$sender.'']);
		$jsonAlmacenar=json_encode($json_denuncias);
		$jsonAlmacenar=str_replace("[","{",$jsonAlmacenar);
		$jsonAlmacenar=str_replace("]","}",$jsonAlmacenar);
		file_put_contents($file,$jsonAlmacenar);
		return $info;
	}

	return null;
	
}
?>