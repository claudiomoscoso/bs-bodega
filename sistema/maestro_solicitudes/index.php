<?php
include '../conexion.inc.php';
include '../autentica.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Maquinaria y Equipos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('pendientes').setAttribute('height', window.innerHeight - 52);
}

function Buscar(){
	Deshabilita(true)
	var contrato=document.getElementById('cmbCargo').value;
	var estado=document.getElementById('cmbEstado').value;
	document.getElementById('pendientes').src='pendientes.php<?php echo $parametros;?>&contrato='+contrato+'&estado='+estado;
}

function Deshabilita(sw){
	document.getElementById('cmbCargo').disabled=sw;
	document.getElementById('cmbEstado').disabled=sw;
	document.getElementById('btnBuscar').disabled=sw;
}

function VerObservacion(ver, texto){
	document.getElementById('observ').value=Reemplazar(texto);
	document.getElementById('divPosit').style.visibility=ver;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divSolicitud" style="z-index:1; position:absolute; top:5px; left:10%; width:80%; visibility:hidden">
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
											Deshabilita(false);
											CierraDialogo('divSolicitud', 'frmSolicitud');
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td id="titulo" align="center"><b>Solicitud de Maquinaria y Equipos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmSolicitud" id="frmSolicitud" frameborder="0" scrolling="no" width="100%" height="178px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divPosit" style="z-index:500; position:absolute; top:5px; left:35%; width:30%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr height="15px" style="background-image:url(../images/borde_med.png)">
								<td width="15px">
									<a href="#"
										onclick="javascript: 
											Deshabilita(false);
											VerObservacion('hidden', '');
										"
									><img border="0" src="../images/close.png" /></a>
								</td>
								<td align="center"><b>&nbsp;Observaciones</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td align="center"><textarea name="observ" id="observ" class="txt-plano" style="width:99%; background-color:#EBF1FF" rows="7" readonly="readonly"></textarea></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divContrasena" style=" z-index: 1; position:absolute; top:5px; left:35%; width:30%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onclick="javascript: 
											CierraDialogo('divContrasena', 'frmContrasena');
											frmSolicitud.Deshabilita(false);
										"
										onmouseover="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="font-size:12px"><b>Contrase&ntilde;a</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmContrasena" id="frmContrasena" frameborder="0" scrolling="no" width="100%" height="90px" src="../blank.html"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%" align="left">&nbsp;Cargo</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbCargo" id="cmbCargo" class="sel-plano" style="width:100%">
						<?php
						if($bodega=='12000'){
							echo '<option value="all">Todos</option>';
							$tipo='O'; 
						}else 
							$tipo='B';
						$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Estado</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
						<?php
						$tipo='';
						if($perfil=='operaciones' || $perfil == 's.operaciones')
							$tipo='O';
						elseif($perfil=='bodega' || $perfil=='admin.contrato' || $perfil=='admin.contrato.m' || $perfil=='jefe.oper' || $perfil=='informatica' || $perfil=='c.operaciones' || $perfil=='bodega.s' || $perfil=='j.terreno.e'){
							$tipo='T';
							echo '<option value="all">Todos</option>';
						}
						$stmt = mssql_query("EXEC Operaciones..sp_getEstados 0, '$tipo'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["dblCodigo"].'">'.$rst["strDescripcion"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr ><td><hr /></td></tr>
	<tr>
		<td align="left">
			<table id="tbl" width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr >
					<th width="3%" >N&deg;</th>
					<th width="8%" >Solicitud</th>
					<th width="10%" >Fecha</th>
					<th width="14%" align="left">&nbsp;Cargo</th>
					<th width="14%" align="left">&nbsp;Solicitante</th>
					<th width="14%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="5%" >Unidad</th>
					<th width="7%" align="right" >Cantidad&nbsp;</th>
					<th width="7%" >Desde</th>
					<th width="7%" >Hasta</th>
					<th width="10%" >Estado</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="top"><iframe name="pendientes" id="pendientes" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>