<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Material Informado</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('datos').setAttribute('height', window.innerHeight - 88);
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
	
	document.getElementById('datos').src = 'resultado.php?fchdsd='+document.getElementById('txtFchDsd').value+'&fchhst='+document.getElementById('txtFchHst').value+'&contrato='+document.getElementById('cmbContrato').value;
}

function getExporta(){
	if(datos.document.getElementById('totfil')){
		if(datos.document.getElementById('totfil').value!=''){
			document.getElementById('exporta').src = 'exporta.php?fchdsd='+document.getElementById('txtFchDsd').value+'&fchhst='+document.getElementById('txtFchHst').value+'&contrato='+document.getElementById('cmbContrato').value;
		}else
			alert('No hay información para exportar.')
	}else
		alert('No hay información para exportar.')
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
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchDsd&foco=imgFchHst&fecha='+document.getElementById('txtFchDsd').value, false, '10%', '19px');
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
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchHst&foco=cmbContrato&fecha='+document.getElementById('txtFchHst').value, false, '34%', '19px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchHst" id="imgFchHst" border="0" src="../images/aba.gif"/></a>
					</td>
					<td width="1%">&nbsp;</td>
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
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%" align="left" >&nbsp;Localizacion</th>
					<th width="10%" align="left">&nbsp;ITO</th>
					<th width="9%">N&deg;ODT</th>
					<th width="15%" align="left">&nbsp;Trabajo</th>
					<th width="7%">Fecha</th>
					<th width="7%">C&oacute;digo</th>
					<th width="15%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="5%">Unidad</th>
					<th width="7%">Cantidad</th>
					<th width="5%">Movil</th>
					<th width="5%">Precio</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="datos" id="datos" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes"></iframe></td>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: getExporta();"
			/>
		</td>
	</tr>
</table>
<iframe name="exporta" id="exporta" style="display: none" ></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>