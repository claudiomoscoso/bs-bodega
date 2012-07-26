<?php
include '../conexion.inc.php';

$campos = $_POST["hdnCampos"];
$contrato = $_POST["hdnContrato"];
$movil = $_POST["hdnMovil"];
$anexos = $_POST["hdnAnexo"];
$tanexo = $_POST["hdnTAnexo"];
$ocriterios = $_POST["hdnOCriterios"];
$ftdesde = str_replace("\'","'",$_POST["hdnFTDesde"]);
$fthasta = str_replace("\'","'",$_POST["hdnFTHasta"]);
$fodesde = str_replace("\'","'",$_POST["hdnFODesde"]);
$fohasta = str_replace("\'","'",$_POST["hdnFOHasta"]);
$fhdesde = str_replace("\'","'",$_POST["hdnFHDesde"]);
$fhhasta = str_replace("\'","'",$_POST["hdnFHHasta"]);
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
header("Content-Disposition: attachment; filename=listadomaestro_".date('d-m-Y').".xls");

$contenido='<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido.='<body>';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido.='<tr>';
for($i = 0; $i < count($alias); $i++)
	$contenido.='<th width="'.$largo[$i].'px" align="'.$alinea[$i].'">'.$alias[$i].'</th>';
$contenido.='</tr>';

$stmt = mssql_query("EXEC Orden..sp_getListadoMaestro 0, '$campos', '$orden', '$contrato', '$movil', '$anexos', '$tanexo', $ocriterios, $ftdesde, $fthasta, $fodesde, $fohasta, $fhdesde, $fhhasta, '$lordenes', '$depto', '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$contenido.='<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		for($i = 0; $i < count($alias); $i++) {
			if(strlen($rst[$i])==26 && substr($rst[$i],14,1)==":" && substr($rst[$i],17,1)==":" && substr($rst[$i],20,1)==":" && substr($rst[$i],25,1)=="M") {
				$d='';
				$d=substr(trim($rst[$i]),4,2).' '.substr(trim($rst[$i]),0,3).' '.substr(trim($rst[$i]),7,4).' '.substr(trim($rst[$i]),12,8).' '.substr($rst[$i],24,2);
				$d=strtotime($d);
				$d=date('d/m/Y H:i:s',$d);
				$contenido.='<td align="'.$alinea[$i].'" valign="top">'.$d.'</td>';
			}
			else {
				$contenido.='<td align="'.$alinea[$i].'" valign="top">'.trim($rst[$i]).'</td>';
			}
		}
		$contenido.='</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	$contenido.='<tr ><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
mssql_free_result($stmt);
$contenido.='</table>';
$contenido.='</body>';
echo $contenido.='</html>';

mssql_close($cnx);
?>
