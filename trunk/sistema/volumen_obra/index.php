<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Volumen de Obra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--


function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
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
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 87);
}

function Buscar(){
	if(document.getElementById('txtFInicio').value != '' && document.getElementById('txtFTermino').value == '')
		alert('Debe ingresar la fecha de término.');
	else if(document.getElementById('txtFInicio').value == '' && document.getElementById('txtFTermino').value != '')
		alert('Debe ingresar la fecha de inicio.');
	else{
		document.getElementById('frm').setAttribute('target', 'resultado');
		if(cmbZona.value=='0')
			str='resultados.php<?php echo $parametros;?>';
		else
			str='resultados2.php<?php echo $parametros;?>';
		document.getElementById('frm').setAttribute('action', str);
		document.getElementById('frm').submit();
		Deshabilita(true);
	}
}

function Deshabilita(sw){
	document.getElementById('cmbContratos').disabled = sw;
	document.getElementById('txtFInicio').disabled = sw;
	document.getElementById('imgFInicio').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('txtFTermino').disabled = sw;
	document.getElementById('imgFTermino').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('cmbEstado').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function Exportar(){
	if(resultado.document.getElementById('totfil')){
		document.getElementById('frm').setAttribute('target', 'transaccion');
		document.getElementById('frm').setAttribute('action', 'exportar.php');
		document.getElementById('frm').submit();
	}else
		alert('No se encontrado información para exportar.');
}

function CambiaTitulos(){
	document.getElementById('Titulos1').style.display='none';
	document.getElementById('Titulos2').style.display='none';
	if(cmbZona.value=='0'){
		document.getElementById('Titulos1').style.display='';
		document.getElementById('TotalI').style.display='';
		document.getElementById('TotalP').style.display='';
	}
	else {
		document.getElementById('Titulos2').style.display='';
		document.getElementById('TotalI').style.display='none';
		document.getElementById('TotalP').style.display='none';
	}
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

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<form name="frm" id="frm" method="post">
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="6%">&nbsp;Contrato</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbContratos" id="cmbContratos" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 4", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;F.Inicio</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFInicio" id="txtFInicio" class="txt-plano" style="width:100%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFInicio', false)"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFInicio&foco=imgFTermino&fecha=' + document.getElementById('txtFInicio').value, '', '30%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFInicio', true)"
						><img id="imgFInicio" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;F.T&eacute;rmino</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtFTermino" id="txtFTermino" class="txt-plano" style="width:100%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre cuadro calendario."
							onblur="javascript: CambiaImagen('imgFTermino', false)"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFTermino&foco=btnBuscar&fecha=' + document.getElementById('txtFTermino').value, '', '51%', '20px');
							"
							onfocus="javascript: CambiaImagen('imgFTermino', true)"
						><img id="imgFTermino" border="0" align="absmiddle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
							<option value="0">No cerradas</option>
							<option value="1">Cerradas</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Zonas</td>
					<td width="1%" align="center">:</td>
					<td width="15%">
						<select name="cmbZona" id="cmbZona" class="sel-plano" style="width:100%" onchange=CambiaTitulos()>
							<option value="0">Todas</option>
							<option value="1">Separadas</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar..." 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
		</form>
	</tr>
	<tr><td><hr /></td></tr>
	<tr style=display:'' id="Titulos1">
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">Item</th>
					<th width="35%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad I.&nbsp;</th>
					<th width="10%" align="right">Cantidad C.&nbsp;</th>
					<th width="10%" align="right">Total I.&nbsp;</th>
					<th width="10%" align="right">Total C.&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr style=display:none id="Titulos2">
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="2%">N&deg;</th>
					<th width="3%">Item</th>
					<th width="11%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="3%">Uni</th>
					<th width="5%" align="right">C.Info&nbsp;</th>
					<th width="5%" align="right">Inf.Z1&nbsp;</th>
					<th width="5%" align="right">Inf.Z2&nbsp;</th>
					<th width="5%" align="right">Inf.Z3&nbsp;</th>
					<th width="5%" align="right">CCobro&nbsp;</th>
					<th width="5%" align="right">CobrZ1&nbsp;</th>
					<th width="5%" align="right">CobrZ2&nbsp;</th>
					<th width="5%" align="right">CobrZ3&nbsp;</th>
					<th width="5%" align="right">T.Info&nbsp;</th>
					<th width="5%" align="right">T.I.Z1&nbsp;</th>
					<th width="5%" align="right">T.I.Z2&nbsp;</th>
					<th width="5%" align="right">T.I.Z3&nbsp;</th>
					<th width="5%" align="right">TCobro&nbsp;</th>
					<th width="5%" align="right">T.C.Z1&nbsp;</th>
					<th width="5%" align="right">T.C.Z2&nbsp;</th>
					<th width="5%" align="right">T.C.Z3&nbsp;</th>
					<th width="1%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" scrolling="yes" width="100%" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="3%">&nbsp;</td>
					<td width="10%">&nbsp;</td>
					<td width="35%" align="left">&nbsp;</td>
					<td width="10%"><input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
			/></td>
					<td width="10%" align="right">&nbsp;</td>
					<td width="10%" align="right">&nbsp;</td>
					<td width="10%" align="right" id=TotalI>0</td>
					<td width="10%" align="right" id=TotalP>0</td>
					<td width="2%">&nbsp;</td>
				</tr>
			</table>

		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
