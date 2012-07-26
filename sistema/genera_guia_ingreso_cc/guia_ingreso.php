<?php
include '../autentica.php';
include '../conexion.inc.php';

$numint = $_GET["numint"];
$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 0, $numint", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numcc = $rst["dblNum"];
	$cargo = $rst["strCargo"];
	$nombresp = $rst["strNombre"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Ingreso por Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 110);
	document.getElementById('frmDetalle').src = 'detalle_guia_ingreso.php?numero=<?php echo $numint;?>'
}

function Envia(accion){
	if(accion == 'A'){
		document.getElementById('frm').target = '';
		document.getElementById('frm').action = 'index.php';
		document.getElementById('frm').submit();
	}else if(accion == 'F'){
		Deshabilita(true);
		AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>');
	}
}

function Deshabilita(sw){
	document.getElementById('observacion').disabled = sw
	document.getElementById('btnAnterior').disabled = sw
	document.getElementById('btnFin').disabled = sw
}
//-->
</script>
<body onload="javascript: Load();">
<div id="divContrasena" style=" z-index: 1; position:absolute; top:20%; left:35%; width:30%; height:110px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											CierraDialogo('divContrasena', 'frmContrasena');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="font-size:12px"><b>Contrase&ntilde;a</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmContrasena" id="frmContrasena" frameborder="0" scrolling="no" width="100%" height="90px" src="../blank.html"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<td width="9%"><b>&nbsp;Fecha</b></td>
					<td width="1%">:</td>
					<td width="90%" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" align="left"><input name="fecha" id="fecha" class="txt-plano" style="width:99%; text-align:center;" readonly="true" value="<?php echo date('d/m/Y');?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="10%"><b>&nbsp;T.Documento</b></td>
								<td width="1%">:</td>
								<td width="17%"><input class="txt-plano" style="width:100%; text-align:left" readonly="true" value=" Caja Chica" /></td>
								<td width="1%">&nbsp;</td>
								<td width="3%"><b>&nbsp;N&deg;</b></td>
								<td width="1%">:</td>
								<td width="10%"><input name="ndocumento" id="ndocumento" class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo $numcc;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;Responsable</b></td>
								<td width="1%">:</td>
								<td align="left"><input class="txt-plano" style="width:100%; text-align:left" readonly="true" value=" <?php echo $nombresp != '' ? $nombresp : $cargo;?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>&nbsp;Observaci&oacute;n</b></td>
					<td>:</td>
					<td>
						<input name="observacion" id="observacion" class="txt-plano" style="width:100%"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onblur="javascript: CambiaColor(this.id, false);"
						>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">&nbsp;C&oacute;digo</th>
					<th width="65%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" ></iframe></td></tr>
	<tr><td height="5px" colspan="2"><hr /></td></tr>
	<tr>
		<td width="50%">
			<input type="button" name="btnAnterior" id="btnAnterior" class="boton" style="width:90px" value="<< Anterior" 
				onClick="javascript: Envia('A');"
			>
		</td>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>">
			<input type="hidden" name="numint" id="numint" value="<?php echo $numint;?>">
			<input type="button" name="btnFin" id="btnFin" class="boton" value="Finalizar" style="width:90px" 
				onClick="javascript: 
					this.disabled=true;
					Envia('F');
				"
			>
		</td>
	</tr>
</table>
</form>
<iframe name="valido" id="valido" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>