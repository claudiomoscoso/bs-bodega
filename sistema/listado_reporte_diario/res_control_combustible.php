<?php
include '../conexion.inc.php';

$obra = $_POST["hdnObra"];
$equipo = $_POST["hdnEquipo"];
$estado = $_POST["cmbEstado"];
$operador = $_POST["hdnOperador"];
$mes = $_POST["cmbMes"];
$ano = $_POST["cmbAno"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado de Reportes Diarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	document.getElementById('divDetalle').style.height = (window.innerHeight - 25) + 'px';
	parent.Deshabilita(false);
}

function Scroll(ctrl){
	document.getElementById('divCabecera').scrollLeft = ctrl.scrollLeft;
	return true;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<div id="divCabecera" style="position:relative;width:100%;height:22px;overflow:hidden">
<table border="0" width="1500px" cellpadding="0" cellspacing="0">
	<tr>
		<th width="25px">N&deg;</th>
		<th width="65px">Fecha</th>
		<th width="150px" align="left">&nbsp;Obra</th>
		<th width="50px">C.Costo</th>
		<th width="55px">Patente</th>
		<th width="150px" align="left">&nbsp;Equipo</th>
		<th width="150px" align="left">&nbsp;Marca</th>
		<th width="150px" align="left">&nbsp;Modelo</th>
		<th width="40px">A&ntilde;o</th>
		<th width="150px" align="left">&nbsp;Operador</th>
		<th width="90px">T.Combustible</th>
		<th width="50px">Litros</th>
		<th width="90px">Kms./Hrs. Inicial</th>
		<th width="90px">Kms./Hrs. Final</th>
		<th width="90px">Kms./Hrs. Total</th>
		<th width="90px">Rend.(Kms./Lts.)</th>
		<th width="25px">&nbsp;</th>
	</tr>
</table>
</div>

<div id="divDetalle" style="position:relative; width:100%; height:100px; overflow:scroll"
	onscroll="javascript: Scroll(this);"
>
<table border="0" width="1476px" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 3, NULL, '$obra', '$equipo', '$estado', '$operador', '$mes', '$ano'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
		<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="25px" align="center"><?php echo $cont;?></td>
		<td width="65px" align="center"><?php echo $rst["dtmFecha"];?></td>
		<td width="150px">
			<input type="hidden" name="hdnObra<?php echo $cont;?>" id="hdnObra<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strObra"]);?>" />
			<input name="txtObra<?php echo $cont;?>" id="txtObra<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strObra"]);?>"
				onmouseover="javascript:
					clearInterval(Intervalo);
					Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnObra<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnObra<?php echo $cont;?>')
				"
			>
		</td>
		<td width="50px" align="center"><?php echo $rst["strCCosto"];?></td>
		<td width="52px" align="center"><?php echo $rst["strPatente"];?></td>
		<td width="150px">
			<input type="hidden" name="hdnEquipo<?php echo $cont;?>" id="hdnEquipo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strEquipo"]);?>" />
			<input name="txtEquipo<?php echo $cont;?>" id="txtEquipo<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strEquipo"]);?>"
				onmouseover="javascript:
					clearInterval(Intervalo);
					Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnEquipo<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEquipo<?php echo $cont;?>')
				"
			>
		</td>
		<td width="150px">&nbsp;<?php echo $rst["strMarca"];?></td>
		<td width="150px">&nbsp;<?php echo $rst["strModelo"];?></td>
		<td width="35px" align="center"><?php echo $rst["dblAno"];?></td>
		<td width="150px">
			<input type="hidden" name="hdnOperador<?php echo $cont;?>" id="hdnOperador<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strNombre"]);?>" />
			<input name="txtOperador<?php echo $cont;?>" id="txtOperador<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strNombre"]);?>"
				onmouseover="javascript:
					clearInterval(Intervalo);
					Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnOperador<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnOperador<?php echo $cont;?>')
				"
			>
		</td>
		<td width="90px" align="center"><?php echo $rst["strTCombustible"];?></td>
		<td width="50px" align="center"><?php echo number_format($rst["dblCombustible"], 2, ',', '.');?></td>
		<td width="90px" align="center"><?php echo number_format($rst["dblOdometroInicial"], 2, ',', '.');?></td>
		<td width="90px" align="center"><?php echo number_format($rst["dblOdometroFinal"], 2, ',', '.');?></td>
		<td width="90px" align="center"><?php echo number_format($rst["dblOdometroFinal"] - $rst["dblOdometroInicial"], 2, ',', '.');?></td>
		<td width="90px" align="center"><?php echo @number_format(($rst["dblOdometroFinal"] - $rst["dblOdometroInicial"]) / $rst["dblCombustible"], 2, ',', '.');?></td>
		</tr>
	<?php
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="font-weight:bold; color:#FF0000">No se ha encontrado informaci&oacute;n.</td></tr>';
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</div>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
