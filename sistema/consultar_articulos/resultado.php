<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$material = $_GET["material"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consulta de Art&iacute;culos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getDevolucionesPendientes 5, NULL, '$bodega', NULL, '$material'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		if($rut != trim($rst["strRut"])){ 
			echo '<tr><td colspan="5" style="background-image:url(../images/borde_menu.gif)"><b>&nbsp;['.trim($rst["strRut"]).'] '.$rst["strNombre"].'</b></td></tr>';
			$rut = trim($rst["strRut"]);
		}
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
		echo '<td width="65%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
		echo '<td width="10%" align="center">'.($rst["dtmFch"] != '' ? $rst["dtmFch"] : 'S/Registro').'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblSaldo"], 2, ',', '.').'&nbsp;</td>';
		echo '</tr>';
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
echo '<input type="hidden" name="totfil" id="totfil" value="'.$cont.'">';
?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>