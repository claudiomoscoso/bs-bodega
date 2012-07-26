<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtCodigo':
			var bcentral = document.getElementById('chkBCentral').checked ? 1 : 0;
			document.getElementById('transaccion').src = 'valida.php?modulo=1&bcentral=' + bcentral + '&valor=' + ctrl.value;	
			break;
	}
}

function Change(bodega){
	document.getElementById('cmbCargo').disabled = true;
	document.getElementById('transaccion').src = 'valida.php?modulo=0&valor=' + bodega;
	document.getElementById('detalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=' + bodega;
}

function KeyPress(evento, ctrl){
	var bodega = document.getElementById('cmbBodega').value;
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtCodigo':
				var bcentral = document.getElementById('chkBCentral').checked ? 1 : 0;
				Deshabilita(true);
				AbreDialogo('divMateriales','frmMateriales','buscar_material.php?bcentral=' + bcentral + '&texto=' + ctrl.value);
				break;
			case 'txtCantidad':
				var codigo = document.getElementById('txtCodigo').value;
				var cantidad = document.getElementById('txtCantidad').value;
				if(codigo == ''){
					alert('Debe ingresar el código del material.');
				}else if(parseFloat(cantidad) == 0){
					alert('Debe ingresar la cantidad.');
				}else{					
					document.getElementById('detalle').src = 'agrega.php?accion=G&bodega=' + bodega + '&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + cantidad;
					
					document.getElementById('txtCodigo').value = '';
					document.getElementById('txtDescripcion').value = '';
					document.getElementById('txtUnidad').value = '';
					document.getElementById('hdnStock').value = 0;
					document.getElementById('txtCantidad').value = 0;
					document.getElementById('txtCodigo').focus();
				}
				break;
		}
	}else{
		switch(ctrl.id){
			case 'txtNAjuste':
				switch(tecla){
					case 8:
					case 46:
						return true;
						break;
					default:
						return ValNumeros(evento, ctrl.id, false);
				}
				break;
			case 'txtCantidad':
				var unidad = document.getElementById('txtUnidad').value;
				switch(unidad.toUpperCase()){
					case 'Nº':
					case 'JGO':
					case 'LATA':
					case 'N':
					case 'PAR':
					case 'GLOBAL':
					case 'PZA':
						return ValNumeros(evento, ctrl.id, false);
						break;
					default:
						return ValNumeros(evento, ctrl.id, true);
						break;
				}
				break;
		}
	}
}

function KeyUp(ctrl){
	if(ctrl.id == 'txtNAjuste'){
		if(ctrl.value == ''){ 
			document.getElementById('txtNota').value = '';
			document.getElementById('txtNota').readOnly = false;
			document.getElementById('detalle').src = '../blank.html';
		}		
	}
}

function Load(){
	var bodega = document.getElementById('cmbBodega').value;
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 150);
	document.getElementById('detalle').src = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=' + bodega;
}

function CargarDetalle(){
	var bodega = document.getElementById('cmbBodega').value;
	var najuste = document.getElementById('txtNAjuste').value;
	
	document.getElementById('btnCargar').disabled = true;
	document.getElementById('detalle').src = 'agrega.php?accion=C&bodega=' + bodega + '&usuario=<?php echo $usuario;?>&numero=' + najuste;
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbCargo').disabled = sw;
	if('<?php echo $perfil;?>' == 'j.bodega' || '<?php echo $perfil;?>' == 'informatica'){
		document.getElementById('txtNAjuste').disabled = sw;
		document.getElementById('btnCargar').disabled = sw;
	}
	document.getElementById('txtNota').disabled = sw;
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('txtCantidad').disabled = sw;
	document.getElementById('btnGrabar').disabled = sw;
}

