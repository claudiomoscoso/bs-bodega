<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administraci&oacute;n de Usuarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmUsuarios').setAttribute('height', window.innerHeight - 90);
}

function Deshabilitar(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbEstado').disabled = sw;
	document.getElementById('cmbVigente').disabled = sw;
	
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnNuevo').disabled = sw;
	if(frmUsuarios.document.getElementById('totfil')){
		var totfil = frmUsuarios.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			frmUsuarios.document.getElementById('chkVigente' + i).disabled = sw;
			//frmUsuarios.document.getElementById('btnLogOut' + i).disabled = sw;
		}
	}
}

function Buscar(){
	var bodega=document.getElementById('cmbBodega').value;
	var estado=document.getElementById('cmbEstado').value;
	var vigente='all';
	if(document.getElementById('cmbVigente')) vigente=document.getElementById('cmbVigente').value;
	document.getElementById('frmUsuarios').src='detalle.php?userlog=<?php echo $usuario;?>&bodega='+bodega+'&estado='+estado+'&vigente='+vigente;
}
-->
</script>
<body onload="javascript: Load();">
<div id="divActividad" style="position:absolute; top:62px; left:35%; width:30%; visibility:hidden">
<table width="100%" height="100%" cellpadding="0" cellspacing="0" class=ventana>
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_sup.png">
							<tr>
								<td align="center" style="color:#000000; font-size:12px"><b>&Uacute;ltima Actividad</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmActividad" id="frmActividad" frameborder="0" style="border:thin" scrolling="no" width="100%" height="165px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divUsuario" style="position:absolute; top:5px; left:10%; width:80%; visibility:hidden">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" class="ventana">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_sup.png">
							<tr>
								<td width="2%">
									<a href="#" title="Cierra cuadro de dialogo."
										onclick="javascript: 
											CierraDialogo('divUsuario', 'frmUsuario');
											Deshabilitar(false);
										"
									><img border="0" src="../images/close.png" /></a>
								</td>
								<td align="center" style="color:#000000;font-size:12px;font-weight:bold">Usuarios</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmUsuario" id="frmUsuario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="210px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="9">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="30%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						echo '<option value="all">Todas</option>';
						$stmt = mssql_query("EXEC General..sp_getBodega 6", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="8">&nbsp;Estado</td>
					<td width="1%">:</td>
					<td width="30%">
						<select name="cmbEstado" id="cmbEstado" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
							<option value="login">Conectados</option>
							<option value="logout">Desconectados</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
				<?php
				if($perfil=='informatica'){?>
					<td width="5%">&nbsp;Vigentes</td>
					<td width="1%">:</td>
					<td width="30%">
						<select name="cmbVigente" id="cmbVigente" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
							<option value="1" selected="selected">Vigentes</option>
							<option value="0">No vigentes</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
				<?php
				}
				?>
					<td>
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar()"
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
					<th width="10%" align="left">&nbsp;Usuario</th>
					<th width="20%" align="left">&nbsp;Nombre</th>
					<th width="10%" align="left">&nbsp;Perfil</th>
					<th width="25%" align="left">&nbsp;Bodega</th>
					<th width="10%" >Ult.Sesi&oacute;n</th>
					<th width="8%" align="left">&nbsp;Est.Actual</th>
					<th width="5%">Vigente</th>
					<th width="7%">&nbsp;</th>
					<th width="2%" >&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmUsuarios" id="frmUsuarios" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<?php
		if($perfil=='informatica'){?>
	<tr>
		<td align="right">
			<input type="button" name="btnNuevo" id="btnNuevo" class="boton" style="width:90px" value="Nvo.Usuario" 
				onclick="javascript:
					Deshabilitar(true);
					AbreDialogo('divUsuario', 'frmUsuario', 'usuarios.php');
				"
			/>
		</td>
	</tr>
	<?php
	}?>
</table>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px" ></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
