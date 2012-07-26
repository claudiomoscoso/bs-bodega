<?php
include '../conexion.inc.php';

$existe = 0;
$usuario = $_GET["usuario"];
$numero = $_POST["txtTermino"];

$stmt = mssql_query("EXEC Bodega..sp_getTerminoBodega 0, $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$existe = 1;
	$fecha = $rst["dtmFch"];
	$descbodega = $rst["strDescBodega"];

	mssql_query("EXEC Bodega..sp_getTMPTerminoBodega 2, '$usuario', NULL, $numero", $cnx);
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita Gu&iacute;a de Devoluci&oacute;n</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
function Load(){
	parent.Deshabilita(false);
	if(document.getElementById('detalle')){
		document.getElementById('detalle').setAttribute('height', window.innerHeight - 80);
		document.getElementById('detalle').src = 'detalle_termino_bodega.php?usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>';
	}
}

function Deshabilita(sw){
	document.getElementById('btnGuardar').disabled = sw;
	if(detalle.document.getElementById('totfil')){
		var totfil = detalle.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			detalle.document.getElementById('txtCantidad' + i).disabled = sw;
		}
	}
}

function Guardar(){
	document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="position:absolute; top:20px; left:17%; width:20%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra calendario"
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="transaccion.php?modulo=1&usuario=<?php echo $usuario;?>">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
if($existe == 1){?>
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="6%" ><b>&nbsp;N&deg;T&eacute;rmino</b></td>
					<td width="1%" align="center">:</td>
					<td width="93%">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%"><?php echo $numero;?></td>
								<td width="1%">&nbsp;</td>
								<td width="4%"><b>&nbsp;Fecha</b></td>
								<td width="1%" align="center">:</td>
								<td width="10%"><?php echo $fecha;?></td>
								<td width="1%">&nbsp;</td>
								<td width="6%"><b>&nbsp;Bodega</b></td>
								<td width="1%" align="center">:</td>
								<td width="63%">&nbsp;<?php echo $descbodega;?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="65%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Stock</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" src="../cargando.php"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="hdnBodega" id="hdnBodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="hdnTermino" id="hdnTermino" value="<?php echo $numero;?>" />
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:80px" value="Guardar" 
				onclick="javascript: 
					this.disabled = true;
					Guardar();
				" 
			/>
		</td>
	</tr>
<?php
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}?>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>