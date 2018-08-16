<?php

 //clase de entidades de wit.ai
 class entitiesWit
{
	public $modulo;
	public $lugar;
	public $placas;
	public $saludo;
}

//Clase para almacenar informcación de la calidad del aire
class infoAir
{
	public $name;
	public $imecaPoints;
	public $indice;
	public $recomendacion;
	public $temperatura;
	public $image;
}

//clase para almacenar informacion sobre el corralon
class infoCorralon
{
	public $nombre;
	public $direccion;
	public $numero;
	public $CP;
	public $coordx;
	public $coordy;
}

//*****************************************************************************************************
//JSON'S DE MENSAJES DE RESPUESTA A FACEBOOK
//*****************************************************************************************************

//Seleccion del fomato Json de respuesta a enviar
function JsonReturn($Info,$sender,$modulo)
{
	$jsonData;
	switch ($modulo) {
		case 'saludo':
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Hola :) ,es un gusto tenerte por aquí <3 , a continuación te presento los módulo con los que contamos, selecciona el que gustes",
									"buttons":[
									{
										"type":"postback",
										"title":"#aire",
										"payload":"btn_aire"
									},
									{
										"type":"postback",
										"title":"#corralon",
										"payload":"btn_corralon"
									},
								]
							}
						}
					}
				}';
			break;
		case 'mensaje':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"Que tal :) , la información que solicitaste es la siguiente, esperamos haber sido de gran ayuda (y) "
					}
			}';
			break;
		case 'menu':
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Hola que tal :) , estos son los módulos con los que contamos, selecciona el que gustes",
									"buttons":[
									{
										"type":"postback",
										"title":"#aire",
										"payload":"btn_aire"
									},
									{
										"type":"postback",
										"title":"#corralon",
										"payload":"btn_corralon"
									},
								]
							}
						}
					}
				}';
			break;
		case 'btn_aire':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" Este módulo te permite consultar información sobre la calidad del aire en alguna delegación, para ello debes poner #aire y la delegación, por ejemplo\n #aire TLAHUAC\n  ;) "
					}
			}';
			break;
		case 'btn_corralon':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" Este módulo te permite consultar información sobre el corralon donde está su auto, para ello debe poner #corralon y las placas del auto, por ejemplo\n #corralon placas\n  ;) "
					}
			}';
			break;
		case '#aire':
			if(is_null($Info))
			{
				$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"No encontramos información relacionada con la delegación :/ . Un ejemplo de solicitud de calidad del aire podría ser:\n #aire IZTAPALAPA"
						}
				}';
			}
			else
			{
				$image="http://ancient-brushlands-87186.herokuapp.com/imagenes/Climas_bot/".$Info->image;

				$title=$Info->name." ".$Info->temperatura."°C";
				$subtitle=$Info->imecaPoints.' IMECA\nCalidad del Aire '.$Info->indice.'\n'.$Info->recomendacion;

				$jsonData='{
							"recipient":{
									"id":"'. $sender .'"
							},
							"message":
							{
								"attachment":
								{
									"type":"template",
									"payload":
									{
										"template_type":"generic",
										"elements":[
											{
												"title":"'.$title .'",
												"image_url":"'.$image.'",
				            					"subtitle":"'.$subtitle.'",
											}]
									}
								}
							}
					}';
			}
			break;
		case '#corralon':
			if(is_null($Info))
			{
				$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" :o Al parecer su auto no se encuentra en un corralon, o ingresó erroneamente las placas del auto"
					}
				}';
			}
			else
			{
				$title=$Info->nombre.' '.$Info->CP.'\nContacto: '.$Info->numero;
				$subtitle=$Info->direccion;
				$image='https://maps.googleapis.com/maps/api/staticmap?center='.$Info->coordx.','.$Info->coordy.'&markers=color:red%7Clabel:C%7C'.$Info->coordx.','.$Info->coordy.'&zoom=15&size=200x100';
				$payload_url='https://www.openstreetmap.org/#map=18/'.$Info->coordx.'/'.$Info->coordy;
				$numero='+5255'.$Info->numero;

				$jsonData='{
							"recipient":{
									"id":"'. $sender .'"
							},
							"message":
							{
								"attachment":
								{
									"type":"template",
									"payload":
									{
										"template_type":"generic",
										"elements":[
											{
												"title":"'.$title .'",
												"image_url":"'.$image.'",
				            					"subtitle":"'.$subtitle.'",
				            					 "default_action": {
										              "type": "web_url",
										              "url": "'.$payload_url.'",
										            },
				            					"buttons":[
														{
															"type":"phone_number",
															"title":"llamar a Corralon",
															"payload":"'.$numero.'"
														}
												]
											}
										]
									}
								}
							}
					}';
				}
			break;
		default:
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Esa opción no está disponible :( , pero te puedo ofrecer que veas nuestro menu",
									"buttons":[
									{
										"type":"postback",
										"title":"menu",
										"payload":"menu"
									},
								]
							}
						}
					}
				}';
			break;
	}
	return $jsonData;
}
 

