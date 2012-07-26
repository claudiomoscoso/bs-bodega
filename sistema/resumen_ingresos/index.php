<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consulta de Ingresos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var idTab = 0, busco = 0;

function Load(){
	document.getElementById('frmResultado').setAttribute('height', window.innerHeight - 100);
	document.getElementById('tdFicha2').style.visibility = 'hidden';
	ClickFicha(1);
}

function ClickFicha(idFicha){
	idTab = idFicha;
	if(idFicha == 1){
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha1').style.color='#FFFFFF';
		document.getElementById('aFicha1').style.fontWeight='bold';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha2').style.color='#3A4CFB';
		document.getElementById('aFicha2').style.fontWeight='normal';
		if(busco == 1)
			if(document.getElementById('cmbMes').value == 'all')
				document.getElementById('frmResultado').src = 'ingresosxmes.php?bodega=' + document.getElementById('cmbBodega').value + '&ano=' + 
				document.getElementById('cmbAno').value + '&ctrl=txtTotGnral&col=1&orden=A';
			else
				document.getElementById('frmResultado').src = 'ingresosxmaterial.php?bodega=' + document.getElementById('cmbBodega').value +
				'&mes=' + document.getElementById('cmbMes').value + '&ano=' + document.getElementById('cmbAno').value + '&ctrl=txtTotGnral&col=1&orden=A';
	}else{
		document.getElementById('tdFicha1').setAttribute('background', '../images/ficha.gif');
		document.getElementById('aFicha1').style.color = '#3A4CFB';
		document.getElementById('aFicha1').style.fontWeight = 'normal';
		document.getElementById('tdFicha2').setAttribute('background', '../images/ficha_sel.gif');
		document.getElementById('aFicha2').style.color = '#FFFFFF';
		document.getElementById('aFicha2').style.fontWeight = 'bold';
		if(busco == 1)
			document.getElementById('frmResultado').src = 'agrupadoxmaterial.php?bodega=' + document.getElementById('cmbBodega').value + '&ano=' + 
			document.getElementById('cmbAno').value + '&ctrl=txtTotGnral&col=1&orden=A';
	}
}

function Buscar(col, orden){
	busco = 1;
	Deshabilita(true)
	if(document.getElementById('cmbMes').value == 'all'){
		if(idTab == 1)
			document.getElementById('frmResultado').src = 'ingresosxmes.php?bodega=' + document.getElementById('cmbBodega').value +
			'&ano=' + document.getElementById('cmbAno').value + '&col=' + col + '&orden=' + orden;
		else
			document.getElementById('frmResultado').src = 'agrupadoxmaterial.php?bodega=' + document.getElementById('cmbBodega').value + '&ano=' + 
			document.getElementById('cmbAno').value + '&ctrl=txtTotGnral&col=' + col + '&orden=' + orden;
	}
	else
		document.getElementById('frmResultado').src = 'ingresosxmaterial.php?bodega=' + document.getElementById('cmbBodega').value +
		'&mes=' + document.getElementById('cmbMes').value + '&ano=' + document.getElementById('cmbAno').value + '&ctrl=txtTotGnral&col='+
		col + '&orden=' + orden;
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbMes').disabled = sw;
	document.getElementById('cmbAno').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}

