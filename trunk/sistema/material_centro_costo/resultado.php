<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$mes = $_GET["mes"];
$ano = $_GET["ano"];
$bodega = $_GET["bodega"];
$movil = $_GET["movil"];
$ccosto = $_GET["ccosto"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado de Materiales por Centro de Costo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('tdTotal').innerHTML = document.getElementById('hdnTotal').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getMaterialCentroCosto $modulo, '$mes', '$ano', '$bodega', '$movil', '$ccosto'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;
		$total+=$rst["dblTotal"];?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo ($rst["strMovil"] != '' ? $rst["strMovil"] : 'N/A');?></td>
		<td width="10%" align="center"><?php echo $rst["dblNumero"];?></td>
		<td width="8%" align="center"><?php echo $rst["dtmFch"];?></td>
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td width="20%" align="left">
			<input type="hidden" id="hdnDescripcion<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["strDescripcion"]));?>" />
			<input id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#EBF3FE' : '#FFFFFF';?>" readonly="true" value="<?php echo htmlentities(trim($rst["strDescripcion"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="7%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblPrecio"], 0, '', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblTotal"], 0, '', '.');?>&nbsp;</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{?>
	<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>
<?php
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnTotal" id="hdnTotal" value="<?php echo number_format($total, 0, '', '.');?>">
</body>
</html>
<?php
mssql_close($cnx);
?>
