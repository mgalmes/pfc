<?php
session_start();
header("access-control-allow-origin: *");
if (!isset($_SESSION['user'])) {
	header("Location: ./#seccion=inicio#error=Sesi%C3%B3n%20no%20iniciada.");
	die();
} else if ($_SESSION['user']['rolUsuario']!="1") {
	header("Location: ./#seccion=inicio#error=No%20tienes%20autorizaci%C3%B3n%20para%20visitar%20este%20sitio.");
	die();
}
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Gestión de Ayuntamiento de Foobar</title>  
	

	<!-- jquery -->
	<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script> -->
	
	<!-- jquery offline -->
	<script src="js/jquery.min.js"></script>
	
	<!-- iconos ffa -->
	<!-- <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script> -->
	
	<!-- font aw offline -->
	<script src="js/fontawesome.js"></script>
	
	  <!-- select 2 -->
	  <!-- <link async href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> -->
	
	<!-- select 2 offline -->
	<link async href="css/select2.min.css" rel="stylesheet" />
	<script src="js/select2.min.js"></script>
	
	<link href="css/toastme.css" rel="stylesheet">
	<script src="js/toastme.js"></script>

	<link rel="stylesheet" href="css/estilos.css">
	<script src="js/clases.js"></script>
	<script src="js/js.js"></script>
	<script src="js/admin.js"></script>
      <link rel="stylesheet" href="css/buttons.css">
	  <link rel="stylesheet" href="css/admin.css">
	  
	  <!-- google maps -->
  <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC24GO81Fb-gw3SzEpSGxy_d3oV4r3jiew&callback=initMap"></script>
  <!-- google maps offline-->
  <!-- <script async src="js/google-maps.js?key=AIzaSyC24GO81Fb-gw3SzEpSGxy_d3oV4r3jiew&callback=initMap"></script> -->
</head>

<body>
	<div class="side_menu">
		<div class="burger_box">
			<div class="menu-icon-container">
				<a href="#" class="menu-icon js-menu_toggle closed">
					<span class="menu-icon_box">
						<span class="menu-icon_line menu-icon_line--1"></span>
						<span class="menu-icon_line menu-icon_line--2"></span>
						<span class="menu-icon_line menu-icon_line--3"></span>
					</span>
				</a>
			</div>
		</div>
		<div class="container">
			<h2 class="menu_title">Administración interna</h2>
			<img class="logo_ayuntamiento">
			<ul class="list_load menu-lateral">
				<li class="list_item"><a href="index.php" id="link-logout"> <i class="fas fa-sign-out-alt"></i> Volver al mapa </a></li>
				<br>
				<li class="list_item"><a href="#">Dashboard</a></li>
				<li class="list_item"><a href="#seccion=ayuntamiento">Gestionar ayuntamiento</a></li>
				<li class="list_item"><a href="#seccion=usuariosRecientes">Gestionar usuarios</a></li>
				<li class="list_item"><a href="#seccion=categorias">Gestionar categorias</a></li>
				<li class="list_item"><a href="#seccion=mapaIncidencias">Mapa de incidencias</a></li>
				<li class="list_item"><a href="#seccion=incidenciasRecientes">Incidencias recientes</a></li>
				<br>
				
			</ul>
			<div class="spacer_box"><p>Ayuntamiento de Foobar</p></div>
		</div>
	</div>
	<div id="mainpage" class="content">
	<div class="main">
	<div class="what_to_do titulo">Dashboard del sistema</div>
		<h4>Administración interna del ayuntamiento</h4>
		<div class="panel">
			<div class="titulo-panel">Ayuntamiento</div>
			<div class="propiedad">Nombre</div>
			<div class="valor nombre_ayuntamiento"> - </div>
			<img src="" class="logo logo_ayuntamiento">
			<a href="#seccion=ayuntamiento" style="font-size: 80%;">Cambiar ayuntamiento</a>
		</div>
		<div class="panel" id="incidencias_zona">
			<div class="titulo-panel">Incidencias por zona</div>
			<div class="propiedad"><b>Zona</b></div>
			<div class="valor"> <b>Num. incidencias </b></div>
		</div>
		<div class="panel" id="incidencias_tipo">
			<div class="titulo-panel">Incidencias por tipo</div>
			<div class="propiedad"><b>Tipo</b></div>
			<div class="valor"> <b>Num. incidencias </b></div>
		</div>
		<div class="panel" id="incidencias_prioridad">
			<div class="titulo-panel">Incidencias por prioridad</div>
			<div class="propiedad"><b>Prioridad</b></div>
			<div class="valor"> <b>Num. incidencias </b></div>
		</div>
		<div class="panel" id="incidencias_estado">
			<div class="titulo-panel">Incidencias por estado</div>
			<div class="propiedad"><b>Estado</b></div>
			<div class="valor"> <b>Num. incidencias </b></div>
		</div>
	</div>
