<?php
include '../autentica.php';
include '../conexion.inc.php';

$numero = $_GET["numero"];
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 3, '', '', NULL, '$numero'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$fchorden = $rst["dtmFchOrden"];
	$numot = $rst["strOrden"];
	$contrato = $rst["strContrato"];
	$desccontrato = $rst["strDescContrato"];
	$movil = $rst["strMovil"];
	$descmovil = $rst["strNombre"];
	$fchvcto = $rst["dtmFchVcto"];
	$motivo = $rst["strMotivo"];
	$epago = $rst["dblEP"];
	$cerrada = $rst["intCerrada"];
	$certificado = $rst["dblCertificado"];
}
mssql_free_result($stmt);

$sololectura = 0;
if(($cerrada == 1 || $cerrada == 2) || $certificado > 0){
	$sololectura = 1;
	if($perfil == 'j.cobranza' || $perfil == 'informatica' || $perfil == 'ccaces') $sololectura = 0;
}

switch($perfil){
	case 'informatica':
	case 'cobranza':
	case 'j.cobranza':
		$editcant = 1;
		break;
	default:
		$editcant = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informar Trabajos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Accion(ctrl){
	if(ctrl.id == 'btnAtras'){
		if(parent.name == 'workspace')
			self.location.href='index.php<?php echo $parametros;?>';
		else{
			parent.location.href = '../edita_orden_trabajo/index.php<?php echo $parametros;?>';
		}
	}else if(ctrl.id == 'btnFin'){
		if(!detalle.document.getElementById('totfil'))
			alert('Debe agregar al menos un item al detalle.');
		else if(detalle.document.getElementById('totfil').value == '')
			alert('Debe agregar al menos un item al detalle.');
		else{
			ctrl.disabled = true;
			document.getElementById('transaccion').src = 'transaccion.php<?php echo $parametros;?>&modulo=4&numero=<?php echo $numero;?>';
		}
	}
}

function GotFocus(ctrl){
	var cant = document.getElementById('hdnCantidades').value;
	var paso = document.getElementById('txtCantInf').value;
	var cubic = paso.split('x');
	cubic[0] = (cubic[0] ? cubic[0] : 0);
	cubic[1] = (cubic[1] ? cubic[1] : 0);
	cubic[2] = (cubic[2] ? cubic[2] : 0);
	
	if(parseInt(cant) > 1){
		if(cant == 2){
			document.getElementById('txtCant1').value = cubic[0];
			document.getElementById('txtCant2').value = cubic[1];
			document.getElementById('txtCant3').disabled = true;
		}else{
			document.getElementById('txtCant1').value = cubic[0];
			document.getElementById('txtCant2').value = cubic[1];
			document.getElementById('txtCant3').value = cubic[2];
			document.getElementById('txtCant3').disabled = false;
		}
		Deshabilita(true);
		document.getElementById('divCantidades').style.top = '23%';
		document.getElementById('divCantidades').style.visibility = 'visible';
		
		document.getElementById('txtCant1').focus();
		document.getElementById('txtCant1').select();
	}else
		CambiaColor(ctrl.id, true);
}

function KeyPress(evento, ctrl){
	if(parseInt('<?php echo $sololectura;?>') != 0) return false;
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl){
			case 'cmbMovil':
				document.getElementById('txtCodigo').focus();
				document.getElementById('txtCodigo').select();
				break;
			case 'txtCodigo':
				var movil = document.getElementById('cmbMovil').value;
				var texto = document.getElementById(ctrl).value;
				Deshabilita(true);
				CambiaColor(ctrl, false)
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?texto='+texto+'&contrato=<?php echo $contrato;?>&perfil=<?php echo $perfil;?>&ctrl='+ctrl+'&foco=txtCantInf');
				break;
			case 'txtCant1':
				document.getElementById('txtCant2').focus();
				document.getElementById('txtCant2').select();
				break;
			case 'txtCant2':
				if(document.getElementById('hdnCantidades').value == 2){
					var cant1 = document.getElementById('txtCant1').value;
					var cant2 = document.getElementById('txtCant2').value;
					if(document.getElementById('txtCodigo').value == '')
						alert('Debe ingresar el material.');
					else if(cant1 == '' || cant2 == '' || cant1 == 0 || cant2 == 0)
						alert('Debe ingresar la cantidad.');
					else{
						document.getElementById('txtCant1').value = 0;
						document.getElementById('txtCant2').value = 0;
						document.getElementById('txtCant3').value = 0;
						document.getElementById('txtCantInf').value = cant1 + 'x' + cant2;
						document.getElementById('divCantidades').style.visibility = 'hidden';
						Deshabilita(false);
						
						document.getElementById('detalle').src = 'detalle_orden_trabajo.php?modulo=1&editcant=<?php echo $editcant;?>&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $numero;?>&contrato=<?php echo $contrato;?>&movil=' + document.getElementById('cmbMovil').value +
						'&codigo=' + document.getElementById('txtCodigo').value + '&cubicacion=' + document.getElementById('txtCantInf').value + '&movilpadre=<?php echo $movil;?>';
						
						setLimpiaDetalle();
						document.getElementById('cmbMovil').focus();
					}
				}else{
					document.getElementById('txtCant3').focus();
					document.getElementById('txtCant3').select();
				}
				break;
			case 'txtCant3':
				var cant1 = document.getElementById('txtCant1').value;
				var cant2 = document.getElementById('txtCant2').value;
				var cant3 = document.getElementById('txtCant3').value;
				
				if(document.getElementById('txtCodigo').value == '')
					alert('Debe ingresar el material.');
				else if(cant1 == '' || cant2 == '' || cant2 == '' || cant1 == 0 || cant2 == 0 || cant3 == 0)
					alert('Debe ingresar la cantidad.');
				else{
					document.getElementById('txtCant1').value = 0;
					document.getElementById('txtCant2').value = 0;
					document.getElementById('txtCant3').value = 0;
					document.getElementById('txtCantInf').value = cant1 + 'x' + cant2 + 'x' + cant3;
					document.getElementById('divCantidades').style.visibility = 'hidden';
					Deshabilita(false);
					
					document.getElementById('detalle').src='detalle_orden_trabajo.php?modulo=1&editcant=<?php echo $editcant;?>&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $numero;?>&contrato=<?php echo $contrato;?>&movil=' + document.getElementById('cmbMovil').value +
					'&codigo=' + document.getElementById('txtCodigo').value + '&cubicacion=' + document.getElementById('txtCantInf').value + '&movilpadre=<?php echo $movil;?>';
					
					setLimpiaDetalle();
					document.getElementById('cmbMovil').focus();
				}
				break;
			case 'txtCantInf':
				if(parseInt('<?php echo $editcant;?>') == 0){
					if(document.getElementById('txtCodigo').value == ''){
						alert('Debe ingresar el material.');
					}else if(document.getElementById(ctrl).value == ''){
						document.getElementById(ctrl).value = 0;
						alert('Debe ingresar la cantidad.');
					}else{
						document.getElementById('detalle').src = 'detalle_orden_trabajo.php?modulo=1&editcant=<?php echo $editcant;?>&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $numero;?>&contrato=<?php echo $contrato;?>&movil=' + document.getElementById('cmbMovil').value +
						'&codigo=' + document.getElementById('txtCodigo').value + '&cubicacion=' + document.getElementById('txtCantInf').value + '&movilpadre=<?php echo $movil;?>';
						
						setLimpiaDetalle();
						document.getElementById('cmbMovil').focus();
					}
				}else{
					document.getElementById('txtCantPag').focus();
					document.getElementById('txtCantPag').select();
				}
				break;
			case 'txtCantPag':
				if(parseInt('<?php echo $editcant;?>') == 1){
					if(document.getElementById('txtCodigo').value == ''){
						alert('Debe ingresar el item.');
					}else if(document.getElementById(ctrl).value == ''){
						document.getElementById(ctrl).value = 0;
						alert('Debe ingresar la cantidad.');
					}else{
						document.getElementById('detalle').src = 'detalle_orden_trabajo.php?modulo=1&editcant=<?php echo $editcant;?>&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $numero;?>&contrato=<?php echo $contrato;?>&movil=' + document.getElementById('cmbMovil').value +
						'&codigo=' + document.getElementById('txtCodigo').value + '&cubicacion=' + document.getElementById('txtCantInf').value + '&cantpagada=' + document.getElementById('txtCantPag').value + '&movilpadre=<?php echo $movil;?>';
						
						setLimpiaDetalle();
						document.getElementById('cmbMovil').focus();
					}
				}
				break;
		}
	}else if(tecla == 27){
		document.getElementById('txtCantInf').value = 0;
		Deshabilita(false);
		document.getElementById('divCantidades').style.visibility = 'hidden';
		document.getElementById('txtCodigo').focus();
		document.getElementById('txtCodigo').select();
	}else{
		switch(ctrl){
			case 'txtCantInf':
			case 'txtCantPag':
			case 'txtCant1':
			case 'txtCant2':
			case 'txtCant3':
				var unidad = document.getElementById('txtUnidad').value;
				switch(unidad.toUpperCase()){
					case 'Nº':
					case 'JGO':
					case 'LATA':
					case 'N':
					case 'PAR':
					case 'GLOBAL':
					case 'PZA':
					case 'UNIDAD':
						return ValNumeros(evento, ctrl, false);
						break;
					default:
						//alert(ValNumeros(evento, ctrl, true))
						return ValNumeros(evento, ctrl, true);
						break;
				}
				break;
		}
	}
}

