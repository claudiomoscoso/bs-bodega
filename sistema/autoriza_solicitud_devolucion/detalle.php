<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autorizar Solicitud de Devoluci&oacute;n</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC sp_getSolicitudDevolucion 0, '$bodega'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF1FF').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="15%" >&nbsp;'.$rst["strCargo"].'</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFecha"].'</td>';
		echo '<td width="10%" align="center">';
		echo '<a href="#" title="Autorizar Gu&iacute;a N&deg;: '.$rst["dblNumero"].'"';
		echo 'onclick="javascript: ';
		echo 'parent.Deshabilita(true);';
		echo "AbreDialogo('divSolicitud', 'frmSolicitud', 'solicitud_devolucion.php?usuario=$usuario&numero=".$rst["dblNumero"]."', true);";
		echo '"';
		echo 'onmouseover="javascript: window.status=\'Autorizar Gu&iacute;a N&deg;: '.$rst["dblNumero"].'\'; return true;"';
		echo '>'.$rst["dblNumero"].'</a>';
		echo '</td>';
		echo '<td width="20%" >&nbsp;'.$rst["strSolicitante"].'</td>';
		echo '<td width="40%" >&nbsp;'.$rst["strObservacion"].'</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>