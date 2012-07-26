<?php
include '../autentica.php';
include '../conexion.inc.php';

$contrato = $_POST["contrato"];
$solicitante = Reemplaza($_POST["solicitante"]);
$descripcion = Reemplaza($_POST["descripcion"]);
$unidad = $_POST["unidad"];
$cantidad = $_POST["cantidad"];
$fdesde = $_POST["desde"] != '' ? "'".formato_fecha($_POST["desde"], false, true)."'" : 'NULL';
$fhasta = $_POST["hasta"] != '' ? "'".formato_fecha($_POST["hasta"], false, true)."'" : 'NULL';
$observacion = Reemplaza($_POST["observacion"]);

if($solicitante != '' && $descripcion != ''){
	$estado = ($bodega == 12000 ? 1 : 0);
	$stmt = mssql_query("EXEC Operaciones..sp_setSolicitudOperaciones 0, '$usuario', '$contrato', '$solicitante', '$descripcion', '$unidad', '$cantidad', $fdesde, $fhasta, '$estado', '$observacion'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $numero = $rst["dblNumero"];
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Operaciones</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $numero;?>' != ''){ 
		alert('Se ha generado la Solicitud con Nº <?php echo $numero;?>');
	}
}

function Deshabilita(sw){
	document.getElementById('contrato').disabled=sw;
	document.getElementById('solicitante').disabled=sw;
	document.getElementById('descripcion').disabled=sw;
	document.getElementById('unidad').disabled=sw;
	document.getElementById('cantidad').disabled=sw;
	document.getElementById('desde').disabled=sw;
	document.getElementById('hasta').disabled=sw;
	document.getElementById('observacion').disabled=sw;
	document.getElementById('btnOk').disabled=sw;
}

function Graba(){
	document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="position:absolute; top:100px; left:56%; width:20%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
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
											CierraDialogo('divCalendario','frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									title="Cierra calendario.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="5%" align="left">&nbsp;Fecha</td>
		<td width="1%">:</td>
		<td width="43%" align="left">&nbsp;<?php echo date('d/m/Y');?></td>
		<td width="1%">&nbsp;</td>
		<td width="6%" align="left">&nbsp;Contrato</td>
		<td width="1%">:</td>
		<td width="43%" align="right">
			<select name="contrato" id="contrato" class="sel-plano" style="width:100%">
			<?php
			if($bodega=='12000') $tipo='O'; else $tipo='B';
			$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
			while($rst=mssql_fetch_array($stmt)){
				echo '<option value="'.$rst["strCodigo"].'">'.$rst["strCargo"].'</option>';
			}
			mssql_free_result($stmt);
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="left">&nbsp;Solicitante</td>
		<td>:</td>
		<td width="43%" align="left">
			<input name="solicitante" id="solicitante" class="txt-plano" style="width:99%"
				onfocus="javascript: CambiaColor(this.id, true);" 
				onblur="javascript: CambiaColor(this.id, false);"
			/>		
		</td>
		<td width="1%">&nbsp;</td>
		<td width="5%" align="left">&nbsp;Descripci&oacute;n</td>
		<td width="1%">:</td>
		<td width="43%" align="left">
			<input name="descripcion" id="descripcion" class="txt-plano" style="width:98%"
				onfocus="javascript: CambiaColor(this.id, true);" 
				onblur="javascript: CambiaColor(this.id, false);"
			/>		
		</td>
	</tr>
	<tr>
		<td align="left">&nbsp;Unidad</td>
		<td>:</td>
		<td align="left">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="43%" align="left">
						<input name="unidad" id="unidad" class="txt-plano" style="width:99%"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onblur="javascript: CambiaColor(this.id, false);"
						/>					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="left">&nbsp;Cantidad</td>
					<td width="1%">:</td>
					<td width="43%" align="left">
						<input name="cantidad" id="cantidad" class="txt-plano" style="width:97%; text-align:right" value="0"
							onkeypress="javascript: return ValNumeros(event, this.id, true);"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onblur="javascript: CambiaColor(this.id, false); if(this.value=='') this.value=0;"
						/>					
					</td>
				</tr>
			</table>		
		</td>
		<td width="1%">&nbsp;</td>
		<td width="5%" align="left">&nbsp;Desde</td>
		<td width="1%">:</td>
		<td align="left">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left"><input name="desde" id="desde" class="txt-plano" style="width:98%; text-align:center" readonly="true" /></td>
					<td width="1%">&nbsp;</td>
					<td width="2%" align="center" valign="top">
						<a href="#" title="Seleccione una fecha"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario','frmCalendario','../calendario/index.php?ctrl=desde&fecha='+document.getElementById('desde').value, '', '57%', '60px');
							"
						><img border="0" align="middle" src="../images/aba.gif" /></a>					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="left">&nbsp;Hasta</td>
					<td width="1%">:</td>
					<td align="left"><input name="hasta" id="hasta" class="txt-plano" style="width:98%; text-align:center" readonly="true"/></td>
					<td width="1%">&nbsp;</td>
					<td width="2%" align="center" valign="top">
						<a href="#" title="Seleccione una fecha"
							onclick="javascript: 
								Deshabilita(true);
								AbreDialogo('divCalendario','frmCalendario','../calendario/index.php?ctrl=hasta&fecha='+document.getElementById('hasta').value, '', '79%', '60px');
							"
						><img border="0" align="middle" name="btnFch" id="btnFch" src="../images/aba.gif" /></a>					
					</td>
				</tr>
			</table>		
		</td>
	</tr>
	<tr>
		<td>&nbsp;Observaci&oacute;n</td>
		<td>:</td>
		<td colspan="5">
			<input name="observacion" id="observacion" class="txt-plano" style="width:99%;" 
				onfocus="javascript: CambiaColor(this.id, true);" 
				onblur="javascript: CambiaColor(this.id, false);"
			/>		</td>
	</tr>
	<tr><td colspan="7" valign="bottom" style="height:250px"><hr /></td></tr>
	<tr>
		<td align="right" colspan="7">
			<input type="button" name="btnOk" id="btnOk" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript:
					if(document.getElementById('solicitante').value==''){
						alert('Debe ingresar el solicitante.');
						document.getElementById('solicitante').focus();
					}else if(document.getElementById('descripcion').value==''){
						alert('Debe ingresar la descripción de lo solicitado.');
						document.getElementById('descripcion').focus();
					}else if(document.getElementById('desde').value==''){
						alert('Debe ingresar la fecha de inicio');
						document.getElementById('desde').focus();
					}else if(document.getElementById('hasta').value==''){
						alert('Debe ingresar la fecha de término');
						document.getElementById('hasta').focus();
					}else if(ComparaFechas(document.getElementById('desde').value, '<', '<?php echo date('d/m/Y');?>')){
						alert('La primera fecha debe ser mayor a la fecha de hoy.');
					}else if(ComparaFechas(document.getElementById('desde').value, '>', document.getElementById('hasta').value)){
						alert('La primera fecha debe ser menor que la segunda.');
					}else{
						Graba();
					}
				"
			/></td>
	</tr>
</table>
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>