<?php
include '../conexion.inc.php';

$directo=$_GET["directo"];
$bodega=$_GET["bodega"];
$numero=$_GET["numero"];

//if($directo)
$sql = "EXEC Bodega..sp_getGuiaCargo 0, $numero";
//else
	//$sql = "EXEC Bodega..sp_getCajaChica 0, $numero";
$existe = 0;
$stmt = mssql_query($sql, $cnx);
if($rst=mssql_fetch_array($stmt)){
	$fecha = $rst["dtmFch"];
	$cargo = $rst["strCargo"];
	$nombre = $rst["strNombre"];
	$descbodega = $rst["strDescBodega"];
	$existe = 1;
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
<title>Gu&iacute;a de Cargos</title>
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
if($existe == 1){?>
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleGuiaCargo $numero", $cnx);
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
					<td align="center" valign="top" >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%" align="center"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Gu&iacute;a de Cargos</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td >&nbsp;</td></tr>
				<tr>
					<td valign="top" align="center" >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%" align="left" nowrap="nowrap"><b>&nbsp;Gu&iacute;a N&deg;</b></td>
								<td width="1%">:</td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $numero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%">:</td>
											<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Bodega</b></td>
											<td width="1%">:</td>
											<td width="66%" align="left">&nbsp;<?php echo $descbodega;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Cargo</b></td>
								<td>:</td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="78%" align="left">&nbsp;<?php echo $nombre;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;R.U.T.</b></td>
											<td width="1%">:</td>
											<td width="15%">&nbsp;<?php echo $cargo;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td >&nbsp;</td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="3%" align="center"><b>N&deg;</b></td>
								<td width="10%" align="center"><b>Rut</b></td>
								<td width="10%" align="center"><b>C&oacute;digo</b></td>
								<td width="57%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
								<td width="10%" align="center"><b>Unidad</b></td>
								<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td ><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="3%" align="center" ><?php echo $cont;?>&nbsp;</td>
								<td width="10%" align="center"><?php echo $rst["strCargo"];?></td>
								<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
								<td width="57%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
								<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
								<td align="right"><?php echo number_format($rst["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td ><hr /></td></tr>
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
