<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$movil = $_GET["movil"];
$usuario = $_GET["usuario"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bitacora</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Imprime(){
	self.focus();
	self.print();
}

function Load(){
	//alert('<?php echo $usuario;?>');
}
-->
</script>
<body topmargin="0px" style="background-color:#FFFFFF" onload="javascript: Load();">
<?php
$stmt = mssql_query("EXEC Orden..sp_getBitacora 1, '$contrato', '$movil', '$usuario'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;?>
<table border="1" width="100%" cellpadding="0" cellspacing="0">
<?php
	if($cont == 1){?>
		<tr><td><b>&nbsp;BITACORA MOVIL: <?php echo $movil.' - ['.$rst["strNombre"].']';?></b></td></tr>
	<?php
	}?>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr><td colspan="7" ></td></tr>
				<tr>
					<td width="11%" valign="top"><b>&nbsp;Orden N&deg;</b></td>
					<td width="1%" valign="top">:</td>
					<td width="38%" valign="top">&nbsp;<?php echo $rst["strOrden"];?></td>
					<td width="1%" valign="top">&nbsp;</td>
					<td width="9%" valign="top"><b>&nbsp;Tr.Realizar</b></td>
					<td width="1%" valign="top">:</td>
					<td width="39%" valign="top">&nbsp;<?php echo $rst["strObservacion"];?></td>
				</tr>
				<tr>
					<td><b>&nbsp;M.Hidr&aacute;ulico</b></td>
					<td>:</td>
					<td>&nbsp;<?php echo $rst["strMovilH"];?></td>
					<td>&nbsp;</td>
					<td><b>&nbsp;Prioridad</b></td>
					<td>:</td>
					<td>&nbsp;<?php echo $rst["strPrioridad"];?></td>
				</tr>
				<tr>
					<td valign="top"><b>&nbsp;Direcci&oacute;n</b></td>
					<td valign="top">:</td>
					<td valign="top">&nbsp;<?php echo trim($rst["strDireccion"]);?></td>
					<td >&nbsp;</td>
					<td valign="top"><b>&nbsp;Emisi&oacute;n</b></td>
					<td valign="top">:</td>
					<td valign="top">&nbsp;<?php echo formato_fecha($rst["dtmEmision"], true, false);?></td>
				</tr>
				<tr>
					<td valign="top"><b>&nbsp;Entre Calles</b></td>
					<td valign="top">:</td>
					<td valign="top">&nbsp;<?php echo $rst["strEntreCalle"];?></td>
					<td >&nbsp;</td>
					<td valign="top"><b>&nbsp;Vencto.</b></td>
					<td valign="top">:</td>
					<td valign="top">&nbsp;<?php echo formato_fecha($rst["dtmTermino"], true, false);?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Comuna</b></td>
					<td >:</td>
					<td >&nbsp;<?php echo $rst["strComuna"];?></td>
					<td >&nbsp;</td>
					<td ><b>&nbsp;Fch.ODT</b></td>
					<td >:</td>
					<td >&nbsp;<?php echo formato_fecha($rst["dtmOrden"], true, false);?></td>
				</tr>
				<tr><td colspan="7" style="height:5px"></td></tr>
				<tr valign="top">
					<td colspan="3" >
						<table border="1" width="100%" cellpadding="0" cellspacing="0">
							<tr><td><b>&nbsp;TRABAJOS</b></td>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td><b>&nbsp;TRABAJOS PENDIENTES</b></td>
							<tr><td>&nbsp;</td></tr>
						</table>
					</td>
					<td >&nbsp;</td>
					<td colspan="3" >
						<table border="1" width="100%" cellpadding="0" cellspacing="0">
							<tr><td><b>&nbsp;MATERIALES</b></td>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
	if($cont == 4){ 
		$cont = 0;
		echo '<H3>&nbsp;</H3>';
	}
}
mssql_free_result($stmt);
?>
</body>
</html>
<?php
mssql_close($cnx);
?>
