<?php
include '../autentica.php';
include '../conexion.inc.php';

$news = 0;
$stmt = mssql_query("EXEC General..sp_getInformativo 0", $cnx);
if($rst=mssql_fetch_array($stmt)){
	if($rst["dblMinId"]!='') $news = 1;
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../images/favicon.ico" rel="shortcut icon"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n</title>
<style>
a {text-decoration: none;}
a:hover {text-decoration: none;}
</style>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" src="../ventanas.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('workspace').setAttribute('height', window.innerHeight - 25);
	document.getElementById('divIngreso').style.left=getPosicion('aIngreso'); 
	document.getElementById('divConsulta').style.left=getPosicion('aConsulta');
	document.getElementById('divListado').style.left=getPosicion('aListado');
	document.getElementById('divMantencion').style.left=getPosicion('aMantencion');
	document.getElementById('divAutorizar').style.left=getPosicion('aAutorizar');
	document.getElementById('divInmobiliaria').style.left=getPosicion('aInmobiliaria');
/*
	if(parseInt('<?php echo $news;?>') == 0) 
		Expander();
	else{
		document.getElementById('frmInformativo').src="../noticias.php"
		document.getElementById('divInformativo').style.left=(window.innerWidth - 250)/2;
	}
*/
}

function Unload(){
	top.location.href='../logout.php<?php echo $parametros;?>'
}

