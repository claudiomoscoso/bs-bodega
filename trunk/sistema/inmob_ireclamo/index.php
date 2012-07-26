<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ingreso de Reclamos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(inmobil){
	document.getElementById('transaccion').src = 'transaccion.php?modulo=0&inmobil=' + inmobil;
}

function Blur(ctrl){
	var contrato = document.getElementById('cmbInmobil').value;
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtHora':
			if(!EsHora(ctrl)) ctrl.value = '  :  ';
		case 'txtHVence':
			var fch = '<?php echo date('d/m/Y');?> ' + document.getElementById('txtHora').value;
			var cant = document.getElementById('txtHVence').value;
			document.getElementById('hdnFVence').value = SumarFecha(fch, cant);
			break;
		case 'txtMovil':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=1&contrato=' + contrato + '&texto=' + ctrl.value;
			break;
		case 'txtOrden':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=2&contrato=' + contrato + '&orden=' + ctrl.value;
			break;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var inmobil = document.getElementById('cmbInmobil').value;
		switch(ctrl.id){
			case 'cmbInmobil':
				document.getElementById('cmbProyecto').focus();
				break;
		}
	}else{
		switch(ctrl.id){
			case 'txtHVence':
				return ValNumeros(evento, ctrl.id, false);
				break;
			case 'txtHora':
				return ValHora(evento, ctrl);
				break;
		}
	}
}

function Load(){

}

function Deshabilita(sw){
	document.getElementById('cmbInmobil').disabled = sw;
	document.getElementById('txtMovil').disabled = sw;
	document.getElementById('txtOrden').disabled = sw;
	document.getElementById('txtHora').disabled = sw;
	document.getElementById('txtHVence').disabled = sw;
	document.getElementById('cmbProyecto').disabled = sw;
	document.getElementById('cmbPrioridad').disabled = sw;
	document.getElementById('txtMotivo').disabled = sw;
	document.getElementById('txtCliente').disabled = sw;
	document.getElementById('txtDireccion').disabled = sw;
	document.getElementById('txtEntreCalle').disabled = sw;
	document.getElementById('cmbComuna').disabled = sw;
	document.getElementById('cmbZona').disabled = sw;
	document.getElementById('txtFaxOds').disabled = sw;
	document.getElementById('cmbInspector').disabled = sw;	
	document.getElementById('txtObservacion').disabled = sw;
	
	document.getElementById('btnGuardar').disabled = sw;
}

function setGuardar(){
	if(document.getElementById('hdnMovil').value == ''){
		alert('Debe ingresar el movil para la orden de trabajo.');
	}else if(document.getElementById('txtOrden').value == ''){
		alert('Debe ingresar el número de la orden de trabajo.');
	}else if(document.getElementById('txtHora').value == '  :  '){
		alert('Debe ingresar la hora de la orden de trabajo.');
	}else if(document.getElementById('txtHVence').value == 0){
		alert('Debe ingresar las horas de vencimiento.');
	}else if(document.getElementById('txtMotivo').value == ''){
		alert('Debe ingresar el motivo de la orden de trabajo.');
	}else if(document.getElementById('txtCliente').value == ''){
		alert('Debe ingresar el nombre del cliente.');
	}else if(document.getElementById('txtDireccion').value == ''){
		alert('Debe ingresar la dirección.');
	}else{
		document.getElementById('frm').submit();
	}
}
-->
</script>
<body onload="javascript: Load();">
<div id="divMoviles" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
										Deshabilita(false);
										CierraDialogo('divMoviles','frmMoviles');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Moviles</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe id="frmMoviles" frameborder="0" scrolling="no" width="100%" height="245px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="grabar.php<?php echo $parametros;?>" target="transaccion">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="8%">&nbsp;Inmobiliaria</td>
		<td width="1%">:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="46%">
						<select name="cmbInmobil" id="cmbInmobil" class="sel-plano" style="width:100%"
							onchange="javascript: Change(this.value)"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Inmobiliaria..sp_getInmobiliarias 0, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							if($inmobil == '') $inmobil = $rst["Codigo"];
							echo '<option value="'.$rst["Codigo"].'">'.$rst["Detalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Proyecto</td>
					<td width="1%">:</td>
					<td width="47%">
						<select name="cmbProyecto" id="cmbProyecto" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Inmobiliaria..sp_getProyectos 0, '$inmobil'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["Codigo"].'">'.$rst["Detalle"].'</option>';
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
		<td>&nbsp;N&deg; Orden</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="10%">
						<input name="txtOrden" id="txtOrden" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td width="8%"><?php echo date('d/m/Y');?></td>
					<td width="1%">&nbsp;</td>
					<td width="6%">
						<input name="txtHora" id="txtHora" class="txt-plano" style="width:99%; text-align:center" maxlength="5" value="<?php echo date('H:i');?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = '  :  ';"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;Hrs.Vcto.</td>
					<td width="1%">:</td>
					<td width="6%">
						<input name="txtHVence" id="txtHVence" class="txt-plano" style="width:99%; text-align:center" value="0" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0;"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;Tipo ODT</td>
					<td width="1%">:</td>
					<td width="21%">
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Prioridad</td>
					<td width="1%">:</td>
					<td width="12%">
						<select name="cmbPrioridad" id="cmbPrioridad" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Orden..sp_getPrioridad 0, '$inmobil'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.(trim($rst["strCodigo"]) == trim($prioridad) ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
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
		<td>&nbsp;Motivo</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%">
						<input name="txtMotivo" id="txtMotivo" class="txt-plano" style="width:99%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Cliente</td>
					<td width="1%">:</td>
					<td width="46%">
						<input name="txtCliente" id="txtCliente" class="txt-plano" style="width:100%" 
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
		<td>&nbsp;Direcci&oacute;n</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="0%">
						<input name="txtDireccion" id="txtDireccion" class="txt-plano" style="width:100%"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="10%">&nbsp;Entre Calles</td>
					<td width="1%">:</td>
					<td width="0%">
						<input name="txtEntreCalle" id="txtEntreCalle" class="txt-plano" style="width:100%" 
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
		<td>&nbsp;Comuna</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%">
						<select name="cmbComuna" id="cmbComuna" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getComunas 0, '$inmobil'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.($rst["strCodigo"] == $comuna ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Zona</td>
					<td width="1%">:</td>
					<td width="20%">
						<select name="cmbZona" id="cmbZona" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Orden..sp_getZonas 0, '$inmobil'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.($rst["strCodigo"] == $distrito ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Fax/ODS</td>
					<td width="1%">:</td>
					<td width="19%">
						<input name="txtFaxOds" id="txtFaxOds" class="txt-plano" style="width:99%"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Inspector</td>
					<td width="1%">:</td>
					<td width="20%">
						<select name="cmbInspector" id="cmbInspector" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Orden..sp_getInspector 0, '$inmobil'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'" '.(trim($rst["strCodigo"]) == trim($inspector) ? 'selected' : '').'>'.$rst["strNombre"].'</option>';
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
		<td>&nbsp;Observaci&oacute;n</td>
		<td>:</td>
		<td>
			<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" 
				onblur="javascript: Blur(this);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this);"
			/>
		</td>
	</tr>
	<tr><td colspan="3" valign="bottom" style="height:207px"><hr /></td></tr>
	<tr>
		<td colspan="3" align="right">
			<input type="hidden" name="hdnMovil" id="hdnMovil" />
			<input type="hidden" name="hdnFVence" id="hdnFVence" />
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: setGuardar()"
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