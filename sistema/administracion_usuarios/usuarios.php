<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$accion = $_GET["accion"];

if($accion == 'G'){
	$narchivo = $HTTP_POST_FILES['flFirma']['name'];
	$tarchivo = $HTTP_POST_FILES['flFirma']['type'];
	$error = 0;
	if($tarchivo != ''){
		if ($tarchivo != 'image/jpeg') $error = 1;
		
		if ($error == 0 && !move_uploaded_file($HTTP_POST_FILES['flFirma']['tmp_name'], '../images/'.$narchivo)) $error = 2;
		if ($error == 0 && !copy('../images/'.$narchivo, '../images/'.trim(substr($narchivo, 0, -3)).'dot')) $error = 3;
		$firma = trim(substr($narchivo, 0, -3)).'dot';
	}else
		$firma = $_POST["hdnFirma"];
	
	$nombre = $_POST["txtNombre"];
	$rut = ($_POST["txtRut"] != '' ? "'".$_POST["txtRut"].'-'.$_POST["txtDigito"]."'" : 'NULL');
	$telefono = $_POST["txtTelefono"];
	$email = $_POST["txtEMail"];
	$usuario = $_POST["txtUsuario"];
	$usuarioant = $_POST["hdnUsuario"];
	$clave = $_POST["txtClave"];
	$claveant = $_POST["hdnClave"];
	$perfil = $_POST["cmbPerfil"];
	$correlativo = $_POST["txtCorrelativo"] != '' ? $_POST["txtCorrelativo"] : 'NULL';
	$monto = $_POST["txtMonto"] != '' ? $_POST["txtMonto"] : 'NULL';
	$bodegas = $_POST["hdnBodegas"];
	$vigentes = $_POST["hdnVigentes"];
	$contratos = $_POST["hdnContratos"];
	$nivel = ($_POST["chkNivel"] == 'on' ? 1 : 0);
	$control = ($_POST["chkControl"] == 'on' ? 1 : 0);
	$vigencia = ($_POST["chkVigencia"] == 'on' ? 1 : 0);
	$dpto = $_POST["txtDpto"] != '' ? $_POST["txtDpto"] : 'NULL';
	
	mssql_query("EXEC General..sp_setUsuarios 0, '$usuario', '$usuarioant', '$clave', '$claveant', '$nombre', '$perfil', '$email', '$telefono', '$bodegas', '$vigentes', '$contratos', '$firma', $nivel, $vigencia, $monto, $correlativo, $rut, $control, $dpto", $cnx);
}

$stmt = mssql_query("EXEC General..sp_getUsuarios 2, NULL, NULL, 'all', '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$nombre = $rst["nombre"];
	$ci = split('-', $rst["strRut"]);
	$telefono = $rst["telefono"];
	$email = $rst["email"];
	$usuario = $rst["usuario"];
	$clave = $rst["clave"];
	$perfil = $rst["perfil"];
	$firma = $rst["firma"];
	$correlativo = $rst["dblCorrelativo"];
	$monto = $rst["dblFondoFijo"];
	$nivel = $rst["nivel"];
	$control = $rst["dblControl"];
	$vigente = $rst["vigente"];
	$dpto = $rst["dblDpto"];
}
mssql_free_result($stmt);

$bodegas = '';
$vigentes = '';
$stmt = mssql_query("EXEC General..sp_getBodega 4, '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$bodegas .= "'".$rst["strBodega"]."',";
		$vigentes .= "'".$rst["intVigente"]."',";
	}while($rst = mssql_fetch_array($stmt));
	$bodegas = substr($bodegas, 0, -1);
	$vigentes = substr($vigentes, 0, -1);
}

$contratos = '';
$stmt = mssql_query("EXEC General..sp_getContratos 1,  '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$contratos .= "'".$rst["strContrato"]."',";
	}while($rst = mssql_fetch_array($stmt));
	$contratos = substr($contratos, 0, -1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Usuario</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl){
	switch(ctrl.id){
		case 'txtUsuario':
			if('<?php echo trim($usuario);?>' != ctrl.value)
				document.getElementById('transaccion').src = 'transaccion.php?tipo=USR&usuario=' + ctrl.value;
			break;
	}
}

