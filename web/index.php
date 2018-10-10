<?php
require_once('ReglamentoTransito/reglamento.php');
require_once('SAC/formularioSac.php');
 //clase de entidades de wit.ai
 class entitiesWit
{
	public $modulo;
	public $lugar;
	public $placas;
	public $saludo;
	public $hechosInfraccion;
	public $usuarioInfraccion;
	public $hechosSAC;
	public $correo;
	public $nombre;
}

class boton_Seleccionado
{
	public $modulo;
	public $articulo;
	public $latitud;
	public $longitud;
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
	public $numCorralon;
	public $numero;
	public $CP;
	public $coordx;
	public $coordy;
}

//clase para almacenar informacion sobre el Reglamento de transito
class infoReglamento
{
	public $articulo;
	public $descripcion;
}
//*****************************************************************************************************
//JSON'S DE MENSAJES DE RESPUESTA A FACEBOOK
//*****************************************************************************************************

/*Seleccion del fomato Json de respuesta a enviar*/
function JsonReturn($Info,$sender,$modulo,$access_token)
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
						"text":" :) La información que solicitaste es la siguiente, espero haber sido de gran ayuda (y) "
					}
			}';
			break;

		//El caso SALUDO envia un saludo de respuesta y botones con los módulos disponibles en el chatbot
		case 'saludo':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" SmartCDMX \"La ciudad en la palma de tu mano\", por éste medio tendrás acceso a las siguientes funcionalidades de nuestra app: "
					}
			}';
			enviar($jsonData,$access_token);


			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo de la calidad del aire",
									"buttons":[
									{
										"type":"postback",
										"title":"#aire",
										"payload":"btn_aire"
									},
								]
							}
						}
					}
				}';
			enviar($jsonData,$access_token);
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo de Corralon",
									"buttons":[
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
			enviar($jsonData,$access_token);
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo del Reglamento de Tránsito",
									"buttons":[
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
			enviar($jsonData,$access_token);
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo de atención ciudadana",
									"buttons":[
									{
										"type":"postback",
										"title":"#atencionCiudadana",
										"payload":"btn_atencionCiudadana"
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
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" Hola :) , estos son los módulos que actualmente están disponibles, selecciona el que gustes "
					}
			}';
			enviar($jsonData,$access_token);

			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo de la calidad del aire",
									"buttons":[
									{
										"type":"postback",
										"title":"#aire",
										"payload":"btn_aire"
									},
								]
							}
						}
					}
				}';
			enviar($jsonData,$access_token);
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo de Corralon",
									"buttons":[
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
			enviar($jsonData,$access_token);
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo del Reglamento de Tránsito",
									"buttons":[
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
			enviar($jsonData,$access_token);
			$jsonData='{
					"recipient":{
							"id":"'. $sender .'"
					},
					"message":{
						"attachment":{
							"type":"template",
							"payload":{
									"template_type":"button",
									"text":"Módulo de atención ciudadana",
									"buttons":[
									{
										"type":"postback",
										"title":"#atencionCiudadana",
										"payload":"btn_atencionCiudadana"
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
						"text":" Éste módulo te permite consultar información sobre la calidad del aire de alguna delegación, para ello debes poner #aire y la delegación, por ejemplo\n #aire TLAHUAC\n  ;) "
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
						"text":" Éste módulo te permite consultar información sobre el corralón donde está tu auto, para ello debes poner #corralon y la placa del auto, por ejemplo\n #corralon 123ZBC\n  ;) "
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
						"text":" Éste módulo te permite consultar información sobre el reglamento de tránsito de la CDMX. \n Para consultar el reglamento debes poner #reglamento \n  ;) "
					}
			}';			
			break;

		case 'btn_atencionCiudadana':
			$jsonData='{
					"recipient":
					{
						"id":"'. $sender .'"
					},
					"message":
					{
						"text":" Éste módulo te permite realizar demandas de servicios, quejas, denuncias, solicitudes de información, comentarios o sugerencias sobre uno o más asuntos que le competan, para ello debes de ingresar #atencionCiudadana y una descripcion de su solicitud. Por ejemplo \n #atencionCiudadana no hay agua en Iztapalapa desde hace tres días "
					}
			}';		
			break;

		//El caso #aire muestra la información de respuesta que el usuario a solicitado al modulo de calidad del aire
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
							"text":"No encontré información relacionada con la delegación :/ . Un ejemplo de solicitud de calidad del aire podría ser:\n #aire IZTAPALAPA"
						}
				}';
			}
			else
			{
				if(strcmp($Info->name, "CDMX")===0)
				{
					$jsonData='{
							"recipient":
							{
								"id":"'. $sender .'"
							},
							"message":
							{
								"text":" :) La información que solicitaste es la siguiente, espero haber sido de gran ayuda (y) , también puedes consultar la calidad del aire en alguna alcaldía, ejemplo: #aire Iztapalapa "
							}
					}';
					enviar($jsonData,$access_token);
				}
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

		//El caso #corralon muestra la información de respuesta que el usuario a solicitado al modulo de corralon
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
						"text":" :o Al parecer tu auto no se encuentra en un corralon, o ingresaste erroneamente la placa del auto"
					}
				}';
			}
			else
			{

					$Info->direccion=str_replace(" ", "%20", $Info->direccion);
					$jsonData='{
						"recipient":{
								"id":"'. $sender .'"
						},
						"message":{
							"attachment":{
								"type":"template",
								"payload":{
										"template_type":"button",
										"text":":) La información que solicitaste es la siguiente, espero haber sido de gran ayuda (y)",
										"buttons":[
										{
											"type":"web_url",
											"url":"http://ancient-brushlands-87186.herokuapp.com/Corralon/infoCorralon.html?corralon='.$Info->nombre.'&direccion='.$Info->direccion.'&NumCorralon='.$Info->numCorralon.'&telefono='.$Info->numero.'&lat='.$Info->coordx.'&long='.$Info->coordy.'",
											"title":"ver corralon"
										},
									]
								}
							}
						}
					}';
				}
			break;

		//El caso #reglsamento envia el articulo relacionado con la descripción que el usuario ingreso
		case '#reglamento':
			$image="http://ancient-brushlands-87186.herokuapp.com/imagenes/reglamento_bot/img_reglamento.png";
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
												"title":" Artículo '.$Info->articulo.'",
												"image_url":"'.$image.'",
				            					"subtitle":"'.$Info->descripcion.'",
				            					"buttons":[
														{
															"type":"web_url",
															"url":"http://ancient-brushlands-87186.herokuapp.com/ReglamentoTransito/articulos.php?articulo='.$Info->articulo.'",
															"title":"ver artículo"
														}
												]
											}
										]
									}
								}
							}
					}';
			break;
		case 'manualReglamento':
		$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"Para consultar el reglamento de tránsito debes poner #reglamento usuario descripcion por ejemplo:\n#reglamento conductor_particular me pase un alto"
						}
				}';
		break;
		case 'usuariosReglamento':
			$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"Los usuarios disponibles actualmente son:\nciclista\nmotociclista\nconductor_particular\nconductor_publico\nconductor_otro"
						}
				}';
			break;
		case '#atencionCiudadana':
			$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"Bienvenido al modulo de atención ciudadana, por favor ingresa una breve descripción de los hechos"
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
									"text":" SmartCDMX \"La ciudad en la palma de tu mano\", te muestro el menú de modelos disponibles ",
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
 


 function formularioSAC($sender,$selec)
 {
 	$jsonData;
 	switch ($selec) {
 		case 'pedirCorreo':

 			$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"Ok, para darle seguimiento a tu denuncia, por favor ingresa una cuenta de correo electrónico"
						}
				}';
 			break;

 		case 'pedirNombre':
 			$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"Para finalizar por favor, ingresa tu nombre o algún alias"
						}
				}';
 			break;
 		case 'enviarFormulario':
 			consultarDenuncia($sender);
 			$jsonData='{
						"recipient":
						{
							"id":"'. $sender .'"
						},
						"message":
						{
							"text":"Tu denuncia se ha enviado con exito"
						}
				}';
 			break;
 		
 		default:
 			# code...
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

	$witEntities->hechosSAC=handle_wit($entities,"hechosSAC");
	$witEntities->correo=handle_wit($entities,"email");
	$witEntities->nombre=handle_wit($entities,"contact");

	if (isset($witEntities->hechosSAC) && (strcmp($witEntities->modulo, "#atencionCiudadana")===0)) {
		$witEntities->modulo="#atencionCiudadana";
		$witEntities->hechosSAC=$result_json["_text"];
	}
	elseif (strcmp($witEntities->modulo, "#atencionCiudadana")===0) {
		$witEntities->modulo="btn_atencionCiudadana";
	}
	elseif (isset($witEntities->correo)) {
		$witEntities->correo=str_replace("%40","@",$witEntities->correo);
		$witEntities->modulo="#atencionCiudadana";
	}
	elseif (isset($witEntities->nombre)) {
		$witEntities->modulo="#atencionCiudadana";
	}

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
		$info->numCorralon=$jsonCorralon[$ultimoCorralon]["No_Corralon"];
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
function consultaReglamento($witEntities,$sender)
{
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
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
				enviar($jsonData,$access_token);
				break;

			//Botones seleccionados del menú, muestran información y la forma de realizar las consultas en el chatbot para cada módulo	
			case 'btn_aire':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
				enviar($jsonData,$access_token);
				break;
			case 'btn_corralon':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
				enviar($jsonData,$access_token);
				break;
			case 'btn_reglamento':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
				enviar($jsonData,$access_token);
				break;
			case 'btn_atencionCiudadana':
				$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
				enviar($jsonData,$access_token);
				break;
			//Módulos de la aplicación de SMARTCDMX	
			case '#aire':
				if(isset($witEntities->lugar))
				{
					$AirInfo=ConsultaCalidadAire($witEntities->lugar);	
					if(isset($AirInfo))
					{
						$jsonData=JsonReturn(null,$sender,'mensaje',$access_token);
						enviar($jsonData,$access_token);
					}
					$jsonData=JsonReturn($AirInfo,$sender,$witEntities->modulo,$access_token);
					enviar($jsonData,$access_token);
				}
				else
				{
					$witEntities->lugar="CDMX";
					$AirInfo=ConsultaCalidadAire($witEntities->lugar);
					//$jsonData=JsonReturn(null,$sender,'mensaje',$access_token);
					//enviar($jsonData,$access_token);	
					$jsonData=JsonReturn($AirInfo,$sender,$witEntities->modulo,$access_token);
					enviar($jsonData,$access_token);
				}
				break;

			case '#corralon':
				$corralonInfo=consultaCorralon($witEntities->placas);
				/*if(isset($corralonInfo))
				{
					$jsonData=JsonReturn(null,$sender,'mensaje');
					enviar($jsonData,$access_token);
				}*/
				$jsonData=JsonReturn($corralonInfo,$sender,$witEntities->modulo,$access_token);
				enviar($jsonData,$access_token);
				break;

			case '#reglamento':
				if(isset($witEntities->usuarioInfraccion)&&isset($witEntities->hechosInfraccion))
				{
					$reglamentoInfo=consultaReglamento($witEntities,$sender);
					$num_articulos=count($reglamentoInfo);
					if($num_articulos>0)
					{
						for($i=0;$i<$num_articulos;$i++)
						{
							$jsonData=JsonReturn($reglamentoInfo[$i],$sender,$witEntities->modulo,$access_token);
							enviar($jsonData,$access_token);
						}
					}
				}
				else
				{
					$jsonData=JsonReturn(null,$sender,'manualReglamento',$access_token);
					enviar($jsonData,$access_token);
					$jsonData=JsonReturn(null,$sender,'usuariosReglamento',$access_token);
					enviar($jsonData,$access_token);
				}
			break;
			case '#atencionCiudadana':
					//$info=consultarDenuncia($sender);
					if (isset($witEntities->hechosSAC)) 
					{
						almacenaDenuncia($sender,$witEntities->hechosSAC,"correo","nombre");
						$jsonData=formularioSAC($sender,'pedirCorreo',$access_token);
						enviar($jsonData,$access_token);
					}
					elseif (isset($witEntities->correo)) {
						$info=consultarDenuncia($sender);
						almacenaDenuncia($sender,$info["descripcion"],$witEntities->correo,"nombre");
						$jsonData=formularioSAC($sender,'pedirNombre',$access_token);
						enviar($jsonData,$access_token);
					}
					elseif (isset($witEntities->nombre)) {
						$info=consultarDenuncia($sender);

						if((strcmp($info["descripcion"], "")!==0)&&(strcmp($info["correo"], "")!==0) )
						{
							almacenaDenuncia($sender,$info["descripcion"],$info["correo"],$witEntities->nombre);
							$jsonData=formularioSAC($sender,'enviarFormulario',$access_token);
							enviar($jsonData,$access_token);
						}
						else
						{
							$jsonData=JsonReturn(null,$sender,"indefinido",$access_token);
							enviar($jsonData,$access_token);
						}
					}
					else
					{
						$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
						enviar($jsonData,$access_token);
					}
				break;
			default:
				$witEntities->modulo='indefinido';
				$jsonData=JsonReturn($AirInfo,$sender,$witEntities->modulo,$access_token);
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
			$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
			enviar($jsonData,$access_token);
		}
		else
		{
			$witEntities->modulo='indefinido';
			$jsonData=JsonReturn(null,$sender,$witEntities->modulo,$access_token);
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
	$access_token="EAADlwZCSgxfgBAH8RYVcvWayahMjtVnMCtrXtNygol5eFGcwHaTbIaqUmMi34YcZCtQU4iz6mZCjePKphlPib987wJCFLxDs5CsU5YNJ6nVtvOJITrnY0q04MFnrhkNKvRqvKUXrCIsSjK5HPNpecHYusDRSojV9oJdqyOfaaidaK5cbYpG";

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
			//si el usuario envió un mensaje sutituimos los espacios en blanco por su respectivo código url %20
			$message=str_replace(" ","%20",$message);
			//si el usuario envió un mensaje sutituimos los # por su respectivo código url %23
			$message=str_replace("#","%23",$message);
			//si el usuario envió un mensaje sutituimos los @ por su respectivo código url %40
			// puedes consultar los codigos url en https://es.wikipedia.org/wiki/C%C3%B3digo_porciento
			$message=str_replace("@","%40",$message);
			$response_wit=wit_response($message);
			ReturnMessage($response_wit,$sender,$access_token);
		}
		elseif ($selec_btn) {
			//si el usuario presionó un botón entramos en está sección
			/*if(strpos($selec_btn,"#")===0)
			{
				$selec_btn=str_replace(" ","%20",$selec_btn);
				$selec_btn=str_replace("#","%23",$selec_btn);
				$response_wit=wit_response($selec_btn);
				ReturnMessage($response_wit,$sender,$access_token);
			}
			else
			{*/
				$btn_selec=new boton_Seleccionado();
				$payload_div=explode(" ",$selec_btn);
				$sub_strings=count($payload_div);
				$btn_selec->modulo=$payload_div[0];

				/*if((strcmp($payload_div[0],'leer_art')===0)||(strcmp($payload_div[0],'sanciones_art')===0))
				{
					$btn_selec->articulo=$payload_div[1];
				}
				elseif((strcmp($payload_div[0],'multa_art')===0)||(strcmp($payload_div[0],'puntosLicencia_art')===0)||(strcmp($payload_div[0],'remision_art')===0))
				{
					$btn_selec->articulo=$payload_div[1];
				}
				elseif((strcmp($payload_div[0],'resumen_art')===0)||(strcmp($payload_div[0],'completo_art')===0))
				{
					$btn_selec->articulo=$payload_div[1];
				}*/

				ReturnMessage($btn_selec,$sender,$access_token);
			//}
		}
		
	}
}

Principal();

?>