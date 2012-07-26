<?php
include '../autentica.php';
include '../conexion.inc.php';

$accion = $_POST["accion"];
if($accion == 'G'){
	$ccosto = $_POST["txtCCosto"];
	$obra = $_POST["hdnObra"];
	$fecha = formato_fecha($_POST["txtFecha"], false, true);
	$estado = $_POST["cmbEstado"];
	$ulectura = $_POST["txtUltLectura"] != '' ? $_POST["txtUltLectura"] : 'NULL';
	$odometro = $_POST["txtKmsHrs"];
	$tcombustible = $_POST["cmbTCombustible"] != 'none' ? $_POST["cmbTCombustible"] : 'NULL';
	$combustible = $_POST["txtLitros"];
	$operador = $_POST["hdnChofer"];
	$otrabajo = $_POST["txtOT"];
	$observacion = $_POST["txtObservacion"];
	$falla = $_POST["txtFalla"];
	
	$stmt = mssql_query("EXEC Operaciones..sp_setReporteDiario 0, '$ccosto', '$obra', '$fecha', $estado, $ulectura, $odometro, '$tcombustible', $combustible, '$operador', '$otrabajo', '$observacion', '$falla'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $numero = $rst["dblNumero"];
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reporte Diario</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value == '') return 0;
	
	switch(ctrl.id){
		case 'txtCCosto':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&texto=' + ctrl.value;
			break;
		case 'txtObra':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=1&texto=' + ctrl.value + '&usuario=<?php echo $usuario;?>';
			break;
		case 'txtChofer':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=2&texto=' + ctrl.value;
			break;
		case 'txtKmsHrs':
			if(parseFloat(ctrl.value) > 0){
				if(parseFloat(document.getElementById('txtUltLectura').value) > parseFloat(ctrl.value)){
					ctrl.value = 0;
					alert('El Kms/Hrs. debe ser mayor a la última lectura.');
				}else if(parseFloat(ctrl.value) - parseFloat(document.getElementById('txtUltLectura').value) > 1000){
					Deshabilita(true);
					document.getElementById('txtConfirma').value = '';
					AbreDialogo('divConfirmar', 'frmConfirmar', '../blank.html');
				}
			}
			break;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		CambiaColor(ctrl.id, false);
		switch(ctrl.id){
			case 'txtCCosto':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_ccosto.php?usuario=<?php echo $usuario;?>&texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=txtObra');
				break;
			case 'txtObra':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_obra.php?usuario=<?php echo $usuario;?>&texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=cmbEstado');
				break;
			case 'txtChofer':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_chofer.php?texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=txtOT');
				break;
		}
	}else{
		switch(ctrl.id){
			case 'txtKmsHrs':
			case 'txtLitros':
				return ValNumeros(evento, ctrl.id, true);
				break;
			case 'txtOT':
				return ValNumeros(evento, ctrl.id, false);
				break;
		}
	}
}

function Load(){
	if('<?php echo $accion;?>' == 'G') 
		alert('El reporte ha sido guardado con el número <?php echo $numero;?>.');
	document.getElementById('txtCCosto').focus();
}

function Confirma(){
	var respuesta = document.getElementById('txtConfirma').value;
	if(respuesta == '')
		alert('Debe ingresar una repuesta.');
	else if(respuesta.toUpperCase() == 'SI'){
		Deshabilita(false);
		CierraDialogo('divConfirmar', 'frmConfirmar');
	}else if(respuesta.toUpperCase() == 'NO'){	
		document.getElementById('txtKmsHrs').value = 0;
		Deshabilita(false);
		CierraDialogo('divConfirmar', 'frmConfirmar');
	}
}

function Deshabilita(sw){
	document.getElementById('txtCCosto').disabled = sw;
	document.getElementById('txtObra').disabled = sw;
	document.getElementById('cmbEstado').disabled = sw;
	document.getElementById('txtKmsHrs').disabled = sw;
	document.getElementById('cmbTCombustible').disabled = sw;
	document.getElementById('txtLitros').disabled = sw;
	document.getElementById('txtFecha').disabled = sw;
	document.getElementById('imgFecha').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtChofer').disabled = sw;
	document.getElementById('txtOT').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
	document.getElementById('txtFalla').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
}

