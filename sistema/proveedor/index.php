<?php
include '../conexion.inc.php';
$accion=$_POST["accion"] !='' ? $_POST["accion"] : $_GET["accion"];
$modulo=$_POST["modulo"] != '' ? $_POST["modulo"] : $_GET["modulo"];
$consulta=$_GET["consulta"];

$error = 2;
if($accion=='G'){
	$rut=$_POST["rut"] !='' ? $_POST["rut"] : $_GET["rut"];
	$dig=$_POST["dig"] !='' ? $_POST["dig"] : $_GET["dig"];
	$nombre=$_POST["nombre"] !='' ? $_POST["nombre"] : $_GET["nombre"];
	$direccion=$_POST["direccion"] !='' ? $_POST["direccion"] : $_GET["direccion"];
	$comuna=$_POST["comuna"] !='' ? $_POST["comuna"] : $_GET["comuna"];
	$telefono=$_POST["telefono"] !='' ? $_POST["telefono"] : $_GET["telefono"];
	$fax=$_POST["fax"] !='' ? $_POST["fax"] : $_GET["fax"];
	$vendedor=$_POST["vendedor"] !='' ? $_POST["vendedor"] : $_GET["vendedor"];
	$email=$_POST["email"] !='' ? $_POST["email"] : $_GET["email"];
	
	$stmt = mssql_query("SELECT strRut FROM proveedor WHERE strRut='$rut-$dig'", $cnx);
	if($rst=mssql_fetch_array($stmt))
		mssql_query("EXEC Bodega..sp_ActualizaProveedor '$rut-$dig', '".Reemplaza($nombre)."', '".Reemplaza($direccion)."', '$comuna', '$telefono', '$fax', '".Reemplaza($vendedor)."', '$email'", $cnx);
	else{
		$stmt1 = mssql_query("EXEC Bodega..sp_ConsultarCorrelativo 'PRV'", $cnx);
		if($rst1 = mssql_fetch_array($stmt1)) $codigo = $rst1["dblNumero"];
		mssql_free_result($stmt1);
		$codigo++;
		
		$stmt1 = mssql_query("EXEC Bodega..sp_AgregaProveedor '$codigo', '$rut-$dig', '".Reemplaza($nombre)."', '".Reemplaza($direccion)."', '$comuna', '$telefono', '$fax', '".Reemplaza($vendedor)."','$email'", $cnx);
		if($rst1 = mssql_fetch_array($stmt1)) $error = $rst1["dblError"];
		mssql_free_result($stmt1);
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Proveedores</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $error;?>') == 0)
		self.location.href='../<?php echo $modulo;?>/buscar_proveedor.php?texto=<?php echo $consulta;?>';
	else if(parseInt('<?php echo $error;?>') == 1)
		alert('El proveedor ya existe...!!!');
}

function Grabar(){
	if(document.getElementById('rut').value==''){
		alert('Debe ingresar el R.U.T. del proveedor');
	}else if(document.getElementById('nombre').value==''){
		alert('Debe ingresar el nombre del proveedor');
	}else if(document.getElementById('direccion').value==''){
		alert('Debe ingresar la dirección del proveedor');
	}else
		document.getElementById('frm').submit();
}

function BusquedaRapida(valor){
	if(valor) document.getElementById('transaccion').src='transaccion.php?valor='+valor;
}
-->
</script>
<body onload="javascript: Load()">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr><td class="menu_principal">&nbsp;<b>:: Nuevo Proveedor</b></td></tr>
	<tr><td style="height:10px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;R.U.T.</td>
					<td width="1%">:</td>
					<td >
						<table border="0" width="100%" cellpadding="1" cellspacing="0">
							<tr>
								<td width="20%">
									<input name="rut" id="rut" class="txt-plano" style="width:100%; text-align:right;" maxlength="8" 
										onblur="javascript: 
											BusquedaRapida(this.value+'-'+document.getElementById('dig').value);
											CambiaColor(this.id, false);
											document.getElementById('nombre').focus();
										"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return ValNumeros(event, this.id, false);"
										onkeyup="javascript: ValRut(this.value, 'dig');"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&ndash;</td>
								<td width="5%"><input name="dig" id="dig" class="txt-plano" style="width:99%; text-align:center" readonly="true" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Nombre</td>
								<td width="1%">:</td>
								<td width="60%" align="right">
									<input name="nombre" id="nombre" class="txt-plano" style="width:98%;" 
										onblur="javascript: CambiaColor(this.id, false)"
										onfocus="javascript: CambiaColor(this.id, true)"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;Direcci&oacute;n</td>
					<td>:</td>
					<td>
						<table border="0" width="100%" cellpadding="1" cellspacing="0">
							<tr>
								<td width="47%">
									<input name="direccion" id="direccion" class="txt-plano" style="width:99%" 
										onblur="javascript: CambiaColor(this.id, false)"
										onfocus="javascript: CambiaColor(this.id, true)"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" >&nbsp;Comuna</td>
								<td width="1%">:</td>
								<td width="46%">
									<select name="comuna" id="comuna" class="sel-plano" style="width:100%">
									<?php
									$stmt = mssql_query("EXEC General..sp_ListarComunas", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.trim($rst["strCodigo"]).'">'.$rst["strDetalle"].'</option>';
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
					<td>&nbsp;Tel&eacute;fono</td>
					<td>:</td>
					<td >
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="20%">
									<input name="telefono" id="telefono" class="txt-plano" style="width:99%" 
										onfocus="javascript: CambiaColor(this.id, true)"
										onblur="javascript: CambiaColor(this.id, false)"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Fax</td>
								<td width="1%">:</td>
								<td width="20%">
									<input name="fax" id="fax" class="txt-plano" style="width:100%" 
										onfocus="javascript: CambiaColor(this.id, true)"
										onblur="javascript: CambiaColor(this.id, false)"
									/>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" nowrap="nowrap">&nbsp;E-Mail</td>
								<td width="1%">:</td>
								<td width="46%">
									<input name="email" id="email" class="txt-plano" style="width:100%" 
										onfocus="javascript: CambiaColor(this.id, true)"
										onblur="javascript: CambiaColor(this.id, false)"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;Vendedor</td>
					<td>:</td>
					<td >
						<input name="vendedor" id="vendedor" class="txt-plano" style="width:99%" 
							onfocus="javascript: CambiaColor(this.id, true)"
							onblur="javascript: CambiaColor(this.id, false)"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><hr /></td></tr>
	<tr>
	<td align="right">
		<input type="hidden" name="accion" id="accion" value="G" />
		<input type="hidden" name="modulo" id="modulo" value="<?php echo $modulo;?>" />
		<input type="button" name="btnOk" id="btnOk" class="boton" style="90px" value="Guardar" 
			onclick="javascript: Grabar();"
		/>
		<input type="button" name="btnCancel" id="btnCancel" class="boton" style="90px" value="Cancelar" 
			onclick="javascript: self.location.href='../<?php echo $modulo;?>/buscar_proveedor.php?texto=<?php echo $consulta;?>';"
		/>
	</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>