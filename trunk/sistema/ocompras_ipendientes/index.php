<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra con Ingresos Pendientes</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 55);
}

function Buscar(){
	Deshabilita(true)
	var bodega = document.getElementById('cmbBodega').value;
	var proveedor = document.getElementById('txtproveedor').value;
	var Fecha1 = document.getElementById('txtFchDsd').value;
	var Fecha2 = document.getElementById('txtFchHst').value;
	document.getElementById('frmDetalle').src = 'detalle.php?bodega=' + bodega + '&proveedor=' + proveedor + '&desde=' + Fecha1 + '&hasta=' + Fecha2;
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="z-index:1;position:absolute; width:20%; height:180px; left:400px; top:60px; visibility:hidden">
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
					<td width="6%" >&nbsp;Bodega</td>
					<td width="1%" >:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;Proveedor</td>
					<td width="1%">:</td>
					<td width="8%"><input type="text" name="txtproveedor" id="txtproveedor" class="txt-plano"></td>
					<td width="1%">&nbsp;</td>

					<td width="5%">&nbsp;Desde</td>
					<td width="1%">:</td>
					<td width="8%"><input name="txtFchDsd" id="txtFchDsd" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
					<td width="1%" align="center">
						<a href="#" title="Abre calendario"
							onfocus="javascript: CambiaImagen('imgFchDsd', true);"
							onblur="javascript: CambiaImagen('imgFchDsd', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchDsd&foco=imgFchHst&fecha='+document.getElementById('txtFchDsd').value, false, '60%', '19px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchDsd" id="imgFchDsd" border="0" src="../images/aba.gif"/></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Hasta</td>
					<td width="1%">:</td>
					<td width="8%"><input name="txtFchHst" id="txtFchHst" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>"/></td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario"
							onfocus="javascript: CambiaImagen('imgFchHst', true);"
							onblur="javascript: CambiaImagen('imgFchHst', false);"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFchHst&foco=cmbContrato&fecha='+document.getElementById('txtFchHst').value, false, '70%', '19px');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img name="imgFchHst" id="imgFchHst" border="0" src="../images/aba.gif"/></a>
					</td>

					<td>
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar()"
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
					<th width="10%">O.Compra</th>
					<th width="10%">Fecha</th>
					<th width="10%">C&oacute;digo</th>
					<th width="48%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%" align="right">Cantidad OC.&nbsp;</th>
					<th width="10%" align="right">Cantidad Ingr.&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" scrolling="yes" src="../cargando.php"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
