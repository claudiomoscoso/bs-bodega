<?php
include '../conexion.inc.php';

$accion = $_GET["accion"];
$modulo = $_GET["modulo"];
$numero = $_GET["numero"];
if($accion == 'G' && ($modulo == 0 || $modulo == 1)){
	$solicitante = $_POST["cmbSolicitante"];
	$equipo = $_POST["txtEquipo"];
	$motivo = $_POST["txtMotivo"];
	$problema = $_POST["txtProblema"];
	$diagnostico = $_POST["txtDiagnostico"];
	$solucion = $_POST["txtSolucion"];
	$evaluacion = $_POST["txtEvaluacion"];
	$nota = $_POST["txtNota"];
	$solucionado = ($_POST["chkSolucionado"] == 'on' ? 1 : 0);
	if($modulo == 0)
		mssql_query("EXEC General..sp_setSoporte 0, '$motivo', '$solicitante', '$problema', '$equipo', '$diagnostico', '$solucion', '$evaluacion', '$nota', $solucionado", $cnx);
	else
		mssql_query("EXEC General..sp_setSoporte 1, NULL, NULL, NULL, NULL, '$diagnostico', '$solucion', '$evaluacion', '$nota', $solucionado, $numero", $cnx);
}

if($numero != ''){
	$stmt = mssql_query("EXEC General..sp_getSoporte 1, NULL, NULL, NULL, NULL, $numero", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$fecha = $rst["dtmFch"];
		$solicitante = ($rst["strSolicitante"] != '' ? $rst["strSolicitante"] : $rst["strNombre"]);
		$equipo = $rst["strEquipo"];
		$motivo = $rst["strMotivo"];
		$problema = $rst["strProblema"];
		$diagnostico = $rst["strDiagnostico"];
		$solucion = $rst["strSolucion"];
		$evaluacion = $rst["strEvaluacion"];
		$nota = $rst["strNotas"];
		$fsolucion = $rst["dtmFSolucion"];
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Soporte a Usuarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $accion;?>' == 'G'){
		parent.document.getElementById('resultado').src = parent.document.getElementById('resultado').src;
		parent.CierraDialogo('divSoporte', 'frmSoporte');
		parent.Deshabilita(false);
	}
}

