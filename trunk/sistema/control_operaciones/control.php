<?php
include '../conexion.inc.php';
$usuario = $_GET["usuario"];
$ccosto = $_GET["ccosto"];
$accion = $_POST["hdnAccion"];

if($accion == 'G'){
	$ccosto = $_POST["hdnCCosto"];
	$obra = $_POST["cmbObra"];
	$uactual = $_POST["cmbUActual"];
	$cargo = $_POST["hdnCargo"];
	$rtecnica = $_POST["txtRTecnica"] != '' ? "'".formato_fecha($_POST["txtRTecnica"], false, true)."'" : 'NULL';
	$fllegada = $_POST["txtLlegada"] != '' ? "'".formato_fecha($_POST["txtLlegada"], false, true)."'" : 'NULL';
	$uarriendo = $_POST["cmbUArriendo"];
	$varriendo = $_POST["txtVArriendo"];
	$fbaja = $_POST["txtFBaja"] != '' ? "'".formato_fecha($_POST["txtFBaja"], false, true)."'" : 'NULL';
	$respbaja = $_POST["hdnRespBaja"];
	$tseguro = $_POST["txtTSeguro"];
	$compania = $_POST["txtCompania"];
	$vigencia = $_POST["txtVigencia"] != '' ? "'".formato_fecha($_POST["txtVigencia"], false, true)."'" : 'NULL';
	$observ1 = $_POST["txtObserv1"];
	$observ2 = $_POST["txtObserv2"];
	
	$stmt = mssql_query("EXEC Operaciones..sp_setHojaVida 0, '$ccosto', '$obra', '$uactual', '$cargo', $rtecnica, $fllegada, $uarriendo, $varriendo, $fbaja, '$respbaja', '$tseguro', '$compania', $vigencia, '$observ1', '$observ2'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
	mssql_free_result($stmt);
}else{
	$stmt = mssql_query("EXEC Operaciones..sp_getHojaVida 0, '$ccosto'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$equipo = $rst["strDescEquipo"];
		$obra = $rst["strObra"];
		$uactual = $rst["strUbicacion"];
		$cargo = $rst["strCargo"];
		$nombcargo = $rst["strNombCargo"];
		$rtecnica = $rst["dtmRTecnica"];
		$fllegada = $rst["dtmFLlegada"];
		$uarriendo = $rst["strUniArriendo"];
		$varriendo = $rst["dblValArriendo"];
		$fbaja = $rst["dtmFBaja"];
		$respbaja = $rst["strRutBaja"];
		$nombresp = $rst["strNombResp"];
		$tseguro = $rst["strTSeguro"];
		$compania = $rst["strCompania"];
		$vigencia = $rst["dtmVigente"];
		$observ1 = $rst["strObservacion1"];
		$observ2 = $rst["strObservacion2"];
	}
	mssql_free_result($stmt);
	if($equipo == ''){
		$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 1, '$ccosto'", $cnx);
		if($rst = mssql_fetch_array($stmt)) $equipo = $rst["strEquipo"];
		mssql_free_result($stmt);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Control de Operaciones</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $error;?>') == 0){
		parent.Deshabilita(false);
		parent.document.getElementById('frmResultados').src = parent.document.getElementById('frmResultados').src;
		parent.CierraDialogo('divControl', 'frmControl');
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var valor = ctrl.value, hdn = '', foco = '';
		Deshabilita(true);
		switch(ctrl.id){
			case 'txtCargo':
				hdn = 'hdnCargo';
				foco = 'imgLlegada';
				break;
			case 'txtRespBaja':
				hdn = 'hdnRespBaja';
				foco = 'txtTSeguro';
				break;
		}
		AbreDialogo('divPersonal', 'frmPersonal', 'buscar_personal.php?texto='+valor+'&ctrl='+ctrl.id+'&hdn='+hdn+'&foco='+foco);
	}
	return true;
}

function Deshabilita(sw){
	document.getElementById('cmbObra').disabled = sw;
	document.getElementById('cmbUActual').disabled = sw;
	document.getElementById('txtCargo').disabled = sw;
	document.getElementById('imgRTecnica').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('imgLlegada').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('cmbUArriendo').disabled = sw;
	document.getElementById('txtVArriendo').disabled = sw;
	document.getElementById('imgFBaja').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('txtRespBaja').disabled = sw;
	document.getElementById('txtTSeguro').disabled = sw;
	document.getElementById('txtCompania').disabled = sw;
	document.getElementById('imgVigencia').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('txtObserv1').disabled = sw;
	document.getElementById('txtObserv2').disabled = sw;
	document.getElementById('btnGraba').disabled = sw;
	document.getElementById('btnCerrar').disabled = sw;
}

function getPersonal(ctrl, hdn){
	document.getElementById('transaccion').src='transaccion.php?valor='+ctrl.value+'&hdn='+hdn+'&ctrl='+ctrl.id;
}

function setGuardar(){
	if(document.getElementById('cmbObra').value != ''){
		document.getElementById('btnGraba').disabled = true;
		document.getElementById('frm').submit();
	 }else
	 	alert('Debe seleccionar al menos la obra');
}
-->
</script>
<body onload="javascript: Load();">
<div id="divPersonal" style="z-index:1; position:absolute; width:60%; left: 20%; top: 1%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de dialogo." 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divPersonal', 'frmPersonal');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" align="middle" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Cargo/Responsable</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmPersonal" id="frmPersonal" frameborder="1" style="border:thin" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divCalendario" style="z-index:1; position:absolute; width:20%; left: 40%; top: 8%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra calendario." 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra administrador de equipos.'; return true"
									><img border="0" align="middle" src="../images/close.png"></a>
								</td>
								<td align="center">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" target="_self" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="6%">&nbsp;C.Costo</td>
		<td width="1%">:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="7%"><?php echo $ccosto;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Equipo</td>
					<td width="1%">:</td>
					<td width="21%"><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="&nbsp;<?php echo $equipo;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Obra</td>
					<td width="1%">:</td>
					<td width="23%">
						<select name="cmbObra" id="cmbObra" class="sel-plano" style="width:100%">
							<option value="">&nbsp;</option>
						<?php
						$stmt = mssql_query("EXEC General..sp_getCargos 6, NULL, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.($rst["strCodigo"] == $obra ? 'selected' : '').'>'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;U.Actual</td>
					<td width="1%">:</td>
					<td width="23%">
						<select name="cmbUActual" id="cmbUActual" class="sel-plano" style="width:100%">
							<option value="">&nbsp;</option>
						<?php
						$stmt = mssql_query("EXEC General..sp_getCargos 6, NULL, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.($rst["strCodigo"] == $uactual ? 'selected' : '').'>'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;Cargo</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="15%">
						<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:100%" value="<?php echo $nombcargo;?>"
							onblur="javascript: 
								CambiaColor(this.id, false);
								getPersonal(this, 'hdnCargo');
							"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">R.T&eacute;cnica</td>
					<td width="1%">:</td>
					<td width="13%">
						<input name="txtRTecnica" id="txtRTecnica" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo $rtecnica;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="3%" align="center">
						<a href="#" title="Abre calendario."
							onblur="javascript: CambiaImagen('imgRTecnica', false);"
							onfocus="javascript: CambiaImagen('imgRTecnica', true);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtRTecnica&fecha='+document.getElementById('txtRTecnica').value);
							"
						><img id="imgRTecnica" border="0" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">Llegada</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtLlegada" id="txtLlegada" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo $fllegada;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="3%" align="center">
						<a href="#" title="Abre calendario."
							onblur="javascript: CambiaImagen('imgLlegada', false);"
							onfocus="javascript: CambiaImagen('imgLlegada', true);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtLlegada&fecha='+document.getElementById('txtLlegada').value);
							"
						><img id="imgLlegada" border="0" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;U.Arriendo</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="cmbUArriendo" id="cmbUArriendo" class="sel-plano" style="width:100%">
							<option value="0" <?php echo $uarriendo == 0 ? 'selected' : '';?>></option>
							<option value="1" <?php echo $uarriendo == 1 ? 'selected' : '';?>>Mes</option>
							<option value="2" <?php echo $uarriendo == 2 ? 'selected' : '';?>>D&iacute;a</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;V.Arriendo</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtVArriendo" id="txtVArriendo" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($varriendo, 0, '', '');?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onchange="javascript: if(this.value == '') this.value = 0;" 
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;F.Baja</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="10%">
						<input name="txtFBaja" id="txtFBaja" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo $fbaja;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="3%" align="center">
						<a href="#" title="Abre calendario."
							onblur="javascript: CambiaImagen('imgFBaja', false);"
							onfocus="javascript: CambiaImagen('imgFBaja', true);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFBaja&fecha='+document.getElementById('txtFBaja').value);
							"
						><img id="imgFBaja" border="0" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">Resp.Baja</td>
					<td width="1%">:</td>
					<td width="23%">
						<input name="txtRespBaja" id="txtRespBaja" class="txt-plano" style="width:100%" value="<?php echo $nombresp;?>" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								getPersonal(this, 'hdnRespBaja');
							"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;T.Seguro</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtTSeguro" id="txtTSeguro" class="txt-plano" style="width:100%" maxlength="200" value="<?php echo $tseguro;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Compa&ntilde;ia</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtCompania" id="txtCompania" class="txt-plano" style="width:100%" maxlength="200" value="<?php echo $compania;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Vigencia</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtVigencia" id="txtVigencia" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo $vigencia;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="4%" align="center">
						<a href="#" title="Abre calendario."
							onblur="javascript: CambiaImagen('imgVigencia', false);"
							onfocus="javascript: CambiaImagen('imgVigencia', true);"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtVigencia&fecha='+document.getElementById('txtVigencia').value);
							"
						><img id="imgVigencia" border="0" src="../images/aba.gif" /></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="11%" valign="top">&nbsp;Observaciones</td>
		<td valign="top">:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td >
						<textarea name="txtObserv1" id="txtObserv1" class="txt-plano" style="width:100%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="return CuentaCaracter(event, this, 1000);" 
						><?php echo $observ1;?></textarea>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
						<textarea name="txtObserv2" id="txtObserv2" class="txt-plano" style="width:99%"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="return CuentaCaracter(event, this, 1000);" 
						><?php echo $observ2;?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="3" style="height:35px">&nbsp;</td></tr>
	<tr><td colspan="3"><hr /></td></tr>
	<tr>
		<td align="right" colspan="3">
			<input type="hidden" name="hdnAccion" id="hdnAccion" value="G" />
			<input type="hidden" name="hdnCCosto" id="hdnCCosto" value="<?php echo $ccosto;?>" />
			<input type="hidden" name="hdnCargo" id="hdnCargo" value="<?php echo $cargo;?>" />
			<input type="hidden" name="hdnRespBaja" id="hdnRespBaja" value="<?php echo $respbaja;?>" />
			
			<input type="button" name="btnGraba" id="btnGraba" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: setGuardar();"
			/>
			<input type="button" name="btnCerrar" id="btnCerrar" class="boton" style="width:90px" value="Cerrar" 
				onclick="javascript:
					parent.Deshabilita(false);
					parent.CierraDialogo('divControl', 'frmControl');
				"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>