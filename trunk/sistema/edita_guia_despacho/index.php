<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita Gu&iacute;a de Devoluci&oacute;n</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('documento').setAttribute('height', window.innerHeight - 35);
}

function Buscar(){
	if(document.getElementById('txtDespacho').value == '')
		alert('Debe ingresar el nñumero de la guía de devolución.');
	else{
		document.getElementById('frm').setAttribute('target', 'documento');
		document.getElementById('frm').setAttribute('action', 'guia_despacho.php?usuario=<?php echo $usuario;?>');
		document.getElementById('frm').submit();
		Deshabilita(true);
	}
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtDespacho').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<form name="frm" id="frm" method="post">
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4%">&nbsp;Bodega</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strBodega"]).'" '.(trim($rst["strBodega"]) == $bodega ? 'selected="selected"' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;N&deg;Despacho</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtDespacho" id="txtDespacho" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
		</form>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="documento" id="documento" width="100%" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>