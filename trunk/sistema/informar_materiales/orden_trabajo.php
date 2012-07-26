<?php
include '../autentica.php';
include '../conexion.inc.php';

if($perfil=='valorizador' || $perfil=='coord.oper' || $perfil=='j.coord.nomaterial' || $perfil=='j.coordinacion.v')
	$tipo = 1;
elseif($perfil=='coordinacion' || $perfil=='controlcalidad' || $perfil=='j.coordinacion' || $perfil=='informatica' || $perfil=='cobranza' || $perfil=='j.cobranza' || $perfil=='pexterno_t1')
	$tipo = 0;

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
	$cerrada = $rst["intCerrada"];
	$certificado = $rst["dblCertificado"];
}
mssql_free_result($stmt);

if($perfil=='cobranza')
	$sql = "EXEC General..sp_getMoviles 6, NULL, '$contrato'";
else
	$sql = "EXEC General..sp_getMoviles 4, NULL, '$numero'";
	
$sololectura = 0;
if($cerrada == 1 || $cerrada == 2){
	$sololectura = 1;
	if($perfil == 'j.cobranza' || $perfil == 'informatica' || $perfil == 'j.coordinacion' || $perfil == 'coordinacion' ) $sololectura = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informar Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Accion(obj){
	if(obj.id == 'btnAtras'){
		if(parent.name == 'workspace')
			self.location.href='index.php<?php echo $parametros;?>';
		else
			parent.location.href='../edita_orden_trabajo/index.php<?php echo $parametros;?>';
	}else if(obj.id == 'btnFin'){
		document.getElementById('btnFin').disabled = true;
		document.getElementById('transaccion').src='transaccion.php<?php echo $parametros;?>&modulo=4&numero=<?php echo $numero;?>';
	}
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
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?texto='+texto+'&contrato=<?php echo $contrato;?>&movil='+movil+'&tipo=<?php echo $tipo;?>&ctrl='+ctrl+'&foco=txtCantidad');
				break;
			case 'txtCantidad':
				if(document.getElementById('txtCodigo').value == ''){
					alert('Debe ingresar el material');
				}else if(document.getElementById(ctrl).value == '' || parseFloat(document.getElementById(ctrl).value) == 0){
					document.getElementById(ctrl).value = '0';
					alert('Debe ingresar la cantidad');
				}else{
					if(document.getElementById('hdnUnidad').value == 'Nº') document.getElementById('txtCantidad').value = parseInt(document.getElementById('txtCantidad').value);
					
					document.getElementById('detalle').src='detalle_orden_trabajo.php?modulo=1&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $numero;?>&movil='+document.getElementById('cmbMovil').value+
					'&codigo='+document.getElementById('txtCodigo').value+'&cantidad='+document.getElementById('txtCantidad').value+'&tipo=<?php echo $tipo;?>&contrato=<?php echo $contrato;?>&cerrada=<?php echo $sololectura;?>';
					
					document.getElementById('cmbMovil').selectedIndex = 0;
					document.getElementById('txtCodigo').value = '';
					document.getElementById('txtDescripcion').value = '';
					document.getElementById('txtCantidad').value = 0;
					document.getElementById('hdnCantidad').value = 0;
					document.getElementById('cmbMovil').focus();
				}
				break;
		}
	}else{
		if(ctrl == 'txtCantidad'){
			switch(document.getElementById('hdnUnidad').value){
				case 'Nº':
				case 'JGO':
				case 'LATA':
				case 'N':
				case 'PAR':
				case 'GLOBAL':
				case 'PZA':
					return ValNumeros(evento, ctrl, false);
					break;
				default:
					return ValNumeros(evento, ctrl, true);
					break;
			}
		}
	}
}

function Load(){
	if(parent.name == 'workspace')
		document.getElementById('detalle').setAttribute('height', window.innerHeight - 135);
	else
		document.getElementById('detalle').setAttribute('height', window.innerHeight - 141);

	document.getElementById('detalle').src="detalle_orden_trabajo.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&modulo=0&numero=<?php echo $numero;?>&tipo=<?php echo $tipo;?>&contrato=<?php echo $contrato;?>&cerrada=<?php echo $sololectura;?>";
	
}

