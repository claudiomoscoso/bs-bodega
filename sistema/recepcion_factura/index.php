<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recepci&oacute;n de Facturas</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.value != ''){
		if(ctrl.id == 'txtProveedor'){
			document.getElementById('exportar').src = 'busqueda_rapida.php?modulo=PRV&prov=' + ctrl.value;
		}else if(ctrl.id == 'txtCargo'){
			document.getElementById('exportar').src = 'busqueda_rapida.php?modulo=CRG&texto=' + ctrl.value;
		}
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtProveedor':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_proveedor.php?texto=' + ctrl.value + '&display=none');
				break;
			case 'txtCargo':
				Deshabilita(true);
				AbreDialogo('divBuscador', 'frmBuscador', 'buscar_cargo.php?texto=' + ctrl.value);
				break;
		}
	}
}

function Load(){
	document.getElementById('frmListado').setAttribute('height', window.innerHeight - 115);
}

function Buscar(){
	var mes = document.getElementById('cmbMes').value;
	var ano = document.getElementById('cmbAno').value;
	if(mes != 0 && ano == 0)
		alert('Debe seleccionar el año.');
	else if(mes == 0 && ano == 0)
		alert('Debe seleccionar el mes.');
	else{
		Deshabilita(true);
		var singreso = (document.getElementById('chkSIng').checked ? 1 : 0);
		var ndoc = document.getElementById('txtNumDoc').value;
		var norden = document.getElementById('txtNumOC').value;
		var proveedor = document.getElementById('hdnProveedor').value;
		var cargo = document.getElementById('hdnCargo').value;
		
		document.getElementById('frmListado').src = 'resultado.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&mes=' + mes + '&ano=' + ano +
		'&numdoc=' + ndoc + '&numoc=' + norden + '&sing=' + singreso + '&proveedor=' + proveedor + '&cargo=' + cargo;
	}
}

function Deshabilita(sw){
	document.getElementById('cmbMes').disabled=sw;
	document.getElementById('cmbAno').disabled=sw;
	document.getElementById('txtNumDoc').disabled=sw;
	document.getElementById('txtProveedor').disabled=sw;
	document.getElementById('txtCargo').disabled=sw;
	document.getElementById('txtNumOC').disabled=sw;
	document.getElementById('chkSIng').disabled = sw;
	document.getElementById('btnExportar').disabled=sw;
	document.getElementById('btnBuscar').disabled=sw;
	if(document.getElementById('btnNvaFact')){
		document.getElementById('btnNvaFact').disabled=sw;
		document.getElementById('btnNvaFactSOC').disabled=sw;
	}
}

