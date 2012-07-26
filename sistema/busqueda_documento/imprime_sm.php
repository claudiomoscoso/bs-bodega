<?php
include '../conexion.inc.php';

$directo=$_GET["directo"];
if($directo){
	$bodega=$_GET["bodega"];
	$numero=$_GET["numero"];
	$stmt = mssql_query("EXEC Bodega..sp_getSolicitudMaterial 3, $numero, NULL, '$bodega'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $interno=$rst["dblNumero"];
	mssql_free_result($stmt);
}else
	$interno=$_GET["numero"];
	$bodega=$_GET["bodega"];

if($interno!=''){
	$stmt = mssql_query("EXEC Bodega..sp_getSolicitudMaterial 0, $interno", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$numero=$rst["dblNumUsu"];
		$fecha=$rst["dtmSolicitud"];
		$cargo=$rst["strDetalle"];
		$nota=$rst["strObservacion"];
		$estado=$rst["strDescEstado"];
		$usuario=$rst["nombre"];
	}
	mssql_free_result($stmt);
}
$sql = "Select strPrint, strLogo From General..Contrato Where strBodega='$bodega'";
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	$encabezado = $rst["strPrint"];
	$logo = $rst["strLogo"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(<?php echo $directo;?>) parent.Deshabilita(false);
}
-->
</script>
<body id="cuerpo" style="background-color:#FFFFFF" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
if($interno!=''){?>
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleSolicitudMaterial 1, $interno", $cnx);
		while($rst=mssql_fetch_array($stmt)){
			$fil++;
			$cont++;
			if($fil==39){
				$fil=1;
				if($sw==0){ 
					echo '</table><table borde="0"><tr><td><H3></H3></td></tr></table>';
					$sw=1;
				}
				$sw=0;?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" valign="top" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Solicitud de Materiales</h1></td>
								<td align="center" width="18%" nowrap><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" align="left"><b>&nbsp;N&deg; Interno</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="16%" align="left">&nbsp;<?php echo $interno;?></td>
											<td width="1%">&nbsp;</td>
											<td width="9%" align="left"><b>&nbsp;Usuario</b></td>
											<td width="1%"><b>:</b></td>
											<td width="30%" align="left">&nbsp;<?php echo $usuario;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" valign="top">&nbsp;<b>Estado</b></td>
											<td width="1%" valign="top"><b>:</b></td>
											<td width="30%" align="left" valign="top">&nbsp;<?php echo $estado;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" ><b>&nbsp;Solicitud N&deg;</b></td>
								<td ><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="46%" align="left">&nbsp;<?php echo $numero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="47%" align="left">&nbsp;<?php echo $fecha;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" valign="top">&nbsp;<b>Cargo</b></td>
								<td valign="top"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="46%" align="left" valign="top">&nbsp;<?php echo $cargo;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left" valign="top">&nbsp;<b>Nota</b></td>
											<td width="1%" valign="top"><b>:</b></td>
											<td width="47%" align="left" valign="top">&nbsp;<?php echo $nota;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="67%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="center"><b>Cantidad</b></td>
				</tr>
				<tr><td colspan="5"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="right" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="center"><?php echo $rst["strUnidad"];?></td>
					<td align="right"><?php echo number_format($rst["dblCantidadAut"], 2, ',', '.');?>&nbsp;</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td colspan="5"><hr /></td></tr>
			</table>
		</td>
	</tr>
	<input type="hidden" id="documento" value="1" />
<?php
}else{?>
	<tr><td align="center" style="color:#FF0000"><b>El documento que busca no existe.</b></td></tr>
<?php
}?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
