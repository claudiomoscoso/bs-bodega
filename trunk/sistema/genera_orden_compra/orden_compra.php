<?php
include '../autentica.php';
include '../conexion.inc.php';

$numSM = $_POST["numSM"];
$numusuSM = $_POST["numusuSM"];
$bodSM = $_POST["bodSM"];
$cargo = $_POST["cargo"];
mssql_query("EXEC Bodega..sp_setTMPDetalleOrdenCompra 3, '$usuario'", $cnx);

$stmt = mssql_query("SELECT id, dblFactor FROM Impuesto ORDER BY id", $cnx);
while($rst = mssql_fetch_array($stmt)){ 
	if($rst["id"] == 1) $fact_iva = $rst["dblFactor"]; else $fact_10 = $rst["dblFactor"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC General..sp_getCargos 0, NULL, '$cargo'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$despachar_en = $rst["strDireccion"];
	$desccargo = $rst["strCargo"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getSolicitudMaterial 0, $numSM", $cnx);
if($rst = mssql_fetch_array($stmt)) {
	$obs_solicitud = $rst["strObservacion"];
	$obs_admin = $rst["strObsAdmin"];
}	
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra Autom&aacute;tica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 253);
	document.getElementById('frmDetalle').src = "detalle_orden_compra.php?usuario=<?php echo $usuario;?>";
	document.getElementById('proveedor').focus();
}

function Envia(accion){
	if(accion=='A'){
		document.getElementById('frm').target='';
		document.getElementById('frm').action='solicitud_material.php';
		document.getElementById('frm').submit();
	}else if(accion=='F'){
		var sw = 0, totfil = frmDetalle.document.getElementById('totfil').value;
		for(i=1; i<=totfil; i++){
			if(frmDetalle.document.getElementById('valor' + i).value > 0){
				sw = 1;
				break;
			}
		}
		if(sw == 1){
			document.getElementById('frm').target='valido';
			document.getElementById('frm').action='graba.php';
			document.getElementById('frm').submit();
		}else
			alert('Debe ingresar un valor a todos los ítem del detalle.');
	}
}

function ReCalc(neto, fact){
	var imp = neto * fact;
	document.getElementById('iva').value = Math.round(imp);
	neto *= 10; neto /= 10;
	imp *= 10; imp /= 10;
	document.getElementById('totalOC').value = Math.round(neto + imp);
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		if(ctrl == 'proveedor'){
			Deshabilita(true);
			AbreDialogo('BProveedor', 'ifrmP', 'buscar_proveedor.php?texto=' + document.getElementById(ctrl).value + '&modulo=OCA');
		}
	}
}

function BusqRapida(tipo, bodega, valor){
	document.getElementById('valido').src='valida.php?tipo='+tipo+'&bodega='+bodega+'&valor='+valor;	
}

function Deshabilita(sw){
	document.getElementById('forma_pago').disabled=sw;
	document.getElementById('proveedor').disabled=sw;
	document.getElementById('ccosto').disabled=sw;
	document.getElementById('tipodoc').disabled=sw;
	document.getElementById('email').disabled=sw;
	document.getElementById('nota').disabled=sw;
	document.getElementById('despachar').disabled=sw;
	var totfil=frmDetalle.document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		frmDetalle.document.getElementById('valor'+i).disabled=sw;
	}
	document.getElementById('btnAnt').disabled=sw;
	document.getElementById('btnFin').disabled=sw;
}

function LimpiaDatosProveedor(){
	document.getElementById('forma_pago').selectedIndex=0;
	document.getElementById('codigo_proveedor').value = '';
	document.getElementById('proveedor').value = '';
	document.getElementById('direccion').value = '';
	document.getElementById('comuna').value = '';
	document.getElementById('telefono').value = '';
	document.getElementById('fax').value = '';
	document.getElementById('atencion').value = '';
	document.getElementById('email').value = '';
	document.getElementById('tipodoc').selectedIndex=0;
}

function Enviar_EMail(numSM, codigo, proveedor, contacto, email, cargo, numusuSM){
	if(proveedor=='')
		alert('Debe ingresar el proveedor');
	else if(email=='')
		alert('Debe ingresar el email');
	else
		document.getElementById('valido').src='envia_correo.php?usuario=<?php echo $usuario;?>&modulo=OCA&codigo='+codigo+'&proveedor='+proveedor+'&contacto='+contacto+'&email='+email+'&cargo='+cargo+'&numusuSM='+numusuSM+'&numSM='+numSM;
}
-->
</script>
<body onload="javascript: Load();">
<div id="BProveedor" style="z-index: 1; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
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
											CierraDialogo('BProveedor','ifrmP');
										"
										onmouseover="javascript: window.status='Cierra la lista de proveeedores.'; return true"
									title="Cierra la lista de proveeedores.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Proveedores</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="ifrmP" id="ifrmP" frameborder="0" scrolling="no" width="100%" height="240px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post">