function getExportar(){
	if(frmListado.document.getElementById('totfil')){
		var mes = document.getElementById('cmbMes').value;
		var ano = document.getElementById('cmbAno').value;
		var singreso = (document.getElementById('chkSIng').checked ? 1 : 0);
		var ndoc = document.getElementById('txtNumDoc').value;
		var norden = document.getElementById('txtNumOC').value;
		var proveedor = document.getElementById('hdnProveedor').value;
		var cargo = document.getElementById('hdnCargo').value;

		document.getElementById('exportar').src = 'exportar.php?usuario=<?php echo $usuario;?>&mes=' + mes + '&ano=' + ano + '&numdoc=' + ndoc +'&proveedor=' + proveedor + 
		'&numoc=' + norden + '&sing=' + singreso;
	}else
		alert('Debe buscar para poder exportar');
}
-->
</script>
<body onload="javascript: Load();">
<div id="divFactura" style="position:absolute; width:80%; left:10%; top:5px; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" class="ventana">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_sup.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divFactura','frmFactura');"
										onmouseover="javascript: window.status='Cierra ventana de facturas.'; return true"
									title="Cierra ventana de facturas.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Factura</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmFactura" id="frmFactura" frameborder="0" scrolling="no" width="100%" height="215px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divBuscador" style="z-index: 1; position:absolute; top:0px; left:13%; width:75%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" class="ventana">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_sup.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
									    Deshabilita(false);
										CierraDialogo('divBuscador', 'frmBuscador');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Buscador</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmBuscador" id="frmBuscador" frameborder="0" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divPreview" style="z-index: 1; position:absolute; top:0px; left:13%; width:75%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" class="ventana2">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_sup.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
									    Deshabilita(false);
										CierraDialogo('divPreview', 'frmPreview');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Vista del documento</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmPreview" id="frmPreview" frameborder="0" scrolling="no" width="100%" height="250px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="7%" align="left">&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="92%">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="12%" align="left">
									<select name="cmbMes" id="cmbMes" class="sel-plano" style="width:100%">
										<option value="00" >--</option>
										<option value="01" <?php echo date('m')=='01' ? 'selected' : '';?>>Enero</option>
										<option value="02" <?php echo date('m')=='02' ? 'selected' : '';?>>Febrero</option>
										<option value="03" <?php echo date('m')=='03' ? 'selected' : '';?>>Marzo</option>
										<option value="04" <?php echo date('m')=='04' ? 'selected' : '';?>>Abril</option>
										<option value="05" <?php echo date('m')=='05' ? 'selected' : '';?>>Mayo</option>
										<option value="06" <?php echo date('m')=='06' ? 'selected' : '';?>>Junio</option>
										<option value="07" <?php echo date('m')=='07' ? 'selected' : '';?>>Julio</option>
										<option value="08" <?php echo date('m')=='08' ? 'selected' : '';?>>Agosto</option>
										<option value="09" <?php echo date('m')=='09' ? 'selected' : '';?>>Septiembre</option>
										<option value="10" <?php echo date('m')=='10' ? 'selected' : '';?>>Octubre</option>
										<option value="11" <?php echo date('m')=='11' ? 'selected' : '';?>>Noviembre</option>
										<option value="12" <?php echo date('m')=='12' ? 'selected' : '';?>>Diciembre</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">
									<select name="cmbAno" id="cmbAno" class="sel-plano" style="width:100%">
									<?php
									for($ano=2006; $ano<=date('Y'); $ano++){?>
										<option value="<?php echo $ano;?>" <?php echo date('Y')==$ano ? 'selected' : '';?>><?php echo $ano;?></option>
									<?php
									}
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="7%" align="left" nowrap="nowrap">&nbsp;N&deg;Factura</td>
								<td width="1%" align="center">:</td>
								<td width="10%" align="left">
									<input name="txtNumDoc" id="txtNumDoc" class="txt-plano" style="width:100%; text-align:center" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="9%" >&nbsp;N&deg;O.Compra</td>
								<td width="1%" align="center">:</td>
								<td width="10%" align="left">
									<input name="txtNumOC" id="txtNumOC" class="txt-plano" style="width:100%; text-align:center" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="2%" align="center"><input type="checkbox" name="chkSIng" id="chkSIng" /></td>
								<td width="1%">&nbsp;</td>
								<td width="33%">&nbsp;Sin ingresos (Softland)</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td >&nbsp;Proveedor</td>
					<td >:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="32%">
									<input type="hidden" name="hdnProveedor" id="hdnProveedor" />
									<input name="txtProveedor" id="txtProveedor" class="txt-plano" style="width:99%" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnProveedor').value = ''"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Cargo</td>
								<td width="1%" align="center">:</td>
								<td width="32%">
									<input type="hidden" name="hdnCargo" id="hdnCargo" />
									<input name="txtCargo" id="txtCargo" class="txt-plano" style="width:99%" 
										onblur="javascript: Blur(this);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: KeyPress(event, this);"
										onkeyup="javascript: if(this.value == '') document.getElementById('hdnCargo').value = ''"
									/>
								</td>
								
								<td width="1%">&nbsp;</td>
								<td width="0%" align="right">
									<input type="button" id="btnBuscar" class="boton" style="width:80px;" value="Buscar" 
										onclick="javascript: Buscar();"
									/>
									<input type="button" id="btnExportar" class="boton" style="width:80px;" value="Exportar..." 
										onclick="javascript: getExportar();"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="2%">&nbsp;</th>
					<th width="5%">N&deg;</th>
					<th width="8%">Fecha</th>
					<th width="9%">N&deg;OC</th>
					<th width="25%" align="left">&nbsp;Proveedor</th>
					<th width="10%" align="left">&nbsp;T.Documento</th>
					<th width="9%">N&deg;Doc.</th>
					<th width="10%" align="right">Monto&nbsp;</th>
					<th width="10%" align="left">&nbsp;Usuario</th>
					<th width="10%" align="left">&nbsp;Estado</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><iframe name="frmListado" id="frmListado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td align="right"><hr /></td></tr>
	<?php
	if($perfil=='recepcion' || $perfil=='informatica' || $perfil=='adquisiciones' || $perfil=='bodega.e'){?>
	<tr>
		<td colspan="9" align="right">
			
			
			<input type="button" name="btnNvaFact" id="btnNvaFact" class="boton" style="width:90px" value="Nva. Factura..." 
				onclick="javascript:
					Deshabilita(true);
					AbreDialogo('divFactura','frmFactura','factura.php?usuario=<?php echo $usuario;?>');
				"
			/>
			<input type="button" name="btnNvaFactSOC" id="btnNvaFactSOC" class="boton" style="width:120px" value="Nva. Factura Sin OC" 
				onclick="javascript:
					Deshabilita(true);
					AbreDialogo('divFactura','frmFactura','factura_sin_oc.php?usuario=<?php echo $usuario;?>');
				"
			/>
		</td>
	</tr>
	<?php
	}?>
</table>
<iframe id="exportar" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
