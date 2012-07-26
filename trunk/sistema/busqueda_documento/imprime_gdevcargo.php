<?php
include '../conexion.inc.php';
$directo=$_GET["directo"];
$bodega=$_GET["bodega"];
$numero=$_GET["numero"];

if($directo)
	$sql = "EXEC Bodega..sp_getDevoluciones 1, $numero, '$bodega'";
else
	$sql = "EXEC Bodega..sp_getDevoluciones 0, $numero";

$stmt = mssql_query($sql, $cnx);
if($rst=mssql_fetch_array($stmt)){
	$interno=$rst["dblNumero"];
	$numguia = $rst["dblNum"];
	$fecha=$rst["dtmFecha"];
	$descbodega=$rst["strDescBodega"];
	$rut=$rst["strCargo"];
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
<html>
<head><title>Devoluci&oacute;n de Cargos</title></head>
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
if($interno != ''){?>
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleDevoluciones 0, $interno", $cnx);
		while($rst=mssql_fetch_array($stmt)){
			$cont++;
			$fil++;
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
								<td align="center"><h1>Gu&iacute;a de Devoluci&oacute;n de Cargos</h1></td>
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
								<td width="10%" ><b>&nbsp;N&deg; Interno</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%">&nbsp;<?php echo $interno;?></td>
											<td width="1%">&nbsp;</td>
											<td width="13%"><b>&nbsp;Devolución N&deg;</b></td>
											<td width="1%"><b>:</b></td>
											<td width="10%">&nbsp;<?php echo $numguia;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%"><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="10%">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%"><b>&nbsp;Bodega</b></td>
											<td width="1%"><b>:</b></td>
											<td width="41%">&nbsp;<?php echo $descbodega;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td ><b>&nbsp;Nombre</b></td>
								<td ><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="78%">&nbsp;<?php echo $nombre;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%"><b>&nbsp;R.U.T.</b></td>
											<td width="1%"><b>:</b></td>
											<td width="15%">&nbsp;<?php echo $rut;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="5%" align="center"><b>N&deg;</b></td>
								<td width="10%" align="center"><b>C&oacute;digo</b></td>
								<td width="75%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
								<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="5%"><?php echo $fil;?></td>
								<td width="10%"><?php echo $rst["strCodigo"];?></td>
								<td width="75%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
								<td width="10%" align="right"><?php echo $rst["dblCantidad"];?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>				
<?php	}
		mssql_free_result($stmt);?>	
				<tr><td><hr /></td></tr>
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