function Load(){
	if('<?php echo $accion;?>' == 'G'){
		switch(parseInt('<?php echo $error;?>')){
			case 0:
				parent.CierraDialogo('divUsuario', 'frmUsuario');
				parent.Deshabilitar(false);
				return 0;
				break;
			case 1:
				alert('El usuario fue guardado, sin embargo el tipo de archivo no es el correcto.');
				break;
			case 2:
				alert('El usuario fue guardado, sin embargo no fue posible copiar el archivo adjunto (.jpg)');
				break;
			case 3:
				alert('El usuario fue guardado, sin embargo no fue posible copiar el archivo adjunto (.dot)');
				break;
		}
	}
	
	var bodegas = new Array(<?php echo $bodegas;?>);
	var vigencias = new Array(<?php echo $vigentes;?>);
	var totfil = document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		for(j = 0; j < bodegas.length; j++){
			if(document.getElementById('hdnBodega' + i).value == bodegas[j])
				document.getElementById('cmbBVigente' + i).selectedIndex = vigencias[j];
		}
	}
	
	var contratos = new Array(<?php echo $contratos;?>);
	var totfil = document.getElementById('totcont').value;
	for(i = 1; i <= totfil; i++){
		for(j = 0; j < contratos.length; j++){
			if(document.getElementById('chkSContrato' + i).value == contratos[j])
				document.getElementById('chkSContrato' + i).checked = true;
		}
	}
}

