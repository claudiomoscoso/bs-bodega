<?php
include '../autentica.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Maquinaria y Equipos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />

<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td width="2%" nowrap="nowrap" align="center" background="../images/borde_med.png"><b>N&deg;</b></td>
		<td width="5%" align="center" background="../images/borde_med.png"><b>Solicitud</b></td>
		<td width="5%" align="center" background="../images/borde_med.png"><b>Fecha</b></td>
		<td width="15%" align="left" background="../images/borde_med.png"><b>Contrato</b></td>
		<td width="15%" align="left" background="../images/borde_med.png"><b>Solicitante</b></td>
		<td width="15%" align="left" background="../images/borde_med.png"><b>Descripci&oacute;n</b></td>
		<td width="5%" align="center" background="../images/borde_med.png"><b>Unidad</b></td>
		<td width="8%" align="center" background="../images/borde_med.png"><b>Cantidad</b></td>
		<td width="5%" align="center" background="../images/borde_med.png"><b>Desde</b></td>
		<td width="5%" align="center" background="../images/borde_med.png"><b>Hasta</b></td>
		<td width="10%" align="center" background="../images/borde_med.png"><b>Estado/OC</b></td>
	</tr>
	<tr height="20px"><td colspan="11" align="left" background="../images/borde_med_grilla.gif"><b>&nbsp;Solicitudes Resueltas</b></td></tr>
	<tr><td colspan="11"><iframe name="resueltos" id="resueltos" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="resueltos.php" width="100%"></iframe></td></tr>
</table>
</div>
</body>
</html>
<script language="javascript">
<!--
var height=screen.height-115;
document.getElementById('resueltos').height=height+'px';
window.scrollBy('0px', '0px');
-->
</script>