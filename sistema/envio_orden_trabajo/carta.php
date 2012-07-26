<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$correlativo = $_GET["envio"];

//$stmt = mssql_query("SELECT DISTINCT dblEP FROM Orden..CaratulaOrden WHERE strContrato = '$contrato' AND dblCertificado = $correlativo", $cnx);
$stmt = mssql_query("SELECT DISTINCT C.dblEP, Z.strZona-2019 as strZona FROM Orden..CaratulaOrden as C LEFT JOIN General..Tablon as Z on C.strComuna=Z.strCodigo and Z.strContrato=C.strContrato WHERE C.strContrato = '$contrato' AND dblCertificado= $correlativo", $cnx);
if($rst = mssql_fetch_array($stmt)){ 
	$epago = $rst["dblEP"];
	$zona = $rst["strZona"];
}
mssql_free_result($stmt);

$fecha = 'No registra fecha';
$stmt1 = mssql_query("SELECT CONVERT(VARCHAR, dtmFecha, 120) AS dtmFecha FROM Orden..Envio WHERE dblNumero = $correlativo and strContrato = $contrato", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $fecha = formato_fecha($rst1["dtmFecha"], true, false);
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'CABENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $cab = str_replace('$fecha', $fecha, str_replace('$correlativo', $correlativo, $rst1["strHtml"]));
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'DIRENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $dir = $rst1["strHtml"];
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'REFENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $ref = $rst1["strHtml"];
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'LEYENV'", $cnx);
//if($rst1 = mssql_fetch_array($stmt1)) $ley = str_replace('$tipo', ($nvo == 1 ? 'env&iacute;o' : 'reenv&iacute;o'), str_replace('$epago', $epago, trim($rst1["strHtml"])));
if($rst1 = mssql_fetch_array($stmt1)) $ley = str_replace('$zona', $zona, str_replace('$ano', date('Y'), str_replace('$mes' , mes(date('m')), str_replace('$tipo', ($nvo == 1 ? 'env&iacute;o' : 'reenv&iacute;o'), str_replace('$epago', $epago, trim($rst1["strHtml"]))))));
mssql_free_result($stmt1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Envio Orden de Trabajo</title>
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
<body topmargin="0px" style="background-color:#FFFFFF; padding-top:0px" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
<?php
$stmt = mssql_query("EXEC Orden..sp_getEnvioOrdenTrabajo 0, '$contrato', $correlativo", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$ln++;
	if($cont == 1 || $cont > 25){
		$cont = 1;?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td><img border="0" align="absmiddle" src="../images/logo.jpg" /></td>
								<td width="21%" valign="top"><?php echo $cab;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td style="height:40px">&nbsp;</td></tr>
				<?php /*if($contrato == "13001") $dir = str_replace("Tongoy - Guanaqueros", $rst["strComuna"], str_replace("Carlos Gallardo", $rst["strInspector"], $dir));*/?>
				<tr><td><?php echo $dir;?></td></tr>
				<tr><td style="height:20px">&nbsp;</td></tr>
				<tr><td><?php echo $ref;?></td></tr>
				<tr><td style="height:20px">&nbsp;</td></tr>
				<tr><td><?php echo $ley;?></td></tr>
				<tr><td><hr /></td></tr>
			</table>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="8%" align="center"><b>Orden</b></td>
					<td width="10%" align="center"><b>Fecha</b></td>
					<?php if($contrato == "13001") echo '<td width="8%" align="center"><b>N.Cliente</b></td>';?>
					<td width="40%" align="left"><b>&nbsp;Direcci&oacute;n</b></td>
					<td width="13%" align="left"><b>&nbsp;Comuna</b></td>
					<td width="13%" align="left"><b>&nbsp;Inspector</b></td>
					<td width="9%" align="right"><b>Total&nbsp;</b></td>
				</tr>
			</table>
	<?php
	}?>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="3%" align="center" style="font-size:10px"><?php echo $ln;?></td>
					<td width="8%" align="center" style="font-size:10px"><?php echo $rst["strOrden"];?></td>
					<td width="10%" align="left" style="font-size:10px"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo formato_fecha($rst["dtmOrden"]);?>" /></td>
					<?php if($contrato == "13001") echo '<td width="8%" align="left" style="font-size:10px">'.$rst["strNumCliente"].'</td>';?>
					<td width="40%" align="left" style="font-size:10px"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo strtolower($rst["strDireccion"]);?>" /></td>
					<td width="13%" align="left" style="font-size:10px">&nbsp;<?php echo $rst["strComuna"];?></td>
					<td width="13%" align="left" style="font-size:10px"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strInspector"];?>" /></td>
					<td width="9%" align="right" style="font-size:10px"><?php echo number_format($rst["dblTotal"], 0, '', '.');?>&nbsp;</td>
				</tr>
			</table></TD>
<?php
	if($cont == 25) echo '<H3>&nbsp;</H3>';
}
mssql_free_result($stmt);?>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="center">
			<table border="1" width="70%" cellpadding="0" cellspacing="0">
				<tr align="center" valign="bottom" style="height:50px">
					<td width="50%"><b>Coordinador EDECO S.A.</b></td>
					<td width="50%"><b>Recepci&oacute;n ESVAL</b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
