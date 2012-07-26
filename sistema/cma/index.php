<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consulta de Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('informe').setAttribute('height', window.innerHeight - 90);
}

function buscar(bodega){
	document.getElementById('btnOk').disabled=true;
	document.getElementById('bodega').disabled=true;
	document.getElementById('informe').src='../cargando.php';
	
	document.getElementById('informe').src='nivel1.php?usuario=<?php echo $usuario;?>&bodega='+bodega;
}

function Deshabilita(sw){
	document.getElementById('bodega').disabled=sw
	document.getElementById('btnOk').disabled=sw
	if(!sw){
		document.getElementById('TotalNivel2').value=0;
		document.getElementById('TotalNivel3').value=0;
	}
}
-->
</script>
<body onload="javascript: Load();">
<div id="divNivel2" style="position:absolute; top:5px; left:1%; width:98%; visibility:hidden">
<table border="1" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td align="center">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onclick="javascript: 
											CierraDialogo('divNivel3', 'frmNivel3');
											CierraDialogo('divNivel2', 'frmNivel2');
											Deshabilita(false);
										"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Partida</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="6">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="5%">&nbsp;<b>Partida</b></td>
											<td width="1%">:</td>
											<td><input name="strPartida" id="strPartida" readonly="true" class="txt-sborde" style="width:98%" /></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr align="center">
								<th width="2%">&nbsp;</th>
								<th width="66%" align="left">&nbsp;Descripci&oacute;n</th>
								<th width="10%" align="center">Unidad&nbsp;</th>
								<th width="10%" align="right">Cantidad&nbsp;</th>
								<th width="10%" align="right">Total&nbsp;</th>
								<th width="2%">&nbsp;</th>
							</tr>
							<tr>
								<td colspan="6" valign="top"><iframe name="frmNivel2" id="frmNivel2" frameborder="0" scrolling="yes" width="100%" height="245px" src="../cargando.php"></iframe></td>
							</tr>
							<tr><td colspan="6"><hr /></td></tr>
							<tr>
								<td align="right" colspan="6">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td align="right"><b>TOTAL</b></td>
											<td width="1%">:</td>
											<td width="10%"><input name="TotalNivel2" id="TotalNivel2" readonly="true" style="width:100%; text-align:right" class="txt-plano" value="0" /></td>
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
</table>
</div>

<div id="divNivel3" style="position:absolute; top:5px; left:25%; width:50%; height:150px; visibility:hidden">
<table border="1" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td align="center">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onclick="javascript: CierraDialogo('divNivel3', 'frmNivel3');"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center">&nbsp;<b>Material</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="5%"><b>Material</b></td>
								<td width="1%">:</td>
								<td><input name="strMaterial" id="strMaterial" class="txt-sborde" style="width:98%" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<th width="3%">N&deg;</th>
								<th width="15%">Fecha</th>
								<th width="15%">N&deg; VC</th>
								<th width="50%" align="left">&nbsp;Responsable</th>
								<th width="15%" align="right">Cantidad&nbsp;</th>
								<th width="2%">&nbsp;</th>
							</tr>
							<tr><td colspan="6"><iframe name="frmNivel3" id="frmNivel3" frameborder="0" scrolling="yes" width="100%" height="245px" src="../cargando.php"></iframe></td></tr>
							<tr><td colspan="6"><hr /></td></tr>
							<tr>
								<td align="right" colspan="6">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td align="right"><b>TOTAL</b></td>
											<td width="1%"><b>:</b></td>
											<td width="20%"><input name="TotalNivel3" id="TotalNivel3" readonly="true" style="width:98%; text-align:right" class="txt-plano" value="0" /></td>
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
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="6">
			<table border="0" width="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="30%">
						<select name="bodega" id="bodega" class="sel-plano" style="width:300px">
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
					<td align="left">
						<input type="button" name="btnOk" id="btnOk" class="boton" value="Buscar" 
							onclick="javascript: buscar(document.getElementById('bodega').value);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="6"><hr /></td></tr>
	<tr>
		<th width="3%">&nbsp;</th>
		<th width="75%" colspan="2" align="left">&nbsp;Partida</th>
		<th width="10%">Unidad</th>
		<th width="10%" align="right">Total&nbsp;</th>
		<th width="2%">&nbsp;</th>
	</tr>
	<tr><td valign="top" colspan="6"><iframe name="informe" id="informe" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td colspan="6"><hr /></td></tr>
	<tr>
		<td colspan="6">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<td align="right"><b>TOTAL</b></td>
				<td width="1%">:</td>
				<td width="10%" align="right"><input name="totgnral" id="totgnral" class="txt-plano" readonly="true" style="width:100%; text-align:right" value="0" /></td>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>