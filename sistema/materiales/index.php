<?php
include '../conexion.inc.php';

$consulta = $_GET["consulta"];
$tipounidad = $_GET["tipounidad"];
$tipofamilia = $_GET["tipofamilia"];
$tipo = $_GET["tipo"];
$interna = $_POST["interna"];
$accion = $_GET["accion"];
if($accion == 'G'){
	$codigo = $_POST["hdnCodigo"];
	$descripcion = $_POST["txtDescripcion"];
	$familia = $_POST["cmbFamilia"];
	$unidad = $_POST["cmbUnidad"];
	
	if($codigo == ''){
		$stmt = mssql_query("EXEC Bodega..sp_setMateriales 0, NULL, '$familia', '".Reemplaza($descripcion)."', '$unidad', '$tipounidad'", $cnx);
		if($rst = mssql_fetch_array($stmt)){
			$error = $rst["dblError"];
			$codigo = $rst["dblNumero"];
		}
		mssql_free_result($stmt);
	}else
		mssql_query("EXEC Bodega..sp_setMateriales 1, '$codigo', '$familia', '".Reemplaza($descripcion)."', '$unidad', '$tipounidad'", $cnx);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function KeyPress(evento, ctrl){
	//if('<?php echo trim($consulta);?>' != ''){
		var tecla = getCodigoTecla(evento);
		if(tecla == 13){
			if(ctrl.id == 'txtDescripcion'){
				Deshabilita(true);
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?texto=' + ctrl.value);
			}
		}
	//}
}

function Load(){
	document.getElementById('tdEspacio').style.height = (window.innerHeight - 65) + 'px';
	if('<?php echo $accion;?>' == 'G'){
		switch(parseInt('<?php echo $error;?>')){
			case 0:
				alert('El material ha sido guardado con el código <?php echo $codigo;?>');
				if('<?php echo $consulta;?>' != '')
					self.location.href = '../orden_compra_manual/buscar_material.php?texto=<?php echo $consulta;?>';
				break;
			case 1:
				alert('No se ha podido obtener el nuevo código.')
		}
	}
}

function Cancelar(){
	self.location.href = '../orden_compra_manual/buscar_material.php?texto=<?php echo $consulta;?>';
}

function Deshabilita(sw){
	document.getElementById('txtDescripcion').disabled = sw;
	document.getElementById('cmbFamilia').disabled = sw;
	document.getElementById('cmbUnidad').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
	if(document.getElementById('btnCancel')) document.getElementById('btnCancel').disabled = sw;
}

function Guardar(){
	document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load();">
<div id="divMateriales" style="position:absolute; top:5px; left:10%; width:80%; visibility:hidden">
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
										onclick="javascript: 
											//Deshabilita(false);
											CierraDialogo('divMateriales', 'frmMateriales');
										"
										onmouseover="javascript: window.status='Cierra cuadro de dialogo.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000;font-size:12px;font-weight:bold">Buscador</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMateriales" id="frmMateriales" frameborder="1" style="border:thin" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF']."?accion=G&consulta=$consulta&tipounidad=$tipounidad&tipofamilia=$tipofamilia";?>">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="9%">&nbsp;Descripci&oacute;n</td>
		<td width="1%" align="center">:</td>
		<td width="90%">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="100%">
						<input type="hidden" name="hdnCodigo" id="hdnCodigo" value="<?php echo $codigo;?>" />
						<input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width:99%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
							onkeyup="javascript: if(this.value == '') document.getElementById('hdnCodigo').value = '';"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td >&nbsp;Familia</td>
		<td align="center">:</td>
		<td >
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="0%">
						<select name="cmbFamilia" id="cmbFamilia" class="sel-plano" style="width:100%">
						<?php
						if($tipo == 'A' && $interna == 0){
							$stmt = mssql_query("EXEC Bodega..sp_getFamilia 2", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								echo '<option value="'.trim($rst["strCodigo"]).'">'.trim($rst["strDetalle"]).'</option>';
							}
							mssql_free_result($stmt);
						}elseif($tipo == 'A' && $interna == 1){
							$stmt = mssql_query("EXEC Bodega..sp_getFamilia 4", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								echo '<option value="'.trim($rst["strCodigo"]).'">'.trim($rst["strDetalle"]).'</option>';
							}
							mssql_free_result($stmt);
						}else{
							echo '<optgroup label="Cargos" style="font-style:normal">';
							$stmt = mssql_query("EXEC Bodega..sp_getFamilia 3", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								echo '<option value="'.trim($rst["strCodigo"]).'">'.trim($rst["strDetalle"]).'</option>';
							}
							mssql_free_result($stmt);
							
							echo '</optgroup>';
							echo '<optgroup label="Materiales" style="font-style:normal">';
							$stmt = mssql_query("EXEC Bodega..sp_getFamilia 1", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								echo '<option value="'.trim($rst["strCodigo"]).'">'.trim($rst["strDetalle"]).'</option>';
							}
							mssql_free_result($stmt);
							echo '</optgroup>';
						}
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Unidad</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbUnidad" id="cmbUnidad" class="sel-plano" style="width:100%">
						<?php
						if($tipo == 'A')
							$stmt = mssql_query("EXEC General..sp_getUnidades 1", $cnx);
						else
							$stmt = mssql_query("EXEC General..sp_getUnidades 0", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strDetalle"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td id="tdEspacio" colspan="3" valign="bottom" style="height:100px"><hr /></td></tr>
	<tr>
		<td colspan="3" align="right">
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
			<?php
			if($consulta != ''){?>
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width:90px" value="Cancelar" 
				onclick="javascript: Cancelar()"
			/>
			<?php
			}
			?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>