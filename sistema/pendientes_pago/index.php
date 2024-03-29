<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pendientes de Pagos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 117);
	setMuestraCabecera(document.getElementById('cmbContrato').value);
}

function Deshabilita(sw){
	document.getElementById('imgFchDsd').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('imgFchHst').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function getBuscar(){
	Deshabilita(true);
	document.getElementById('resultado').src='resultado.php?fchdsd='+document.getElementById('txtFchDsd').value+'&fchhst='+document.getElementById('txtFchHst').value+'&contrato='+document.getElementById('cmbContrato').value;
}

function getExporta(){
	if(resultado.document.getElementById('totfil')){
		if(resultado.document.getElementById('totfil').value!='')
			document.getElementById('exporta').src='exporta.php?fchdsd='+document.getElementById('txtFchDsd').value+'&fchhst='+document.getElementById('txtFchHst').value+'&contrato='+document.getElementById('cmbContrato').value;
		else
			alert('No hay información para exportar.')
	}else
		alert('No hay información para exportar.')
}

function setMuestraCabecera(contrato){
	if(contrato == '13000'){
		document.getElementById('cabecera1').style.display = 'none';
		document.getElementById('cabecera2').style.display = '';
	}else{
		document.getElementById('cabecera2').style.display = 'none';
		document.getElementById('cabecera1').style.display = '';
	}
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="z-index:1;position:absolute; width:20%; height:150px; left:108px; top:60px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									title="Cierra calendario.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="140px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="9%">&nbsp;Fecha Desde</td>
					<td width="1%">:</td>
					<td width="10%"><input name="txtFchDsd" id="txtFchDsd" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario"
							onfocus="javascript: CambiaImagen('imgFchDsd', true);"
							onblur="javascript: CambiaImagen('imgFchDsd', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchDsd&foco=imgFchHst&fecha='+document.getElementById('txtFchDsd').value, false, '10%', '20px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchDsd" id="imgFchDsd" border="0" src="../images/aba.gif"/></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="9%">&nbsp;Fecha Hasta</td>
					<td width="1%">:</td>
					<td width="10%"><input name="txtFchHst" id="txtFchHst" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario"
							onfocus="javascript: CambiaImagen('imgFchHst', true);"
							onblur="javascript: CambiaImagen('imgFchHst', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchHst&foco=cmbContrato&fecha='+document.getElementById('txtFchHst').value, false, '34%', '20px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchHst" id="imgFchHst" border="0" src="../images/aba.gif"/></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Contrato</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%"
							onchange="javascript: setMuestraCabecera(this.value);"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr id="cabecera1">
					<th width="3%">N&deg;</th>
					<th width="13%" align="left">&nbsp;Localidad</th>
					<th width="13%" align="left">&nbsp;Inspector</th>
					<th width="9%">N&deg; ODT</th>
					<th width="8%">Fecha</th>
					<th width="5%">&Iacute;tem</th>
					<th width="14%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="7%" align="right">Cantidad&nbsp;</th>
					<th width="6%">Unidad</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="10%" align="left">&nbsp;Estado</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr id="cabecera2">
					<th width="3%">N&deg;</th>
					<th width="10%">N&deg; ODT</th>
					<th width="10%">Fax</th>
					<th width="15%" align="left">&nbsp;Direcci&oacute;n</th>
					<th width="10%" align="left">&nbsp;Comuna</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="5%" >EP</th>
					<th width="8%" >Env&iacute;o</th>
					<th width="15%" align="left">&nbsp;Inspector</th>
					<th width="15%" align="left">&nbsp;Tipo</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html"></iframe></td>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td align="right"><b>TOTAL</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="10%"><input name="txtTotal" id="txtTotal" class="txt-plano" style="width:99%; text-align:right" readonly="true" value="0" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: getExporta();"
			/>
		</td>
	</tr>
</table>
<iframe name="exporta" id="exporta" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>