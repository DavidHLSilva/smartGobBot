<?php
require_once('ReglamentoTransito/reglamento.php');
 //clase de entidades de wit.ai
 class entitiesWit
{
	public $modulo;
	public $lugar;
	public $placas;
	public $saludo;
	public $hechosInfraccion;
	public $usuarioInfraccion;
}

class boton_Seleccionado
{
	public $modulo;
	public $articulo;
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

//clase para almacenar informacion sobre el corralon
class infoReglamento
{
	public $articulo;
	public $descripcion;
}
//*****************************************************************************************************
//JSON'S DE MENSAJES DE RESPUESTA A FACEBOOK
//*****************************************************************************************************

/*Seleccion del fomato Json de respuesta a enviar*/
function JsonReturn($Info,$sender,$modulo)
{
	$jsonData;
	switch ($modulo) {
		//El caso MENSAJE envía un saludo antes de enviar la información del módulo que el usuario solicitó	
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

		//El caso SALUDO envia un saludo de respuesta y botones con los módulos disponibles en el chatbot
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
									{
										"type":"postback",
										"title":"#reglamento",
										"payload":"btn_reglamento"
									},
								]
							}
						}
					}
				}';
			break;

		//El caso MENU muestra los módulos disponibles en el chatbot 
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
									{
										"type":"postback",
										"title":"#reglamento",
										"payload":"btn_reglamento"
									},
								]
							}
						}
					}
				}';
			break;

		//El caso BTN_AIRE muestra información sobre el modo de uso  del módulo de la calidad del aire
		//Este caso es accionado cuando el bonton #aire del menú o del mensaje de saludo es seleccionado
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

		//El caso BTN_CORRALON muestra información sobre el modo de uso del módulo de corralon
		//Este caso es accionado cuando el bonton #corralon del menú o del mensaje de saludo es seleccionado
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

		//El caso BTN_REGLAMENTO muestra información sobre el modo de uso del módulo del reglamento de tránsito
		//Este caso es accionado cuando el bonton #reglamento del menú o del mensaje de saludo es seleccionado
		case 'btn_reglamento':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" Este módulo te permite consultar información sobre el reglamento de tránsito de la CDMX, para ello sólo debe poner #reglamento, por ejemplo\n #reglamento\n  ;) "
					}
			}';			
			break;

		//El caso #aire muestra información hacerca del módulo de la calidad del aire
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

		//El caso #aire muestra información hacerca del módulo del corralon
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

		//El caso #aire muestra información hacerca del módulo del reglemento de tránsito
		case '#reglamento':
				$mensaje='articulo:'.$Info->articulo.', descripción:'.$Info->descripcion;
				$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"'.$mensaje.'",
									"buttons":[
									{
										"type":"postback",
										"title":"ver artículo",
										"payload":"leer_art '.$Info->articulo.'"
									},
									{
										"type":"postback",
										"title":"sanciones",
										"payload":"sanciones_art '.$Info->articulo.'"
									},
								]
							}
						}
					}
				}';
			break;
		case 'leer_art':
			$mensaje='articulo:'.$Info->articulo;
				$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"'.$mensaje.'",
									"buttons":[
									{
										"type":"postback",
										"title":"resumen",
										"payload":"resumen_art '.$Info->articulo.'"
									},
									{
										"type":"postback",
										"title":"artículo completo",
										"payload":"completo_art '.$Info->articulo.'"
									},
								]
							}
						}
					}
				}';
			break;
		case 'sanciones_art':
			$mensaje='articulo:'.$Info->articulo;
				$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"'.$mensaje.'",
									"buttons":[
									{
										"type":"postback",
										"title":"multa",
										"payload":"multa_art '.$Info->articulo.'"
									},
									{
										"type":"postback",
										"title":"puntos a la licencia",
										"payload":"puntosLicencia_art '.$Info->articulo.'"
									},
									{
										"type":"postback",
										"title":"remisión de vehículo",
										"payload":"remision_art '.$Info->articulo.'"
									},
								]
							}
						}
					}
				}';
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

