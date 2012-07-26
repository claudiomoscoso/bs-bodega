<?php
include '../conexion.inc.php';

$directo = $_GET["directo"];
if($directo){
	$bodega=$_GET["bodega"];
	$numero=$_GET["numero"];
	$stmt = mssql_query("EXEC Bodega..sp_getGuiaDevolucion 1, $numero, '$bodega'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $interno=$rst["dblNumero"];
	mssql_free_result($stmt);
}else
	$interno=$_GET["numero"];

$stmt = mssql_query("EXEC Bodega..sp_getGuiaDevolucion 0, $interno", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$numusu=$rst["dblNum"];
	$fecha=$rst["dtmFecha"];
	$descbodega=$rst["strDescBodega"];
	$movil=$rst["strNombre"];
	$usuario=$rst["strNombUsua"];
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
<title>Gu&iacute;a de Devoluci&oacute;n</title>
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
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleGuiaDevolucion 0, $interno", $cnx);
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
								<td valign="top" width="16%" align="center"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Gu&iacute;a de Devoluci&oacute;n</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" align="left">&nbsp;<b>N&deg;Interno</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="38%" align="left">&nbsp;<?php echo $interno;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left"><b>&nbsp;Usuario</b></td>
											<td width="1%">:</td>
											<td width="50%" align="left">&nbsp;<?php echo $usuario;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" ><b>&nbsp;Gu&iacute;a Dev. N&deg;</b></td>
								<td ><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="25%" align="left">&nbsp;<?php echo $numusu;?></td>
											<td width="1%">&nbsp;</td>
											<td width="8%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%">:</td>
											<td width="25%" align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="13%" align="left">&nbsp;<b>G.Despacho N&deg;</b></td>
											<td width="1%"><b>:</b></td>
											<td width="25%" align="left">&nbsp;
											<?php 
												echo $numdesp;
												echo $numdesp!='' ? ' (Inventario)' : '';
											?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Movil</b></td>
								<td ><b>:</b></td>
								<td colspan="9">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="44%">&nbsp;<?php echo $movil;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%"><b>&nbsp;Bodega</b></td>
											<td width="1%"><b>:</b></td>
											<td width="44%">&nbsp;<?php echo $descbodega;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5">&nbsp;</td></tr>
				<tr><td height="5px" colspan="5"><hr /></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="67%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
				</tr>
				<tr><td colspan="5"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="center" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="center">&nbsp;<?php echo $rst["strUnidad"];?></td>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
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