function Guardar(){
	var FMinTope = RestarFecha('<?php echo date('d/m/Y');?>', 5);
	if(document.getElementById('txtCCosto').value == '')
		alert('Debe ingresar un centro de costo.');
	else if(document.getElementById('hdnObra').value == '')
		alert('Debe ingresar una obra asignada al centro de costo.');
	else if(document.getElementById('txtFecha').value == '')
		alert('Debe ingresar la fecha del reporte.');
	else if(!ComparaFechas(document.getElementById('txtFecha').value, 'Entre', FMinTope, '<?php echo date('d/m/Y');?>'))
		alert('La fecha ingresada debe estar entre ' + FMinTope + ' y <?php echo date('d/m/Y');?>');
	else 
		document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load()">
<div id="divConfirmar" style="z-index:10; position:absolute;width:30%;visibility:hidden;left:30%;top:10px;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr><td align="center" style="color:#000000;font-size:12px;font-weight:bold">Confirmar...</td></tr>
						</table>
					</td>
				</tr>
				<tr >
					<td valign="top" align="center">
						<table border="0" width="90%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="color:#FF0000">
									La diferencia entre la <b>última lectura</b> y el <b>Kms/Hrs.</b> ingresado es mayor a 1000.<br /><br />
									Escriba <b>SI</b> para continuar o <b>NO</b> para corregir el valor&nbsp;
									<input name="txtConfirma" id="txtConfirma" class="txt-plano" style="width:25px; text-align:center" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									/>
								</td>
							</tr>
							<tr><td><hr /></td></tr>
							<tr>
								<td align="right">
									<input type="button" name="btnConfirma" id="btnConfirma" class="boton" style="width:80px" value="Confirmar" 
										onclick="javascript: Confirma();"
									/>
								</td>
							</tr>
						</table>
						<iframe name="frmConfirmar" id="frmConfirmar" frameborder="1" style="border:thin; display:none" scrolling="no" width="100%" height="172px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divBuscador" style="z-index:10; position:absolute;width:60%;visibility:hidden;left:15%;top:10px;">
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
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divBuscador', 'frmBuscador');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000;font-size:12px;font-weight:bold">Buscador</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="1" style="border:thin" scrolling="no" width="100%" height="172px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divCalendario" style="z-index:10; position:absolute; width:20%; visibility:hidden; left: 10%; top: 80px;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro calendario."
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra cuadro calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
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

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'].$parametros;?>" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="5%">&nbsp;C.Costo</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtCCosto" id="txtCCosto" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('txtEquipo').value = '';"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Equipo</td>
					<td width="1%" align="center">:</td>
					<td width="25%"><input name="txtEquipo" id="txtEquipo" class="txt-plano" style="width:99%" readonly="true" /></td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Obra</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<input name="txtObra" id="txtObra" class="txt-plano" style="width:99%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('hdnObra').value = '';"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%" align="center">:</td>
					<td width="15%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC Operaciones..sp_getEstados 1, ''", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["dblCodigo"].'">'.$rst["strDescripcion"].'</option>';
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
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="10%">&nbsp;&Uacute;lt.Lectura</td>
					<td width="1%" align="center">:</td>
					<td width="13%"><input name="txtUltLectura" id="txtUltLectura" class="txt-plano" style="width:99%;text-align:right" readonly="true" value="0" /></td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;Kms/Hrs.</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<input name="txtKmsHrs" id="txtKmsHrs" class="txt-plano" style="width:99%; text-align:right" value="0" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0;"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;T.Combustible</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<select name="cmbTCombustible" id="cmbTCombustible" class="sel-plano" style="width:100%">
							<option value="none">&nbsp;</option>
							<option value="Diesel">Diesel</option>
							<option value="Bencina">Bencina</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="11%">&nbsp;Ltrs.Combustible</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<input name="txtLitros" id="txtLitros" class="txt-plano" style="width:99%;text-align:right" value="0"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0;"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFecha" id="txtFecha" class="txt-plano" style="width:99%;text-align:center" readonly="true"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFecha', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&foco=txtChofer&avanza=0', '', '6%', '60px');
							"
							onfocus="javascript: CambiaImagen('imgFecha', true);"
							onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
						><img id="imgFecha" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="11%">&nbsp;Chofer/Operador</td>
					<td width="1%" align="center">:</td>
					<td width="53%">
						<input name="txtChofer" id="txtChofer" class="txt-plano" style="width:99%" 	
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							inkeyup="javascript: if(this.value == '') document.getElementById('hdnChofer').value = '';"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;N&deg;OT</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtOT" id="txtOT" class="txt-plano" style="width:99%;text-align:center" 	
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="9%">&nbsp;Observaci&oacute;n</td>
					<td width="1%" align="center">:</td>
					<td width="90%">
						<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="1000" 	
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
				<tr>
					<td width="9%">&nbsp;Reporte Falla</td>
					<td width="1%" align="center">:</td>
					<td width="90%">
						<input name="txtFalla" id="txtFalla" class="txt-plano" style="width:100%" maxlength="1000" 	
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
				<tr>
					<td width="9%">&nbsp;</td>
					<td width="1%" align="center">&nbsp;</td>
					<td width="90%"><div id="divFallas" style="z-index:0; position:relative; width:99%; height:100px; border:solid 1px; overflow:scroll; background-color:#FFFFFF"></div></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="bottom" style="height:127px"><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="hdnObra" id="hdnObra" />
			<input type="hidden" name="hdnChofer" id="hdnChofer" />
			<input type="hidden" name="accion" id="accion" value="G" />
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:80px" value="Guardar" 
				onclick="javascript: Guardar();"
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