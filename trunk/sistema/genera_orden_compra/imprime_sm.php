<?php
include '../autentica.php';
include '../conexion.inc.php';
$dblInterno=$_GET["numero"];

mssql_query("UPDATE CaratulaSM SET strEstado=3 WHERE dblNumero=$dblInterno AND strEstado=2", $cnx);

$stmt = mssql_query("EXEC Bodega..sp_getSolicitudMaterial 0, $dblInterno", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$numero=$rst["dblNumUsu"];
	$fecha=$rst["dtmSolicitud"];
	$cargo=$rst["strDetalle"];
	$nota=$rst["strObservacion"];
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
<title>Solicitud de Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	self.focus();
	self.print();
	parent.location.href='index.php<?php echo $parametros;?>';
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC sp_getDetalleSolicitudMaterial 0, $dblInterno", $cnx);
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
								<td width="8%" align="left" nowrap="nowrap"><b>&nbsp;Solicitud N&deg;</b></td>
								<td width="1%">:</td>
								<td width="20%" align="left">&nbsp;<?php echo $numero;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
								<td width="1%">:</td>
								<td align="left">&nbsp;<?php echo $fecha;?></td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Cargo</b></td>
								<td>:</td>
								<td align="left">&nbsp;<?php echo $cargo;?></td>
								<td></td>
								<td align="left">&nbsp;<b>Nota</b></td>
								<td>:</td>
								<td align="left">&nbsp;<?php echo $nota;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="67%" align="center"><b>Descripci&oacute;n</b></td>
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
					<td align="right"><?php echo number_format($rst["dblCantidadAut"],2,',','.');?>&nbsp;</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td colspan="5"><hr /></td></tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
