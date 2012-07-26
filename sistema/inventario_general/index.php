<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Inventario General</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmResultados').setAttribute('height', window.innerHeight - 88);	
}

function Buscar(){
	Deshabilita(true);
	document.getElementById('frmResultados').src = 'resultado.php?cargo=' + document.getElementById('cmbCargo').value + '&equipo=' + document.getElementById('cmbEquipos').value;
}

function Deshabilita(sw){
	document.getElementById('cmbCargo').disabled = sw;
	document.getElementById('cmbEquipos').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	if(document.getElementById('btnNvoCCosto')) document.getElementById('btnNvoCCosto').disabled = sw;
}

function Change(cargo){
	document.getElementById('cmbEquipos').disabled = true;
	document.getElementById('transaccion').src = 'transaccion.php?modulo=0&cargo=' + cargo;
}
-->
</script>
<body onload="javascript: Load()" >
<div id="divCCosto" style="z-index:1; position:absolute; width:70%; left: 15%; top: 5px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCCosto', 'frmCosto');
										"
										onmouseover="javascript: window.status='Cierra administrador de centros de costo.'; return true"
									title="Cierra administrador de centros de costo."><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Centro de Costos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCCosto" id="frmCCosto" frameborder="1" style="border:thin" scrolling="no" width="100%" height="185px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divReport" style="z-index:0; position:absolute; top:5px; left:5%; width:90%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#"
										onClick="javascript: 
											CierraDialogo('divEReport', 'frmEReport');
											CierraDialogo('divReport', 'frmReport');
											Deshabilita(false);
										"
									><img border="0" src="../images/close.png" /></a>
								</td>
								<td align="center"><b>&nbsp;Report</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="14%" align="left"><b>&nbsp;Centro de Costo</b></td>
								<td width="1%">:</td>
								<td width="85%"><input name="txtCCosto" id="txtCCosto" class="txt-sborde" style="width:98%" /></td>
							</tr>
							<tr>
								<td colspan="3">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<th width="3%">N&deg;</th>
											<th width="10%">&nbsp;Fecha</th>
											<th width="13%" align="left">&nbsp;Equipo</th>
											<th width="10%" align="right">Recorrido&nbsp;</th>
											<th width="10%" align="right">U.Lectura&nbsp;</th>
											<th width="5%" >Tipo</th>
											<th width="13%" align="left">&nbsp;Obra</th>
											<th width="10%" align="left">&nbsp;Estado</th>
											<th width="10%" align="left">&nbsp;Rev.T&eacute;cnica</th>
											<th width="14%" align="left">&nbsp;Observaci&oacute;n</th>
											<th width="2%">&nbsp;</th>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="3"><iframe name="frmReport" id="frmReport" width="100%" height="180px" frameborder="0" scrolling="yes" src="../cargando.php"></iframe></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divEReport" style="z-index:1; position:absolute; width:80%; left: 10%; top: 5px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: CierraDialogo('divEReport', 'frmEReport');"
										onmouseover="javascript: window.status='Cierra ventana.'; return true"
									title="Cierra administrador de centros de costo."><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Report</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmEReport" id="frmEReport" frameborder="1" style="border:thin" scrolling="no" width="100%" height="185px" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="5%" >&nbsp;Cargo</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbCargo" id="cmbCargo" class="sel-plano" style="width:100%"
							onchange="javascript: Change(this.value);"
						>
						<?php
						switch($perfil){
							case 'operaciones':
							case 's.operaciones':
								$stmt = mssql_query("EXEC General..sp_getCargos 7", $cnx);
								break;
							default:
								$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
						}
						while($rst = mssql_fetch_array($stmt)){
							if($cargo == '') $cargo = $rst["strCodigo"];
							echo '<option value="'.trim($rst["strCodigo"]).'">'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" nowrap="nowrap">&nbsp;Equipos</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbEquipos" id="cmbEquipos" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
						<?php
						$stmt = mssql_query("EXEC Operaciones..sp_getEquipos 2, '$cargo'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["dblEquipo"].'">'.$rst["strDescripcion"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:80px" value="Buscar"
							onclick="javascript: Buscar();"
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
					<th width="10%" align="center">&nbsp;C.Costo</th>
					<th width="25%" align="left">&nbsp;Equipo</th>
					<th width="20%" align="left">&nbsp;Marca</th>
					<th width="20%" align="left">&nbsp;Modelo</th>
					<th width="10%">Rev. T&eacute;cnica</th>
					<th width="10%">&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr><td colspan="8"><iframe name="frmResultados" id="frmResultados" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html"></iframe></td></tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<?php
	if($perfil == 'operaciones' || $perfil == 's.operaciones' || $perfil == 'informatica'){?>
	<tr>
		<td align="right">
			<input type="button" name="btnNvoCCosto" id="btnNvoCCosto" class="boton" style="width:80px" value="Nvo.C.Costo" 
			 	onclick="javascript:
					Deshabilita(true);
					AbreDialogo('divCCosto', 'frmCCosto', 'centro_costo.php');
				"
			/>
		</td>
	</tr>
	<?php
	}?>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
