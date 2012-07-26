<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Maestro Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(bodega){
	document.getElementById('transaccion').src = 'responsables.php?bodega=' + bodega;
}

function getBuscar(){
	Deshabilitar(true);
	document.getElementById('frmResultado').src = 'resultado.php<?php echo $parametros;?>&bodsel='+document.getElementById('cmbBodega').value+'&responsable='+document.getElementById('cmbResponsable').value+'&estado='+document.getElementById('cmbEstado').value+
	'&mes='+document.getElementById('cmbMes').value+'&ano='+document.getElementById('cmbAno').value+'&periodo='+document.getElementById('cmbPeriodo').value;
}

function Load(){
	document.getElementById('frmResultado').setAttribute('height', window.innerHeight - 95);
}

function setBloquea(ctrl){
	if (ctrl.value=='all'){
		document.getElementById('cmbPeriodo').selectedIndex = 0;
		document.getElementById('cmbPeriodo').disabled=true;
	}else
		document.getElementById('cmbPeriodo').disabled=false;
}

function Deshabilitar(sw){
	document.getElementById('cmbBodega').disabled=sw;
	document.getElementById('cmbResponsable').disabled=sw;
	document.getElementById('cmbEstado').disabled=sw;
	document.getElementById('cmbMes').disabled=sw;
	document.getElementById('cmbAno').disabled=sw;
	if(document.getElementById('cmbMes').value!='all') document.getElementById('cmbPeriodo').disabled=sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnImprimir').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function Exportar(){
	document.getElementById('transaccion').src = 'exporta.php<?php echo $parametros;?>&responsable='+document.getElementById('cmbResponsable').value+'&estado='+document.getElementById('cmbEstado').value+
	'&mes='+document.getElementById('cmbMes').value+'&ano='+document.getElementById('cmbAno').value+'&periodo='+document.getElementById('cmbPeriodo').value;
}

function Imprimir(){
	document.getElementById('transaccion').src = 'imprime.php<?php echo $parametros;?>&responsable='+document.getElementById('cmbResponsable').value+'&estado='+document.getElementById('cmbEstado').value+
	'&mes='+document.getElementById('cmbMes').value+'&ano='+document.getElementById('cmbAno').value+'&periodo='+document.getElementById('cmbPeriodo').value;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divEstado" style="z-index:0; position:absolute; top:5%; left:30%; width:40%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td class="menu_principal">
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15px" align="center">
						<a href="#" title="Cierra cuadro de dialogo."
							onClick="javascript: 
								CierraDialogo('divEstado', 'frmEstado');
								Deshabilitar(false);
							"
							onmouseover="window.status='Cierra cuadro de dialogo'; return true"
						><img border="0" align="middle" src="../images/close.png" /></a>
					</td>
					<td align="center"><b>&nbsp;Seguimiento</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="25%">Fecha</th>
					<th align="left">&nbsp;Usuario</th>
					<th width="25%">&nbsp;Estado</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><iframe name="frmEstado" id="frmEstado" width="100%" height="150px" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" src="../cargando.php"></iframe></td>
	</tr>
</table>
</div>

<div id="divCajaChica" style="z-index:1; position:absolute; top:5px; left:1%; width:98%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td class="menu_principal">
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15px" align="center">
						<a href="#" title="Cierra cuadro de dialogo."
							onClick="javascript: 
								CierraDialogo('divCajaChica', 'frmCajaChica');
								Deshabilitar(false);
							"
							onmouseover="window.status='Cierra cuadro de dialogo'; return true"
						><img border="0" align="middle" src="../images/close.png" /></a>
					</td>
					<td align="center"><b>Caja Chica</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><iframe name="frmCajaChica" id="frmCajaChica" width="100%" height="305px" frameborder="0" scrolling="auto" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%" align="center">:</td>
					<td width="15%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%" onchange="javascript: Change(this.value);">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							if($bodsel == '') $bodsel = $rst["strBodega"];
							echo '<option value="'.$rst["strBodega"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="7%">&nbsp;Responsable</td>
					<td width="1%">:</td>
					<td width="12%">
						<select name="cmbResponsable" id="cmbResponsable" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getUsuarios 3, '$bodsel'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strUsuario"].'">'.$rst["strNombre"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="4%">&nbsp;Estado</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
						<?php
						$stmt = mssql_query("EXEC Bodega..sp_getEstados 'CC'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="3%">&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="cmbMes" id="cmbMes" class="sel-plano" style="width:100%"
							onchange="javascript: setBloquea(this);"
						>
							<option value="all" >--</option>
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
					<td width="7%">
						<select name="cmbAno" id="cmbAno" class="sel-plano" style="width:100%">
						<?php
						for($i=2005; $i<=date('Y'); $i++){?>
							<option value="<?php echo $i;?>"  <?php echo date('Y')==$i ? 'selected' : '';?>><?php echo $i;?></option>
						<?php
						}
						?>
						</select>
					</td>
					<td width="4%">&nbsp;Periodo</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="cmbPeriodo" id="cmbPeriodo" class="sel-plano" style="width:100%">
							<option value="0" >--</option>
							<option value="3" >Trimestral</option>
							<option value="6" >Semestral</option>
						</select>
					</td>
					<td width="10%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar()"
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
					<th width="3%">N&deg;</th>
					<th width="8%">Fecha</th>
					<th width="8%">N&deg; C.Chica</th>
					<th width="19%" align="left">&nbsp;Bodega</th>
					<th width="15%" align="left">&nbsp;Responsable</th>
					<th width="20%" align="left">&nbsp;Nota</th>
					<th width="15%">&nbsp;Estado</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmResultado" id="frmResultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px" value="Imprimir..." 
				onclick="javascript: Imprimir();"
			/>
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
			/>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>