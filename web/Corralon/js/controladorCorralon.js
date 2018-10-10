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
		lat=parseFloat(lat)+0.0002;
		long=parseFloat(long)-0.0002;
        }else{
		lat=19.4327133;
		long=-99.133854;
        }

    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat( long ,lat )
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );
          
    var zoom=18;

    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
    
    markers.addMarker(new OpenLayers.Marker(lonLat));
    
    map.setCenter(lonLat, zoom);

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
		document.getElementById("telefono").textContent=telefono;
        }else{
            document.getElementById("telefono").textContent=".---";
        }

}