function Deshabilita(sw){
	document.getElementById('cmbMovil').disabled = sw;
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('txtCantidad').disabled = sw;
	document.getElementById('btnAtras').disabled = sw;
	document.getElementById('btnFin').disabled = sw;
	
	var totfil = detalle.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(detalle.document.getElementById('cmbMovil' + i)) detalle.document.getElementById('cmbMovil' + i).disabled = sw;
		if(detalle.document.getElementById('txtCantidad' + i)) detalle.document.getElementById('txtCantidad' + i).disabled = sw;
		if(detalle.document.getElementById('img' + i)) detalle.document.getElementById('img' + i).style.visibility = (sw ? 'hidden' : 'visible');
	}
}

function getBuscar(ctrl){
	if(ctrl.value != ''){
		var movil = document.getElementById('cmbMovil').value;
		var codigo = document.getElementById('txtCodigo').value;
		document.getElementById('transaccion').src='transaccion.php?modulo=3&contrato=<?php echo $contrato;?>&movil='+movil+'&material='+codigo+'&tipo=<?php echo $tipo;?>';
	}else{
		document.getElementById('txtDescripcion').value = '';
		document.getElementById('hdnUnidad').value = '';
		document.getElementById('hdnCantidad').value = 0;
		document.getElementById('txtCantidad').value = 0;
	}
}

function setCambiaDetalle(){
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('txtCantidad').value = 0;
	document.getElementById('hdnCantidad').value = 0;
}

function setSoloLectura(){
	document.getElementById('cmbMovil').disabled = true;
	document.getElementById('txtCodigo').setAttribute('readOnly', true);
	document.getElementById('txtCantidad').setAttribute('readOnly', true);
	document.getElementById('btnFin').disabled = true;
	var totfil = detalle.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(detalle.document.getElementById('cmbMovil' + i)) detalle.document.getElementById('cmbMovil' + i).disabled = true;
		if(detalle.document.getElementById('txtCantidad' + i)) detalle.document.getElementById('txtCantidad' + i).setAttribute('readOnly', true);
		if(detalle.document.getElementById('img' + i)) detalle.document.getElementById('img' + i).style.visibility = 'hidden';
	}
}
-->
</script>
<body onload="javascript: Load()">
<div id="divMateriales" style="position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
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
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
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

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><b>&nbsp;N&deg; Orden</b></td>
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
					<th width="40%" align="left">&nbsp;Movil</th>
					<th width="10%">C&oacute;digo</th>
					<th width="33%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="4%">&nbsp;</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<select id="cmbMovil" class="sel-plano" style="width:99%" 
							onchange="javascript: setCambiaDetalle();"
							onkeypress="javascript: KeyPress(event, this.id);"
						>
						<?php
						$stmt = mssql_query($sql, $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strMovil"]).'" >['.trim($rst["strMovil"]).'] '.$rst["strNombre"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td>
						<input id="txtCodigo" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: 
								CambiaColor(this.id, false);
								getBuscar(this);
							"
							onfocus="javascript: CambiaColor(this.id, true)"
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
					<td><input id="txtDescripcion" class="txt-plano" style="width:99%" readonly="true" /></td>
					<td align="right">
						<input type="hidden" id="hdnUnidad" />
						<input type="hidden" id="hdnCantidad" />
						<input id="txtCantidad" class="txt-plano" style="width:99%; text-align:right" value="0"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true)"
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe id="detalle" name="detalle" frameborder="0" width="100%" scrolling="yes" src="../cargando.php"></iframe></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<input type="button" id="btnAtras" class="boton" style="width:90px" value="&lt;&lt; Atras" 
							onclick="javascript: Accion(this);"
						/>
					</td>
					<td width="50%" align="right">
						<input type="button" id="btnFin" class="boton" style="width:90px" value="Guardar" 
							onclick="javascript: Accion(this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
