<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$ano = $_GET["ano"];
$ctrl = $_GET["ctrl"];
$col = $_GET["col"]!='' ? $_GET["col"] : 'NULL';
$orden = $_GET["orden"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consulta de Ingresos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('<?php echo $ctrl;?>').value=document.getElementById('total').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getIngresosAcumulados 3, '$bodega', NULL, $ano, NULL, $col, '$orden'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="3%" align="center">&nbsp;</td>';
		/*echo '<a href="#" title="Ver mas..." ';
		echo 'onclick="javascript: ';
		echo "parent.document.getElementById('txtMaterial').value='[".$rst["strCodigo"]."] ".htmlentities($rst["strDescripcion"])."'; ";
		echo "parent.document.getElementById('hdnMesNvl3').value='$mes'; ";
		echo "parent.document.getElementById('hdnAnoNvl3').value='$ano'; ";
		echo "parent.document.getElementById('hdnMaterial').value='".$rst["strCodigo"]."'; ";
		echo "if(parent.document.getElementById('divIngresos').style.visibility=='hidden') parent.Deshabilita(true); ";
		echo 'parent.OcultaFlechas(2, false); ';
		echo "AbreDialogo('divGIngresos', 'frmGIngresos', 'detalleingresos.php?bodega=$bodega&mes=$mes&ano=$ano&codigo=".$rst["strCodigo"]."&col=1&orden=A', true); ";
		echo '" ';
		echo 'onmouseover="javascript: window.status=\'Guías de Ingreso con '.htmlentities($rst["strDescripcion"]).'\'; return true;" ';
		echo '><img border="0" src="../images/mas.gif" /></a>';*/
		//echo '</td>';
		echo '<td width="87%">&nbsp;['.$rst["strCodigo"].'] '.htmlentities($rst["strDescripcion"]).'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '','.').'&nbsp;</td>';
		echo '</tr>';
		$total+=$rst["dblTotal"];
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="total" id="total" value="<?php echo number_format($total,0,'','.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>