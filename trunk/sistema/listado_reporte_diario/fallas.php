<?php
include '../conexion.inc.php';

$ccosto = $_GET["ccosto"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reporte de Fallas</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('divDetalle').style.height = (window.innerHeight - 20) + 'px';
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<th width="3%">N&deg;</th>
		<th width="85%" align="left">&nbsp;Descripci&oacute;n</th>
		<th width="10%">Fecha</th>
		<th width="2%">&nbsp;</th>
	</tr>
</table>
<div id="divDetalle" style="position:relative; width:100%; overflow:scroll">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("SELECT strDescripcion, CONVERT(VARCHAR, dtmFecha, 103) AS dtmFch FROM Operaciones..ReporteFalla WHERE strCCosto = '$ccosto' ORDER BY dtmFecha DESC", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="85%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</div>
</body>
</html>