function Load(){
	if(parent.name == 'workspace')
		document.getElementById('detalle').setAttribute('height', window.innerHeight - 135);
	else
		document.getElementById('detalle').setAttribute('height', window.innerHeight - 142);
	if(parseInt('<?php echo $sololectura;?>') != 0) setSoloLectura();
	document.getElementById('detalle').src = "detalle_orden_trabajo.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&modulo=0&editcant=<?php echo $editcant;?>&contrato=<?php echo $contrato;?>&numero=<?php echo $numero;?>&tipo=<?php echo $tipo;?>&movilpadre=<?php echo $movil;?>&cerrada=<?php echo $cerrada;?>";
}

function Deshabilita(sw){
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('txtCantInf').disabled = sw;
	document.getElementById('txtCantPag').disabled = sw;
	document.getElementById('btnAtras').disabled = sw;
	if(parseInt('<?php echo $sololectura;?>') == 0){
		document.getElementById('cmbMovil').disabled = sw;
		document.getElementById('btnFin').disabled = sw;
		if(detalle.document.getElementById('totfil')){
			var totfil = detalle.document.getElementById('totfil').value;
			for(i = 1; i <= totfil; i++){
				detalle.document.getElementById('txtCantInf' + i).disabled = sw;
				detalle.document.getElementById('txtCantPag' + i).disabled = sw;
				if(detalle.document.getElementById('img' + i)) detalle.document.getElementById('img' + i).style.visibility = (sw ? 'hidden' : 'visible');
				if(detalle.document.getElementById('chkCerrar' + i)) detalle.document.getElementById('chkCerrar' + i).disabled = sw;
			}
		}
	}
}

