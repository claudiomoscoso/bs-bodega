<?php
include '../conexion.inc.php';

$directo=$_GET["directo"];
$numero=$_GET["numero"];
if($directo){
	$bodega=$_GET["bodega"];
	$sql = "EXEC Bodega..sp_getCajaChica 4, $numero, '$bodega'";
}else
	$sql = "EXEC Bodega..sp_getCajaChica 0, $numero";

$stmt = mssql_query($sql, $cnx);
if($rst=mssql_fetch_array($stmt)){
	$interno = $rst["dblNumero"];
	$numcc = $rst["dblNum"];
	$fecha = $rst["dtmFch"];
	$descestado = $rst["strDescEstado"];
	$descbodega = $rst["strDescBodega"];
	$nombre = $rst["strNombre"];
	$rut = $rst["strRut"];
	$nota = $rst["strNota"];
	$factor = $rst["dblFactor"];
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
<title>Caja Chica</title>
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
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleCajaChica 0, $interno", $cnx);
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
								<td valign="top" width="16%" align="center">
									<img border="0" src="../images/<?php echo $logo;?>" />
									<br />
									[Interno: <?php echo $interno;?>]
								</td>
								<td align="center"><h1>Caja Chica</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" >&nbsp;</td></tr>
				<tr>
					<td valign="top" align="center" >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%" align="left" nowrap="nowrap"><b>&nbsp;Caja Chica N&deg;</b></td>
								<td width="1%">:</td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $numcc;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%">:</td>
											<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Bodega</b></td>
											<td width="1%">:</td>
											<td width="34%" align="left">&nbsp;<?php echo $descbodega;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Estado</b></td>
											<td width="1%">:</td>
											<td width="25%" align="left">&nbsp;<?php echo $descestado;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Responsable</b></td>
								<td>:</td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="1%">&nbsp;</td>
											<td width="36%" align="left">&nbsp;<?php echo $nombre;?></td>
											<td width="5%" align="5%"><b>R.U.T</b></td>
											<td width="1%"><b>:</b></td>
											<td width="10%">&nbsp;<?php echo $rut;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left" ><b>&nbsp;Nota</b></td>
											<td width="1%">:</td>
											<td width="37%" align="left">&nbsp;<?php echo $nota;?></td>
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
								<td width="10%" align="center"><b>C&oacute;digo</b></td>
								<td width="27%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
								<td width="10%" align="left"><b>&nbsp;Documento</b></td>
								<td width="10%" align="center"><b>N&deg; Doc.</b></td>
								<td width="10%" align="center"><b>N&deg; O.Compra</b></td>
								<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
								<td width="10%" align="right"><b>Precio Neto&nbsp;</b></td>
								<td width="10%" align="right"><b>Total (*)&nbsp;</b></td>
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
								<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
								<td width="27%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
								<td width="10%" align="left">&nbsp;<?php echo $rst["dblTipoDoc"] == 0 ? 'Factura' : 'Boleta';?></td>
								<td width="10%" align="center"><?php echo $rst["dblNumDoc"];?></td>
								<td width="10%" align="center"><?php echo $rst["dblUltima"];?></td>
								<td align="right"><?php echo number_format($rst["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
								<td width="10%" align="right"><?php echo number_format($rst["dblValor"], 0, '', '.');?>&nbsp;</td>
								<td width="10%" align="right">
								<?php 
								if($rst["dblTipoDoc"]==0){
									echo number_format(($rst["dblValor"] * $rst["dblCantidad"]) * $factor, 0, '', '.');
									$total+=$rst["dblValor"] * $rst["dblCantidad"] * $factor;
								}else{
									echo number_format($rst["dblValor"] * $rst["dblCantidad"], 0, '', '.');
									$total+=$rst["dblValor"] * $rst["dblCantidad"];
								}
								?>&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td ><hr /></td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><b>&nbsp;(*) El Total incluye I.V.A. si corresponde a una factura.</b></td>
								<td align="right"><b>TOTAL</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%" align="right"><?php echo number_format($total, 0, '', '.');?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td >&nbsp;</td></tr>
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
