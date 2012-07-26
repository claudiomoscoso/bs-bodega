<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Lisorval</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	switch(ctrl.id){
		case 'txtFInicio':
		case 'txtFTermino':
			switch(tecla){
				case 8:
				case 46:
					ctrl.value = '';
				case 9:
					return true;
					break;
				default:
					return false;
			}
			break;
		case 'txtEPago':
		case 'txtCertificado':
			return ValNumeros(evento, ctrl.id, false);
			break;
		case 'txtMovil':
			if(tecla == 13){
				var contrato = document.getElementById('cmbContrato').value;
				Deshabilita(true);
				AbreDialogo('divMovil', 'frmMovil', 'buscar_moviles.php?contrato=' + contrato + '&texto=' + ctrl.value + '&ctrl=' + ctrl.id + '&foco=chkCerradas');
			}
	}
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 130);
}

function Buscar(){
	var finicio = document.getElementById('txtFInicio').value;
	var ftermino = document.getElementById('txtFTermino').value;
	var epago = document.getElementById('txtEPago').value;
	var certificado = document.getElementById('txtCertificado').value;
	var movil = document.getElementById('hdnMovil').value;
	if(finicio != '' && ftermino == '')
		alert('Debe ingresar la fecha de término.');
	else if(finicio == '' && ftermino != '')
		alert('Debe ingresar la fecha de inicio.');
	else if((finicio == '' && ftermino == '') && movil != '')
		alert('Debe ingresar un rango de fechas.')
	else if((finicio == '' && ftermino == '') && epago == '' && certificado == '' && movil == '')
		alert('Debe ingresar al menos en uno de los criterios de busqueda.');
	else{
		document.getElementById('frm').setAttribute('action', 'resultado.php');
		document.getElementById('frm').setAttribute('target', 'resultado');
		document.getElementById('frm').submit();
		Deshabilita(true);
	}
}

function Deshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('txtFInicio').disabled = sw;
	document.getElementById('imgFInicio').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtFTermino').disabled = sw;
	document.getElementById('imgFTermino').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtEPago').disabled = sw;
	document.getElementById('txtCertificado').disabled = sw;
	document.getElementById('txtMovil').disabled = sw;
	if(document.getElementById('txtEPago').value == '') document.getElementById('chkCerradas').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	
	//document.getElementById('btnImprimir').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function Imprimir(){
	var contrato = document.getElementById('cmbContrato').value;
	var finicio = document.getElementById('txtFInicio').value;
	var ftermino = document.getElementById('txtFTermino').value;
	var epago = document.getElementById('txtEPago').value;
	document.getElementById('transaccion').src = 'imprimir.php?contrato=' + contrato + '&finicio=' + finicio + '&ftermino=' + ftermino + '&epago=' + epago;
}

function Exportar(){
	if(resultado.document.getElementById('totfil')){
		var contrato = document.getElementById('cmbContrato').value;
		var finicio = document.getElementById('txtFInicio').value;
		var ftermino = document.getElementById('txtFTermino').value;
		var epago = document.getElementById('txtEPago').value;
		var certificado = document.getElementById('txtCertificado').value;
		var movil = document.getElementById('hdnMovil').value;
		var cerradas = document.getElementById('chkCerradas').checked ? 1 : 0;
		document.getElementById('transaccion').src = 'exportar.php?contrato=' + contrato + '&finicio=' + finicio + '&ftermino=' + ftermino + '&epago=' + epago + '&certificado=' + certificado + '&movil=' + movil + '&cerradas=' + cerradas;
	}else
		alert('No hay datos para exportar.')
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="position:absolute; width:20%; visibility:hidden; left: 10%; top: 5px;">
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

<div id="divMovil" style="position:absolute; width:50%; visibility:hidden; left: 25%; top: 5px;">
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
											CierraDialogo('divMovil', 'frmMovil');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Busqueda de moviles</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMovil" id="frmMovil" frameborder="1" style="border:thin" scrolling="no" width="100%" height="200px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="6%">&nbsp;Contrato</td>
					<td width="1%" align="center">:</td>
					<td width="37%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%"
							onchange="javascript: document.getElementById('resultado').src = '../blank.html'"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Fch.Inicio</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFInicio" id="txtFInicio" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFInicio', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFInicio&foco=imgFTermino&fecha=' + document.getElementById('txtFInicio').value, '', '25%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFInicio', true);"
							onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
						><img id="imgFInicio" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;Fch.T&eacute;rmino</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFTermino" id="txtFTermino" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFTermino', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFTermino&foco=txtEPago&fecha=' + document.getElementById('txtFTermino').value, '', '46%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFTermino', true);"
							onmouseover="javascript: window.status='Abre cuadro calendario.'; return true;"
						><img id="imgFTermino" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;E.Pago</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtEPago" id="txtEPago" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript:
								if(this.value == '') 
									document.getElementById('chkCerradas').disabled = false;
								else
									document.getElementById('chkCerradas').disabled = true;
							"
						/>
					</td>
					
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="7%">&nbsp;Certificado</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtCertificado" id="txtCertificado" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Movil</td>
					<td width="1%" align="center">:</td>
					<td width="30%">
						<input type="hidden" name="hdnMovil" id="hdnMovil" />
						<input name="txtMovil" id="txtMovil" class="txt-plano" style="width:99%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('hdnMovil').value  =''"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="1%"><input type="checkbox" name="chkCerradas" id="chkCerradas" /></td>
					<td width="5%">&nbsp;Cerradas</td>
					<td width="1%">&nbsp;</td>
					<td width="37%" align="right">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="10%">N&deg;Orden</th>
					<th width="10%">Fax/ODS</th>
					<th width="15%">Fecha</th>
					<th width="43%" align="left">&nbsp;Direcci&oacute;n</th>
					<th width="20%" align="left">&nbsp;Sector/Localidad</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="6%" >Item</th>
					<th width="30%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="6%">Unidad</th>
					<th width="10%" align="right">Precio&nbsp;</th>
					<th width="10%" align="right">C.Informada&nbsp;</th>
					<th width="10%" align="right">C.Pagada&nbsp;</th>
					<th width="10%" align="right">T.Informado&nbsp;</th>
					<th width="10%" align="right">T.Pagado&nbsp;</th>
					<th width="6%" >Movil</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<!--<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px" value="Imprimir..." 
				onclick="javascript: Imprimir();"
			/>-->
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
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