<?php
include '../autentica.php';
include '../conexion.inc.php';

$fecha = $_POST["cmbFecha"];
$numero = $_POST["txtNumero"];
$bodegavc = $_POST["cmbBodega"];
$responsable = $_POST["cmbResponsable"];

$stmt = mssql_query("EXEC Bodega..sp_setValeConsumo 0, '$usuario', '$bodegavc', $numero, '".formato_fecha($fecha, false, true)."', '$responsable'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$error = $rst["dblError"];
	$interno = $rst["dblInterno"];
	$numero = $rst["dblCorrelativo"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getValeConsumo 0, $interno", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numero = $rst["dblNumero"];
	$fecha = $rst["dtmFecha"];
	$responsable = $rst["strNombre"];
	$descobra = $rst["strDescObra"];
	$numref = $rst["dblNum"];
}
mssql_free_result($stmt);
$sql = "Select strPrint, strLogo From General..Contrato Where strBodega='$bodegavc'";
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
<title>Vale de Consumo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $error;?>') == 0){
		alert('El vale de consumo ha sido creado con el número <?php echo $numero;?>')
		self.focus();
		self.print();
		parent.location.href = 'index.php<?php echo $parametros;?>';
	}else if(parseInt('<?php echo $error;?>') == 1){
		alert('No ha sido posible obtener el número interno.');
	}else if(parseInt('<?php echo $error;?>') == 2){
		alert('No ha sido posible obtener el número correlativo.');
	}
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();" style="background-color:#FFFFFF">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
		<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleValeConsumo $interno", $cnx);
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
								<td align="center"><h1>Vale de Consumo</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="6"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="6">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="11%" align="left"><b>&nbsp;N&deg; VC</b></td>
								<td width="1%"><b>:</b></td>
								<td width="39%" align="left">&nbsp;<?php echo $numero;?></td>
								<td width="1%">&nbsp;</td>
								<td width="7%" align="left"><b>&nbsp;Fecha</b></td>
								<td width="1%"><b>:</b></td>
								<td width="40%" align="left">&nbsp;<?php echo $fecha;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Responsable</b></td>
								<td><b>:</b></td>
								<td align="left">&nbsp;<?php echo $responsable;?></td>
								<td>&nbsp;</td>
								<td align="left">&nbsp;<b>Bodega</b></td>
								<td><b>:</b></td>
								<td align="left">&nbsp;<?php echo $descobra;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Referencia</b></td>
								<td><b>:</b></td>
								<td align="left">&nbsp;<?php echo $numref;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="6"></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="57%" align="center"><b>Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="center"><b>C.Costo</b></td>
					<td width="10%" align="center"><b>Cantidad</b></td>
				</tr>
				<tr><td colspan="6"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="right" ><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="center"><?php echo $rst["strUnidad"];?></td>
					<td align="center"><?php echo $rst["strPartida"];?></td>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
				</tr>
		<?php
		}
		mssql_free_result($stmt);?>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
