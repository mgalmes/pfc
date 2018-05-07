// Requires jQuery
//Declaration of functions
var queryFn,queryMatches;
var openMenu = function(e){
    if (e) e.preventDefault(); $('.list_load, .list_item').stop();
    $(".js-menu_toggle").removeClass('closed').addClass('opened');

    $('.side_menu').css({ 'left':'0px' });

    var count = $('.list_item').length;
    $('.side_menu .list_load').slideDown( (count*.6)*100 );
    $('.side_menu .list_item').each(function(i){
        var thisLI = $(".side_menu .list_item");
        timeOut = 100*i;
        setTimeout(function(){
            thisLI.css({
                'opacity':'1',
                'margin-left':'0'
            });
        },100*i);
    });
};
var closeMenu = function(e){
	if (!queryMatches) return;
    if (e)e.preventDefault(); $('.list_load, .list_item').stop();
    $(".js-menu_toggle").removeClass('opened').addClass('closed');

    $('.side_menu').css({ 'left':'-250px' });

    var count = $('.side_menu .list_item').length;
    $('.side_menu .list_item').css({
        'opacity':'0',
        'margin-left':'-20px'
    });
    $('.side_menu .list_load').slideUp(300);
};
queryFn = function (query) {
	queryMatches = query.matches;
    if (query.matches) {
        closeMenu();
    } else {
        openMenu();
    }
};

var mediaQuery = window.matchMedia("(max-width:768px)");
mediaQuery.addListener(queryFn);
//document listeners
$(document).on('click','.js-menu_toggle.opened',closeMenu);
$(document).on('click','.js-menu_toggle.closed',openMenu);

//on load
$(function () {
	//auto mobile query
	queryFn(mediaQuery);
});
$(function(){
	loadSection(window.location.hash);
	$(window).on("hashchange",function(){
		var hash = window.location.hash;
		loadSection(hash);
	});
});

var loadSection = function(hash) {
	var match = hash.match(/seccion=(\w+)/);
	if (match){
		//oculta/muestra la seccion
		$(".seccion").toggleClass("hide",true);
		$("#"+match[1]).toggleClass("hide",false);
		switch (match[1]) {
			case "mapaIncidencias":
				$("#mapaIncidencias .mapContainer").append($("#mapa"));
				break;
			case "modificarIncidencia":
				match = hash.match(/incidencia=(\d+)/);
				if (match && match[1]) {
					var incidencia = match[1];
					console.log("Abrir incidencia "+incidencia);
				}
				buscarIncidencia({},function(incidencias){
					
					var incidencia = incidencias[0];
					$("#tituloIncidencia").val(incidencia.titulo);
				});
				$("#modificarIncidencia .mapContainer").append($("#mapa"));
				$("#tituloIncidencia").focus();
				$("#tipoIncidencia").select2();
				break;
		}
		closeMenu();//cerrar el menu
	}
};


/*

CARGAR RECURSOS BBDD
Obtener objetos de la base de datos.

*/

function buscarIncidencia(objFiltro,callback) {
	var filtro = "?";
	$.getJSON('php/buscarIncidencia.php'+filtro,function(incidencias) {
		if (callback) {
			var result = [];
			for (var incidencia of incidencias) {
				var obj = new Incidencia();
				obj.codigo = incidencia.codigo;
				obj.titulo = incidencia.titulo;
				obj.tipoIncidencia = incidencia.tipoIncidencia;
				obj.prioridad = incidencia.prioridad;
				obj.estado = incidencia.estado;
				obj.codigoUsuario = incidencia.codigoUsuario;
				obj.fecha = incidencia.fecha;
				obj.fechaResolucion = incidencia.fechaResolucion;
				obj.latitud = incidencia.latitud;
				obj.longitud = incidencia.longitud;
				result.push(obj);
			}
			callback(result);
		}
	});
}


/**

GOOGLE MAPS
Para conectar el mapa a google maps

*/

/*
TODO: consultar nombre de calles

*/

