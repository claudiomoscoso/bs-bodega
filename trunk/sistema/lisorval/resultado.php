<?php
include '../conexion.inc.php';

$contrato = $_POST["cmbContrato"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';
$epago = $_POST["txtEPago"] != '' ? $_POST["txtEPago"] : 'NULL';
$certificado = $_POST["txtCertificado"] != '' ? $_POST["txtCertificado"] : 'NULL';
$movil = $_POST["hdnMovil"] != '' ? "'".$_POST["hdnMovil"]."'" : 'NULL';
$cerradas = $_POST["chkCerradas"] == 'on' ? 1 : 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Lisorval</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$strSQL="EXEC Orden..sp_getOrdenTrabajo 6, '', '$contrato', $movil, NULL, NULL, NULL, NULL, NULL, $epago, $finicio, $ftermino, $certificado, $cerradas";
$stmt = mssql_query($strSQL, $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		if($orden != $rst["strOrden"]){
			if($orden != ''){
				$ln++;
				echo '<tr bgcolor="#FFFFFF">';
				echo '<td>';
				echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
				echo '<tr>';
				echo '<td width="72%" align="right" style="border-top:solid 1px"><b>TOTAL</b></td>';
				echo '<td width="1%" align="center" style="border-top:solid 1px"><b>:</b></td>';
				echo '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tinformada, 0, '', '.').'&nbsp;</b></td>';
				echo '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tpagada, 0, '', '.').'&nbsp;</b></td>';
				echo '<td width="6%" style="border-top:solid 1px">&nbsp;</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
				$ln++;
				echo '<tr bgcolor="#FFFFFF"><td>&nbsp;</td></tr>';
			}
			$tinformada = 0;
			$tpagada = 0;
			$orden = $rst["strOrden"];
			$ln++;
			echo '<tr >';
			echo '<td>';
			echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
			echo '<tr style="background-image:url(../images/borde_menu.gif)">';
			echo '<td width="10%" align="center">'.$rst["strOrden"].'</td>';
			echo '<td width="10%" align="center">'.$rst["strODS"].'</td>';
			echo '<td width="15%" align="center">'.formato_fecha($rst["dtmOrden"], true, false).'</td>';
			echo '<td width="43%" align="left">&nbsp;'.$rst["strDireccion"].'</td>';
			echo '<td width="20%" align="left">&nbsp;'.$rst["strSector"].'</td>';
			echo '</tr>';
			echo '</table>';
			echo '</td>';
			echo '</tr>';
		}
		$ln++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td>';
		echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
		echo '<tr>';
		echo '<td width="6%" align="center">'.$rst["strItem"].'</td>';
		echo '<td width="30%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="6%" align="center">'.$rst["strUnidad"].'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblPrecio"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCantidadEmos"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTInformada"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTPagada"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="6%" align="center">'.$rst["strMovil"].'</td>';
		echo '</tr>';
		echo '</table>';
		echo '</td>';
		echo '</tr>';
		$tinformada += $rst["dblTInformada"];
		$tpagada += $rst["dblTPagada"];
	}while($rst = mssql_fetch_array($stmt));
	$ln++;
	echo '<tr bgcolor="#FFFFFF">';
	echo '<td>';
	echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<td width="79%" align="right" style="border-top:solid 1px"><b>TOTAL</b></td>';
	echo '<td width="1%" align="center" style="border-top:solid 1px"><b>:</b></td>';
	echo '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tinformada, 0, '', '.').'&nbsp;</b></td>';
	echo '<td width="10%" align="right" style="border-top:solid 1px"><b>'.number_format($tpagada, 0, '', '.').'&nbsp;</b></td>';
	echo '<td width="10%" style="border-top:solid 1px">&nbsp;</td>';
	echo '</tr>';
	echo '</table>';
	echo '</td>';
	echo '</tr>';
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td><td>'.$strSQL.'</td></tr>';
}
mssql_free_result($stmt);
?>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $ln;?>" />
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
