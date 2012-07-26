<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado de Materiales por Centro de Costo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Deshabilita(sw){
	document.getElementById('cmbMes').disabled =  sw;
	document.getElementById('cmbAno').disabled =  sw;
	document.getElementById('cmbDocumento').disabled =  sw;
	document.getElementById('cmbCCosto').disabled =  sw;
	document.getElementById('cmbBodega').disabled =  sw;
	document.getElementById('cmbMovil').disabled =  sw;
	document.getElementById('btnBuscar').disabled =  sw;
	document.getElementById('btnExportar').disabled =  sw;
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 75);
}

function getBuscar(){
	Deshabilita(true);
	document.getElementById('resultado').src='resultado.php?modulo='+document.getElementById('cmbDocumento').value+'&mes='+document.getElementById('cmbMes').value+
	'&ano='+document.getElementById('cmbAno').value+'&bodega='+document.getElementById('cmbBodega').value+'&movil='+document.getElementById('cmbMovil').value+'&ccosto='+document.getElementById('cmbCCosto').value
}

function getExportar(){
	if(resultado.document.getElementById('totfil'))
		document.getElementById('transaccion').src='exporta.php?modulo='+document.getElementById('cmbDocumento').value+'&mes='+document.getElementById('cmbMes').value+
		'&ano='+document.getElementById('cmbAno').value+'&bodega='+document.getElementById('cmbBodega').value+'&movil='+document.getElementById('cmbMovil').value+'&ccosto='+document.getElementById('cmbCCosto').value
	else
		alert('Debe buscar para exportar.');
}

function setActualizaMoviles(ctrl){
	document.getElementById('transaccion').src='transaccion.php?modulo=0&bodega='+ctrl.value;
}
-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="15%">
									<select id="cmbMes" class="sel-plano" style="width:100%">
										<option value="01" <?php echo date('m') == '01' ? 'selected' : '';?>>Enero</option>
										<option value="02" <?php echo date('m') == '02' ? 'selected' : '';?>>Febrero</option>
										<option value="03" <?php echo date('m') == '03' ? 'selected' : '';?>>Marzo</option>
										<option value="04" <?php echo date('m') == '04' ? 'selected' : '';?>>Abril</option>
										<option value="05" <?php echo date('m') == '05' ? 'selected' : '';?>>Mayo</option>
										<option value="06" <?php echo date('m') == '06' ? 'selected' : '';?>>Junio</option>
										<option value="07" <?php echo date('m') == '07' ? 'selected' : '';?>>Julio</option>
										<option value="08" <?php echo date('m') == '08' ? 'selected' : '';?>>Agosto</option>
										<option value="09" <?php echo date('m') == '09' ? 'selected' : '';?>>Septiembre</option>
										<option value="10" <?php echo date('m') == '10' ? 'selected' : '';?>>Octubre</option>
										<option value="11" <?php echo date('m') == '11' ? 'selected' : '';?>>Noviembre</option>
										<option value="12" <?php echo date('m') == '12' ? 'selected' : '';?>>Diciembre</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">
									<select id="cmbAno" class="sel-plano" style="width:100%">
								<?php
									for($ano=2005; $ano<=date('Y'); $ano++){?>
										<option value="<?php echo $ano;?>" <?php echo date('Y') == $ano ? 'selected' : '';?>><?php echo $ano;?></option>
								<?php
									}?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">&nbsp;Documento</td>
								<td width="1%">:</td>
								<td width="20%">
									<select id="cmbDocumento" class="sel-plano" style="width:100%">
										<option value="0">Despacho</option>
										<option value="1">Ingreso</option>
										<option value="2">Devoluci&oacute;n</option>
										<option value="3">Vale Consumo</option>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">&nbsp;Centro Costo</td>
								<td width="1%">:</td>
								<td width="30%">
									<select id="cmbCCosto" class="sel-plano" style="width:100%">
										<option value="all">Todos</option>
									<?php
									$stmt = mssql_query("EXEC General..sp_getCentroCosto", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
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
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="30%">
									<select id="cmbBodega" class="sel-plano" style="width:100%"
										onclick="javascript: setActualizaMoviles(this);"
									>
									<?php
									$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										if($bodsel=='') $bodsel=$rst["strBodega"];
										echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Movil</td>
								<td width="1%">:</td>
								<td width="30%">
									<select id="cmbMovil" class="sel-plano" style="width:100%">
										<option value="all">Todos</option>
										<?php
										$stmt = mssql_query("EXEC General..sp_getMoviles 12, '$bodsel'", $cnx);
										while($rst = mssql_fetch_array($stmt)){
											echo '<option value="'.trim($rst["strMovil"]).'">['.trim($rst["strMovil"]).'] '.$rst["strNombre"].'</option>';
										}
										mssql_free_result($stmt);?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td >
									<input type="button" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
										onclick="javascript: getBuscar();"
									/>
									<input type="button" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
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
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%" align="center">&nbsp;Movil</th>
					<th width="10%">N&uacute;mero</th>
					<th width="8%">Fecha</th>
					<th width="10%">C&oacute;digo</th>
					<th width="20%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="7%">Unidad</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Precio&nbsp;</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr>
		<td><table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td width="3%" align="right">&nbsp;</td>
		<td width="10%">&nbsp;</td>
		<td width="10%">&nbsp;</td>
		<td width="8%">&nbsp;</td>
		<td width="10%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
		<td width="7%">&nbsp;</td>
		<td width="10%">&nbsp;</td>
		<td width="10%" align="right">TOTAL</td>
		<td width="10%" align="right" name=tdTotal id=tdTotal>0</td>
		<td width="2%">&nbsp;</td></tr>
		</table></td>
	</tr>
</table>
<iframe id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
