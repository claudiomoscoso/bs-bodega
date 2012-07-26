<?php
include '../autentica.php';
include '../conexion.inc.php';

$numSM=$_POST["numSM"]!='' ? $_POST["numSM"] : $_GET["numSM"];
$bodSM=$_POST["bodSM"]!='' ? $_POST["bodSM"] : $_GET["bodSM"];

$stmt = mssql_query("EXEC sp_getSolicitudMaterial 0, $numSM", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numusuSM = $rst["dblNumUsu"];
	$detalle = $rst["strDetalle"];
	$cargo = trim($rst["strCargo"]);
	$fecha = $rst["dtmSolicitud"];
	$observacion = $rst["strObservacion"];
	$nguia = $rst["dblGuia"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra Autom&aacute;tica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 138);
	document.getElementById('frmDetalle').src="detalle_solicitud_material.php?usuario=<?php echo $usuario;?>&numSM=<?php echo $numSM;?>&nguia=<?php echo $nguia;?>";
}

function Siguiente(){
	var totfil = frmDetalle.document.getElementById('totfil').value;
	var sw = 0;
	for(i = 1; i <= totfil; i++){
		if(frmDetalle.document.getElementById('chk'+i).checked){
			sw = 1;
			break;
		}
	}
	
	if(sw == 1)
		document.getElementById('frm').submit();
	else
		alert('Debe seleccionar al menos un ítem del detalle.');
}

function setSelecciona(ctrl){
	var totfil = frmDetalle.document.getElementById('totfil').value;
	if(!ctrl.checked) 
		frmDetalle.document.getElementById('transaccion').src = 'transaccion.php?modulo=1&usuario=<?php echo $usuario;?>';
	else
		frmDetalle.document.getElementById('transaccion').src = 'transaccion.php?modulo=3&usuario=<?php echo $usuario;?>';
		
	for(i = 1; i <= totfil; i++){
		frmDetalle.document.getElementById('chk' + i).checked = ctrl.checked;
		frmDetalle.document.getElementById('cant' + i).disabled = !ctrl.checked;		
		if(!ctrl.checked) 
			frmDetalle.document.getElementById('cant' + i).value = 0; 
		else
			frmDetalle.document.getElementById('cant' + i).value = frmDetalle.document.getElementById('hdnCantidad' + i).value;
	}
}
//-->
</script>
<body onload="javascript: Load();">
<center>
<form name="frm" id="frm" method="post" action="orden_compra.php">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="4%" align="left"><b>&nbsp;N&uacute;mero</b></td>
					<td width="1%">:</td>
					<td width="20%" align="left">&nbsp;<?php echo $numusuSM;?></td>
					<td width="4%" align="left"><b>&nbsp;Cargo</b></td>
					<td width="1%">:</td>
					<td align="left">&nbsp;<?php echo $detalle;?></td>
				</tr>
				<tr>
					<td align="left" valign="top"><b>&nbsp;Fecha</b></td>
					<td valign="top">:</td>
					<td align="left" valign="top">&nbsp;<?php echo $fecha;?></td>
					<td valign="top"><b>&nbsp;Observaci&oacute;n</b></td>
					<td valign="top">:</td>
					<td align="left">
						<textarea name="strNota" id="strNota" class="txt-plano" style="width:99%" readonly="readonly"><?php echo $observacion;?></textarea>
					</td>
				</tr>
	
			</table>
		</td>
	</tr>
	<tr><td colspan="2"></td></tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr >
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="53%" align="left">&nbsp;Detalle</th>
					<th width="10%">Unidad</th>
					<th width="10%">Cantidad</th>
					<th width="2%" align="center">
						<input type="checkbox" name="chkAll" id="chkAll" 
							onclick="javascript: setSelecciona(this);"
						/>
					</th>
					<th width="10%">Comprar...</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" ></iframe></td></tr>
	<tr><td colspan="2" style="height:5px"><hr></td></tr>
	<tr>
		<td align="left" width="50%">
			<input type="button" name="btnAnt" id="btnAnt" class="boton" style="width:90px" value="&lt;&lt; Anterior" 
				onclick="javascript: self.location.href='index.php<?php echo $parametros;?>';"
			>
			
		</td>
		<td align="right">
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>">
			
			<input type="hidden" name="numSM" id="numSM" value="<?php echo $numSM;?>">
			<input type="hidden" name="numusuSM" id="numusuSM" value="<?php echo $numusuSM;?>">
			<input type="hidden" name="bodSM" id="bodSM" value="<?php echo $bodSM;?>">
			<input type="hidden" name="cargo" id="cargo" value="<?php echo $cargo;?>">
			<input type="hidden" name="detalle" id="detalle" />

			<input type="button" name="btnSgte" id="btnSgte" class="boton" style="width:90px" disabled="disabled" value="Siguiente &gt;&gt;" 
				onClick="javascript: Siguiente();"
			>
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>
<?php
mssql_close($cnx);
?>