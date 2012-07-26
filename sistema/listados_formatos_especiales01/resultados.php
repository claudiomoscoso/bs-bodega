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
<title>Listado Especial 01</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('Total').innerHTML = document.getElementById('hdnTotal').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$sql="EXEC Orden..sp_F_AV01 $finicio, $ftermino, '$contrato'";
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	$Total=0;
	do{
		$cont++;
		echo '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'>';
		echo '<td width="2%" align="center">'.$cont.'</td>';
		echo '<td width="3%" align="left">'.$rst["Sisda"].'</td>';
		echo '<td width="6%" align="center">'.$rst["CierreAt"].'</td>';
		echo '<td width="10%" align="left">'.$rst["ITO"].'</td>';
		echo '<td width="12%" align="left">'.$rst["Direccion"].'</td>';
		echo '<td width="5%" align="center">'.$rst["EnvPpto"].'</td>';
		echo '<td width="4%" align="center">'.$rst["Memo"].'</td>';
		echo '<td width="8%" align="left">'.$rst["MV"].'</td>';
		echo '<td width="4%" align="center"><table>'.$rst["Item"].'</table></td>';
		echo '<td width="5%" align="center"><table>'.$rst["Unidad"].'</table></td>';
		echo '<td width="8%" align="center"><table>'.$rst["Cant"].'</table></td>';
		echo '<td width="8%" align="center"><table>'.$rst["PUnit"].'</table></td>';
		echo '<td width="8%" align="center"><table>'.$rst["ValorItem"].'</table></td>';
		echo '<td width="8%" align="center">'.number_format($rst["TotalPpto"], 0, ',', '.').'</td>';
		echo '</tr>';
		$Total+=$rst["TotalPpto"];
	}while($rst = mssql_fetch_array($stmt));	
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="hdnTotal" id="hdnTotal" value="<?php echo number_format($Total, 0, '', '.');?>">
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
