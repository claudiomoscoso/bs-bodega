<?php
include '../autentica.php';
include '../conexion.inc.php';

$numint = $_POST["numint"];
$fecha = $_POST["fecha"];
$observacion = $_POST["observacion"];

$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 0, $numint", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numcc = $rst["dblNum"];
	$cargo = $rst["strCargo"];
	$nombresp = $rst["strNombre"];
	$bodcc = $rst["strBodega"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'GNR', NULL, '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$nombresp = $rst["nombre"];
	$firmresp = $rst["firma"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_setGuiaIngreso 2, '$usuario', '$bodcc', '".formato_fecha($fecha, false, true)."', NULL, $numint, NULL, '$observacion'");
if($rst = mssql_fetch_array($stmt)){ 
	$error = $rst["dblError"];
	$numinting = $rst["dblCorrelativo"];
	$numing = $rst["dblNumero"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Ingreso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $error;?>') == 0){
		alert('La guía de ingreso se ha guardado con el número: <?php echo $numing;?>.');
		self.focus();
		self.print();
		parent.location.href = 'index.php<?php echo $parametros;?>';
	}else if(parseInt('<?php echo $error;?>') == 1){
		alert('No es posible obtener el correlativo interno.');
	}else if(parseInt('<?php echo $error;?>') == 2){
		alert('No es posible obtener el correlativo.');
	}
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleCajaChica 1 , $numint", $cnx);
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
								<td valign="top" width="16%" align="center">
									<img border="0" src="../images/logo.jpg" />
									<br />
									[Interno: <?php echo $numinting;?>]
								</td>
								<td align="center"><h1>Gu&iacute;a de Ingreso</h1></td>
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
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%" align="left" nowrap="nowrap"><b>&nbsp;Gu&iacute;a de Ingreso N&deg;</b></td>
								<td width="1%"><b>:</b></td>
								<td width="26%" align="left">&nbsp;<?php echo $numing;?></td>
								<td width="1%">&nbsp;</td>
								<td width="13%" align="left" nowrap="nowrap"><b>&nbsp;Caja Chica N&deg;</b></td>
								<td width="1%"><b>:</b></td>
								<td width="26%" align="left">&nbsp;<?php echo $numcc;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
								<td width="1%"><b>:</b></td>
								<td width="26%" align="left">&nbsp;<?php echo $fecha;?></td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Responsable</b></td>
								<td><b>:</b></td>
								<td align="left">&nbsp;<?php echo $nombresp;?></td>
								<td width="1%">&nbsp;</td>
								<td width="10%" align="left">&nbsp;<b>Observaci&oacute;n</b></td>
								<td><b>:</b></td>
								<td colspan="5" align="left">&nbsp;<?php echo $observacion;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="77%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
				</tr>
				<tr><td colspan="5"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="center" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td colspan="5"><hr /></td></tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" align="center">
						<table border="1" width="20%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center" valign="bottom">
									<br /><br /><br /><br />
								<?php
									if($firmresp!='') echo '<img border="0" src="../images/'.$firmresp.'" />';
									echo $nombresp;?>
									<br />
									Responsable
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