function Guardar(){
	if(document.getElementById('txtProblema').value == '')
		alert('Debe ingresar el problema del usuario.');
	else
		document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load()">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF']."?accion=G&modulo=$modulo&numero=$numero";?>">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<fieldset>
				<legend>Problema</legend>
				<table border="0" width="100%" cellpadding="1" cellspacing="0">
					<tr>
						<td width="6%">&nbsp;Fecha</td>
						<td width="1%" align="center">:</td>
						<td width="93%">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td width="15%"><input class="txt-plano" style="width:100%; text-align:center" readonly="true" value="<?php echo date('d/m/Y H:i');?>" /></td>
									<td width="1%">&nbsp;</td>
									<td width="5%">&nbsp;Solicitante</td>
									<td width="1%" align="center">:</td>
									<td width="20%">
										<select name="cmbSolicitante" id="cmbSolicitante" class="sel-plano" style="width:100%" <?php echo ($numero != '' ? 'disabled="disabled"' : '');?>>
										<?php
										$stmt = mssql_query("EXEC General..sp_getUsuarios 1", $cnx);
										while($rst = mssql_fetch_array($stmt)){
											echo '<option value="'.trim($rst["usuario"]).'" '.(trim($solicitante) == trim($rst["usuario"]) ? 'selected="selected"' : '').'>'.$rst["nombre"].'</option>';
										}
										mssql_free_result($stmt);
										?>
										</select>
									</td>
									<td width="1%">&nbsp;</td>
									<td width="5%">&nbsp;Equipo</td>
									<td width="1%" align="center">:</td>
									<td width="15%">
										<input name="txtEquipo" id="txtEquipo" class="txt-plano" style="width:99%" <?php echo ($numero != '' ? 'readonly="readonly"' : '');?> value="<?php echo $equipo;?>" 
											onblur="javascript: CambiaColor(this.id, false);"
											onfocus="javascript: CambiaColor(this.id, true);"
										/>
									</td>
									<td width="1%">&nbsp;</td>
									<td width="5%">&nbsp;Motivo</td>
									<td width="1%" align="center">:</td>
									<td width="29%">
										<input name="txtMotivo" id="txtMotivo" class="txt-plano" style="width:100%" <?php echo ($numero != '' ? 'readonly="readonly"' : '');?> value="<?php echo $motivo;?>" 
											onblur="javascript: CambiaColor(this.id, false);"
											onfocus="javascript: CambiaColor(this.id, true);"
										/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr valign="top">
						<td>&nbsp;Problema</td>
						<td align="center">:</td>
						<td>
							<textarea name="txtProblema" id="txtProblema" class="txt-plano" style="width:100%" <?php echo ($numero != '' ? 'readonly="readonly"' : '');?>
								onblur="javascript: CambiaColor(this.id, false);"
								onfocus="javascript: CambiaColor(this.id, true);"
							><?php echo $problema;?></textarea>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset >
				<legend>Soluci&oacute;n</legend>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr valign="top">
						<td width="8%">&nbsp;Diagn&oacute;stico</td>
						<td width="1%" align="center">:</td>
						<td width="41%">
							<textarea name="txtDiagnostico" id="txtDiagnostico" class="txt-plano" style="width:100%" <?php echo ($fsolucion != '' ? 'readonly="readonly"' : '');?>
								onblur="javascript: CambiaColor(this.id, false);"
								onfocus="javascript: CambiaColor(this.id, true);"
							><?php echo $diagnostico;?></textarea>
						</td>
						<td width="1%">&nbsp;</td>
						<td width="6%">&nbsp;Soluci&oacute;n</td>
						<td width="1%" align="center">:</td>
						<td width="42%">
							<textarea name="txtSolucion" id="txtSolucion" class="txt-plano" style="width:100%" <?php echo ($fsolucion != '' ? 'readonly="readonly"' : '');?>
								onblur="javascript: CambiaColor(this.id, false);"
								onfocus="javascript: CambiaColor(this.id, true);"
							><?php echo $solucion;?></textarea>
						</td>
					</tr>
					<tr valign="top">
						<td >&nbsp;Evaluaci&oacute;n</td>
						<td align="center">:</td>
						<td >
							<textarea name="txtEvaluacion" id="txtEvaluacion" class="txt-plano" style="width:100%" <?php echo ($fsolucion != '' ? 'readonly="readonly"' : '');?>
								onblur="javascript: CambiaColor(this.id, false);"
								onfocus="javascript: CambiaColor(this.id, true);"
							><?php echo $evaluacion;?></textarea>
						</td>
						<td >&nbsp;</td>
						<td >&nbsp;Nota</td>
						<td align="center">:</td>
						<td >
							<textarea name="txtNota" id="txtNota" class="txt-plano" style="width:100%" <?php echo ($fsolucion != '' ? 'readonly="readonly"' : '');?>
								onblur="javascript: CambiaColor(this.id, false);"
								onfocus="javascript: CambiaColor(this.id, true);"
							><?php echo $nota;?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;Solucionado</td>
						<td align="center">:</td>
						<td valign="middle">
							<input type="checkbox" name="chkSolucionado" id="chkSolucionado" <?php echo ($fsolucion != '' ? 'checked="checked" disabled="disabled"' : '');?>/>
							<?php
							if($fsolucion != '') echo '&nbsp;Fecha: '.formato_fecha($fsolucion, true, false);
							?>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" <?php echo ($fsolucion != '' ? 'disabled="disabled"' : '');?>
				onclick="javascript: Guardar();"
			/>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>