function getBuscar(ctrl){
	if(ctrl.value != ''){
		var codigo = document.getElementById('txtCodigo').value;
		document.getElementById('transaccion').src='transaccion.php?modulo=1&contrato=<?php echo $contrato;?>&codigo=' + codigo;
	}else{
		document.getElementById('txtDescripcion').value = '';
		document.getElementById('txtUnidad').value = '';
		document.getElementById('hdnCantidades').value = '';
		document.getElementById('txtCantInf').value = '';
	}
}

function setCambiaDetalle(){
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('txtCantInf').value = '';
	document.getElementById('hdnCantidades').value = '';
}

function setLimpiaDetalle(){
	document.getElementById('cmbMovil').selectedIndex = 0;
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('txtUnidad').value = '';
	document.getElementById('txtCantInf').value = 0;
	document.getElementById('hdnCantidades').value = 0;
	document.getElementById('txtCantPag').value = 0;
	document.getElementById('hdnCantPag').value = 0;
}

function setSoloLectura(){
	document.getElementById('cmbMovil').disabled = true;
	document.getElementById('txtCodigo').setAttribute('readOnly', 'true');
	document.getElementById('txtCantInf').setAttribute('readOnly', 'true');
	document.getElementById('txtCantPag').setAttribute('readOnly', 'true');
	document.getElementById('btnFin').disabled = true;
	if(document.getElementById('btnParaEnvio')) document.getElementById('btnParaEnvio').disabled = true;
	if(detalle.document.getElementById('totfil')){
		var totfil = detalle.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			detalle.document.getElementById('txtCantInf' + i).setAttribute('readOnly', 'true');
			detalle.document.getElementById('txtCantPag' + i).setAttribute('readOnly', 'true');
			if(detalle.document.getElementById('chkCerrar' + i)) detalle.document.getElementById('img' + i).style.visibility = (sw ? 'hidden' : 'visible');
			if(detalle.document.getElementById('chkCerrar' + i)) detalle.document.getElementById('chkCerrar' + i).disabled = sw;
		}
	}	
}

