function getParametros(name)
{
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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

    var corralon=getParametros("corralon");

    switch(corralon) {
        case "Centro Histórico":
            lat=19.4251511;
            long=-99.1363881;
            break;
        case "Obrera":
            lat=19.4135045;
            long=-99.1341072;
            break;
        case "Zarco":
            lat=19.4670702;
            long=-99.0949738;
            break;
        case "Indios Verdes":
            lat=19.5007946;
            long=-99.1177778;
            break;
        case "Culturas":
            lat=19.5098808;
            long=-99.1892982;
            break;
        case "Las Armas":
            lat=19.4849162;
            long=-99.2145211;
            break;
        case "Salinillas":
            lat=19.4445796;
            long=-99.2164059;
            break;
        case "Canarios":
            lat=19.3936252;
            long=-99.1927138;
            break;
        case "Grutas":
            lat=19.3933501;
            long=-99.1894197;
            break;
        case "Las Águilas":
            lat=19.3523528;
            long=-99.2073985;
            break;
        case "Xochimilco":
            lat=19.2498871;
            long=-99.1085244;
            break;
        case "Tláhuac":
            lat=19.279446662;
            long=-99.00141156;
            break;
        case "Piraña":
            lat=19.28284108;
            long=-99.06373134;
            break;
        case "Santa Cruz Meyehualco":
            lat=19.3663758;
            long=-99.0295706;
            break;
        case "Módulo 39":
            lat=19.3848047;
            long=-99.0468299;
            break;
        case "Fuerte Loreto":
            lat=19.3639998;
            long=-99.03092529;
            break;
        case "Añil":
            lat=19.4062972;
            long=-99.1029259;
            break;
        case "Troncoso":
            lat=19.3924256;
            long=-99.1139732;
            break;
        case "La Viga":
            lat=19.3860634;
            long=-99.1208932;
            break;
        case "Cuemánco":
            lat=19.2918259;
            long=-99.1082136;
            break;
        case "La Noria":
            lat=19.2621502;
            long=-99.1258209;
            break;
        case "Río San Joaquín":
            lat=19.4451816;
            long=-99.1926872;
            break;
        case "Cien Metros":
            lat=19.48419;
            long=-99.1428807;
            break;
        case "Velódromo":
            lat=19.4101754;
            long=-99.1022906;
            break;
        case "Santa Fé":
            lat=19.3691817;
            long=-99.2578685;
            break;
        case "Coyoacán":
            lat=19.3015874;
            long=-99.1455427;
            break;
        case "Fresno":
            lat=19.4622004;
            long=-99.1576416;
            break;
        case "Tierra Unida":
            lat=19.3020266;
            long=-99.2698592;
        default:
        break;
    }

    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat( long ,lat )
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );
          
    var zoom=17;

    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
    
    markers.addMarker(new OpenLayers.Marker(lonLat));
    
    map.setCenter(lonLat, zoom);

    return map;
}