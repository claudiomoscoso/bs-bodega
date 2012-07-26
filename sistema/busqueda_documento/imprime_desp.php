<?php
include '../conexion.inc.php';

$directo = $_GET["directo"];
$numero = $_GET["numero"];
$bodega = $_GET["bodega"];

$stmt = mssql_query("EXEC Bodega..sp_getGuiaDespacho 0, $numero, '$bodega'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$nombprov=$rst["strNombre"];
	$rut=$rst["strRut"];
	$fecha=$rst["dtmFecha"];
	$nombre=$rst["strNombre"];
	$direccion=$rst["strDireccion"];
}
mssql_free_result($stmt);
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
<title>Gu&iacute;a de Despacho</title>
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
if($fecha!=''){?>
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleDespacho 0, $numero", $cnx);
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
					<td align="center" valign="top" colspan="4">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%" align="center"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Gu&iacute;a de Despacho</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="4"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="15%" align="left" nowrap="nowrap"><b>&nbsp;G.Despacho N&deg;</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="44%" align="left">&nbsp;<?php echo $numero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" ><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="44%" align="left">&nbsp;<?php echo $fecha;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Se&ntilde;ores</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="68%" align="left">&nbsp;<?php echo $nombre != '' ? $nombre : 'No existen datos en Softland.';?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" ><b>&nbsp;R.U.T.</b></td>
											<td width="1%"><b>:</b></td>
											<td width="20%" align="left">&nbsp;<?php echo $rut;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Direcci&oacute;n</b></td>
								<td><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="68%" align="left">&nbsp;<?php echo $direccion;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" ><b>&nbsp;Ciudad</b></td>
											<td width="1%"><b>:</b></td>
											<td width="20%" align="left">&nbsp;<?php echo $ciudad;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;O.Compra N&deg;</b></td>
								<td><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="44%" align="left">&nbsp;<?php echo $numoc;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" ><b>&nbsp;Tel&eacute;fono</b></td>
											<td width="1%"><b>:</b></td>
											<td width="44%" align="left">&nbsp;<?php echo $telefono;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="10%" align="left"><b>&nbsp;Condici&oacute;n de Pago</b></td>
								<td width="1%"><b>:</b></td>
								<td colspan="9">&nbsp;<?php echo $cpago;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="4"><hr /></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
					<td width="87%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
				</tr>
				<tr><td colspan="4"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="center" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
					<td align="left">&nbsp;<?php echo '['.$rst["strCodigo"].' - '.$rst["strUnidad"].']  '.$rst["strDescripcion"];?></td>
				</tr>
<?php	}
		mssql_free_result($stmt);
	?>
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
