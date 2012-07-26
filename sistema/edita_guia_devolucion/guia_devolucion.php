<?php
include '../conexion.inc.php';

$existe = 0;
$usuario = $_GET["usuario"];
$bodega = $_POST["cmbBodega"];
$numero = $_POST["txtDevolucion"];
$stmt = mssql_query("EXEC Bodega..sp_getGuiaDevolucion 2, $numero, '$bodega'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$existe = 1;
	$interno = $rst["dblNumero"];
	$devolucion = $rst["dblNum"];
	$fecha = $rst["dtmFecha"];
	$descbodega = $rst["strDescBodega"];
	$descmovil = $rst["strNombre"];
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
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 95);
	document.getElementById('detalle').src = 'detalle_guia_devolucion.php?usuario=<?php echo $usuario;?>&interno=<?php echo $interno;?>';
	document.getElementById('imgFecha').focus();
}

function Deshabilita(sw){
	document.getElementById('txtFecha').disabled = sw;
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

<div id="divAlerta" style="position:absolute; top:5px; left:30%; width:40%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra cuadro de alerta."
										onClick="javascript: 
											Deshabilita(false);
											var fila = detalle.document.getElementById('fila').value;
											detalle.document.getElementById('txtCantidad' + fila).value = detalle.document.getElementById('hdnCantidad' + fila).value
											CierraDialogo('divAlerta', 'frmAlerta');
										"
										onmouseover="javascript: window.status='Cierra cuadro de alerta.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px; font-weight:bold">PRECAUCI&Oacute;N</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="color:#FF0000; font-weight:bold">
									<br />
									&nbsp;La cantidad digitada puede no ser correcta. 
									Si lo ingresado es correcto, escriba SI y presiones el boton Continuar.
								</td>
							</tr>
							<tr><td valign="top"><iframe name="frmAlerta" id="frmAlerta" style="display:none" ></iframe></td></tr>
							<tr>
								<td align="center">
									<input name="txtConfirma" id="txtConfirma" class="txt-plano" style="width:20px; text-align:center" 
										onblur="javascript: CambiaColor(this.id, false)"
										onfocus="javascript: CambiaColor(this.id, true)"
									/>
									&nbsp;
									<input type="button" name="btnConfirma" id="btnConfirma" class="boton" style="width:90px;" value="Continuar"
										onclick="javascript:
											if(document.getElementById('txtConfirma').value == 'SI'){
												var fila = detalle.document.getElementById('fila').value;
												detalle.Guarda(fila);
												Deshabilita(false);
												CierraDialogo('divAlerta', 'frmAlerta');
											}
										"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
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
					<td width="7%" align="left" >&nbsp;N&deg;Devoluci&oacute;n</td>
					<td width="1%" align="center">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%">&nbsp;<?php echo $devolucion;?></td>
								<td width="1%">&nbsp;</td>
								<td width="4%">&nbsp;Fecha</td>
								<td width="1%" align="center">:</td>
								<td width="10%"><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo $fecha;?>"/></td>
								<td width="2%" align="center">
									<a href="#" title="Abre calendario"
										onblur="javascript: CambiaImagen('imgFecha', false);"
										onclick="javascript:
											Deshabilita(true);
											AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&fecha=' + document.getElementById('txtFecha').value + '&retrocede=&avanza=0');
										"
										onfocus="javascript: CambiaImagen('imgFecha', true);"
										onmouseover="javascript: window.status='Abre calendario'; return true;"
									><img id="imgFecha" border="0" align="middle" src="../images/aba.gif" /></a>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="6%">&nbsp;Bodega</td>
								<td width="1%" align="center">:</td>
								<td width="25%">&nbsp;<?php echo $descbodega;?></td>
								<td width="1%">&nbsp;</td>
								<td width="4%">&nbsp;Movil</td>
								<td width="1%" align="center">:</td>
								<td width="30%" >&nbsp;<?php echo $descmovil;?></td>
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
					<th width="10%">Unidad</th>
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
			<input type="hidden" name="hdnDevolucion" id="hdnDevolucion" value="<?php echo $interno;?>" />
			
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
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>