function JsonReturnSancionesReglamento($Info,$sender,$modulo)
{
	$jsonData;
	switch ($modulo) {
		case 'multa_art':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"'.$Info.'"
					}
				}';
			break;
		case 'puntosLicencia_art':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"'.$Info.'"
					}
				}';
			break;
		case 'resumen_art':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"'.$Info.'"
					}
				}';
			break;
		case 'completo_art':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"'.$Info.'"
					}
				}';
			break;
		default:
			break;
	}
	return $jsonData;

}
function JsonReturnReglamento($usuario,$sender,$witEntities)
{
	$jsonData;
	switch ($usuario) {
		case 'ubicacion_usuario':
				$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"Excelente.Por ultimo envianos tu ubiación",
						"quick_replies":[
					      {
					        "content_type":"location"
					      }
					    ]
					}
				}';
			break;
		case 'conductor_vehiculo':
			$jsonData='{
					"recipient":{
						"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"ok. A continuación selecciona que tipo de vehículo conduces:",
									"buttons":[
											{
												"type":"postback",
												"title":"vehículo particular",
												"payload":"#reglamento conductor_particular '.$witEntities->hechosInfraccion.'"
											},
											{
												"type":"postback",
												"title":"vehículo publico",
												"payload":"#reglamento conductor_publico '.$witEntities->hechosInfraccion.'"
											},
											{
												"type":"postback",
												"title":"otro tipo vehículo",
												"payload":"#reglamento conductor_otro '.$witEntities->hechosInfraccion.'"
											}
									]
								}
							}
						}
					}';
			break;
		case 'usuarios':
			$jsonData='{
				"recipient":{
					"id":"'. $sender .'"
				},
				"message":{
					"attachment":{
						"type":"template",
						"payload":{
								"template_type":"button",
								"text":"Muy bien. Ahora selecciona que tipo de usuario eres:",
								"buttons":[
											{
												"type":"postback",
												"title":"ciclista",
												"payload":"#reglamento ciclista '.$witEntities->hechosInfraccion.'"
											},
											{
												"type":"postback",
												"title":"motociclista",
												"payload":"#reglamento motociclista '.$witEntities->hechosInfraccion.'"
											},
											{
												"type":"postback",
												"title":"conductor vehículo",
												"payload":"#reglamento conductor_vehiculo '.$witEntities->hechosInfraccion.'"
											}
									]
								}
							}
						}
					}';
			break;
		default:
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":"Hola que tal :), ingresa lo que quieres consultar del reglamento de tránsito",
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
 function handle_wit($entities,$entity)
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
	$witEntities->modulo=handle_wit($entities,"modulo");
	if(isset($witEntities->modulo))
	{
		$witEntities->modulo=str_replace("%23","#",$witEntities->modulo);
	}
	$witEntities->lugar=handle_wit($entities,"lugar");
	$witEntities->placas=handle_wit($entities,"placas");
	$witEntities->saludo=handle_wit($entities,"saludo");
	$witEntities->usuarioInfraccion=handle_wit($entities,"usuarioInfraccion");
	$witEntities->hechosInfraccion=handle_wit($entities,"hechosInfraccion");

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

//funcion para obtener información sobre el REGLAMENTO DE TRÁNSITO
function getInfoReglamento($jsonReglamento)
{
	$array_info;
	$num_articulos=count($jsonReglamento['response'])-1;
	if($num_articulos>0)
	{
		for($i=0;$i<=$num_articulos;$i++)
		{
			$info=new infoReglamento();
			$info->articulo=$jsonReglamento['response'][$i]["Articulo"];
			$info->descripcion=$jsonReglamento['response'][$i]["Descripcion"];
			$array_info[$i]=$info;
		}
	}
	return $array_info;
}

