<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
$accion = $_POST["accion"];
if($accion == 'G'){
	$odometro = $_POST["txtKmsHrs"];
	mssql_query("EXEC Operaciones..sp_setReporteDiario 2, NULL, NULL, NULL, NULL, NULL, $odometro, NULL, NULL, NULL, NULL, NULL, NULL, $numero", $cnx);
}else{
	$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 1, $numero", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$ccosto = $rst["strCCosto"];
		$equipo = $rst["strDescEquipo"];
		$obra = $rst["strDescObra"];
		$estado = $rst["strDescEstado"];
		$oinicial = $rst["dblOdometroInicial"];
		$ofinal = $rst["dblOdometroFinal"];
		$tcombustible = $rst["strTCombustible"];
		$combustible = $rst["dblCombustible"];
		$fecha = $rst["dtmFecha"];
		$operador = $rst["strNombre"];
		$otrabajo = $rst["strOTrabajo"];
		$observacion = $rst["strObservacion"];
	}
	mssql_free_result($stmt);
	
	$falla = '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
	$stmt = mssql_query("SELECT strDescripcion, CONVERT(VARCHAR, dtmFecha, 103) AS dtmFch FROM Operaciones..ReporteFalla WHERE strCCosto = '$ccosto' ORDER BY dtmFecha DESC", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		do{
			$cont++;
			$falla .= '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
			$falla .= '<td width="90%">&nbsp;'.$rst["strDescripcion"].'</td>';
			$falla .= '<td width="10%">'.$rst["dtmFch"].'</td>';
			$falla .= '</tr>';
		}while($rst = mssql_fetch_array($stmt));
	}else
		$falla .= '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
	$falla .= '</table>';
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
	switch(ctrl.id){
		case 'txtKmsHrs':
			return ValNumeros(evento, ctrl.id, true);
			break;
	}
}

function Load(){
	if('<?php echo $accion;?>' == 'G'){
		alert('El reporte ha sido guardado.');
		parent.document.getElementById('txtNumero').focus();
	}else{
		document.getElementById('divFallas').innerHTML = '<?php echo $falla;?>';
		document.getElementById('txtKmsHrs').focus();
	}
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

function Guardar(){
	if(confirm('¿Está seguro que desea guardar los cambios hechos?')) document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load()">
<div id="divConfirmar" style="z-index:10; position:absolute;width:30%;visibility:hidden;left:35%;top:10px;">
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

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF']."?numero=$numero";?>" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="5%">&nbsp;C.Costo</td>
					<td width="1%" align="center">:</td>
					<td width="10%"><input class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo $ccosto;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Equipo</td>
					<td width="1%" align="center">:</td>
					<td width="25%"><input class="txt-plano" style="width:99%" readonly="true" value="&nbsp;<?php echo $equipo;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Obra</td>
					<td width="1%" align="center">:</td>
					<td width="25%"><input class="txt-plano" style="width:99%" readonly="true" value="&nbsp;<?php echo $obra;?>"/></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%" align="center">:</td>
					<td width="15%"><input class="txt-plano" style="width:99%" readonly="true" value="&nbsp;<?php echo $estado;?>"/></td>
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
					<td width="13%"><input name="txtUltLectura" id="txtUltLectura" class="txt-plano" style="width:99%;text-align:right" readonly="true" value="<?php echo $oinicial;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;Kms/Hrs.</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<input name="txtKmsHrs" id="txtKmsHrs" class="txt-plano" style="width:99%; text-align:right" value="<?php echo $ofinal;?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0;"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;T.Combustible</td>
					<td width="1%" align="center">:</td>
					<td width="13%"><input class="txt-plano" style="width:99%" readonly="true" value="&nbsp;<?php echo $tcombustible;?>"/></td>
					<td width="1%">&nbsp;</td>
					<td width="11%">&nbsp;Ltrs.Combustible</td>
					<td width="1%" align="center">:</td>
					<td width="13%"><input name="txtLitros" id="txtLitros" class="txt-plano" style="width:99%;text-align:right" readonly="true" value="<?php echo $combustible;?>"/></td>
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
					<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:99%;text-align:center" readonly="true" value="<?php echo $fecha;?>"/></td>
					<td width="1%">&nbsp;</td>
					<td width="11%">&nbsp;Chofer/Operador</td>
					<td width="1%" align="center">:</td>
					<td width="55%"><input name="txtChofer" id="txtChofer" class="txt-plano" style="width:99%" readonly="true" value="&nbsp;<?php echo $operador;?>"/></td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;N&deg;OT</td>
					<td width="1%" align="center">:</td>
					<td width="10%"><input name="txtOT" id="txtOT" class="txt-plano" style="width:99%;text-align:center" readonly="true" value="<?php echo $otrabajo;?>"/></td>
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
					<td width="90%"><input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="1000" readonly="true" value="&nbsp;<?php echo $observacion;?>"/></td>
				</tr>
				<tr valign="top">
					<td width="9%">&nbsp;Reporte Falla</td>
					<td width="1%" align="center">:</td>
					<td width="90%"><div id="divFallas" style="z-index:0; position:relative; width:100%; height:100px; border:solid 1px; overflow:scroll;"></div></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="bottom" style="height:105px"><hr /></td></tr>
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