function Expander(){
	if(document.getElementById('sw').value == 'menos'){
		document.getElementById('divInformativo').style.zIndex = 1;
		document.getElementById('divInformativo').style.width = '20px'
		document.getElementById('divInformativo').style.left = (window.innerWidth - 20);
		document.getElementById('divInformativo').style.top = '0%';
		
		document.getElementById('trId').style.display = 'none';
		document.getElementById('tdTitulo').style.display = 'none';
		
		document.getElementById('imgTitle').src = '../images/mas.gif';
		document.getElementById('sw').value = 'mas';
	}else{
		document.getElementById('divInformativo').style.zIndex = 7;
		document.getElementById('divInformativo').style.left = (window.innerWidth - 250)/2;
		document.getElementById('divInformativo').style.top = '4%';
		document.getElementById('divInformativo').style.width = '300px'
		document.getElementById('trId').style.display = '';
		document.getElementById('tdTitulo').style.display = '';
		document.getElementById('imgTitle').src='../images/menos.gif';
		document.getElementById('sw').value = 'menos';
	}
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();" onunload="javascript: Unload()">

<div id="divIngreso" style="border:solid; z-index: 2; position:absolute; top:20px; visibility:hidden; -moz-border-radius:10px; opacity:0.8;">
<iframe id="frmIngreso" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="ingresos.php<?php echo $parametros;?>"></iframe>
</div>
<div id="divConsulta" style="border:solid; z-index: 3; position:absolute; top:20px; visibility:hidden; -moz-border-radius:10px; opacity:0.8;">
<iframe id="frmConsulta" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="consultas.php<?php echo $parametros;?>"></iframe>
</div>
<div id="divListado" style="border:solid; z-index: 4; position:absolute; top:20px; visibility:hidden; -moz-border-radius:10px; opacity:0.8;">
<iframe id="frmListado" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="listados.php<?php echo $parametros;?>"></iframe>
</div>
<div id="divMantencion" style="border:solid; z-index: 5; position:absolute; top:20px; visibility:hidden; -moz-border-radius:10px; opacity:0.8;">
<iframe id="frmMantencion" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="mantencion.php<?php echo $parametros;?>"></iframe>
</div>
<div id="divAutorizar" style="border:solid; z-index: 6; position:absolute; top:20px; visibility:hidden; -moz-border-radius:10px; opacity:0.8;">
<iframe id="frmAutorizar" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="autorizar.php<?php echo $parametros;?>"></iframe>
</div>
<div id="divInmobiliaria" style="border:solid; z-index: 7; position:absolute; top:20px; visibility:hidden; -moz-border-radius:10px; opacity:0.8;">
<iframe id="frmInmobiliaria" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="inmobiliaria.php<?php echo $parametros;?>"></iframe>
</div>
<!--
<div id="divInformativo" style="border:solid; z-index:1; position:absolute; width:300px; top:4% ; -moz-border-radius:10px; opacity:0.8;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="1" cellspacing="0">
							<tr >
								<td width="1%" class="menu_principal">
									<a href="#" title="Oculta el cuadro de informaci&oacute;n."
										onclick="javascript: Expander();"
									><img id="imgTitle"  border="0" align="middle" src="../images/menos.gif" /></a>
								</td>
								<td id="tdTitulo" align="left" class="menu_principal">&nbsp;:: Informaciones<input type="hidden" id="sw" value="menos" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="trId"><td ><iframe id="frmInformativo" frameborder="0" width="100%" height="250px"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>
-->
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr class="menu_principal">
					<td width="3%" align="center"></td>
					<td id="tdIngreso" align="center" width="1%" nowrap="nowrap">
						<a id="aIngreso" href="#" 
						 	onmouseover="javascript: 
								MuestraMenu('tdIngreso', 'aIngreso', true, 'divIngreso');
								window.status='Nuevo'; 
								return true;
							"
						>&nbsp;<b>Nuevo</b>&nbsp;</a>
					</td>
					<td width="2%">&nbsp;</td>
					<td id="tdConsulta" align="center" width="1%" nowrap="nowrap">
						<a id="aConsulta" href="#" 
							onmouseover="javascript: 
								MuestraMenu('tdConsulta', 'aConsulta', true, 'divConsulta');
								window.status='Consulta'; 
								return true;
							"
						>&nbsp;<b>Consulta</b>&nbsp;</a>
					</td>
					<td width="2%">&nbsp;</td><style>
a {text-decoration: none;}
a:hover {text-decoration: underline;}
</style>

					<td id="tdListado" align="center" width="1%" nowrap="nowrap">
						<a id="aListado" href="#" 
							onmouseover="javascript: 
								MuestraMenu('tdListado', 'aListado', true, 'divListado');
								window.status='Listado'; 
								return true;
							"
						>&nbsp;<b>Listado</b>&nbsp;</a>
					</td>
					<td width="2%">&nbsp;</td>
					<td id="tdMantencion" align="center" width="1%" nowrap="nowrap">
						<a id="aMantencion" href="#" 
							onmouseover="javascript: 
								MuestraMenu('tdMantencion', 'aMantencion', true, 'divMantencion');
								window.status='Mantenimiento'; 
								return true;
							"
						>&nbsp;<b>Mantenimiento</b>&nbsp;</a>
					</td>
					<td width="2%">&nbsp;</td>
					<td id="tdAutorizar" align="center" width="1%" nowrap="nowrap">
						<a id="aAutorizar" href="#" 
							onmouseover="javascript: 
								MuestraMenu('tdAutorizar', 'aAutorizar', true, 'divAutorizar');
								window.status='Autorizar'; 
								return true;
							"
						>&nbsp;<b>Autorizar</b>&nbsp;</a>
					</td>
					<td width="2%">&nbsp;</td>
					<td id="tdInmobiliaria" align="center" width="1%" nowrap="nowrap">
						<a id="aInmobiliaria" href="#" 
							onmouseover="javascript: 
								MuestraMenu('tdInmobiliaria', 'aInmobiliaria', true, 'divInmobiliaria');
								window.status='Inmobiliaria'; 
								return true;
							"
						>&nbsp;<b>Inmobiliaria</b>&nbsp;</a>
					</td>
					<td>&nbsp;</td>
					<td id="tdCClave" align="center" width="1%" nowrap="nowrap">
						<a id="aCClave" href="#" 
							onclick="javascript: CreaVentana('cambia_clave/<?php echo $parametros;?>', 'Cambiar Contraseña', 30, 110);"
							onmouseover="javascript: 
								MuestraMenu('tdCClave', 'aCClave', true);
								window.status='Cambiar Clave'; 
								return true;
							"
						>&nbsp;<b>Cambiar Clave</b>&nbsp;</a>
					</td>
					<td width="2%">&nbsp;</td><style>
a {text-decoration: none;}
a:hover {text-decoration: underline;}
</style> 
					<td id="tdSalir" align="center" width="1%" nowrap="nowrap">
						<a id="aSalir" href="#" 
							onclick="javascript: top.location.href='../logout.php<?php echo $parametros;?>';"
							onmouseover="javascript: 
								MuestraMenu('tdSalir', 'aSalir', true);
								window.status='Salir'; 
								return true;
							"
						>&nbsp;<b>Salir</b>&nbsp;</a>
					</td>
					<td width="3%">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="top"><iframe name="workspace" id="workspace" frameborder="0" width="100%" scrolling="auto" src="../workspace.php<?php echo $parametros;?>"></iframe></td></tr>
</table>
<iframe name="sesion" id="sesion" style="display:none" src="../sesion.php?usuario=<?php echo $usuario;?>"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
