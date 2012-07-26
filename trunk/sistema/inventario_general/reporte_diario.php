<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
$accion = $_POST["accion"];
if($accion == 'G'){
	$estado = $_POST["cmbEstado"];
	$oinicial = $_POST["txtKmsHrsIni"];
	$ofinal = $_POST["txtKmsHrsFin"];
	$combustible = $_POST["txtLitros"];
	$operador = $_POST["hdnChofer"];
	$observacion = $_POST["txtObservacion"];

	mssql_query("EXEC Operaciones..sp_setReporteDiario 1, NULL, NULL, NULL, $estado, $oinicial, $ofinal, $combustible, '$operador', NULL, '$observacion', $numero", $cnx);
}else{
	$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 1, '$numero'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$ccosto = $rst["strCCosto"];
		$equipo = $rst["strDescEquipo"];
		$obra = $rst["strDescObra"];
		$estado = $rst["dblEstado"];
		$oinicial = $rst["dblOdometroInicial"];
		$ofinal = $rst["dblOdometroFinal"];
		$combustible = $rst["dblCombustible"];
		$fecha = $rst["dtmFecha"];
		$operador = $rst["strOperador"];
		$nombre = $rst["strNombre"];
		$otrabajo = $rst["strOTrabajo"];
		$observacion = $rst["strObservacion"];
	}
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
	if(ctrl.id == 'txtChofer')
		document.getElementById('transaccion').src = 'transaccion.php?modulo=1&texto=' + ctrl.value;
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		if(ctrl.id == 'txtChofer'){
			CambiaColor(ctrl.id, false);
			Deshabilita(true);
			AbreDialogo('divBuscador', 'frmBuscador', 'buscar_chofer.php?texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=txtObservacion');
		}
	}else{
		switch(ctrl.id){
			case 'txtKmsHrsIni':
			case 'txtKmsHrsFin':
			case 'txtLitros':
				return ValNumeros(evento, ctrl.id, true);
				break;
		}
	}
}

function Load(){
	if('<?php echo $accion;?>' == 'G'){
		parent.document.getElementById('frmReport').src = parent.document.getElementById('frmReport').src;
		parent.CierraDialogo('divEReport', 'frmEReport');
	}
}

function Deshabilita(sw){
	document.getElementById('cmbEstado').disabled = sw;
	document.getElementById('txtKmsHrsIni').disabled = sw;
	document.getElementById('txtKmsHrsFin').disabled = sw;
	document.getElementById('txtLitros').disabled = sw;
	document.getElementById('txtChofer').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
}

function Guardar(){
	if(parseFloat(document.getElementById('txtKmsHrsTotal').value) < 0)
		alert('El total en Kms./Hrs. debe ser mayor a cero.');
	else
		document.getElementById('frm').submit();
}

function Totalizar(){
	var valini = document.getElementById('txtKmsHrsIni').value;
	var valter = document.getElementById('txtKmsHrsFin').value;
	document.getElementById('txtKmsHrsTotal').value = Decimales(parseFloat(valter) - parseFloat(valini), 2);
}
-->
</script>
<body onload="javascript: Load()">
<div id="divBuscador" style="position:absolute;width:60%;visibility:hidden;left:15%;top:5px;">
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
				<tr><td valign="top"><iframe name="frmBuscador" id="frmBuscador" frameborder="1" style="border:thin" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
					<td width="6%">&nbsp;N&deg;Reporte</td>
					<td width="1%" align="center">:</td>
					<td width="7%"><input name="txtReporte" id="txtReporte" class="txt-sborde" style="width:99%;text-align:center; background-color:#ececec" readonly="true" value="<?php echo $numero;?>" /></td>
					<td width="5%">&nbsp;C.Costo</td>
					<td width="1%" align="center">:</td>
					<td width="7%"><?php echo $ccosto;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Equipo</td>
					<td width="1%" align="center">:</td>
					<td width="20%"><input class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="<?php echo $equipo;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Obra</td>
					<td width="1%" align="center">:</td>
					<td width="20%"><input class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="<?php echo $obra;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%" align="center">:</td>
					<td width="15%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC Operaciones..sp_getEstados 1, ''", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["dblCodigo"].'" '.($rst["dblCodigo"] == $estado ? 'selected' : '').'>'.$rst["strDescripcion"].'</option>';
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
					<td width="10%">&nbsp;Kms/Hrs.Inicial</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<input name="txtKmsHrsIni" id="txtKmsHrsIni" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($oinicial, 2, '.', '');?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: 
								if(this.value == '') this.value = 0;
								Totalizar();
							"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;Kms/Hrs.Final</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<input name="txtKmsHrsFin" id="txtKmsHrsFin" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($ofinal, 2, '.', '');?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: 
								if(this.value == '') this.value = 0;
								Totalizar();
							"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;Kms/Hrs.Total</td>
					<td width="1%" align="center">:</td>
					<td width="13%"><input name="txtKmsHrsTotal" id="txtKmsHrsTotal" class="txt-plano" style="width:99%; text-align:right" readonly="true" value="<?php echo number_format($ofinal - $oinicial, 2, '.', '');?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="11%">&nbsp;Ltrs.Combustible</td>
					<td width="1%" align="center">:</td>
					<td width="13%">
						<input name="txtLitros" id="txtLitros" class="txt-plano" style="width:99%;text-align:right" value="<?php echo number_format($combustible, 2, '.', '');?>"
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
					<td width="10%"><?php echo $fecha;?></td>
					<td width="1%">&nbsp;</td>
					<td width="11%">&nbsp;Chofer/Operador</td>
					<td width="1%" align="center">:</td>
					<td width="55%">
						<input name="txtChofer" id="txtChofer" class="txt-plano" style="width:99%" value="<?php echo $nombre;?>"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							inkeyup="javascript: if(this.value == '') document.getElementById('hdnChofer').value = '';"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;N&deg;OT</td>
					<td width="1%" align="center">:</td>
					<td width="10%"><?php echo $otrabajo;?></td>
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
						<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" maxlength="1000" value="<?php echo $observacion;?>"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="bottom" style="height:75px"><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="hdnChofer" id="hdnChofer" value="<?php echo $operador;?>"/>
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