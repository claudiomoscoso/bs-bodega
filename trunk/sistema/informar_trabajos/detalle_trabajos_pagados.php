<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$item = $_GET["item"];
switch($modulo){
	case 1:
		$epago = $_GET["epago"];
		$certificado = $_GET["certificado"];
		$informado = $_GET["informado"];
		$pagado = $_GET["pagado"];

		$stmt = mssql_query("EXEC Orden..sp_setTMPItemPagados 0, '$usuario', $epago, $certificado, $pagado, $informado", $cnx);
		if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
		mssql_free_result($stmt);
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $modulo;?>') == 1 && parseInt('<?php echo $error;?>') == 1)
		alert('La cifra que intenta ingresar excede la cantidad informada.');
	parent.document.getElementById('txtTotal').value = document.getElementById('hdnTotal').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getTMPItemPagados 1, '$usuario'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="4%" align="center" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.$cont.'</td>';
	echo '<td width="15%" align="center" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.$rst["dblEP"].'</td>';
	echo '<td width="15%" align="center" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.$rst["dblCertificado"].'</td>';
	echo '<td width="15%" align="center" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.$item.'</td>';
	echo '<td width="15%" align="right" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.number_format($rst["dblInformado"], 2, ',', '.').'&nbsp;</td>';
	echo '<td width="15%" align="right" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.number_format($rst["dblPagado"], 2, ',', '.').'&nbsp;</td>';
	$tpagado += $rst["dblPagado"];
	$saldo = $rst["dblInformado"] - $tpagado;
	echo '<td width="15%" align="right" '.($cont == 1 ? 'style="font-weight:bold"' : '').'>'.number_format($saldo, 2, ',', '.').'&nbsp;</td>';
	echo '</tr>';
	$total += $rst["dblPagado"];
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnTotal" id="hdnTotal" value="<?php echo number_format($total, 2, ',', '.');?>" />
</body>
</html>