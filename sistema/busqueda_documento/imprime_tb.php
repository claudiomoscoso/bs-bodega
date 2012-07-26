<?php
include '../conexion.inc.php';

$directo = $_GET["directo"];
$numero = $_GET["numero"];
$stmt = mssql_query("EXEC Bodega..sp_getTerminoBodega 0, $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$fecha=$rst["dtmFch"];
	$descbodega=$rst["strDescBodega"];
	$nombre=$rst["strNombre"];
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
<title>Termino de Bodega</title>
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
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleTerminoBodega 0, $numero", $cnx);
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
					<td align="center" valign="top" colspan="6">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>T&eacute;rmino de Bodega</h1></td>
								<td align="center" width="18%" nowrap><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="6"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="6">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%"><b>&nbsp;N&deg; T.Bodega</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="44%" ><?php echo $numero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="44%" align="left">&nbsp;<?php echo $fecha;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Bodega</b></td>
								<td><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="44%" align="left">&nbsp;<?php echo $descbodega;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left">&nbsp;<b>Usuario</b></td>
											<td width="1%"><b>:</b></td>
											<td width="44%" align="left">&nbsp;<?php echo $nombre;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="6"></td></tr>
				<tr><td colspan="6"><hr /></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="0%" align="center"><b>Descripci&oacute;n</b></td>
					<td width="10%" align="right"><b>Stock&nbsp;</b></td>
					<td width="10%" align="right"><b>Devoluci&oacute;n&nbsp;</b></td>
					<td ><b>&nbsp;Observaci&oacute;n</b></td>
				</tr>
				<tr><td colspan="6"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="right" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td>&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="right"><?php echo number_format($rst["dblStock"],2,',','.');?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblDevolucion"],2,',','.');?>&nbsp;</td>
					<td>&nbsp;<?php echo $rst["strObservacion"];?></td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
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
