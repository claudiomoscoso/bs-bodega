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
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 2, NULL, '$obra', '$equipo', '$estado', '$operador', '$mes', '$ano'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="7%" align="center">'.$rst["strCCosto"].'</td>';
		echo '<td width="15%" align="left">';
		echo '<input type="hidden" name="hdnEquipo'.$cont.'" id="hdnEquipo'.$cont.'" value="'.htmlentities($rst["strEquipo"]).'" />';
		echo '<input name="txtEquipo'.$cont.'" id="txtEquipo'.$cont.'" class="txt-sborde" style="width:100%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="'.htmlentities($rst["strEquipo"]).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnEquipo$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnEquipo$cont');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="10%">';
		echo '<input type="hidden" name="hdnEstado'.$cont.'" id="hdnEstado'.$cont.'" value="'.htmlentities($rst["strEstado"]).'" />';
		echo '<input name="txtEstado'.$cont.'" id="txtEstado'.$cont.'" class="txt-sborde" style="width:100%; text-align:center; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="'.htmlentities($rst["strEstado"]).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript:';
		echo "DetieneTexto(Intervalo, this.id, 'hdnEstado$cont');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="10%" align="center">'.number_format($rst["dblOdometroInicial"], 2, ',', '.').'</td>';
		echo '<td width="10%" align="center">'.number_format($rst["dblOdometroFinal"], 2, ',', '.').'</td>';
		echo '<td width="9%" align="center">'.number_format($rst["dblOdometroFinal"] - $rst["dblOdometroInicial"], 2, ',', '.').'</td>';
		echo '<td width="7%" align="center">'.$rst["dblNumero"].'</td>';
		echo '<td width="6%" align="center">'.number_format($rst["dblCombustible"], 2, ',', '.').'</td>';
		echo '<td width="15%" align="left">';
		echo '<input type="hidden" name="hdnOperador'.$cont.'" id="hdnOperador'.$cont.'" value="'.htmlentities($rst["strNombre"]).'" />';
		echo '<input name="txtOperador'.$cont.'" id="txtOperador'.$cont.'" class="txt-sborde" style="width:100%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="'.htmlentities($rst["strNombre"]).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnOperador$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnOperador$cont');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="6%" align="center">'.$rst["strOTrabajo"].'</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="font-weight:bold; color:#FF0000">No se ha encontrado informaci&oacute;n.</td></tr>';
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