$(function(){//intenta ubicar al cargar la pagina
	getMyLocation();
});
var map;
  function initMap() {
	  
  $(function(){//cargar mapa al haber cargado la página
	 
	map = new google.maps.Map(document.getElementById('mapa'), {
	  center: {lat: 39.5687965, lng: 2.6673537},
	  zoom: 18
	});
	
	if (window.myLocation) {
		showMyLocation(window.myLocation);
	}
	
	
	
	map.setZoom(18);
	map.addListener('click', addLatLng);
	
	//mostrar incidencias
	buscarIncidencia({},function(incidencias) {
		/*
			por ahora descargamos todas las incidencias y las situamos en el mapa (provisional!!), mas adelante se filtrara por ubicacion
		*/
		for (var incidencia of incidencias) {
			(function(incidencia){
				var iconUrl = location.origin+location.pathname+"img/info-i_maps.png";
				console.log(iconUrl);
				var marker = new google.maps.Marker({
				  position: {lat:parseFloat(incidencia.latitud),lng:parseFloat(incidencia.longitud)},
				  icon: {url:iconUrl},
				  title: incidencia.titulo,
				  map: map
				});
				
				marker.addListener("click",function(){
					if (typeof infoWindow !="undefined") {
						//borramos cualquier info window que este abierto
						infoWindow.setMap(null);
					}
					infoWindow = new google.maps.InfoWindow({map:map});
					infoWindow.setPosition(marker.getPosition());
					infoWindow.setContent(incidencia.titulo);
				});
			}(incidencia));	
		}
		for (var incidencia of incidencias.sort(function(a,b) {
				var a = Date.parse(a.fecha);
				var b = Date.parse(b.fecha);
				return a-b;
				}
			).reverse()) {
			//incidencias recientes (provisional!!)
				$("#incidenciasRecientesList").append("<li class=\"list-item\">"+incidencia.titulo+" <a class=\"view-btn button\" href=\"#seccion=modificarIncidencia#incidencia="+incidencia.codigo+"\">" +incidencia.fecha+"»</a></li>");
		}
	});
  });
  }
  
  function getMyLocation() {
	// Try HTML5 geolocation.
	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(function(position) {
		  window.myLocation = position;
		  showMyLocation(window.myLocation);
	  }, function() {
		  console.log("Error location");
	  });
	} else {
		alert("Location not supported");
	}
  }
  
  function showMyLocation(position) {
		var pos = {
		  lat: position.coords.latitude,
		  lng: position.coords.longitude
		};
		var myloc = new google.maps.Marker({
			clickable: false,
			icon: new google.maps.MarkerImage('img/miUbicacion.png'),
			// shadow: null,
			// zIndex: 999,
			position: pos,
			map: map
		});
		
		map.setCenter(pos);
		myloc.addListener('click', function() {
			map.setZoom(18);
			map.setCenter(myloc.getPosition());
		});
  }

function addLatLng(event) {
	// Add a new marker at the new plotted point on the polyline.
	if (typeof marker != "undefined") {
		marker.setMap(null);//borrar el marker
	};
	marker = new google.maps.Marker({
	  position: event.latLng,
	  title: 'Incidencia aqui',
	  map: map
	});
	var lat = event.latLng.lat();
	var lng = event.latLng.lng();
	$("#latitud").val(lat);
	$("#longitud").val(lng);
	
	//intentar detectar el nombre de la localización
	$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key=AIzaSyC24GO81Fb-gw3SzEpSGxy_d3oV4r3jiew",function(resp) {
		if (typeof infoWindow !="undefined") {
						//borramos cualquier info window que este abierto
						infoWindow.setMap(null);
		}
		var nombre = "Nombre de lugar desconocido...";
		var descr = "-";
		try {
			//para cualquier caso
			nombre = resp.results[0].formatted_address.slice(0,30);
			descr = nombre;
			//resultados
			var found = false;
			for (var result of resp.results) {
				if (resp.results.indexOf(result)>4) break;//nadie va a la 2a pag de resultados de google, aqui igual
				var components = result.address_components;
				for (var comp of components) {
					if (comp.types.indexOf("route")>-1) {
						nombre = comp.long_name.slice(0,30);
						found = true;
						break;
					}
				}
				if (found) break;
			}
		} catch(e) {
			console.log(e);
		};
		infoWindow = new google.maps.InfoWindow({map:map});
		infoWindow.setPosition(marker.getPosition());
		infoWindow.setContent("<h3>"+nombre+"</h3>"+"<p>"+descr+"</p>"+"<div class=\"menu effect-13\">"+
			"<ul class=\"buttons\">"+
					(location.hash.indexOf("seccion=modificarIncidencia")==-1?"<li class=\"secundario\"><a href=\"#seccion=modificarIncidencia\">"+" <i class=\"fas fa-check-circle\"></i> Notificar incidencia</a></li>":"")+
					"</ul>");
	});
}

function nuevaIncidencia() {
	window.location = "#seccion=modificarIncidencia";
}
