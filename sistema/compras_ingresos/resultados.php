<?php
include '../autentica.php';
include '../conexion.inc.php';

$contrato = $_POST["cmbContratos"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Volumen de Obra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('TotalC').innerHTML = document.getElementById('hdnTotalC').value;
	parent.document.getElementById('TotalI').innerHTML = document.getElementById('hdnTotalI').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$sql="EXEC Bodega..sp_getComprasVingresos '$contrato', $finicio, $ftermino";
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	$TotalCompra=0;
	$TotalIngreso=0;
	do{
		$cont++;
		echo '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'>';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="25%" align="left">'.trim($rst["strProveedor"]).'</td>';
		echo '<td width="10%" align="center">'.trim($rst["dblNumero"]).'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCompra"], 0, ',', '.').'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblIngreso"], 0, ',', '.').'</td>';
		echo '<td width="8%" align="center"><table>'.$rst["Ingresos"].'</table></td>';
		echo '<td width="8%" align="center"><table>'.$rst["Fechas"].'</table></td>';
		echo '<td width="8%" align="right"><table>'.$rst["dblMonto"].'</table></td>';
		echo '<td width="8%" align="center"><table>'.$rst["Guias"].'</table></td>';
		echo '<td width="8%" align="center"><table>'.$rst["Facturas"].'</table></td>';
		echo '</tr>';
		$TotalCompra+=$rst["dblCompra"];
		$TotalIngreso+=$rst["dblIngreso"];
	}while($rst = mssql_fetch_array($stmt));	
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="hdnTotalC" id="hdnTotalC" value="<?php echo number_format($TotalCompra, 0, '', '.');?>">
<input type="hidden" name="hdnTotalI" id="hdnTotalI" value="<?php echo number_format($TotalIngreso, 0, '', '.');?>">
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