function ParaEnvio(){
	document.getElementById('transaccion').src = 'transaccion.php?modulo=7&numero=<?php echo $numero;?>&usuario=<?php echo $usuario;?>';
}
-->
</script>
<body onload="javascript: Load()">
<div id="divMateriales" style="position:absolute; top:5px; left:15%; width:70%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
										Deshabilita(false);
										CierraDialogo('divMateriales','frmMateriales');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Items</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe id="frmMateriales" frameborder="0" scrolling="no" width="100%" height="245px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divCantidades" style="position:absolute; top:15%; left:70%; width:30%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%">
									<input name="txtCant1" id="txtCant1" class="txt-plano" style="width:99%; text-align:center" value="0" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this.id);"
									/>
								</td>
								<td width="1%" align="center">x</td>
								<td width="10%">
									<input name="txtCant2" id="txtCant2" class="txt-plano" style="width:99%; text-align:center" value="0" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this.id);"
									/>
								</td>
								<td width="1%" align="center">x</td>
								<td width="10%">
									<input name="txtCant3" id="txtCant3" class="txt-plano" style="width:99%; text-align:center" value="0" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"

										onkeypress="javascript: return KeyPress(event, this.id);"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td style="height:5px"><hr /></td></tr>
				<tr><td style="color:#FF0000">&nbsp;Presione la tecla <b>ESC</b> para cerrar.</td></tr>
			</table>			
		</td>
	</tr>
</table>
</div>