function CambiaOrden(ctrl, nivel){
	var estado='';

	if(nivel==0){
		if(document.getElementById('hdnCol1Nvl1').value=='A')
			document.getElementById('nvl1col1').src='../images/arr.gif';
		else
			document.getElementById('nvl1col1').src='../images/aba.gif';
			
		if(document.getElementById('hdnCol2Nvl1').value=='A')
			document.getElementById('nvl1col2').src='../images/arr.gif';
		else
			document.getElementById('nvl1col2').src='../images/aba.gif';
					
		if(ctrl=='nvl1col1') {
			if(document.getElementById('hdnCol1Nvl1').value=='A') estado='D'; else estado='A';
			CambiaImagenOrden('nvl1col1', estado);
			document.getElementById('hdnCol1Nvl1').value=estado;
		}
		if(ctrl=='nvl1col2'){
			if(document.getElementById('hdnCol2Nvl1').value=='A') estado='D'; else estado='A';
			CambiaImagenOrden('nvl1col2', estado);
			document.getElementById('hdnCol2Nvl1').value=estado;
		}
		Buscar(ctrl.substr(7, 1), estado);
	}else if(nivel==1){
		if(document.getElementById('hdnCol1Nvl2').value=='A')
			document.getElementById('nvl2col1').src='../images/arr.gif';
		else
			document.getElementById('nvl2col1').src='../images/aba.gif';
			
		if(document.getElementById('hdnCol2Nvl2').value=='A')
			document.getElementById('nvl2col2').src='../images/arr.gif';
		else
			document.getElementById('nvl2col2').src='../images/aba.gif';
					
		if(ctrl=='nvl2col1') {
			if(document.getElementById('hdnCol1Nvl2').value=='A') estado='D'; else estado='A';
			CambiaImagenOrden('nvl2col1', estado);
			document.getElementById('hdnCol1Nvl2').value=estado;
		}
		if(ctrl=='nvl2col2'){
			if(document.getElementById('hdnCol2Nvl2').value=='A') estado='D'; else estado='A';
			CambiaImagenOrden('nvl2col2', estado);
			document.getElementById('hdnCol2Nvl2').value=estado;
		}
		document.getElementById('frmIngresos').src='ingresosxmaterial.php?bodega=' + document.getElementById('cmbBodega').value +
		'&mes=' + document.getElementById('hdnMes').value + '&ano=' + document.getElementById('hdnAno').value + '&ctrl=txtSubTot&col='+
		ctrl.substr(7, 1) + '&orden=' + estado;
	}else if(nivel==2){
		if(document.getElementById('hdnCol1Nvl3').value=='A')
			document.getElementById('nvl3col1').src='../images/arr.gif';
		else
			document.getElementById('nvl3col1').src='../images/aba.gif';
			
		if(document.getElementById('hdnCol2Nvl3').value=='A')
			document.getElementById('nvl3col2').src='../images/arr.gif';
		else
			document.getElementById('nvl3col2').src='../images/aba.gif';
					
		if(ctrl=='nvl3col1') {
			if(document.getElementById('hdnCol1Nvl3').value=='A') estado='D'; else estado='A';
			CambiaImagenOrden('nvl3col1', estado);
			document.getElementById('hdnCol1Nvl3').value=estado;
		}
		if(ctrl=='nvl3col2'){
			if(document.getElementById('hdnCol2Nvl3').value=='A') estado='D'; else estado='A';
			CambiaImagenOrden('nvl3col2', estado);
			document.getElementById('hdnCol2Nvl3').value=estado;
		}
		document.getElementById('frmGIngresos').src='detalleingresos.php?bodega='+document.getElementById('cmbBodega').value+'&mes='+document.getElementById('hdnMesNvl3').value+'&ano='+document.getElementById('hdnAnoNvl3').value+
		'&codigo='+document.getElementById('hdnMaterial').value+'&col='+ctrl.substr(7, 1)+'&orden='+estado;
	}
}