//*****************************************************************************************************
//MANEJO DE LA RESPUESTA DE WIT.AI
//*****************************************************************************************************

//Funsion para obtener los valores de las entidades de Wit.ai
 function handle($entities,$entity)
{
	if(isset($entities[$entity]))
	{
		$val=$entities[$entity][0]["value"];
		return $val;
	}
	else
		return null;
}

//Consulta a Wit.ai
 function wit_response($message)
{
	$witEntities=new entitiesWit();
	$wit_token="DG6OHFRF3ZON67X3GAOTVOZZ4N5QUYOW";
	$url="https://api.wit.ai/message?v=20180726&q=".$message;
		
	$ch=curl_init($url);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json","Authorization: Bearer ".$wit_token));
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	$result=curl_exec($ch);
	curl_close($ch);
		
	$result_json=json_decode($result,true);
	$entities=$result_json['entities'];
	$witEntities->modulo=handle($entities,"modulo");
	if(isset($witEntities->modulo))
	{
		$witEntities->modulo=str_replace("%23","#",$witEntities->modulo);
	}
	$witEntities->lugar=handle($entities,"lugar");
	$witEntities->placas=handle($entities,"placas");
	$witEntities->saludo=handle($entities,"saludo");

	return $witEntities;
}


//*****************************************************************************************************
//OBTENER INFORMACION SOBRE LA CALIDAD DEL AIRE
//*****************************************************************************************************

//Funsion para obtner la informacion de la calidad del aire de la delegacion solicitada
function getInfoAir($jsonAire,$lugar)
{
	$AirInfo= new infoAir();
	if(strnatcasecmp($lugar, 'CDMX')==0)
	{
		$AirInfo->name=$lugar;
		$AirInfo->imecaPoints=$jsonAire["pollutionMeasurements"]["informationCDMX"][0]["valormeca"];
		$AirInfo->indice=$jsonAire["pollutionMeasurements"]["informationCDMX"][0]["indiceimeca"];
		$AirInfo->recomendacion=$jsonAire["pollutionMeasurements"]["informationCDMX"][0]["recomendacionaireuno"];
		$AirInfo->temperatura=$jsonAire["pollutionMeasurements"]["information"][0]["temperatura"];
		$AirInfo->image=$jsonAire["pollutionMeasurements"]["information"][0]["clima"];
		$AirInfo->image=str_replace("PNG","png",$AirInfo->image);
		return $AirInfo;;
	}
	else
	{
		$delegations=$jsonAire["pollutionMeasurements"]["delegations"];
		$delegation;
		for($i=0;$i<46;$i++)
		{
			$delegation=$delegations[$i]["name"];
			if($delegation==$lugar)
			{
				$AirInfo->name=$delegation;
				$AirInfo->imecaPoints=$delegations[$i]["imecaPoints"];
				$AirInfo->indice=$delegations[$i]["indice"];
				$AirInfo->recomendacion=$jsonAire["pollutionMeasurements"]["information"][0]["recomendacionaireuno"];
				$AirInfo->temperatura=$jsonAire["pollutionMeasurements"]["information"][0]["temperatura"];
				$AirInfo->image=$jsonAire["pollutionMeasurements"]["information"][0]["clima"];
				$AirInfo->image=str_replace("PNG","png",$AirInfo->image);
				return $AirInfo;
			}
		}
		return NULL;
	}

	return NULL;
}

//Consulta a la api de SEMARNAT
function ConsultaCalidadAire($lugar)
{
	$url="http://148.243.232.113/calidadaire/xml/simat.json";
	$ch=curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);

	$jsonAire=json_decode($result,true);
	$info=getInfoAir($jsonAire,$lugar);

	return $info;
}
//*****************************************************************************************************
//OBTENER INFORMACION SOBRE EL CORRALON
//*****************************************************************************************************

//funcion para obtener información sobre el corralon
function getInfoCorralon($jsonCorralon)
{
	$info=new infoCorralon();
	$ultimoCorralon=count($jsonCorralon)-1;

	if($ultimoCorralon>=0)
	{
		$info->nombre=$jsonCorralon[$ultimoCorralon]["nombre"];
		$info->direccion=$jsonCorralon[$ultimoCorralon]["direccion"];
		$info->numero=$jsonCorralon[$ultimoCorralon]["telefono"];
		$info->coordy=$jsonCorralon[$ultimoCorralon]["coordy"];
		$info->coordx=$jsonCorralon[$ultimoCorralon]["coordx"];

		$pos = strpos($info->direccion,'CP');
		$info->CP=substr($info->direccion,$pos);
		$info->direccion=substr($info->direccion,0,$pos);
		return $info;
	}

	return null;
}

