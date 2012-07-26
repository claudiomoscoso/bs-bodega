<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$finicio = $_GET["finicio"] != '' ? "'".formato_fecha($_GET["finicio"], false, true)."'" : 'NULL';
$ftermino = $_GET["ftermino"] != '' ? "'".formato_fecha($_GET["ftermino"], false, true)."'" : 'NULL';
$epago = $_GET["epago"] != '' ? $_GET["epago"] : 'NULL';

$spagina = '<H3></H3>';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Lisorval</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	self.focus();
	self.print();
}
-->
</script>
<body marginheight="0" marginwidth="0" onLoad="javascript: Load();">
<?php
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 6, '', '$contrato', NULL, NULL, NULL, NULL, NULL, NULL, $epago, $finicio, $ftermino", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	if($orden != $rst["strOrden"]){
		if($orden != ''){
			$ln++;?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="79%" align="right" style="border-top:solid 1px"><b>TOTAL</b></td>
					<td width="1%" align="center" style="border-top:solid 1px"><b>:</b></td>
					<td width="10%" align="right" style="border-top:solid 1px"><b><?php echo number_format($total, 0, '', '.');?>&nbsp;</b></td>
					<td width="10%" style="border-top:solid 1px">&nbsp;</td>
				</tr>
			</table>
<?php		$ln++;
			if($ln >= 69){
				$ln = 0;
				echo $spagina;
			}else{?>
				<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
<?php		}
		}
		$total = 0;
		$orden = $rst["strOrden"];
		$ln++;
		if($ln >= 67){
			$ln = 1;
			echo $spagina;
		}
		$cabecera = '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
		$cabecera .= '<tr >';
		$cabecera .= '<td width="10%" align="center"><b>'.$rst["strOrden"].'</b></td>';
		$cabecera .= '<td width="10%" align="center"><b>'.$rst["strODS"].'</b></td>';
		$cabecera .= '<td width="15%" align="center"><b>'.formato_fecha($rst["dtmOrden"], true, false).'</b></td>';
		$cabecera .= '<td width="43%" align="left"><b>&nbsp;'.$rst["strDireccion"].'</b></td>';
		$cabecera .= '<td width="20%" align="left"><b>&nbsp;'.$rst["strSector"].'</b></td>';
		$cabecera .= '</tr>';
		$cabecera .= '</table>';
		echo $cabecera;
	}
	
	$ln++;
	if($ln >= 68){
		$ln = 2;
		echo $spagina;
		echo $cabecera;
	}?>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="10%" align="center"><?php echo $rst["strItem"];?></td>
			<td width="38%" align="left">&nbsp;<?php echo substr($rst["strDescripcion"], 0, 60);?></td>
			<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
			<td width="10%" align="right"><?php echo number_format($rst["dblPrecio"], 0, '', '.');?>&nbsp;</td>
			<td width="10%" align="right"><?php echo number_format($rst["dblCantidadEmos"], 2, ',', '.');?>&nbsp;</td>
			<td width="10%" align="right"><?php echo number_format($rst["dblTInformado"], 0, '', '.');?>&nbsp;</td>
			<td width="10%" align="center"><?php echo $rst["strMovil"];?></td>
		</tr>
	</table>
<?php
	$total+=$rst["dblTInformado"];
}
mssql_free_result($stmt);
$ln++;?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="79%" align="right" style="border-top:solid 1px"><b>TOTAL</b></td>
		<td width="1%" align="center" style="border-top:solid 1px"><b>:</b></td>
		<td width="10%" align="right" style="border-top:solid 1px"><b><?php echo number_format($total, 0, '', '.');?>&nbsp;</b></td>
		<td width="10%" style="border-top:solid 1px">&nbsp;</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>