<div id="mapaIncidencias" class="seccion hide">
	
	<div class="what_to_do titulo">Mapa de incidencias</div>

	<div class="mapContainer">
	<div id="mapa"></div>
	</div>
	<div class="menu effect-13">
		<ul class="buttons">
			<li rel="tooltip" title="Mi ubicación"><a href="javascript:getMyLocation()"> <i class="fas fa-location-arrow"></i> <span>Mi ubicación</span></a></li>
			<li rel="tooltip" title="Nueva incidencia"><a href="javascript:nuevaIncidencia()"><i class="fas fa-thumbtack"></i> <span>Nueva incidencia</span></a></li>
			<!-- <li><a href="#"><i class="fas fa-pencil-alt"></i> Editando incidencia</a></li> -->
			<li rel="tooltip" title="Mostrar incidencias"><a href="#seccion=mostrarIncidencias"><i class="fas fa-search"></i><span>Mostrar incidencias</span></a></li>
			<li class="mobile-only"><a href="javascript:mostrarTooltips();"><i class="fas fa-question"></i></a></li>
		</ul>
	</div>
</div>
<div  id="usuariosRecientes" class="seccion hide">
	<h5 class="what_to_do titulo">Administrar usuarios</h5>
	<div class="listado">
		<ul class="list" style="list-style:none;" id="usuariosRecientesList">
		</ul>
	</div>
</div>
<div id="categorias" class="seccion hide">
	<h5 class="what_to_do titulo">Administrar categorias</h5>
	<div class="listado">
		<ul class="list" style="list-style:none;" id="categoriasList">
		</ul>
	</div>
	<div class="menu effect-13">
		<ul class="buttons">
			<li><a href="javascript:null"> <i class="fas fa-plus"></i> <span>Nueva categoría</span></a></li>
		</ul>
	</div>
</div>

<div id="modificarIncidencia" class="seccion hide with-close-btn">
	<div class="close"> <a href="#seccion=mapaIncidencias"><i class="far fa-times-circle"></i></a></div>
		<h2>Nueva incidencia</h2>
		<form class="formulario" method="POST" action="php/guardarIncidencia.php" target="_blank">
			
				<label>Título de la incidencia</label>
				<input type="text" name="titulo" value="" placeholder="Título de la incidencia" id="tituloIncidencia">
				<label>Estado</label>
				<select name="estado" value="" placeholder="Estado" id="estadoIncidencia"></select>
				<label>Prioridad</label>
				<select name="prioridad" value="" placeholder="Prioridad" id="prioridadIncidencia"></select>
			<label>Categoria</label>
				<select id="tipoIncidencia" name="tipoIncidencia">
				</select>
				<h5>Descripción de la incidencia</h5>
				<textarea name="descripcion" id="descripcionIncidencia" placeholder="Descripción de la incidencia"></textarea>
				<h5>Localización de la incidencia</h5>
				<div class="mapContainer">
				
				</div>
				<div style="font-size: 70%;">
				<p>Ubicación seleccionada: <span id="localizacion"></span></p>
				<p>Zona/barrio: <span id="barrio"></span></p>
				</div>
				<input type="text" placeholder="codigo" name="codigo" id="codigoIncidencia">
				<input type="text" placeholder="latitud" name="latitud" id="latitud">
				<input type="text" placeholder="longitud" name="longitud" id="longitud">
				<div class="menu effect-13">
					<ul class="buttons">
						<li id="enviarIncidencia"><a href="javascript:submitIncidenciaForm();"> <i class="fas fa-check-circle"></i> <span>Enviar incidencia</span></a></li>
						<li class="secundario"><a href="javascript:clearIncidenciaForm();"><i class="fas fa-eraser"></i> <span>Borrar</span></a></li>
					</ul>
				</div>
		</form>