//Consula a la api de CORRALONES
function consultaCorralon($placas)
{
	$url="http://148.206.32.60/whbot/test.php?connect-corralon&placas=".$placas;
	$ch=curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);

	$jsonCorralon=json_decode($result,true);
	$info=getInfoCorralon($jsonCorralon);

	return $info;

}

//*****************************************************************************************************
//MANEJO DEL MENSAJE DE RESPUESTA
//*****************************************************************************************************

//funcion para enviar el mensaje de respuesta a facebook
function enviar($jsonData,$access_token)
{
	$url="https://graph.facebook.com/v2.6/me/messages?access_token=".$access_token;
	$ch=curl_init($url);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonData);
	curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	$result=curl_exec($ch);
	curl_close($ch);
}

//funcion para seleccionar el módulo y enviar una respuesta en base a ello
function ReturnMessage($witEntities,$sender,$access_token)
{
	$jsonData;

	if(isset($witEntities->modulo))
	{
		switch ($witEntities->modulo) {
			case 'menu':
				$jsonData=JsonReturn(nuul,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'btn_aire':
				$jsonData=JsonReturn(nuul,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'btn_corralon':
				$jsonData=JsonReturn(nuul,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case '#aire':
				if(isset($witEntities->lugar))
				{
					$AirInfo=ConsultaCalidadAire($witEntities->lugar);	
					if(isset($AirInfo))
					{
						$jsonData=JsonReturn(null,$sender,'mensaje');
						enviar($jsonData,$access_token);
					}
					$jsonData=JsonReturn($AirInfo,$sender,$witEntities->modulo);
					enviar($jsonData,$access_token);
				}
				else
				{
					$witEntities->lugar="CDMX";
					$AirInfo=ConsultaCalidadAire($witEntities->lugar);
					$jsonData=JsonReturn(null,$sender,'mensaje');
					enviar($jsonData,$access_token);	
					$jsonData=JsonReturn($AirInfo,$sender,$witEntities->modulo);
					enviar($jsonData,$access_token);
				}
				break;
			case '#corralon':
				$corralonInfo=consultaCorralon($witEntities->placas);
				if(isset($corralonInfo))
				{
					$jsonData=JsonReturn(null,$sender,'mensaje');
					enviar($jsonData,$access_token);
				}
				$jsonData=JsonReturn($corralonInfo,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			default:
				$witEntities->modulo='indefinido';
				$jsonData=JsonReturn($AirInfo,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
		}
	}
	else
	{
		if(isset($witEntities->saludo))
		{
			$witEntities->modulo='saludo';
			$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
			enviar($jsonData,$access_token);
		}
		else
		{
			$witEntities->modulo='indefinido';
			$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
			enviar($jsonData,$access_token);
		}
	}
	return $jsonData;
}


//*****************************************************************************************************
//PROGRAMA PRINCIPAL
//*****************************************************************************************************

function Principal()
{
	$access_token="EAADlwZCSgxfgBAJyJGZBAkadbagQRW9ZBpICSaJdeOByzdIzzKkrV2tJ2SxBzkSgo6NaRR2U0wzAPM2iZAPUHMZCPxgqldyeJnEXLZBEeEzYsb1MUzAT9Qvh9u2M6DbWFwDcwBTbpqSTqKIsgeWu3NLukpsveAZB0uxPsNxmMrBXE8140vXJERM";

	$verify_token="SmartCDMX";
	$hub_verify_token=null;
	
	if(isset($_REQUEST['hub_mode'])&& $_REQUEST['hub_mode']=='subscribe')
	{
		$challenge=$_REQUEST['hub_challenge'];
		$hub_verify_token=$_REQUEST['hub_verify_token'];
		if($hub_verify_token===$verify_token)
		{
			header('HTTP/1.1 200 ok');
			echo $challenge;
			die;
		}
	}
	
	$input=json_decode(file_get_contents('php://input'), true);
	$sender=$input['entry'][0]['messaging'][0]['sender']['id'];
	$message=isset($input['entry'][0]['messaging'][0]['message']['text'])? $input['entry'][0]['messaging'][0]['message']['text']:'';
	$selec_btn=$input['entry'][0]['messaging'][0]['postback']['payload'];

	if($message || $selec_btn)
	{
		$response_wit;
		if($message)
		{
			$message=str_replace(" ","%20",$message);
			$message=str_replace("#","%23",$message);
			$response_wit=wit_response($message);
		}
		elseif ($selec_btn) {
			$response_wit->modulo=$selec_btn;
		}
		ReturnMessage($response_wit,$sender,$access_token);
		
	}
}

Principal();

?>