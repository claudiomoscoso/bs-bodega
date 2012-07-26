<?php
include '../conexion.inc.php';

$campos = $_POST["hdnCampos"];
$contrato = $_POST["hdnContrato"];
$movil = $_POST["hdnMovil"];
$anexos = $_POST["hdnAnexo"];
$tanexo = $_POST["hdnTAnexo"];
$ocriterios = $_POST["hdnOCriterios"];
$ftdesde = $_POST["hdnFTDesde"] != 'NULL' ? "'".formato_fecha($_POST["hdnFTDesde"], false, true)."'" : 'NULL';
$fthasta = $_POST["hdnFTHasta"] != 'NULL' ? "'".formato_fecha($_POST["hdnFTHasta"], false, true)."'" : 'NULL';
$fodesde = $_POST["hdnFODesde"] != 'NULL' ? "'".formato_fecha($_POST["hdnFODesde"], false, true)."'" : 'NULL';
$fohasta = $_POST["hdnFOHasta"] != 'NULL' ? "'".formato_fecha($_POST["hdnFOHasta"], false, true)."'" : 'NULL';
$fhdesde = $_POST["hdnFHDesde"] != 'NULL' ? "'".formato_fecha($_POST["hdnFHDesde"], false, true)."'" : 'NULL';
$fhhasta = $_POST["hdnFHHasta"] != 'NULL' ? "'".formato_fecha($_POST["hdnFHHasta"], false, true)."'" : 'NULL';
$orden = $_POST["hdnOrden"];
$depto = $_POST["hdnDepto"];

$stmt = mssql_query("EXEC Orden..sp_getCampos 2, '$campos'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$alias = split('&&&', $rst["strAlias"]);
	$alinea = split('&&&', $rst["strAlinea"]);
	$largo = split('&&&', $rst["strLargo"]);
	$lrgttl = $rst["dblLrgTtl"];
}
mssql_free_result($stmt);

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=listadomaestro_".date('d-m-Y').".xml");

$contenido='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
$contenido.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$contenido.='<html>';
$contenido.='<head>';
$contenido.='<meta http-equiv="Content-type" content="text/html;charset=iso-8859-1" />';
$contenido.='<style id="Classeur1_16681_Styles"></style>';
$contenido.='</head>';
$contenido.='<body>';
$contenido.='<div id="Classeur1_16681" align=center x:publishsource="Excel">';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido.='<tr>';
for($i = 0; $i < count($alias); $i++)
	$contenido.='<th width="'.$largo[$i].'px" align="'.$alinea[$i].'">'.$alias[$i].'</th>';
$contenido.='</tr>';

$stmt = mssql_query("EXEC Orden..sp_getListadoMaestro 0, '$campos', '$orden', '$contrato', '$movil', '$anexos', '$tanexo', $ocriterios, $ftdesde, $fthasta, $fodesde, $fohasta, $fhdesde, $fhhasta, '$lordenes', '$depto'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$contenido.='<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	for($i = 0; $i < count($alias); $i++)
		$contenido.='<td align="'.$alinea[$i].'" valign="top">&nbsp;'.trim($rst[$i]).'</td>';
	$contenido.='</tr>';
}
mssql_free_result($stmt);
$contenido.='</table>';
$contenido.='</div>';
$contenido.='</body>';
echo $contenido.='</html>';

mssql_close($cnx);
?>
