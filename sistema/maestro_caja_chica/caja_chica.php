<?php
include '../conexion.inc.php';
$modulo=$_GET["modulo"];
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$perfil=$_GET["perfil"];
$nivel=$_GET["nivel"];

$numero=$_GET["numero"];

if($modulo==0){
	$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 0, $numero", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$numcc=$rst["dblNum"];
		$bodcc=$rst["strBodega"];
		$fecha=$rst["dtmFch"];
		$rut=$rst["strCargo"];
		$nombre=$rst["strNombre"];
		$nota=$rst["strNota"];
		$estado=$rst["dblEstado"];
		$creadapor=$rst["strCreadaPor"];
		$factor=$rst["dblFactor"];
	}
	mssql_free_result($stmt);

	mssql_query("EXEC Bodega..sp_setTMPDetalleCajaChica 2, '$bodega', '$usuario', NULL, NULL, NULL, $numero", $cnx);
	
	if($factor==''){
		$stmt = mssql_query("SELECT dblFactor + 1 AS dblFactor FROM Impuesto WHERE id = 1", $cnx);
		if($rst = mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
		mssql_free_result($stmt);
	}
	
	$stmt = mssql_query("EXEC General..sp_getUsuarios 2, NULL, NULL, NULL, '$usuario'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $ffijo = $rst["dblFondoFijo"];
	mssql_free_result($stmt);
}else if($modulo==1){
	$nota=$_GET["nota"];

	mssql_query("EXEC Bodega..sp_setAgregaCajaChica 1, '$usuario', '$bodega', NULL, '$usuario', '$nota', NULL, $numero", $cnx);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Maestro Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
// EVENTOS
function Load(){
	if(parseInt('<?php echo $modulo;?>') == 0){
		document.getElementById('txtNota').focus();
		document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 110);
		document.getElementById('frmDetalle').src = "detalle.php?modulo=0&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>&factor=<?php echo $factor;?>&rut=<?php echo $rut;?>&estado=<?php echo $estado;?>";
	}else if(parseInt('<?php echo $modulo;?>') == 1){
		parent.CierraDialogo('divCajaChica', 'frmCajaChica');
		parent.Deshabilitar(false)
		parent.document.getElementById('frmResultado').src = parent.document.getElementById('frmResultado').src;
	}
	setBloqueaCampos('<?php echo $estado;?>');
}

// FUNCIONES SET
function Deshabilita(sw){
	var totfil = frmDetalle.document.getElementById('hdnTotFil').value;
	document.getElementById('txtNota').disabled=sw;
	for(i=1; i<=totfil; i++){
		frmDetalle.document.getElementById('txtCantidad' + i).disabled=sw;
		frmDetalle.document.getElementById('txtPrecio' + i).disabled=sw;
		frmDetalle.document.getElementById('txtTotal' + i).disabled=sw;
		if(frmDetalle.document.getElementById('imgBorrar' + i)) frmDetalle.document.getElementById('imgBorrar' + i).style.visibility=(sw ? 'hidden' : 'visible');
		if(frmDetalle.document.getElementById('cmbRechazo' + i)) frmDetalle.document.getElementById('cmbRechazo' + i).disabled=sw;
	}
	if(document.getElementById('btnGuardar')) document.getElementById('btnGuardar').disabled=sw;
	if(document.getElementById('btnRecepcionar')) document.getElementById('btnRecepcionar').disabled=sw;
	if(document.getElementById('btnRechazar')) document.getElementById('btnRechazar').disabled=sw;
	document.getElementById('btnCerrar').disabled=sw;
}

function setBloqueaCampos(estado){
	switch(parseInt(estado)){
		case 0:
			document.getElementById('txtNota').readOnly=false;
			break;
		case 1:
		case 2:
		case 4:
		case 5:
			document.getElementById('txtNota').readOnly=true;
			frmDetalle.setBloqueaDetalle();
			break;
	}
}

function setGuardar(modulo){
	if(frmDetalle.document.getElementById('hdnTotFil').value == '')
		alert('Debe ingresar al menos un ítem en el detalle.');
	else{
		Deshabilita(true);
		if(modulo == 0)
			self.location.href='<?php echo $_SERVER['PHP_SELF'];?>?modulo=1&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>&nota='+document.getElementById('txtNota').value;
		else if(modulo == 1 || modulo == 2)
			AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?modulo='+modulo+'&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>&nota='+document.getElementById('txtNota').value);
	}
}
-->
</script>
<body topmargin="0" onload="javascript: Load()">
<div id="divContrasena" style=" z-index: 1; position:absolute; top:20%; left:35%; width:30%; visibility:hidden">
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

<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<td width="3%"><b>&nbsp;Fecha</b></td>
				<td width="1%"><b>:</b></td>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%">&nbsp;<?php echo $fecha;?></td>
							<td width="1%">&nbsp;</td>
							<td width="9%"><b>&nbsp;C.Chica N&deg;</b></td>
							<td width="1%"><b>:</b></td>
							<td width="10%">&nbsp;<?php echo $numcc;?></td>
							<td width="1%">&nbsp;</td>
							<td width="10%"><b>&nbsp;Responsable</b></td>
							<td width="1%"><b>:</b></td>
							<td width="25%"><input name="txtNombre" id="txtNombre" class="txt-sborde" style="width:100%" readonly="true" value="&nbsp;<?php echo $nombre;?>" /></td>
							<td width="1%">&nbsp;</td>
							<td width="5%"><b>&nbsp;Nota</b></td>
							<td width="1%"><b>:</b></td>
							<td width="26%">
								<input input="text" name="txtNota" id="txtNota" class="txt-plano" style="width:99%" maxlength="1000" value="&nbsp;<?php echo $nota;?>" 
									onblur="javascript: CambiaColor(this.id, false);"
									onfocus="javascript: CambiaColor(this.id, true);"
								/>
							</td>
						</tr>
					</table>
				</td>
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
					<th width="<?php echo $estado==1 || $estado==3 ? '15%' : '24%';?>" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%" align="left">&nbsp;Documento</th>
					<th width="10%" >N&deg; Doc.</th>
					<th width="11%" >N&deg; O.Compra</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Precio Neto&nbsp;</th>
					<th width="10%" align="right">Total <font color="#FF0000">(*)</font>&nbsp;</th>
				<?php
				if($estado==1 || $estado==3){?>
					<th width="16%" align="center">&nbsp;Rechazar</th>
				<?php
				}else{?>
					<th width="7%">&nbsp;</th>
				<?php
				}?>
				</tr>
			</table>		
		</td>
	</tr>
	<tr><td><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" scrolling="yes" src="../cargando.php"></iframe></td></tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="80%" align="left" style="color:#FF0000"><b>&nbsp;(*) El Total incluye I.V.A. si corresponde a una factura.</b></td>
					<td width="5%" align="right">Total</td>
					<td width="1%">:</td>
					<td width="10%" align="left"><input name="txtTotGnral" id="txtTotGnral" class="txt-plano" style="width:99%; text-align:right" readonly="true" /></td>
					<td width="2%">&nbsp;</td>
				</tr>
			</table>		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="modulo" id="modulo" value="1" />
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
			<input type="hidden" name="factor" id="factor" value="<?php echo $factor;?>" />
		<?php
		if($estado == 0){
			if($usuario == $creadapor && $ffijo == 0)
				$sw = 0;
			elseif(trim($usuario) == trim($rut) && $ffijo > 0)
				$sw = 1;?>
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar<?php echo $sw==1 ? ' y V&deg;B&deg;' : '';?>" 
				onclick="javascript: setGuardar('<?php echo $sw;?>');"
			/>
		<?php
		}elseif($estado == 1 && (($perfil=='recepcion' && $nivel==2) || $perfil=='informatica')){?>
			<input type="button" name="btnRecepcionar" id="btnRecepcionar" class="boton" style="width:90px" value="Recepcionar" 
				onclick="javascript: setGuardar('2');"
			/>
		<?php
		}?>
			<input type="button" name="btnCerrar" id="btnCerrar" class="boton" style="width:90px" value="Cerrar" 
				onclick="javascript: 
					parent.CierraDialogo('divCajaChica', 'frmCajaChica');
					parent.Deshabilitar(false);
				"
			/>		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>