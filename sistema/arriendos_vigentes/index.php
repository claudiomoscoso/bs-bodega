<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Arriendos Vigentes</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var busco = 0;
function ClickFicha(idFicha){
	var obra = document.getElementById('cmbObra').value;
	document.getElementById('frmResultado').src = '../blank.html';
	if(idFicha == 1){
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha1').style.color='#FFFFFF';
		document.getElementById('aFicha1').style.fontWeight='bold';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha2').style.color='#3A4CFB';
		document.getElementById('aFicha2').style.fontWeight='normal';
		document.getElementById('tbl1').style.display = '';
		document.getElementById('tr1').style.display = '';
		document.getElementById('tbl2').style.display = 'none';
		document.getElementById('frmResultado').setAttribute('height', window.innerHeight - 95);
		if(busco == 1){
			Deshabilita(true);
			document.getElementById('frmResultado').src = 'resultado.php?obra=' + obra;
		}
	}else{
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha1').style.color='#3A4CFB';
		document.getElementById('aFicha1').style.fontWeight='normal';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha2').style.color='#FFFFFF';
		document.getElementById('aFicha2').style.fontWeight='bold';
		document.getElementById('tbl1').style.display = 'none';
		document.getElementById('tr1').style.display = 'none';
		document.getElementById('tbl2').style.display = '';
		document.getElementById('frmResultado').setAttribute('height', window.innerHeight - 54);
		if(busco == 1){
			Deshabilita(true);
			document.getElementById('frmResultado').src = 'detalle.php?obra=' + obra + '&cont=0';
		}
	}
}

function Load(){ 
	ClickFicha(1);
}

function getBuscar(){
	busco = 1;
	Deshabilita(true);
	ClickFicha(1);
	document.getElementById('frmResultado').src = 'resultado.php?obra=' + document.getElementById('cmbObra').value;
}

function Deshabilita(sw){
	document.getElementById('cmbObra').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnSiguiente').disabled = sw;
}

function setFinalizar(){
	if(confirm('¿Esta seguro que desea finalizar este arriendo?')){
		Deshabilita(false);
		CierraDialogo('divDetalle', 'frmDetalle');
		document.getElementById('frmResultado').src = 'detalle.php?obra='+document.getElementById('cmbObra').value+'&cont=0&numero='+document.getElementById('hdnNumOC').value+'&usuario=<?php echo $usuario;?>';
	}
}

function Siguiente(){
	if(!frmResultado.document.getElementById('totfil')) return 0;
	var totfil = frmResultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(frmResultado.document.getElementById('op' + i).checked){
			var numOC = frmResultado.document.getElementById('numOC' + i).value;
			break;
		}
	}
	if(numOC)
		self.location.href = 'renovar_orden_compra.php<?php echo $parametros;?>&numOC=' + numOC;
	else
		alert('Debe seleccionar una orden de compra.');
}
-->
</script>
<body onload="javascript: Load();">
<div id="divDetalleOC" style="position:absolute; top:100px; left:39%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="2" cellspacing="0">
							<tr><td align="center" class="menu_principal" >Detalle Orden de Compra</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="2" cellspacing="0">
							<tr>
								<th width="3%">N&deg;</th>
								<th width="85%" align="left">&nbsp;Descripci&oacute;n</th>
								<th width="10%" align="right">Cantidad&nbsp;</th>
								<th width="2%">&nbsp;</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmDetalleOC" id="frmDetalleOC" frameborder="0" style="border:thin" scrolling="yes" width="100%" height="135px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divDetalle" style="position:absolute; top:10%; left:23%; width:55%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#ECECEC" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="2" cellspacing="0">
							<tr class="menu_principal">
								<td width="1%">
									<a href="#" title="Cierra cuadro de informaci&oacute;n"
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divDetalle', 'frmDetalle');
										"
									><img border="0" align="middle" src="../images/close.png" /></a>
								</td>
								<td align="center"><b>Detalle Arriendo</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmDetalle" id="frmDetalle" frameborder="0" style="border:thin" scrolling="no" width="100%" height="135px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
				<tr><td style="height:5px"><hr /></td></tr>
				<tr>
					<td align="right">
						<input type="hidden" name="hdnNumOC" id="hdnNumOC" />
						<input type="button" name="btnFin" id="btnFin" class="boton" style="width:90px" value="Finalizar" 
							onclick="setFinalizar()"
						/>
					</td>
				</tr>
				<tr><td style="height:2px"></td></tr>
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
					<td width="3%">&nbsp;Obra</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbObra" id="cmbObra" class="sel-plano" style="width:100%"
							onchange="javascript: 
								document.getElementById('frmResultado').src = '../blank.html';
								busco = 0;
							"
						>
						<?php
						switch($perfil){
							case 'sg.operaciones':
							case 's.operaciones':
							case 'operaciones':
								$stmt = mssql_query("EXEC General..sp_getCargos 7", $cnx);
								break;
							default:
								$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
						}
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strCodigo"]).'">'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td >
			<table border="0" width="160px" cellpadding="0" cellspacing="0">
				<tr>
					<td id="tdFicha1" width="80px" align="center" style="background-repeat:no-repeat;">
						<a id="aFicha1" href="#" style="color:#FFFFFF" title="Listado"
							onclick="javascript: ClickFicha(1);"
							onmouseover="javascript: window.status='Listado.'; return true;"
						>Listado</a>
					</td>
					<td id="tdFicha2" width="80px" align="center" style="background-repeat:no-repeat">
						<a id="aFicha2" href="#" title="Gantt"
							onclick="javascript: ClickFicha(2);"
							onmouseover="javascript: window.status='Gantt.'; return true;"
						>Gantt</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="tbl1" border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="25%" align="left">&nbsp;Proveedor</th>
					<th width="10%">O.Compra N&deg;</th>
					<th width="10%">F.Inicio</th>
					<th width="10%">F.T&eacute;rmino</th>
					<th width="33%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="7%">Seleccionar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
			<table id="tbl2" border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr valign="middle">
					<td width="3%" valign="middle">
						<input type="button" name="btnAnt" id="btnAnt" title="Ir al mes anterior." class="boton" style="width:100%; height:15px" disabled="disabled" value="&lt;&lt;" 
							onclick="javascript: frmResultado.Explora('A');"
						/>
					</td>
					<td width="47%" ><input name="panel1" id="panel1" class="txt-sborde" style="width:100%; text-align:center; font-size:11px; font-weight:bold; background-image:url(../images/borde_med_grilla.gif);" /></td>
					<td width="47%"><input name="panel2" id="panel2" class="txt-sborde" style="width:100%; text-align:center; font-size:11px; font-weight:bold; background-image:url(../images/borde_med_grilla.gif)" /></td>
					<td width="3%" valign="middle">
						<input type="button" name="btnSgte" id="btnSgte" title="Ir al siguiente mes." class="boton" style="width:100%; height:15px" disabled="disabled" value="&gt;&gt;" 
							onclick="javascript: frmResultado.Explora('S');"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmResultado" id="frmResultado" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr id="tr1">
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr><td><hr /></td></tr>
				<tr>
					<td align="right">
						<input type="button" name="btnSiguiente" id="btnSiguiente" class="boton" style="width:90px" value="Siguiente &gt;&gt;" 
							onClick="javascript: Siguiente();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>