function Guardar(){
	if(detalle.document.getElementById('totfil').value > 0){
		document.getElementById('btnGrabar').disabled = true;
		document.getElementById('frm').submit();
	}else
		alert('Debe ingresar a lo menos una línea detalle.');
}
-->
</script>
<body onload="javascript: Load();">
<div id="divMateriales" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" class="ventana2">
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
											CierraDialogo('divMateriales','frmMateriales');
										"
										onmouseover="javascript: window.status='Cierra la lista de materiales.'; return true"
									title="Cierra la lista de materiales.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMateriales" id="frmMateriales" frameborder="0" scrolling="no" width="100%" height="245px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" target="transaccion" action="graba.php">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" height="1%">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="4%" align="left">&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="95%">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" align="left" ><input name="txtFecha" id="txtFecha" class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y');?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Bodega</td>
								<td width="1%" align="center">:</td>
								<td width="31%">
									<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
										onchange="javascript: Change(this.value);"
									>
									<?php
									$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
									while($rst = mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strBodega"].'" '.($bodega == $rst["strBodega"] ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left" >&nbsp;Cargo</td>
								<td width="1%" align="center">:</td>
								<td>
									<select name="cmbCargo" id="cmbCargo" class="sel-plano" style="width:100%">
									<?php
									$sw=0;
									$stmt = mssql_query("EXEC General..sp_getCargos 2, '$bodega'", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.trim($rst["strCodigo"]).'">'.trim($rst["strCargo"]).'</option>';
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
					<td align="left">&nbsp;Nota</td>
					<td align="center">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="40%">
									<input name="txtNota" id="txtNota" class="txt-plano" style="width:99%" 
										onfocus="javascript: CambiaColor(this.id, true);" 
										onblur="javascript: CambiaColor(this.id, false);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="2%" align="center"><input type="checkbox" name="chkBCentral" id="chkBCentral"/></td>
								<td width="29%">&nbsp;Mostrar materiales en bodega central.</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;N&deg;Gu&iacute;a</td>
								<td width="1%" align="center">:</td>
								<td width="10%">
									<input name="txtNAjuste" id="txtNAjuste" class="txt-plano" style="width:99%;text-align:center" <?php echo ($perfil != 'j.bodega' && $perfil != 'informatica' ? 'disabled' : '');?>
										onfocus="javascript: CambiaColor(this.id, true);" 
										onblur="javascript: CambiaColor(this.id, false);"
										onkeypress="javascript: return KeyPress(event, this);"
										onkeyup="javascript: KeyUp(this);"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">
									<input type="button" name="btnCargar" id="btnCargar" class="boton" style="width:90px" value="Obtener datos" <?php echo ($perfil != 'j.bodega' && $perfil != 'informatica' ? 'disabled' : '');?>
										onclick="javascript: CargarDetalle();"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%" >C&oacute;digo</th>
					<th width="63%" align="left" >Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td >
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width: 97%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);" 
							onkeyup="javascript: 
								if(this.value == ''){ 
									document.getElementById('txtDescripcion').value = '';
									document.getElementById('txtUnidad').value = '';
									document.getElementById('hdnStock').value = 0;
								}
							"
									
						/>
					</td>
					<td ><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
					<td >
						<input name="txtUnidad" id="txtUnidad" class="txt-plano" style="width:96%; text-align:center" readonly="true"/>
						<input type="hidden" name="hdnStock" id="hdnStock" />
					</td>
					<td >
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width: 97%; text-align:right" value="0"
							onblur="javascript: CambiaColor(this.id, false);" 
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"><hr /></td></tr>
	<tr><td align="center" valign="top"><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" ></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="left" height="1%">
			<table width="100%" border="0" cellpadding="1" cellspacing="2">
				<tr>
					<td width="5%"><b>Solicitante: </b></td>
					<?php 
					$stmt = mssql_query("SELECT nombre FROM General..Usuarios WHERE usuario='$usuario'", $cnx);
					if($rst=mssql_fetch_array($stmt)) $nombre = $rst["nombre"];
					mssql_free_result($stmt);
					?>
					<td ><input name="solicitante" id="solicitante" class="txt-sborde" readonly="true" style="width:99%; background-color:#ececec" value="<?php echo $nombre;?>" /></td>
					<td align="right">
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
						<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>"/>
						<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>"/>
						<input type="hidden" name="login" id="login" value="<?php echo $login;?>"/>
						
						<input type="hidden" name="tipodoc" id="tipodoc" value="SM" />
						<input type="hidden" name="accion" id="accion"/>

						<input type="button" name="btnGrabar" id="btnGrabar" class="boton" style="width:90px" value="Guardar" 
							onclick="javascript: Guardar();" 
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
