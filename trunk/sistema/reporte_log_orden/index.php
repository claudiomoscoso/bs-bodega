<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Producci&oacute;n entre Fecha</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	switch(ctrl.id){
		case 'txtFchDsd':
		case 'txtFchHst':
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
		case 'txtEP':
		case 'txtCertificado':
			return ValNumeros(evento, ctrl.id, false);
			break;
	}
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 136);
}

function Deshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('imgFchDsd').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('imgFchHst').style.visibility = sw ? 'hidden' : 'visible';
	document.getElementById('txtEPago').disabled = sw;
	document.getElementById('txtCertificado').disabled = sw;
	document.getElementById('cmbCerradas').disabled = sw;
	document.getElementById('chkDiferencias').disabled = sw;
	document.getElementById('cmbTipo').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function getBuscar(){
	
	var contrato = document.getElementById('cmbContrato').value;
	var fdesde = document.getElementById('txtFchDsd').value;
	var fhasta = document.getElementById('txtFchHst').value; 
	if(fdesde == '' && fhasta != '')
		alert('Debe ingresar la fecha de inicio.');
	else if(fdesde != '' && fhasta == '')
		alert('Debe ingresar la fecha de término.');
	else{
		Deshabilita(true);
		document.getElementById('resultado').src = 'res_detallado.php?contrato=' + contrato + '&fchdsd=' + fdesde + '&fchhst=' + fhasta;
		}
	}
}

function getExporta(){
	if(resultado.document.getElementById('totfil')){
		var tipo = document.getElementById('cmbTipo').value;
		var contrato = document.getElementById('cmbContrato').value;
		var fdesde = document.getElementById('txtFchDsd').value;
		var fhasta = document.getElementById('txtFchHst').value;
		var epago = document.getElementById('txtEPago').value;
		var certificado = document.getElementById('txtCertificado').value;
		var cerradas = document.getElementById('cmbCerradas').value;
		var diferencias = document.getElementById('chkDiferencias').checked ? 1 : 0;
		switch(parseInt(tipo)){
			case 0:
				document.getElementById('exporta').src = 'exp_detallado.php?contrato=' + contrato + '&fchdsd=' + fdesde + '&fchhst=' + fhasta + '&epago=' + epago + '&certificado=' + certificado + '&cerradas=' + cerradas + '&diferencias=' + diferencias;
				break;
			case 1:
				document.getElementById('exporta').src = 'exp_resumen.php?contrato=' + contrato + '&fchdsd=' + fdesde + '&fchhst=' + fhasta + '&epago=' + epago + '&certificado=' + certificado + '&cerradas=' + cerradas + '&diferencias=' + diferencias;
				break;
		}
	}else
		alert('No se ha encontrado información para exportar.');
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
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="5%">&nbsp;Contrato</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>					</td>
					
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;F.Desde</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtFchDsd" id="txtFchDsd" class="txt-plano" style="width:100%; text-align:center" value="<?php echo date('d/m/Y');?>"
							onblur="javascript: CambiaColor(this.id, false)"
							onfocus="javascript: CambiaColor(this.id, true)"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario"
							onfocus="javascript: CambiaImagen('imgFchDsd', true);"
							onblur="javascript: CambiaImagen('imgFchDsd', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFchDsd&foco=imgFchHst&fecha='+document.getElementById('txtFchDsd').value, false, '38%', '20px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchDsd" id="imgFchDsd" border="0" src="../images/aba.gif"/></a>					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;F.Hasta</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtFchHst" id="txtFchHst" class="txt-plano" style="width:100%; text-align:center" value="<?php echo date('d/m/Y');?>"
							onblur="javascript: CambiaColor(this.id, false)"
							onfocus="javascript: CambiaColor(this.id, true)"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario"
							onfocus="javascript: CambiaImagen('imgFchHst', true);"
							onblur="javascript: CambiaImagen('imgFchHst', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchHst&foco=cmbContrato&fecha='+document.getElementById('txtFchHst').value, false, '60%', '20px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchHst" id="imgFchHst" border="0" src="../images/aba.gif"/></a>					</td>
					<td width="0%" align="right">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no"></iframe></td>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: getExporta();"
			/>
		</td>
	</tr>
</table>
<iframe name="exporta" id="exporta" style="display:none" ></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