</div>
<div id="modificarUsuario" class="seccion hide with-close-btn">
	<div class="close"> <a href="#seccion=usuariosRecientes"><i class="far fa-times-circle"></i></a></div>
		<h2>Nuevo usuario</h2>
		<form class="formulario" onsubmit="registrar();return false;">
			<label for="rolUsuario">Modificar rol</label>
			<select id="rolUsuario"></select>
			<label for="nombre">Nombre y apellido(s) </label>
			<div class="nombreApellidos">
				<input type="text" id="nombre2" placeholder="Nombre" name="nombre"><input type="text" id="apellidos2" placeholder="Apellido(s)" name="apellidos">
			</div>
			<label for="telefono">Nº Teléfono</label>
			<input type="text" id="telefono2" placeholder="Nº Teléfono (opcional)" name="telefono">
			<div class="content-check">
			<input type="submit" value="Modificar perfil">
			</div>
		</form>
</div>

<div id="registro" class="seccion hide">
	<div class="content-form">
		<h5 class="what_to_do titulo">Regístrate</h5>
		<h4>¿Usuario reincidente? <a href="#seccion=login">Inicia sesión</a></h4>
		<form class="formulario" onsubmit="registrar();return false;">
			<label for="nombre">Nombre y apellido(s) *</label>
			<div class="nombreApellidos">
				<input type="text" id="nombre" placeholder="Nombre" name="nombre"><input type="text" id="apellidos" placeholder="Apellido(s)" name="apellidos">
			</div>
			<label for="email">Email *</label>
			<input type="text" id="emailReg" placeholder="Correo electrónico" name="email">
			<label for="telefono">Nº Teléfono</label>
			<input type="text" id="telefono" placeholder="Nº Teléfono (opcional)" name="telefono">
			<label for="clave">Contraseña *</label>
			<input type="password" id="claveReg" name="password">
			<label for="clave">Repite la contraseña *</label>
			<input type="password" id="clave2">
			<div class="content-check">
			<label for="aceptarTerminos"><input type="checkbox" id="aceptarTerminos"> Acepto los términos y la política de privacidad</label>
			<input type="submit" value="Regístrate">
			</div>
		</form>
	</div>
</div>
<div id="ayuntamiento" class="seccion hide">
	<div class="content-form">
		<h5 class="what_to_do titulo">Datos del ayuntamiento</h5>
		<h4>Modificar ayuntamiento</h4>
		<form class="formulario" method="POST" target="file_frame" action="php/guardarAyuntamiento.php" enctype="multipart/form-data">
			<label>Ayuntamiento: 
			<input type="hidden" id="codigo_ayuntamiento" name="codigo">
			<select id="selectAyuntamiento" disabled>
				<option>Cargando datos...</option>
			</select>
			</label>
			<label>Codigo: <span class="codigo_ayuntamiento"></span></label>
			<h5>Información</h5>
			<p>
				Usuarios registrados: <span id="numeroUsuarios"></span><br>
				Incidencias totales: <span id="numIncidencias"></span><br>
			</p>
			
			<h5>Modificar datos</h5>
			<label for="nombre_ayuntamiento">Nombre</label>
			<input type="text" id="nombre_ayuntamiento" placeholder="Nombre" name="nombre">
			<label for="imagen_ayuntamiento">Imágen del ayuntamiento</label>
			<img src="" title="" class="logo_ayuntamiento">
			<input type="file" id="imagen_ayuntamiento" name="imagen" style="border:0;">
			<img class="logo">
			<iframe name="file_frame" style="display:none;" onload="actualizarAyuntamientos()" ></iframe>
			<div class="content-check">
				<input type="submit" value="Modificar datos">
			</div>
		</form>
	</div>
</div>
<div id="mostrarIncidencias" class="seccion hide with-close-btn">
	<div class="close"> <a href="#seccion=mapaIncidencias"><i class="far fa-times-circle"></i></a></div>
	<div class="content-form">
		<h4>Filtro de incidencias</h4>
		<form class="formulario" onsubmit="mostrarIncidencias();return false;">
			<b>Filtrar por tipo</b>
			<select multiple id="tiposIncidencias2"></select>
			<b>Filtrar por zona</b>
			<select multiple id="zonas"></select>
			<!--<div id="tiposIncidencias">
			<!--<label><input type="checkbox" value="val"> Nombre Tipo</label>-->
			<!--</div>-->
			<div class="content-check">
			</div>
		</form>
	</div>
</div>
<div id="myToast" class="toast-popup"></div>
</div>

</body>
</html>
