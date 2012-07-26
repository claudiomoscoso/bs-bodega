<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$perfil=$_GET["perfil"];
$nivel=$_GET["nivel"];

$bodsel=$_GET["bodsel"];
$responsable=$_GET["responsable"];
$estado=$_GET["estado"];
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$periodo=$_GET["periodo"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Maestro Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

function Load(){
	parent.Deshabilitar(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 1, NULL, NULL, '$responsable', '$estado', '$mes', $ano, $periodo", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="8%" align="center">'.$rst["dtmFch"].'</td>';
		echo '<td width="8%" align="center">';
		echo '<a href="#" title="Ver caja chica N&deg; '.$rst["dblNum"].'" ';
		echo 'onclick="javascript: ';
		echo 'parent.Deshabilitar(true);';
		echo "AbreDialogo('divCajaChica', 'frmCajaChica', 'caja_chica.php?modulo=0&bodega=$bodega&usuario=$usuario&perfil=$perfil&nivel=$nivel&numero=".$rst["dblNumero"]."', true);";
		echo '"';
		echo 'onmouseover="javascript: window.status=\'Ver caja chica Nº '.$rst["dblNum"].'\'; return true;"';
		echo '>'.$rst["dblNum"].'</a>';
		echo '</td>';
		echo '<td width="19%" align="left">&nbsp;'.$rst["strDescBodega"].'</td>';
		echo '<td width="15%" align="left">';
		echo '<input type="hidden" name="hdnNombre'.$cont.'" id="hdnNombre'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strNombre"])).'" />';
		echo '<input name="txtNombre'.$cont.'" id="txtNombre'.$cont.'" class="txt-sborde" style="width:99%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strNombre"])).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnNombre$cont');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="20%" align="left">';
		echo '<input type="hidden" name="hdnNota'.$cont.'" id="hdnNota'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strNota"])).'" />';
		echo '<input name="txtNota'.$cont.'" id="txtNota'.$cont.'" class="txt-sborde" style="width:99%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strNota"])).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnNota$cont');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="15%" align="center">';
		echo '<a href="#" title="Ver m&aacute;s..."';
		echo 'onmouseover="javascript: window.status=\'Ver más...\'; return true;"';
		echo 'onclick="javascript: ';
		echo 'parent.Deshabilitar(true);';
		echo "AbreDialogo('divEstado', 'frmEstado', 'estados.php?numero=".$rst["dblNumero"]."', true);";
		echo '"';
		echo '>'.$rst["strDescEstado"].'</a>';
		echo '</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '.').'&nbsp;</td>';
		echo '</tr>';
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>