function Guardar(){
	var totfil = document.getElementById('totfil').value;
	var bodegas = '', vigentes = '', contratos = '';
	for(i = 1; i <= totfil; i++){
		if(parseInt(document.getElementById('cmbBVigente' + i).value) > 0){
			bodegas += document.getElementById('hdnBodega' + i).value + ';';
			vigentes += document.getElementById('cmbBVigente' + i).value + ';';
		}
	}
	
	var totfil = document.getElementById('totcont').value;
	for(i = 1; i <= totfil; i++){
		if(document.getElementById('chkSContrato' + i).checked){
			contratos += document.getElementById('chkSContrato' + i).value + ';';
		}
	}
	
	if(document.getElementById('txtNombre').value == '')
		alert('Debe ingresar el nombre de usuario.');
	else if(document.getElementById('txtUsuario').value == '')
		alert('Debe ingresar el usuario.');
	else if(document.getElementById('cmbPerfil').value == 'none')
		alert('Debe seleccionar un perfil de usuario.');
	else if(bodegas == '')
		alert('Debe seleccionar al menos una bodega para el usuario.');
	else{
		document.getElementById('hdnBodegas').value = bodegas.substring(0, bodegas.length - 1);
		document.getElementById('hdnVigentes').value = vigentes.substring(0, vigentes.length - 1);
		document.getElementById('hdnContratos').value = contratos.substring(0, contratos.length - 1);
		document.getElementById('frm').submit();
	}
		
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?accion=G';?>" enctype="multipart/form-data">
	<tr>
		<td>
			<table border="0" width="99%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Nombre</td>
					<td width="1%" align="center">:</td>
					<td width="54%">
						<input name="txtNombre" id="txtNombre" class="txt-plano" style="width:99%" value="<?php echo $nombre;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;R.U.T</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtRut" id="txtRut" class="txt-plano" style="width:99%; text-align:right" maxlength="8" value="<?php echo $ci[0];?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
							onkeyup="javascript: ValRut(this.value, 'txtDigito');"
						/>
					</td>
					<td width="2%" align="center">&ndash;</td>
					<td width="2%"><input name="txtDigito" id="txtDigito" class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo $ci[1];?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Tel&eacute;fono</td>
					<td width="1%" align="center">:</td>
					<td width="14%">
						<input name="txtTelefono" id="txtTelefono" class="txt-plano" style="width:99%" value="<?php echo $telefono;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4%">&nbsp;E-Mail</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<input name="txtEMail" id="txtEMail" class="txt-plano" style="width:99%" value="<?php echo $email;?>"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Usuario</td>
					<td width="1%" align="center">:</td>
					<td width="19%">
						<input name="txtUsuario" id="txtUsuario" class="txt-plano" style="width:99%" value="<?php echo trim($usuario);?>" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Clave</td>
					<td width="1%" align="center">:</td>
					<td width="19%">
						<input name="txtClave" id="txtClave" class="txt-plano" style="width:99%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="3%">&nbsp;Perfil</td>
					<td width="1%" align="center">:</td>
					<td width="15%">
						<select name="cmbPerfil" id="cmbPerfil" class="sel-plano" style=" width:100%">
						<?php
						echo '<option value="none">--</option>';
						$stmt = mssql_query("SELECT strGrupo FROM General..Menu Group BY strGrupo ORDER BY strGrupo", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strGrupo"].'" '.($rst["strGrupo"] == $strGrupo ? 'selected="selected"' : '').'>'.$rst["strGrupo"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
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
					<td width="28%">
						<fieldset>
							<legend>Bodegas</legend>
							<div id="divBodegas" style="position:relative; border:solid 1px; height:80px; overflow:scroll">
							<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
							<?php
							$stmt = mssql_query("EXEC General..sp_getBodega 6", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								$cont++;
								echo '<tr>';
								echo '<td width="82%">';
								echo '<input type="hidden" name="hdnBodega'.$cont.'" id="hdnBodega'.$cont.'" value="'.$rst["strCodigo"].'" />';
								echo '<input class="txt-sborde" style="width:100%" readonly="true" value="&nbsp;'.$rst["strDetalle"].'" >';
								echo '</td>';
								echo '<td width="18%">';
								echo '<select name="cmbBVigente'.$cont.'" id="cmbBVigente'.$cont.'" class="sel-plano" style="width:100%">';
								echo '<option value="0">0</option>';
								echo '<option value="1">1</option>';
								echo '<option value="2">2</option>';
								echo '</select>';
								echo '</td>';
								echo '</tr>';
							}
							mssql_free_result($stmt);
							?>
							</table>
							<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
							</div>
						</fieldset>
					</td>
					<td width="28%">
						<fieldset>
							<legend>Contrato</legend>
							<div id="divBodegas" style="position:relative; border:solid 1px; height:80px; overflow:scroll">
							<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
							<?php
							$cont = 0;
							$stmt = mssql_query("EXEC General..sp_getContratos 5", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								$cont++;
								echo '<tr>';
								echo '<td width="2%">';
								echo '<input type="checkbox" name="chkSContrato'.$cont.'" id="chkSContrato'.$cont.'" value="'.$rst["strCodigo"].'" />';
								echo '</td>';
								echo '<td width="98%"><input class="txt-sborde" style="width:100%" readonly="true" value="&nbsp;'.$rst["strDetalle"].'" ></td>';
								echo '</tr>';
							}
							mssql_free_result($stmt);
							?>
							</table>
							<input type="hidden" name="totcont" id="totcont" value="<?php echo $cont;?>" />
							</div>
						</fieldset>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="43%" valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="5%">&nbsp;Firma</td>
											<td width="1%" align="center">:</td>
											<td width="0%">
												<input type="file" name="flFirma" id="flFirma" />
												<b>&nbsp;Solo *.jpg</b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr style="height:30px">
											<td width="17%"><b>&nbsp;Fondo fijo</b></td>
											<td width="0%"><hr /></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center">
									<table border="0" width="70%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%">&nbsp;Correlativo</td>
											<td width="1%" align="center">:</td>
											<td width="0%">
												<input name="txtCorrelativo" id="txtCorrelativo" class="txt-plano" style="width:99%; text-align:center" value="<?php echo $correlativo;?>"
													onblur="javascript: CambiaColor(this.id, false);"
													onfocus="javascript: CambiaColor(this.id, true);"
													onkeypress="javascript: return ValNumeros(event, this.id, false);"
												 />
											</td>
											<td width="1%">&nbsp;</td>
											<td width="5%">&nbsp;Monto</td>
											<td width="1%" align="center">:</td>
											<td width="0%">
												<input name="txtMonto" id="txtMonto" class="txt-plano" style="width:99%; text-align:center" value="<?php echo $monto;?>"
													onblur="javascript: CambiaColor(this.id, false);"
													onfocus="javascript: CambiaColor(this.id, true);"
													onkeypress="javascript: return ValNumeros(event, this.id, false);"
												 />
											</td>
											<td width="0%">&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td><hr /></td></tr>
							<tr>
								<td align="center">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="2%"><input type="checkbox" name="chkNivel" id="chkNivel" <?php echo ($nivel == '1' ? 'checked' : '');?>/></td>
											<td width="1%">&nbsp;</td>
											<td width="0%">Nivel</td>
											<td width="1%">&nbsp;</td>
											<td width="2%"><input type="checkbox" name="chkControl" id="chkControl" <?php echo ($control == '1' ? 'checked' : '');?>/></td>
											<td width="1%">&nbsp;</td>
											<td width="0%">Control</td>
											<td width="1%">&nbsp;</td>
											<td width="2%"><input type="checkbox" name="chkVigencia" id="chkVigencia" <?php echo ($vigente == '1' ? 'checked' : '');?>/></td>
											<td width="1%">&nbsp;</td>
											<td width="0%">Vigencia</td>
											<td width="1%">&nbsp;</td>
											<td width="5%">&nbsp;Depto.</td>
											<td width="1%" align="center">:</td>
											<td width="0%">
												<input name="txtDpto" id="txtDpto" class="txt-plano" style="width:25px; text-align:center" maxlength="3" value="<?php echo number_format($dpto, 0, '', '');?>"
													onblur="javascript: CambiaColor(this.id, false);"
													onfocus="javascript: CambiaColor(this.id, true);"
													onkeypress="javascript: return ValNumeros(event, this.id, false);"
													onkeyup="javascript: if(this.value == '') this.value = 0;"
												/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>	
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="hdnUsuario" id="hdnUsuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="hdnClave" id="hdnClave" value="<?php echo $clave;?>" />
			<input type="hidden" name="hdnFirma" id="hdnFirma" value="<?php echo $firma;?>" />
			<input type="hidden" name="hdnBodegas" id="hdnBodegas" />
			<input type="hidden" name="hdnVigentes" id="hdnVigentes" />
			<input type="hidden" name="hdnContratos" id="hdnContratos" />
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:80px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
		</td>
	</tr>
</form>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
