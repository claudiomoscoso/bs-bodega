<?php
include '../conexion.inc.php';

$bodega = $_POST["cmbBodega"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Entrega de Cargos</title>
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
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getEntregaCargos 0, '$bodega', $finicio, $ftermino", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
		echo '<td width="25%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="5%" align="center">'.$rst["strUnidad"].'</td>';
		echo '<td width="8%" align="right">'.number_format($rst["dblIngreso"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="8%" align="right">'.number_format($rst["dblSalida"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblValor"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="8%" align="center">'.$rst["dtmFch"].'</td>';
		echo '<td width="25%" align="left">'.$rst["strNombre"].'</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