<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr>
		<td >
			<table width="100%" border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td width="8%"><b>&nbsp;F. Pago</b></td>
					<td width="1%"><b>:</b></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="30%">
									<select name="forma_pago" id="forma_pago" class="sel-plano" style="width:100%" 
										onChange="javascript: document.getElementById('strFPago').value=this.options[this.selectedIndex].text;"
									>
									<?php	
									$stmt = mssql_query("EXEC Bodega..sp_getFormasPago 'ALL'");
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%"><b>&nbsp;Fecha</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%">&nbsp;<?php echo date('d/m/Y');?></td>
								<td width="1%">&nbsp;</td>
								<td width="10%"><b>&nbsp;Proveedor</b></td>
								<td width="1%"><b>:</b></td>
								<td width="0%">
									<input name="proveedor" id="proveedor" style="width:99%;" class="txt-plano" 
										onKeyPress="javascript: return KeyPress(event, this.id);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onblur="javascript: BusqRapida('P', '', this.value); CambiaColor(this.id, false);"
									>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td ><b>&nbsp;Direcci&oacute;n</b></td>
					<td><b>:</b></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="23%"><input name="direccion" id="direccion" readonly="true" class="txt-plano" style="width:99%"></td>
								<td width="8%"><b>&nbsp;Comuna</b></td>
								<td width="1%"><b>:</b></td>
								<td width="22%"><input name="comuna" id="comuna" readonly="true" class="txt-plano" style="width:100%"></td>
								<td width="1%">&nbsp;</td>
								<td width="10%"><b>&nbsp;Tel&eacute;fono</b></td>
								<td width="1%"><b>:</b></td>
								<td width="15%"><input name="telefono" id="telefono" readonly="true" class="txt-plano" style="width:100%"></td>
								<td width="1%">&nbsp;</td>
								<td width="3%"><b>&nbsp;Fax</b></td>
								<td width="1%"><b>:</b></td>
								<td width="15%"><input name="fax" id="fax" readonly="true" class="txt-plano" style="width:98%"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>&nbsp;Atenci&oacute;n</b></td>
					<td><b>:</b></td>
					<td >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="45%">
									<input name="atencion" id="atencion" class="txt-plano" style="width:100%"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onblur="javascript: CambiaColor(this.id, false);"
									>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" nowrap="nowrap"><b>&nbsp;E-Mail</b></td>
								<td width="1%"><b>:</b></td>
								<td width="30%" align="left">
									<input name="email" id="email" class="txt-plano" style="width:100%"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onblur="javascript: CambiaColor(this.id, false);"
									>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" nowrap="nowrap"><b>&nbsp;T.Documento</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%">
									<select name="tipodoc" id="tipodoc" class="sel-plano" style="width:100%" onChange="
										javascript:
											switch(parseInt(this.value)){
												case 0:
													ReCalc(document.getElementById('neto').value, 0);
													break;
												case 1:
													document.getElementById('CellNeto').value='NETO';
													document.getElementById('CellImp').value='I.V.A.';
													ReCalc(document.getElementById('neto').value, <?php echo $fact_iva;?>);
													break;
												case 2:
													document.getElementById('CellNeto').value='A Pagar';
													document.getElementById('CellImp').value='(-)Impuesto 10%';
													ReCalc(document.getElementById('neto').value, <?php echo $fact_10;?>);
													break;
											}
									">
										<option value="1">Factura</option>
										<option value="2">Boleta</option>
										<option value="0">Excenta</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td ><b>&nbsp;Cargo</b></td>
					<td><b>:</b></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="27%"><input class="txt-plano" style="width:100%" readonly="true" value="<?php echo $desccargo;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;C.Costo</b></td>
								<td width="1%"><b>:</b></td>
								<td width="18%">
									<select name="ccosto" id="ccosto" class="sel-plano" style="width:100%">
									<?php	
									$rst=mssql_query("EXEC Bodega..sp_getCentrosCosto 'ALL'");
									while($ccosto=mssql_fetch_array($rst)){
										echo '<option value="'.$ccosto["strCodigo"].'">'.$ccosto["strDetalle"].'</option>';
									}
									mssql_free_result($rst);?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="2%">
									<input type="checkbox" name="despachar" id="despachar" 
										onclick="javascript: 
											if(this.checked)
												document.getElementById('despachar_en').value='<?php echo htmlentities($despachar_en);?>';
											else
												document.getElementById('despachar_en').value='';
										"
									/>
								</td>
								<td width="13%"><b>&nbsp;Despachar en</b></td>
								<td width="1%"><b>:</b></td>
								<td width="28%"><input name="despachar_en" id="despachar_en" class="txt-plano" style="width:99%" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>&nbsp;Nota</b></td>
					<td ><b>:</b></td>
					<td>
						<input name="nota" id="nota" class="txt-plano" style="width:100%"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onblur="javascript: CambiaColor(this.id, false);"
						>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td >
			<table width="100%" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="45%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Valor&nbsp;</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" ></iframe></td></tr>
	<tr>
		<td align="right" >
			<table width="100%" border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td width="60%" valign="top">
						<b>&nbsp;Observaci&oacute;n Solicitud de Materiales: </b><?php echo $obs_solicitud;?>
						<?php if($obs_admin <> "") echo '<br><b>&nbsp;Observaci&oacute;n del Administrador: </b>'.$obs_admin;?>
					</td>
					<td valign="top">
						<table width="100%" border="0" cellpadding="1" cellspacing="1">
							<tr>
								<td width="0%" align="right"><input name="CellNeto" id="CellNeto" class="txt-sborde" style="width:100%; border:0px; text-align:right; font-family:Tahoma, Arial, 'Times New Roman', 'Arial Black'; font-weight:bold; font-size:12px; background-color:#ececec" readonly="true" value="NETO"></td>
								<td width="1%"><b>:</b></td>
								<td width="30%"><input name="neto" id="neto" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="0" /></td>
							</tr>
							<tr>
								<td align="right"><input name="CellImp" id="CellImp" class="txt-sborde" style="width:100%; border:0px; text-align:right; font-family:Tahoma, Arial, 'Times New Roman', 'Arial Black'; font-weight:bold; font-size:12px; background-color:#ececec" readonly="true" value="I.V.A."></td>
								<td><b>:</b></td>
								<td><input name="iva" id="iva" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="0" ></td>
							</tr>
							<tr>
								<td align="right"><b>TOTAL</b></td>
								<td><b>:</b></td>
								<td><input name="totalOC" id="totalOC" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="0" ></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:2px"><hr></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<input type="button" name="btnAnt" id="btnAnt" class="boton" style="width:90px" value="&lt;&lt; Anterior" 
							onClick="javascript: Envia('A');"
						>
							
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
						<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
						<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
						<input type="hidden" name="login" id="login" value="<?php echo $login;?>">
						
						<input type="hidden" name="numSM" id="numSM" value="<?php echo $numSM;?>">
						<input type="hidden" name="numusuSM" id="numusuSM" value="<?php echo $numusuSM;?>">
						<input type="hidden" name="bodSM" id="bodSM" value="<?php echo $bodSM;?>">
						<input type="hidden" name="cargo" id="cargo" value="<?php echo $cargo;?>">
						<input type="hidden" name="codigo_proveedor" id="codigo_proveedor">
					</td>
					<td align="right">
						<input type="button" name="btnCotizar" id="btnCotizar" class="boton" value="Cotizar" style="width:90px" 
							onclick="javascript: 
								if(document.getElementById('codigo_proveedor').value=='')
									alert('Debe ingresar el proveedor');
								else if(document.getElementById('atencion').value=='')
									alert('Debe ingresar el contacto');
								else
									Enviar_EMail(document.getElementById('numSM').value, document.getElementById('codigo_proveedor').value, document.getElementById('proveedor').value, document.getElementById('atencion').value, document.getElementById('email').value, document.getElementById('cargo').value, document.getElementById('numusuSM').value);
							"
						/>
						<input type="button" name="btnFin" id="btnFin" class="boton" value="Finalizar" disabled="disabled" style="width:90px" 
							onClick="javascript: 
								if(document.getElementById('codigo_proveedor').value==''){
									alert('Debe seleccionar un proveedor.');
									document.getElementById('proveedor').focus();
								}else{
									sw=0;
									for(i=1; i<=frmDetalle.document.getElementById('totfil').value; i++){
										if(frmDetalle.document.getElementById('valor'+i).value=='0'){
											sw=1;
											break;
										}
									}
									if(sw==1){
										alert('Debe ingresar el valor de los item de la Orden de Compra.');
										document.getElementById('cant1').focus();
									}else{
										Envia('F');
									}
								}
						">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<iframe name="valido" id="valido" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>