<?php
include '../autentica.php';
include '../conexion.inc.php';

$bodGI = $_GET["bodGI"];
$numGI = $_GET["numGI"];

$stmt = mssql_query("EXEC Bodega..sp_getGuiaIngreso 'EGI', $numGI, '$bodGI'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numero = $rst["dblNumero"];
	$fecha = $rst["dtmFecha"];
	$tdocumento = $rst["intTipoDoc"] != '' ? $rst["intTipoDoc"] : 0;
	$referencia = $rst["strReferencia"];
	$numoc = $rst["dblUltima"];
	$ocompra = $rst["dblOrdenC"];
	$nombprov = $rst["strNombre"];
	$direccion = $rst["strDireccion"];
	$comuna = $rst["Comuna"];
	$telefono = $rst["strTelefono"];
	$fax = $rst["strFax"];
	$atencion = $rst["strContacto"];
	
	mssql_query("EXEC Bodega..sp_getTMPDetalleGuiaIngreso 2, '$usuario', $numero", $cnx);
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita Gu&iacute;a de Ingreso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	document.getElementById('valido').src = 'transaccion.php?modulo=0&bodega=<?php echo $bodGI;?>&numoc=' + ctrl.value;
}

function Load(){
	document.getElementById('detalle').setAttribute('height', parent.document.getElementById('frmGuiaIngreso').height - 157);
	document.getElementById('detalle').src = 'detalle_guia_ingreso.php?bodega=<?php echo $bodGI;?>&usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>';
	
	if('<?php echo $perfil;?>' == 'adquisiciones' || '<?php echo $perfil;?>' == 'informatica')
		document.getElementById('btnAnular').disabled = false;
	else
		document.getElementById('btnAnular').disabled = true;
}

function Accion(accion){
	document.getElementById('accion').value = accion;
	if(accion == 'A'){
		if(confirm('¿Está seguro que desea anular esta guía de ingreso?')){
			AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>&numgi=' + document.getElementById('GIngreso').value + '&numoc=' + document.getElementById('hdnOCompra').value);
			Deshabilita(true);
		}
	}else if(accion == 'G'){ //GUARDAR
		//document.getElementById('frm').setAttribute('target', 'valido');
		document.getElementById('frm').submit();
		//Deshabilita(true);
	}
}

function Deshabilita(sw){
	parent.document.getElementById('cmbBodega').disabled = sw;
	parent.document.getElementById('txtNumGI').disabled = sw;
	parent.document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('cmbTDocumento').disabled = sw;
	document.getElementById('txtNDocumento').disabled = sw;
	document.getElementById('txtOCompra').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
	document.getElementById('btnAnular').disabled = sw;
	var totfil = detalle.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		detalle.document.getElementById('txtCantidad' + i).disabled = sw;
		detalle.document.getElementById('txtValor' + i).disabled = sw;
	}
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

<?php
if($numero != ''){?>
<form name="frm" id="frm" method="post" action="graba.php">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<td width="9%"><b>&nbsp;Fecha</b></td>
					<td width="1%">:</td>
					<td width="90%" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;T.Documento</b></td>
								<td width="1%">:</td>
								<td width="15%">
									<select name="cmbTDocumento" id="cmbTDocumento" class="sel-plano" style="width:100%">
										<option value="0" <?php echo $tdocumento==0 ? 'selected' : '';?>>Gu&iacute;a de Despacho</option>
										<option value="1" <?php echo $tdocumento==1 ? 'selected' : '';?>>Factura</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="3%"><b>&nbsp;N&deg;</b></td>
								<td width="1%">:</td>
								<td width="10%">
									<input name="txtNDocumento" id="txtNDocumento" class="txt-plano" style="width:99%; text-align:center" value="<?php echo trim($referencia);?>" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return ValNumeros(event, this.id, false);" 
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="12%"><b>&nbsp;N&deg; Orden Compra</b></td>
								<td width="1%">:</td>
								<td width="10%" align="right">
									<input name="txtOCompra" id="txtOCompra" class="txt-plano" style="width:99%; text-align:center" value="<?php echo $numoc;?>" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onKeyPress="javascript: return ValNumeros(event, this.id, false);" 
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="8%"><b>&nbsp;Proveedor</b></td>
					<td>:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:98%;" readonly="true" value="<?php echo $nombprov;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%"><b>&nbsp;Direcci&oacute;n</b></td>
								<td width="1%">:</td>
								<td align="left"><input name="txtDireccion" id="txtDireccion" class="txt-plano" style="width:98%;" readonly="true" value="<?php echo $direccion;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;Comuna</b></td>
								<td width="1%">:</td>
								<td width="15%" align="right"><input name="txtComuna" id="txtComuna" class="txt-plano" style="width:98%;" readonly="true" value="<?php echo $comuna;?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>			
					<td ><b>&nbsp;Tel&eacute;fono</b></td>
					<td >:</td>
					<td >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="20%" align="left"><input name="txtTelefono" id="txtTelefono" class="txt-plano" style="width:98%;" readonly="true" value="<?php echo $telefono;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="4%"><b>&nbsp;Fax</b></td>
								<td width="1%">:</td>
								<td width="20%" align="left"><input name="txtFax" id="txtFax" class="txt-plano" style="width:98%;" readonly="true" value="<?php echo $fax;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;Atenci&oacute;n</b></td>
								<td width="1%">:</td>
								<td width="40%" align="right"><input name="txtAtencion" id="txtAtencion" class="txt-plano" style="width:98%;" readonly="true" value="<?php echo $atencion;?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>&nbsp;Observaci&oacute;n</b></td>
					<td>:</td>
					<td>
						<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);" 
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
					<th width="55%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Valor&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr><td colspan="7"><iframe name="detalle" id="detalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="5px" colspan="2"><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>">
			
			<input type="hidden" name="GIngreso" id="GIngreso" value="<?php echo $numero;?>">
			<input type="hidden" name="hdnBodega" id="hdnBodega" value="<?php echo $bodGI;?>">
			<input type="hidden"  name="hdnOCompra" id="hdnOCompra" value="<?php echo $ocompra;?>" >
			<input type="hidden" name="accion" id="accion" >
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" value="Guardar" style="width:90px" 
				onClick="javascript: Accion('G');"
			>
			<input type="button" name="btnAnular" id="btnAnular" class="boton" value="Anular" style="width:90px" 
				onClick="javascript: Accion('A');"
			>
		</td>
	</tr>
</table>
</form>
<?php
}else{
	echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>
	</table>';
}?>
<iframe name="valido" id="valido" frameborder="0" width="100%" height="100px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>