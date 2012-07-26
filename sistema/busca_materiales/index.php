<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('divResultado').style.height = (window.innerHeight - 53) + 'px';
}

function Buscar(){
	var bodega = document.getElementById('cmbBodega').value;
	var texto = document.getElementById('txtTexto').value;
	if(texto == '')
		alert('Debe ingresar parte de la descripcion a buscar.');
	else{
		Deshabilita(true);
		var respuesta = document.getElementById('divResultado');
		respuesta.innerHTML = '<center><img src="../images/cargando2.gif"></center>';
		var ajax = new XMLHttpRequest();	
		
		ajax.open('GET', 'resultado.php?bodega=' + bodega + '&texto=' + texto, true);
		ajax.onreadystatechange = function(){
			if(ajax.readyState == 4){ 
				respuesta.innerHTML = ajax.responseText;
				Deshabilita(false);
			}
		}
		ajax.send(null);
	}
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtTexto').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4%">&nbsp;Bodega</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 6", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
						}
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;Material</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<input name="txtTexto" id="txtTexto" class="txt-plano" style="width:99%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:80px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="40%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="25%" align="left">&nbsp;Familia</th>
					<th width="10%" align="right">Entrada&nbsp;</th>
					<th width="10%" align="right">Salida&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><div id="divResultado" style="position:relative; width:100%; height:100px; overflow:scroll"></div></td></tr>
</table>
</body>
</html>
