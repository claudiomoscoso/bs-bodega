<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vales de Consumo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	var bodega = document.getElementById('cmbBodega').value;
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtCodigo':
			document.getElementById('valido').src = 'valida.php?modulo=1&bodega=' + bodega + '&valor=' + ctrl.value;
			break;
		case 'txtCCosto':
			document.getElementById('valido').src = 'valida.php?modulo=2&bodega=' + bodega + '&valor=' + ctrl.value;
			break;
	}
}

function Load(){
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 120);
	document.getElementById('detalle').src = "agrega.php<?php echo $parametros;?>";
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'cmbFecha':
				document.getElementById('cmbBodega').focus();
				break;
			case 'cmbBodega':
				document.getElementById('cmbResponsable').focus();
				break;
			case 'cmbResponsable':
				document.getElementById('txtCodigo').focus();
				break;
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?bodega=' + document.getElementById('cmbBodega').value + '&texto=' + ctrl.value);
				break;
			case 'txtCCosto':
				Deshabilita(true);
				AbreDialogo('divCCosto', 'frmCCosto', 'buscar_ccosto.php?bodega=' + document.getElementById('cmbBodega').value + '&texto=' + ctrl.value);
				break;
			case 'txtCantidad':
				if(document.getElementById('txtCodigo').value == ''){
					alert('Debe ingresar el código del material.');
					document.getElementById('txtCodigo').focus();
				}else if(document.getElementById('txtCCosto').value == ''){
					alert('Debe ingresar el código del centro de costo.');
					document.getElementById('txtCCosto').focus();
				}else if(document.getElementById('txtCantidad').value == 0){
					alert('Debe ingresar la cantidad.');
					document.getElementById('txtCantidad').focus();
				}else if(document.getElementById('txtDescripcion').value == ''){
					alert('El código del material ingresado no es valido.');
					document.getElementById('txtCodigo').focus();
				}else if(document.getElementById('hdnCCosto').value == ''){
					alert('El código del centro de costo ingresado no es valido.');
					document.getElementById('txtCCosto').focus();
				}else if(parseFloat(ctrl.value) > parseFloat(document.getElementById('hdnStock').value)){
					alert('El stock actual es menor (' + document.getElementById('hdnStock').value + ')');
				}else{
					var bodega = document.getElementById('cmbBodega').value;
					document.getElementById('frm').setAttribute('action', 'agrega.php?accion=G&usuario=<?php echo $usuario;?>&bodega=' + bodega);
					document.getElementById('frm').setAttribute('target', 'detalle');
					document.getElementById('frm').submit();
					
					ctrl.readOnly = true;
					document.getElementById('txtCodigo').value = '';
					document.getElementById('txtDescripcion').value = '';
					document.getElementById('txtUnidad').value = '';
					document.getElementById('hdnStock').value = '';
					document.getElementById('txtCCosto').value = '';
					document.getElementById('hdnCCosto').value = '';
					ctrl.value = '';
					ctrl.readOnly = false;
					document.getElementById('txtCodigo').focus();
					break;
				}
		}
	}else{
		switch(ctrl.id){
			case 'txtCantidad':
				switch(document.getElementById('txtUnidad').value){
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

function Graba(){
	if(detalle.document.getElementById('totfil').value == 0){
		alert('Debe ingresar el detalle del Vale de Consumo.')
	}else{
		document.getElementById('frm').setAttribute('target', 'valido');
		document.getElementById('frm').setAttribute('action', 'graba.php');
		document.getElementById('frm').submit();
	}	
}

function Deshabilita(sw){
	document.getElementById('cmbFecha').disabled = sw;
	document.getElementById('txtNumero').disabled = sw;
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbResponsable').disabled = sw;
	
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('txtCCosto').disabled = sw;
	document.getElementById('txtCantidad').disabled = sw;
	
	document.getElementById('btnGuardar').disabled = sw;
}

function getResponsables(ctrl){
	document.getElementById('cmbResponsable').disabled = true;
	document.getElementById('valido').src = 'valida.php?modulo=0&bodega=' + ctrl.value;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divMateriales" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
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
										CierraDialogo('divMateriales','frmMateriales');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMateriales" id="frmMateriales" frameborder="0" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divCCosto" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
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
										CierraDialogo('divCCosto','frmCCosto');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmCCosto" id="frmCCosto" frameborder="0" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" target="detalle">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<select name="cmbFecha" id="cmbFecha" class="sel-plano" style="width:100px"
							onkeypress="javascript: return KeyPress(event, this)"
						>
						<?php
						$dia=date('j');
						$mes=date('n');
						$ano=date('Y');
						for($i = 15; $i > 0; $i--){
							echo '<option>'.($dia < 10 ? '0'.$dia : $dia).'/'.($mes < 10 ? '0'.$mes : $mes)."/$ano".'</option>';
							$dia--;
							if($dia==0){
								$mes--;
								if($mes==0){ 
									$mes=12;
									$ano--;
								}
								if($mes==1 || $mes==3 || $mes==5 || $mes==7 || $mes==8 || $mes==10 || $mes==12)
									$dia=31;
								elseif($mes==4 || $mes==6 || $mes==9 || $mes==11)
									$dia=30;
								else
									$dia = ($ano % 4)==0 ? 29 : 28;
							}
						}?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;N&uacute;mero</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtNumero" id="txtNumero" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="6%">&nbsp;Bodega</td>
					<td width="1%" align="center">:</td>
					<td width="24%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
							onchange="javascript: getResponsables(this);"
							onkeypress="javascript: return KeyPress(event, this)"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="8%">&nbsp;Responsable</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbResponsable" id="cmbResponsable" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this)"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getPersonalObra 5, '$bodega'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strRut"].'">'.$rst["strNombre"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
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
					<th width="10%">C&oacute;digo</th>
					<th width="53%">Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="10%">C.Costo</th>
					<th width="10%">Cantidad</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td >&nbsp;</td>
					<td >
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width: 97%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);" 
							onkeyup="javascript: if(this.value=='') document.getElementById('txtDescripcion').value='';"
						/>
					</td>
					<td ><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
					<td ><input name="txtUnidad" id="txtUnidad" class="txt-plano" style="width: 96%; text-align:center" readonly="true"/></td>
					<td >
						<input type="hidden" name="hdnCCosto" id="hdnCCosto"/>
						<input name="txtCCosto" id="txtCCosto" class="txt-plano" style="width: 96%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);" 
							onkeyup="javascript: if(this.value=='') document.getElementById('hdnCCosto').value='';"
						/>
					</td>
					<td >
						<input type="hidden" name="hdnStock" id="hdnStock" />
						<input name="txtCantidad" id="txtCantidad" class="txt-plano" style="width: 97%; text-align:right" 
							onblur="javascript: CambiaColor(this.id, false);" value="0"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') this.value = 0"
						/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><hr /></td></tr>
	<tr><td align="center"><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginheight="0" ></iframe></td></tr>
	<tr><td ><hr /></td></tr>
	<tr>
		<td align="right">
			<table border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td align="right">
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
						<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>"/>
						<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
						<input type="hidden" name="login" id="login" value="<?php echo $login;?>"/>
												
						<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
							onclick="javascript: Graba();" 
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<iframe name="valido" id="valido" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
