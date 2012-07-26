<?php
include '../autentica.php';
include '../conexion.inc.php';

$modulo = $_GET["modulo"] != '' ? $_GET["modulo"] : $_POST["modulo"];
$contrato = $_GET["contrato"] != '' ? $_GET["contrato"] : $_POST["contrato"];
$numero = $_GET["numero"] != '' ? $_GET["numero"] : $_POST["numero"];

if($modulo == 1){
	$movil = $_POST["cmbMovil"];
	$observacion = $_POST["txtTrabajo"];
	$anexo = $_POST["cmbTAnexo"];
	$tanexo = $_POST["hdnTAnexo"];
	$stmt = mssql_query("EXEC Orden..sp_setAnexo 0, '$usuario', $numero, '$contrato', '$movil', '$tanexo: $observacion', '$anexo'", $cnx);
	if($rst = mssql_fetch_array($stmt))	$error = $rst["dblError"];
	mssql_free_result($stmt);
}

$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 3, '$usuario', '$contrato', NULL, $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$movil = $rst["strNombre"];
	$numodt = $rst["strOrden"];
	$direccion = $rst["strDireccion"];
	$comuna = $rst["strDescComuna"];
	$entrecalles = $rst["strEntreCalle"];
	$prioridad = $rst["strDescPrioridad"];
	$trabajo = $rst["strMotivo"];
	$cerrada = $rst["intCerrada"];
	$epago = $rst["dblEP"];
	$estado = $rst["strEstado"];
}
mssql_free_result($stmt);

$sololectura = 0;
if($cerrada == 1 || $cerrada == 2 || $estado == '07009'){
	$sololectura = 1;
	if($perfil == 'j.cobranza' || $perfil == 'informatica') $sololectura = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Anexos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if(parent.name == 'workspace')
		document.getElementById('frmAnexos').setAttribute('height', window.innerHeight - 177);
	else
		document.getElementById('frmAnexos').setAttribute('height', window.innerHeight - 183);	
	
	//if(parseInt('<?php echo $sololectura;?>') != 0 || parseInt('<?php echo $epago;?>') > 0) setSoloLectura();
	document.getElementById('frmAnexos').src = 'resumen_anexos.php?usuario=<?php echo $usuario;?>&contrato=<?php echo $contrato;?>&numero=<?php echo $numero;?>&cerrada=<?php echo $cerrada;?>&estado=<?php echo $estado;?>';
}

function setEnvia(sw){
	if(sw == 'A'){
		if(parent.name == 'workspace')
			self.location.href='index.php<?php echo $parametros;?>';
		else
			parent.location.href='../edita_orden_trabajo/index.php<?php echo $parametros;?>';
	}else if(sw == 'F'){
		if(document.getElementById('txtTrabajo').value == '')
			alert('Debe ingresar el trabajo a realizar.');
		else{
			document.getElementById('btnFinalizar').disabled = true;
			document.getElementById('frm').submit();
		}
	}
}

function setSoloLectura(){
	document.getElementById('cmbMovil').disabled = true;
	document.getElementById('cmbTAnexo').disabled = true;
	document.getElementById('txtTrabajo').setAttribute('readOnly', true);
	document.getElementById('btnFinalizar').disabled = true;
}
-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="20%" align="left">&nbsp;Movil</th>
					<th width="10%">Fecha</th>
					<th width="30%" align="left">&nbsp;Trabajo Anexo</th>
					<th width="20%" align="left">&nbsp;Estado</th>
					<th width="10%" >Fch. Baja</th>
					<th width="5%" align="center">Quitar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmAnexos" id="frmAnexos" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<form name="frm" id="frm" method="post" target="transaccion" action="transaccion.php<?php echo $parametros;?>&modulo=5&contrato=<?php echo $contrato;?>&cerrada=<?php echo $cerrada;?>&estado=<?php echo $estado;?>">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<?php echo (($cerrada == 1 || $cerrada == 2 || $estado == '07009') ? '<td width="20%"><b>Anexos posterior a cierre.</b></td>' : '');?>
					<td ><hr /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<td width="9%">&nbsp;Movil Anexo</td>
					<td width="1%">:</td>
					<td width="90%">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="37%">
									<select name="cmbMovil" id="cmbMovil" class="sel-plano" style="width:100%">
									<?php
									if($contrato == '13045')
										$stmt = mssql_query("EXEC General..sp_getMoviles 14, '$contrato'", $cnx);
									else
										$stmt = mssql_query("EXEC General..sp_getMoviles 6, NULL, '$contrato'", $cnx);
									while($rst = mssql_fetch_array($stmt)){
										echo '<option value="'.trim($rst["strMovil"]).'">['.trim($rst["strMovil"]).'] '.$rst["strNombre"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="10%">&nbsp;Movil Hidraul.</td>
								<td width="1%">:</td>
								<td width="35%"><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="<?php echo $movil;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="4%">N&deg;ODT</td>
								<td width="1%">:</td>
								<td width="10%"><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="<?php echo $numodt;?>" /></td>
							</tr>
						</table>
					</td>						
				</tr>
				<tr>
					<td>&nbsp;Direcci&oacute;n</td>
					<td>:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="32%"><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="<?php echo $direccion;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;Comuna</td>
								<td width="1%">:</td>
								<td width="20%"><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="<?php echo $comuna;?>" /></td>
								<td width="1%" >&nbsp;</td>
								<td width="10%">&nbsp;Entre Calles</td>
								<td width="1%">:</td>
								<td width="29%" ><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="<?php echo $entrecalles;?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<td>&nbsp;Prioridad</td>
					<td>:</td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<td width="15%"><input class="txt-sborde" style="width:100%; background-color:#ececec" readonly="true" value="<?php echo $prioridad;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="9%">&nbsp;Tipo Anexo</td>
								<td width="1%">:</td>
								<td width="18%">
									<select name="cmbTAnexo" id="cmbTAnexo" class="sel-plano" style="width:100%"
										onchange="javascript: document.getElementById('hdnTAnexo').value = this.value;"
									>
									<?php
									$stmt = mssql_query("EXEC General..sp_getEstados 0, '$contrato'", $cnx);
									while($rst = mssql_fetch_array($stmt)){
										if($tanexo=='') $tanexo=$rst["strDetalle"];
										echo '<option value="'.$rst["strDetalle"].'">'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="13%">&nbsp;Trabajo a realizar</td>
								<td width="1%" >:</td>
								<td width="41%">
									<input name="txtTrabajo" id="txtTrabajo" class="txt-plano" style="width:100%"
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="bottom" style="height:65px"><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<input type="button" name="btnAnterior" id="btnAnterior" class="boton" style="width:90px" value="&lt;&lt; Anterior" 
							onclick="javascript: setEnvia('A');"
						/>
					</td>
					<td width="50%" align="right">
						<input type="hidden" name="modulo" id="modulo" value="1" />
						<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
						<input type="hidden" name="hdnTAnexo" id="hdnTAnexo" value="<?php echo $tanexo;?>" />
						<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
						<input type="hidden" name="contrato" id="contrato" value="<?php echo $contrato;?>" />
						
						<input type="button" name="btnFinalizar" id="btnFinalizar" class="boton" style="width:90px" <?php echo (($estado == '07009' || $epago > 0) ? 'disabled' : '');?> value="Guardar" 
							onclick="javascript: setEnvia('F');"
						/>
					</td>
				</tr>
			</table>
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
