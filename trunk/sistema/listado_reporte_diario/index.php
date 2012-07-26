<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado de Reportes Diarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var busco = 0;
var ficha = 0;

function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value == '') return 0;
	switch(ctrl.id){
		case 'txtObra':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=0&texto=' + ctrl.value + '&usuario=<?php echo $usuario;?>';
			break;
		case 'txtEquipo':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=1&texto=' + ctrl.value;
			break;
		case 'txtOperador':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=2&texto=' + ctrl.value;
			break;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		CambiaColor(ctrl.id, false);
		Deshabilita(true);
		switch(ctrl.id){
			case 'txtObra':
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_obra.php?usuario=<?php echo $usuario;?>&texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=txtEquipo');
				break;
			case 'txtEquipo':
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_equipos.php?texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=cmbEstado');
				break;
			case 'txtOperador':
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_chofer.php?texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=brnBuscar');
				break;
		}
	}
}

function Load(){
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 120);
	ClickFicha(1);
}

function Buscar(){
	if(document.getElementById('cmbMes').value == 'all' && document.getElementById('cmbAno').value != 'all')
		alert('debe seleccionar un mes.');
	else if(document.getElementById('cmbMes').value != 'all' && document.getElementById('cmbAno').value == 'all')
		alert('debe seleccionar un año.');
	else{
		busco = 0;
		ClickFicha(1);
		busco = 1;
		document.getElementById('frm').setAttribute('action', 'res_reportes_diario.php');
		document.getElementById('frm').setAttribute('target', 'detalle');
		document.getElementById('frm').submit();
		Deshabilita(true);
	}
}

function ClickFicha(idFicha){
	ficha = idFicha;
	if(idFicha == 1){
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha1').style.color = '#FFFFFF';
		document.getElementById('aFicha1').style.fontWeight = 'bold';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha2').style.color = '#3A4CFB';
		document.getElementById('aFicha2').style.fontWeight = 'normal';
		if(busco == 1){
			document.getElementById('frm').setAttribute('action', 'res_reportes_diario.php');
			document.getElementById('frm').setAttribute('target', 'detalle');
			document.getElementById('frm').submit();
		}
	}else{
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha1').style.color = '#3A4CFB';
		document.getElementById('aFicha1').style.fontWeight = 'normal';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha2').style.color = '#FFFFFF';
		document.getElementById('aFicha2').style.fontWeight = 'bold';
		if(busco == 1){
			document.getElementById('frm').setAttribute('action', 'res_control_combustible.php');
			document.getElementById('frm').setAttribute('target', 'detalle');
			document.getElementById('frm').submit();
		}
	}
}

function Deshabilita(sw){
	document.getElementById('txtObra').disabled = sw;
	document.getElementById('txtEquipo').disabled = sw;
	document.getElementById('cmbEstado').disabled = sw;
	document.getElementById('txtOperador').disabled = sw;
	document.getElementById('cmbMes').disabled = sw;
	document.getElementById('cmbAno').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	if(detalle.document.getElementById('totfil')){
		var totfil = detalle.document.getElementById('totfil').value;

		for(i = 1; i <= totfil; i++){ 
			if(detalle.document.getElementById('imgFalla' + i)) detalle.document.getElementById('imgFalla' + i).style.visibility = (sw ? 'hidden' : 'visible');
		}
	}
	
	document.getElementById('btnExportar').disabled = sw;
}

function Exportar(){
	if(!detalle.document.getElementById('totfil'))
		alert('No se ha encontrado información para exportar.');
	else{
		if(ficha == 1) 
			document.getElementById('frm').setAttribute('action', 'exp_reportes_diario.php');
		else
			document.getElementById('frm').setAttribute('action', 'exp_control_combustible.php');
		document.getElementById('frm').setAttribute('target', 'transaccion');
		document.getElementById('frm').submit();	
	}
}
-->
</script>
<body onload="javascript: Load();">
<div id="divBuscador" style="position:absolute;width:60%;visibility:hidden;left:15%;top:10px;">
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