<div id="divPagados" style="position:absolute; width:70%; visibility:hidden; left: 15%; top: 5px;">
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
											CierraDialogo('divPagados', 'frmPagados');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmPagados" id="frmPagados" frameborder="1" style="border:thin" scrolling="no" width="100%" height="200px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
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
					<td width="5%"><b>&nbsp;N&deg;Orden</b></td>
					<td width="1%"><b>:</b></td>
					<td width="94%">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%">&nbsp;<?php echo $numot;?></td>
								<td width="1%">&nbsp;</td>
								<td width="7%"><b>&nbsp;Fch.Orden</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%">&nbsp;<?php echo $fchorden;?></td>
								<td width="1%">&nbsp;</td>
								<td width="4%"><b>&nbsp;Movil</b></td>
								<td width="1%"><b>:</b></td>
								<td width="47%" >&nbsp;<?php echo $descmovil;?></td>
								<td width="1%">&nbsp;</td>
								<td width="6%"><b>&nbsp;Fch.Vcto.</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%">&nbsp;<?php echo $fchvcto;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>&nbsp;Contrato</b></td>
					<td><b>:</b></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="46%">&nbsp;<?php echo $desccontrato;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%"><b>&nbsp;Motivo</b></td>
								<td width="1%"><b>:</b></td>
								<td width="47%"><input class="txt-sborde" style="width:100%;background-color:#ececec" readonly="true" value="&nbsp;<?php echo $motivo;?>" /></td>
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
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="2%">&nbsp;</th>
					<th width="17%" align="left">&nbsp;Movil</th>
					<th width="10%">Item</th>
					<th width="20%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<th width="8%" align="center"><?php echo ($perfil == 'j.cobranza' || $perfil == 'informatica') ? 'Precio' : 'F.Informe';?></th>
					<th width="10%" align="right">Cant.Inf.&nbsp;</th>
					<th width="10%" align="right">Cant.Pag.&nbsp;</th>
					<th width="6%">Cerrar</th>
					<th width="2%">&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr>
					<td >&nbsp;</td>
					<td >&nbsp;</td>
					<td >
						<select id="cmbMovil" class="sel-plano" style="width:99%" 
							onchange="javascript: setCambiaDetalle();"
							onkeypress="javascript: KeyPress(event, this.id);"
						>
						<?php
						echo '<option value="'.trim($movil).'&&& " >['.trim($movil).'] '.$descmovil.'</option>';
						$stmt = mssql_query("EXEC Orden..sp_getAnexos 1, '', $numero", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strMovil"]).'@@@'.trim($rst["strTipo"]).'" >['.trim($rst["strMovil"]).'] '.$rst["strTipo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td align="center">
						<input id="txtCodigo" class="txt-plano" style="width:98%; text-align:center" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								getBuscar(this);
							"
							onfocus="javascript: CambiaColor(this.id, true)"
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
					<td align="center"><input id="txtDescripcion" class="txt-plano" style="width:99%" readonly="true" /></td>
					<td align="center"><input id="txtUnidad" class="txt-plano" style="width:99%; text-align:center" readonly="true" /></td>
					<td >&nbsp;</td>
				  	<td align="center">
						<input type="hidden" name="hdnCantidades" id="hdnCantidades" />
						<input id="txtCantInf" class="txt-plano" style="width:98%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: return GotFocus(this);"
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
					<td align="center">
						<input type="hidden" id="hdnCantPag" />
						<input id="txtCantPag" class="txt-plano" style="width:98%; text-align:center" <?php echo ($editcant == 0 ? 'readonly="true"' : '');?>
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: return GotFocus(this);"
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
					<td >&nbsp;</td>
					<td >&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="detalle" id="detalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes"  src="../cargando.php" ></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="33%">
						<input type="button" id="btnAtras" class="boton" style="width:90px" value="&lt;&lt; Atras" 
							onclick="javascript: Accion(this);"
						/>
					</td>
					<td align="center"><div id="divTotal"></div></td>
					<td width="33%" align="right">
						<input type="hidden" name="hdnContrato" id="hdnContrato" value="<?php echo $contrato;?>" />
						<?php
						if(($epago == '' || $epago == 0)){
							if($contrato == '13001') {
								$stmt = mssql_query("SELECT COUNT(strItem) AS Sw FROM Orden..InformeOrden WHERE dblCorrelativo = $numero", $cnx);
							}
							else {
								$stmt = mssql_query("SELECT COUNT(ID) AS Sw FROM Orden..Anexos WHERE dblCorrelativo = $numero AND intOK <> 2", $cnx);
							}
							if($rst = mssql_fetch_array($stmt)) $sw = $rst["Sw"];
							mssql_free_result($stmt);

							$stmt = mssql_query("SELECT isnull(dblCierreAtencion,0) as dblCierreAtencion From Orden..CaratulaOrden WHERE dblCorrelativo = $numero", $cnx);
							if($rst = mssql_fetch_array($stmt)) {
							 $ca = $rst["dblCierreAtencion"];
							}
							mssql_free_result($stmt);
							
							$stmt = mssql_query("SELECT len(strHT) as FinH From Orden..ControlHR WHERE dblCorrelativo = $numero", $cnx);
							if($rst = mssql_fetch_array($stmt)) {
							 $fh = $rst["FinH"];
							}
							mssql_free_result($stmt);

							if($contrato == '13000' || $contrato == '13054') {
								if($ca==0){
									$sw=1;
								}
								if($fh==2){
									$sw=1;
								}
							}
							if($contrato == '13001') {
							// solo para el contrato avalle, la ot esta lista para cobro si tiene informe, y puede tener anexos pendientes
								if($sw==0){
									$sw=1; // no tiene informe, no se puede cobrar
								} else {
									$sw=0; // se puede enviar a cobro
								}
							}

							if($sw == 0){
							?>
							<input type="button" id="btnParaEnvio" class="boton" style="width:95px" value="Lista para Cobro&nbsp;" 
								onclick="javascript: ParaEnvio();"
							/>
							<?php 
							} 
						}?>
						<input type="button" id="btnFin" class="boton" style="width:95px" value="Guardar" 
							onclick="javascript: Accion(this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe id="transaccion" frameborder="0" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
