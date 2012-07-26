<?php
include '../conexion.inc.php';

$usuario = $_POST["usuario"];
$bodega = $_POST["cmbBodega"];
$fecha = $_POST["txtFecha"];
$responsable = $_POST["cmbResponsable"];
$nota = $_POST["txtNota"];

$stmt = mssql_query("SELECT dblFactor + 1 AS dblFactor FROM Impuesto WHERE id=1", $cnx);
if($rst=mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_setAgregaCajaChica 0, '$usuario', '$bodega', '".formato_fecha($fecha, false, true)."', '$responsable', '$nota', $factor", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$numint = $rst["dblNumInt"];
	$numcc = $rst["dblNumCC"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 0, $numint", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$descbodega=$rst["strDescBodega"];
	$fecha = $rst["dtmFch"];
	$rut = $rst["strRut"];
	$nombre = $rst["strNombre"];
	$nota = $rst["strNota"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC General..sp_getEncargadoFondoFijo 1, NULL, '$usuario'", $cnx);
if($rst=mssql_fetch_array($stmt)) $nombusu = $rst["strNombre"];
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
	alert('La caja chica se ha guardado con el número <?php echo $numcc;?>');
	self.focus();
	self.print();
	parent.location.href=parent.location.href;
	//parent.document.getElementById('btnGuardar').disabled=false;
	//alert(parent.location.href);
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleCajaChica 0, $numint", $cnx);
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
									<img border="0" src="../images/logo.jpg" />
									<br />
									[Interno: <?php echo $numint;?>]
								</td>
								<td align="center"><h1>Caja Chica</h1></td>
								<td align="center" width="18%">
									Constructora<br>
									<b>EDECO S.A.</b><br>
									82.637.800-K<br>
									Carmencita 25 of. 41<br>
									Fono:8991000<br>
									Las Condes
								</td>
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
											<td width="66%" align="left">&nbsp;<?php echo $descbodega;?></td>
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
				<tr>
					<td align="center">
						<table border="1" width="40%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="50%" align="center" valign="bottom">
									<br /><br /><br /><br />
									<?php echo htmlentities($nombre);?>
									<br />
									Responsable
								</td>
								<td width="50%" align="center" valign="bottom">
									<br /><br /><br /><br />
									<?php echo htmlentities($nombusu);?>
									<br />
									Bodeguero
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