<div id="divFallas" style="position:absolute;width:60%;visibility:hidden;left:17%;top:10px;">
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
											CierraDialogo('divSolucion', 'frmSolucion');
											CierraDialogo('divFallas', 'frmFallas');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000;font-size:12px;font-weight:bold">Reporte de Fallas</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmFallas" id="frmFallas" frameborder="1" style="border:thin" scrolling="no" width="100%" height="172px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divSolucion" style="position:absolute;width:40%;visibility:hidden;left:30%;top:15px;">
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
											//Deshabilita(false);
											CierraDialogo('divSolucion', 'frmSolucion');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000;font-size:12px;font-weight:bold">Solucionado</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmSolucion" id="frmSolucion" frameborder="1" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">			
			<form name="frm" id="frm" method="post">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">			
							<tr>
								<td width="3%">&nbsp;Obra(*)</td>
								<td width="1%" align="center">:</td>
								<td width="31%">
									<input type="hidden" name="hdnObra" id="hdnObra" />
									<input name="txtObra" id="txtObra" class="txt-plano" style="width:99%" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnObra').value = '';"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Equipo(*)</td>
								<td width="1%" align="center">:</td>
								<td width="31%">
									<input type="hidden" name="hdnEquipo" id="hdnEquipo" />
									<input name="txtEquipo" id="txtEquipo" class="txt-plano" style="width:99%" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnEquipo').value = '';"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Estado</td>
								<td width="1%" align="center">:</td>
								<td width="20%">
									<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:99%">
										<option value="all">Todos</option>
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
						<table border="0" width="100%" cellpadding="0" cellspacing="0">			
							<tr>
								<td width="9%">&nbsp;Operador(*)</td>
								<td width="1%" align="center">:</td>
								<td width="0%">
									<input type="hidden" name="hdnOperador" id="hdnOperador" />
									<input name="txtOperador" id="txtOperador" class="txt-plano" style="width:99%" 	
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnOperador').value = '';"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="9%">&nbsp;Rev.T&eacute;cnica</td>
								<td width="1%" align="center">:</td>
								<td width="10%">
									<select name="cmbMes" id="cmbMes" class="sel-plano" style="width:100%">
										<option value="all" selected="selected">--</option>
										<option value="01">Enero</option>
										<option value="02">Febrero</option>
										<option value="03">Marzo</option>
										<option value="04">Abril</option>
										<option value="05">Mayo</option>
										<option value="06">Junio</option>
										<option value="07">Julio</option>
										<option value="08">Agosto</option>
										<option value="09">Septiembre</option>
										<option value="10">Octubre</option>
										<option value="11">Noviembre</option>
										<option value="12">Diciembre</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="6%">
									<select name="cmbAno" id="cmbAno" class="sel-plano" style="width:100%">
										<option value="all" selected="selected">--</option>
									<?php
									for($i = date('Y') - 5; $i <= date('Y'); $i++)
										echo '<option value="'.$i.'">'.$i.'</option>';
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="0%">
									<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
										onclick="javascript: Buscar();"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
	<tr>
		<td style="color:#FF0000">
			<b>(*) Puede escribir parte del codigo o del nombre y presiones ENTER</b><br />
			<hr />
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="160px" cellpadding="0" cellspacing="0">
				<tr>
					<td id="tdFicha1" width="80px" align="center" style="background-repeat:no-repeat;">
						<a id="aFicha1" href="#" style="color:#FFFFFF" title="General"
							onclick="javascript: ClickFicha(1);"
							onmouseover="javascript: window.status='General.'; return true;"
						>R. Diarios</a>
					</td>
					<td id="tdFicha2" width="80px" align="center" style="background-repeat:no-repeat">
						<a id="aFicha2" href="#" title="Detalle"
							onclick="javascript: ClickFicha(2);"
							onmouseover="javascript: window.status='Detalle.'; return true;"
						>C.Combust.</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="detalle" id="detalle" frameborder="0" width="100%" scrolling="no" src="../blank.html"></iframe><td></td>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
			/>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>