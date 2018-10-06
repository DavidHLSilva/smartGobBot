function getParametros(name)
{
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function mapa()
{
	var lat=getParametros("lat");
	var long=getParametros("long");

	 if(lat && long)
        {
		lat=parseFloat(lat);
		long=parseFloat(long);
        }else{
		lat=19.4327133;
		long=-99.133854;
        }


	var map = new ol.Map({
  		layers: [new ol.layer.Tile({source: new ol.source.OSM()})],
		target: 'map',
		view: new ol.View({
		projection: 'EPSG:4326',
		center: [long, lat],
		zoom: 18})});
	return map;
}

function getCorralon()
{
    var corralon=getParametros("corralon");
        if(corralon)
        {
        document.getElementById("corralon").textContent=corralon;
        }else{
            document.getElementById("corralon").textContent="----";
        }

}


function getDireccion()
{
	var direccion=getParametros("direccion");
        if(direccion)
        {
		document.getElementById("direccion").textContent=direccion;
        }else{
            document.getElementById("direccion").textContent="------";
        }
}


function getNumCorralon()
{
	var NumCorralon=getParametros("NumCorralon");
        if(NumCorralon)
        {
		document.getElementById("NumCorralon").textContent="Corralon número: "+NumCorralon;
        }else{
            document.getElementById("NumCorralon").textContent="Corralon número: ---";
        }

}


function getTelefono()
{
	var telefono=getParametros("telefono");
        if(NumCorralon)
        {
		document.getElementById("telefono").textContent="Teléfono: "+telefono;
        }else{
            document.getElementById("NumCorralon").textContent="Teléfono:---";
        }

}