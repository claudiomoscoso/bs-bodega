<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
$numero = $_GET["numero"];
$stmt = mssql_query("EXEC Bodega..sp_getValeConsumo 2, $numero, '$bodega'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$interno = $rst["dblInterno"];
	$fecha = $rst["dtmFecha"];
	$descbodega = $rst["strDescBodega"];
	$descresponsable = $rst["strDescResponsable"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vales de Consumo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
	document.getElementById('btnGuardar').disabled = false;
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 100);
	document.getElementById('detalle').src = "detalle_vale_consumo.php?usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&interno=<?php echo $interno;?>";
}

function Graba(){
	document.getElementById('frm').setAttribute('target', 'transaccion');
	document.getElementById('frm').setAttribute('action', 'transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&interno=<?php echo $interno;?>');
	document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load();">
<?php
if($interno != ''){?>
<form name="frm" id="frm" method="post" target="transaccion">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="5%" align="left">&nbsp;N&deg; VC</td>
					<td width="1%">:</td>
					<td width="43%">&nbsp;<?php echo $numero;?></td>
					<td width="1%"></td>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td align="left">&nbsp;<?php echo $fecha;?></td>
				</tr>
				<tr>
					<td>&nbsp;Bodega</td>
					<td>:</td>
					<td><input name="txtBodega" id="txtBodega" class="txt-sborde" style="width:100%;background-color:#ececec" readonly="true" value="<?php echo $descbodega;?>" /></td>
					<td></td>
					<td>&nbsp;Responsable</td>
					<td>:</td>
					<td><input name="txtResponsable" id="txtResponsable" class="txt-sborde" style="width:100%;background-color:#ececec" readonly="true" value="<?php echo $descresponsable;?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="55%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%">C.Costo</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td align="center"><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" ></iframe></td></tr>
	<tr><td ><hr /></td></tr>
	<tr>
		<td align="right">
			<table border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td align="right">												
						<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" disabled="disabled" value="Guardar" 
							onclick="javascript: Graba();" 
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
<?php
}else{
	echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
	echo '</table>';
}
mssql_close($cnx);
?>
</body>
</html>