//Consula a la api del REGLAMENTO DE TRANSITO
function consultaReglamento($witEntities)
{
	//$input=json_decode(file_get_contents('php://input'), true);
	//$latitud=$input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['lat'];
	//$longitud$input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['long'];

	$enunciado=str_replace(" ","%20",$witEntities->hechosInfraccion);
	$url='http://148.206.32.60/ReglamentoCDMX/v1/index.php/Articulos?enunciado='.$enunciado.'&tipo_usuario='.$witEntities->usuarioInfraccion.'&latitud=19.4277394&longitud=-99.1290131';
	$ch=curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);

	$jsonReglamento=json_decode($result,true);
	$info=getInfoReglamento($jsonReglamento);

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
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;

			//Botones seleccionados del menú, muestran información y la forma de realizar las consultas en el chatbot para cada módulo	
			case 'btn_aire':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'btn_corralon':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'btn_reglamento':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;

			//Módulos de la aplicación de SMARTCDMX	
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

			case '#reglamento':
				if(isset($witEntities->usuarioInfraccion)&&isset($witEntities->hechosInfraccion)&&(strcmp($witEntities->usuarioInfraccion,'conductor_vehiculo')!==0))
				{
					$reglamentoInfo=consultaReglamento($witEntities);
					$num_articulos=count($reglamentoInfo);
					if($num_articulos>0)
					{
						for($i=0;$i<$num_articulos;$i++)
						{
							$jsonData=JsonReturn($reglamentoInfo[$i],$sender,$witEntities->modulo);
							enviar($jsonData,$access_token);
						}
					}
				}
				elseif (strcmp($witEntities->usuarioInfraccion,'conductor_vehiculo')===0) {
					$jsonData=JsonReturnReglamento($witEntities->usuarioInfraccion,$sender,$witEntities);
					enviar($jsonData,$access_token);
				}
				else{
					$jsonData=JsonReturnReglamento($witEntities->usuarioInfraccion,$sender,$witEntities);
					enviar($jsonData,$access_token);
				}
				break;
			case 'leer_art':
				$jsonData=JsonReturn($witEntities,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'sanciones_art':
				$jsonData=JsonReturn($witEntities,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'resumen_art':
				$resumenArt=Reglamento($witEntities->articulo,'resumen_art',null);
				$jsonData=JsonReturnSancionesReglamento($resumenArt,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'completo_art':
				$artCompleto=Reglamento($witEntities->articulo,'art_completo',null);
				$jsonData=JsonReturnSancionesReglamento($artCompleto,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'multa_art':
				$infoMulta=Reglamento($witEntities->articulo,null,'multa');
				$jsonData=JsonReturnSancionesReglamento($infoMulta,$sender,$witEntities->modulo);
				enviar($jsonData,$access_token);
				break;
			case 'puntosLicencia_art':
				$infoPuntos=Reglamento($witEntities->articulo,null,'puntos_licencia');
				$jsonData=JsonReturnSancionesReglamento($infoPuntos,$sender,$witEntities->modulo);
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
		//Json de repuesta cuando el usuario manda un saludo
		if(isset($witEntities->saludo))
		{
			$witEntities->modulo='saludo';
			$jsonData=JsonReturn(null,$sender,$witEntities->modulo);
			enviar($jsonData,$access_token);
		}
		elseif (isset($witEntities->hechosInfraccion)) {
			$jsonData=JsonReturnReglamento('usuarios',$sender,$witEntities);
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
			ReturnMessage($response_wit,$sender,$access_token);
		}
		elseif ($selec_btn) {
			if(strpos($selec_btn,"#")===0)
			{
				$selec_btn=str_replace(" ","%20",$selec_btn);
				$selec_btn=str_replace("#","%23",$selec_btn);
				$response_wit=wit_response($selec_btn);
				ReturnMessage($response_wit,$sender,$access_token);
			}
			else
			{
				$btn_selec=new boton_Seleccionado();
				$payload_div=explode(" ",$selec_btn);
				$sub_strings=count($payload_div);
				$btn_selec->modulo=$payload_div[0];

				if((strcmp($payload_div[0],'leer_art')===0)||(strcmp($payload_div[0],'sanciones_art')===0))
				{
					$btn_selec->articulo=$payload_div[1];
				}
				elseif((strcmp($payload_div[0],'multa_art')===0)||(strcmp($payload_div[0],'puntosLicencia_art')===0))
				{
					$btn_selec->articulo=$payload_div[1];
				}
				elseif((strcmp($payload_div[0],'resumen_art')===0)||(strcmp($payload_div[0],'completo_art')===0))
				{
					$btn_selec->articulo=$payload_div[1];
				}

				ReturnMessage($btn_selec,$sender,$access_token);
			}
		}
		
	}
}

Principal();

?>