function OcultaFlechas(nivel, sw){
	var estado=(sw ? 'hidden' : 'visible');
	if(estado=='hidden'){
		if(nivel==1){
			document.getElementById('nvl2col1').style.visibility=estado;
			document.getElementById('nvl2col2').style.visibility=estado;
		}
		document.getElementById('nvl3col1').style.visibility=estado;
		document.getElementById('nvl3col2').style.visibility=estado;
	}else{
		if(nivel==1){
			document.getElementById('nvl2col1').style.visibility=estado;
			document.getElementById('nvl2col2').style.visibility=estado;
			document.getElementById('nvl2col1').src='../images/arr.gif';
			document.getElementById('nvl2col2').src='../images/arr.gif';
			document.getElementById('hdnCol1Nvl2').value='A';
			document.getElementById('hdnCol2Nvl2').value='A';
		}else if(nivel==2){
			document.getElementById('nvl3col1').style.visibility=estado;
			document.getElementById('nvl3col2').style.visibility=estado;
			document.getElementById('nvl3col1').src='../images/arr.gif';
			document.getElementById('nvl3col2').src='../images/arr.gif';
			document.getElementById('hdnCol1Nvl3').value='A';
			document.getElementById('hdnCol2Nvl3').value='A';
		}
	}
}
-->
</script>
<body onload="javascript: Load()">
<div id="divIngresos" style="position:absolute; width:75%; left:15%; top:5px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra el cuadro de dialogo."
										onClick="javascript: 
											Deshabilita(false);
											OcultaFlechas(1, true);
											CierraDialogo('divGIngresos', 'frmGIngresos');
											CierraDialogo('divIngresos','frmIngresos');"
										onmouseover="javascript: window.status='Cierra el cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Ingresos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%" align="left"><b>&nbsp;Mes de</b></td>
								<td width="1%"><b>:</b></td>
								<td width="90%"><input name="txtMes" id="txtMes" class="txt-sborde" style="width:98%" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<th width="2%">&nbsp;</th>
								<th width="79%" align="left">
									<a href="#" title="Ordena la columna Detalle" style="color:#000000"
										onclick="javascript: CambiaOrden('nvl2col1', 1)"
										onmouseover="javascript: window.status='Ordena la columna Detalle'; return true;"
									>&nbsp;Detalle</a>
								</th>
								<th width="1%">
									<a href="#" title="Ordena la columna Detalle"
										onclick="javascript: CambiaOrden('nvl2col1', 1)"
										onmouseover="javascript: window.status='Ordena la columna Detalle'; return true;"
									><img id="nvl2col1" border="0" align="middle" src="../images/arr.gif" /></a>
								</th>
								<th width="14%" align="right">
									<a href="#" title="Ordena la columna Acumulado" style="color:#000000"
										onclick="javascript: CambiaOrden('nvl2col2', 1)"
										onmouseover="javascript: window.status='Ordena la columna Acumulado'; return true;"
									>Acumulado&nbsp;</a>
								</th>
								<th width="1%">
									<a href="#" title="Ordena la columna Acumulado"
										onclick="javascript: CambiaOrden('nvl2col2', 1)"
										onmouseover="javascript: window.status='Ordena la columna Acumulado'; return true;"
									><img id="nvl2col2" border="0" align="middle" src="../images/arr.gif" /></a>
								</th>
								<th width="3%">&nbsp;</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmIngresos" id="frmIngresos" frameborder="1" style="border:thin" scrolling="auto" width="100%" height="200px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr /></td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="right">
									<input type="hidden" name="hdnMes" id="hdnMes" />
									<input type="hidden" name="hdnAno" id="hdnAno" />
									<input type="hidden" name="hdnCol1Nvl2" id="hdnCol1Nvl2" value="A" />
									<input type="hidden" name="hdnCol2Nvl2" id="hdnCol2Nvl2" value="A" />
									<b>TOTAL</b>
								</td>
								<td width="1%"><b>:</b></td>
								<td width="15%"><input name="txtSubTot" id="txtSubTot" class="txt-plano" style="width: 98%; text-align:right" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divGIngresos" style="position:absolute; width:90%; left:6%; top:5px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra el cuadro de dialogo."
										onClick="javascript:
											if(document.getElementById('divIngresos').style.visibility=='hidden') Deshabilita(false); 
											OcultaFlechas(2, true);
											CierraDialogo('divGIngresos','frmGIngresos');"
										onmouseover="javascript: window.status='Cierra el cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>Detalles Gu&iacute;a de Ingresos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%" align="left"><b>&nbsp;Material</b></td>
								<td width="1%"><b>:</b></td>
								<td width="90%"><input name="txtMaterial" id="txtMaterial" class="txt-sborde" style="width:98%" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<th width="3%">N&deg;</th>
								<th width="9%">
									<a href="#" title="Ordena por la columna Guía de Ingreso." style="color:#000000"
										onclick="javascript: CambiaOrden('nvl3col1', 2)"
										onmouseover="javascript: window.status='Ordena la columna Guía de Ingreso'; return true;"
									>G.Ingreso</a>
								</th>
								<th width="1%">
									<a href="#" title="Ordena por la columna Guía de Ingreso."
										onclick="javascript: CambiaOrden('nvl3col1', 2)"
										onmouseover="javascript: window.status='Ordena la columna Guía de Ingreso'; return true;"
									><img id="nvl3col1" border="0" align="middle" src="../images/arr.gif" /></a>
								</th>
								<th width="10%">Fecha</th>
								<th width="20%" align="left">&nbsp;Tipo Doc.</th>
								<th width="10%">N&deg; Ref.</th>
								<th width="15%">N&deg; O.Compra</th>
								<th width="10%" align="right">Cantidad&nbsp;</th>
								<th width="10%" align="right">Valor&nbsp;</th>
								<th width="9%" align="right">
									<a href="#" title="Ordena por la columna Total." style="color:#000000"
										onclick="javascript: CambiaOrden('nvl3col2', 2)"
										onmouseover="javascript: window.status='Ordena la columna Total'; return true;"
									>Total&nbsp;</a>
								</th>
								<th width="1%">
									<a href="#" title="Ordena por la columna Total."
										onclick="javascript: CambiaOrden('nvl3col2', 2)"
										onmouseover="javascript: window.status='Ordena la columna Total'; return true;"
									><img id="nvl3col2" border="0" align="middle" src="../images/arr.gif" /></a>
								</th>
								<th width="2%">&nbsp;</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmGIngresos" id="frmGIngresos" frameborder="1" style="border:thin" scrolling="auto" width="100%" height="190px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr /></td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="right">
									<input type="hidden" name="hdnMesNvl3" id="hdnMesNvl3" />
									<input type="hidden" name="hdnAnoNvl3" id="hdnAnoNvl3" />
									<input type="hidden" name="hdnMaterial" id="hdnMaterial" />
									<input type="hidden" name="hdnCol1Nvl3" id="hdnCol1Nvl3" value="A" />
									<input type="hidden" name="hdnCol2Nvl3" id="hdnCol2Nvl3" value="A" />
									<b>TOTAL</b>
								</td>
								<td width="1%"><b>:</b></td>
								<td width="15%"><input name="txtTotGI" id="txtTotGI" class="txt-plano" style="width: 98%; text-align:right" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
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
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Mes</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="cmbMes" id="cmbMes" class="sel-plano" style="width:100%"
							onchange="javascript:
								busco = 0;
								document.getElementById('frmResultado').src = '../blank.html';
								if(this.value == 'all')
									document.getElementById('tdFicha2').style.visibility = 'visible';
								else
									document.getElementById('tdFicha2').style.visibility = 'hidden';
							"
						>
							<option value="all" >Todos</option>
							<option value="01" <?php echo date('m')==01 ? 'selected' : '';?>>Enero</option>
							<option value="02" <?php echo date('m')==02 ? 'selected' : '';?>>Febrero</option>
							<option value="03" <?php echo date('m')==03 ? 'selected' : '';?>>Marzo</option>
							<option value="04" <?php echo date('m')==04 ? 'selected' : '';?>>Abril</option>
							<option value="05" <?php echo date('m')==05 ? 'selected' : '';?>>Mayo</option>
							<option value="06" <?php echo date('m')==06 ? 'selected' : '';?>>Junio</option>
							<option value="07" <?php echo date('m')==07 ? 'selected' : '';?>>Julio</option>
							<option value="08" <?php echo date('m')==08 ? 'selected' : '';?>>Agosto</option>
							<option value="09" <?php echo date('m')==09 ? 'selected' : '';?>>Septiembre</option>
							<option value="10" <?php echo date('m')==10 ? 'selected' : '';?>>Octubre</option>
							<option value="11" <?php echo date('m')==11 ? 'selected' : '';?>>Noviembre</option>
							<option value="12" <?php echo date('m')==12 ? 'selected' : '';?>>Diciembre</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">
						<select name="cmbAno" id="cmbAno" class="sel-plano" style="width:100%">
						<?php
						for($ano=2000; $ano<=date('Y'); $ano++){
							echo '<option value="'.$ano.'" '.(date('Y') == $ano ? 'selected' : '').'>'.$ano.'</option>';
						}
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td align="left">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: 
								document.getElementById('nvl1col1').src='../images/arr.gif';
								document.getElementById('nvl1col2').src='../images/arr.gif';
								Buscar(1, 'A');
							"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="160px" cellpadding="0" cellspacing="0">
				<tr>
					<td id="tdFicha1" width="80px" align="center" style="background-repeat:no-repeat;">
						<a id="aFicha1" href="#" style="color:#FFFFFF" title="Mes"
							onclick="javascript: ClickFicha(1);"
							onmouseover="javascript: window.status='Mes.'; return true;"
						>Mes</a>
					</td>
					<td id="tdFicha2" width="80px" align="center" style="background-repeat:no-repeat">
						<a id="aFicha2" href="#" title="Material"
							onclick="javascript: ClickFicha(2);"
							onmouseover="javascript: window.status='Material.'; return true;"
						>Material</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="2%">&nbsp;</th>
					<th width="84%" align="left">
						<a href="#" title="Ordena la columna Detalle" style="color:#000000"
							onclick="javascript: CambiaOrden('nvl1col1', 0)"
							onmouseover="javascript: window.status='Ordena la columna Detalle'; return true;"
						>&nbsp;Detalle</a>
					</th>
					<th width="1%">
						<a href="#" title="Ordena la columna Detalle"
							onclick="javascript: CambiaOrden('nvl1col1', 0)"
							onmouseover="javascript: window.status='Ordena la columna Detalle'; return true;"
						><img id="nvl1col1" border="0" align="middle" src="../images/arr.gif" /></a>
					</th>
					<th width="9%" align="right">
						<a href="#" title="Ordena la columna Acumulado" style="color:#000000"
							onclick="javascript: CambiaOrden('nvl1col2', 0)"
							onmouseover="javascript: window.status='Ordena la columna Acumulado'; return true;"
						>Acumulado&nbsp;</a>
					</th>
					<th width="1%">
						<a href="#" title="Ordena la columna Acumulado"
							onclick="javascript: CambiaOrden('nvl1col2', 0)"
							onmouseover="javascript: window.status='Ordena la columna Acumulado'; return true;"
						><img id="nvl1col2" border="0" align="middle" src="../images/arr.gif" /></a>
					</th>
					<th width="3%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmResultado" id="frmResultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right">
						<input type="hidden" name="hdnCol1Nvl1" id="hdnCol1Nvl1" value="A" />
						<input type="hidden" name="hdnCol2Nvl1" id="hdnCol2Nvl1" value="A" />
						<b>TOTAL</b>
					</td>
					<td width="1%"><b>:</b></td>
					<td width="10%"><input name="txtTotGnral" id="txtTotGnral" class="txt-plano" style="width:98%; text-align:right" readonly="true" /></td>
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