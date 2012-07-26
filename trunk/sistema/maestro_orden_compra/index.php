<?php
include '../autentica.php';
include '../conexion.inc.php';

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'GNR', NULL, '$usuario'", $cnx);
if($rst=mssql_fetch_array($stmt)) $nivel=$rst["nivel"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Maestro de Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 52);
}

function Busca(){
	if(document.getElementById('ano').value==0){
		alert('Debe ingresar el año de busqueda.');
		document.getElementById('ano').focus();
	}else{
		var cargo = document.getElementById('cargo').value;
		var estado = document.getElementById('estado').value;
		var mes = document.getElementById('mes').value;
		var ano = document.getElementById('ano').value;
		var periodo = document.getElementById('periodo').value;
		Deshabilitar(true);
		document.getElementById('detalle').src='resultado.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&cargo=' +
		cargo + '&estado=' + estado + '&mes=' + mes + '&ano=' + ano + '&periodo=' + periodo;
	}
}

function Deshabilitar(sw){
	document.getElementById('cargo').disabled = sw;
	document.getElementById('estado').disabled = sw;
	document.getElementById('mes').disabled = sw;
	document.getElementById('ano').disabled = sw;
	document.getElementById('periodo').disabled = sw;
	document.getElementById('btnOk').disabled = sw;
}

-->
</script>
<body onload="javascript: Load();">
<div id="divGeneraPDF" style="z-index:510; position:absolute; top:5px; left:3%; width:95%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)" height="20px">
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onclick="javascript: 
											CierraDialogo('divGeneraPDF', 'frmGeneraPDF');
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center"><b>Orden de Compra</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmGeneraPDF" id="frmGeneraPDF" frameborder="0" scrolling="no" width="100%" height="310px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divOrdenCompra" style="z-index:500; position:absolute; top:5px; left:3%; width:95%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)" height="20px">
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
											Deshabilitar(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center"><b>Orden de Compra</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmOrdenCompra" id="frmOrdenCompra" frameborder="0" scrolling="no" width="100%" height="310px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divEstado" style="z-index:500; position:absolute; top:5px; left:30%; width:40%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr height="15px" style="background-image:url(../images/borde_med.png)">
					<td width="15px">
						<a href="#"
							onClick="javascript: 
								CierraDialogo('divEstado', 'frmEstado');
								Deshabilitar(false);
							"
						><img border="0" src="../images/close.png" /></a>
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
					<th width="30%">Fecha</th>
					<th width="40%" align="left">&nbsp;Usuario</th>
					<th width="30%" align="left">&nbsp;Estado</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><iframe name="frmEstado" id="frmEstado" width="100%" height="150px" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" src="../cargando.php"></iframe></td>
	</tr>
</table>
</div>

<div id="divObservacion" style="z-index:500; position:absolute; top:5px; left:30%; width:40%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr height="15px" style="background-image:url(../images/borde_med.png)">
					<td width="15px">
						<a href="#"
							onClick="javascript: 
								CierraDialogo('divObservacion', 'frmObservacion');
								Deshabilitar(false);
							"
						><img border="0" src="../images/close.png" /></a>
					</td>
					<td align="center"><b>&nbsp;Observaciones</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmObservacion" id="frmObservacion" width="100%" height="150px" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" src="../cargando.php"></iframe></td></tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td >
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%" align="left">&nbsp;Cargo</td>
					<td width="1%">:</td>
					<td width="20%"><select name="cargo" id="cargo" class="sel-plano" style="width:100%">
                      <option value="all">Todas</option>
                      <?php
						$sql = ($bodega == '12000') ? "EXEC General..sp_getCargos 1, '$bodega'" : "EXEC General..sp_getCargos 13, NULL, '$usuario'";
						$stmt = mssql_query($sql, $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);?>
                    </select></td>
					<td width="1%">&nbsp;</td>
					<td width="5%" >&nbsp;Estado</td>
					<td width="1%">:</td>
					<td width="20%">
						<select name="estado" id="estado" class="sel-plano" style="width:100%">
							<option value="all">Todas</option>
						<?php
						$stmt = mssql_query("EXEC Bodega..sp_getEstados 'IOC', 1, NULL", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strCodigo"]).'">'.trim($rst["strDetalle"]).'</option>';
						}
						mssql_free_result($stmt);?>
						</select>					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%" >&nbsp;Mes/A&ntilde;o</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="mes" id="mes" class="sel-plano" style="width:100%">
							<option value="1" <?php echo 1==date('m') ? 'selected' : '';?>>Enero</option>
							<option value="2" <?php echo 2==date('m') ? 'selected' : '';?>>Febrero</option>
							<option value="3" <?php echo 3==date('m') ? 'selected' : '';?>>Marzo</option>
							<option value="4" <?php echo 4==date('m') ? 'selected' : '';?>>Abril</option>
							<option value="5" <?php echo 5==date('m') ? 'selected' : '';?>>Mayo</option>
							<option value="6" <?php echo 6==date('m') ? 'selected' : '';?>>Junio</option>
							<option value="7" <?php echo 7==date('m') ? 'selected' : '';?>>Julio</option>
							<option value="8" <?php echo 8==date('m') ? 'selected' : '';?>>Agosto</option>
							<option value="9" <?php echo 9==date('m') ? 'selected' : '';?>>Septiembre</option>
							<option value="10" <?php echo 10==date('m') ? 'selected' : '';?>>Octubre</option>
							<option value="11" <?php echo 11==date('m') ? 'selected' : '';?>>Noviembre</option>
							<option value="12" <?php echo 12==date('m') ? 'selected' : '';?>>Diciembre</option>
						</select>					</td>
					<td width="1%">&nbsp;</td>
					<td width="7%">
						<select name="ano" id="ano" class="sel-plano" style="width:100%">
						<?php
						for($ano=2000; $ano<=date('Y'); $ano++){
							echo '<option value="'.$ano.'" '.($ano == date('Y') ? 'selected' : '').'>'.$ano.'</option>';
						}?>
						</select>					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Periodo</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="periodo" id="periodo" class="sel-plano" style="width:100%">
							<option value="0" >--</option>
							<option value="3" >Trimestral</option>
							<option value="6" >Semestral</option>
						</select>					</td>
					<td width="1%">&nbsp;</td>
					<td>
						<input type="button" name="btnOk" id="btnOk" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Busca();"
						/>					</td>
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
					<th width="20%" align="left">&nbsp;Cargo</th>
					<th width="10%">N&deg;OC</th>
					<th width="8%">Fecha</th>
					<th width="25%" align="left">&nbsp;Nota</th>
					<th width="8%">Fecha Sol.</th>
					<th width="15%" >Estado</th>
					<th width="7%" align="left">&nbsp;Observ.</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="top"><iframe name="detalle" id="detalle" width="100%" frameborder="0" scrolling="yes" marginwidth="0" marginheight="0" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
