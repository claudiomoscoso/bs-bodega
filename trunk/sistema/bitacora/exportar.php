<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$movil = $_GET["movil"];
$usuario = $_GET["usuario"];
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=bitacora_".$movil."_".date('d-m-Y').".xls");
$contenido = '<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido .= '<body>';
$contenido .= '<table border="1" width="100%" cellpadding="0" cellspacing="0">';
$contenido .= '<tr>';
$contenido .= '<th>Orden</th>';
$contenido .= '<th>Trabajo</th>';
$contenido .= '<th>Movil Madre</th>';
$contenido .= '<th>Prioridad</th>';
$contenido .= '<th>Direccion</th>';
$contenido .= '<th>Emision</th>';
$contenido .= '<th>Entre Calles</th>';
$contenido .= '<th>Termino</th>';
$contenido .= '<th>Comuna</th>';
$contenido .= '<th>Fecha Orden</th>';
$contenido .= '</tr>';
$stmt = mssql_query("EXEC Orden..sp_getBitacora 1, '$contrato', '$movil', '$usuario'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$contenido .= '<tr>';
	$contenido .= '<td>'.$rst["strOrden"].'</td>';
	$contenido .= '<td nowrap>'.$rst["strObservacion"].'</td>';
	$contenido .= '<td>'.$rst["strMovilH"].'</td>';
	$contenido .= '<td>'.$rst["strPrioridad"].'</td>';
	$contenido .= '<td nowrap>'.$rst["strDireccion"].'</td>';
	$contenido .= '<td>'.formato_fecha($rst["dtmEmision"],true, false).'</td>';
	$contenido .= '<td nowrap>'.$rst["strEntreCalle"].'</td>';
	$contenido .= '<td>'.formato_fecha($rst["dtmTermino"],true, false).'</td>';
	$contenido .= '<td>'.$rst["strComuna"].'</td>';	
	$contenido .= '<td>'.formato_fecha($rst["dtmOrden"],true, false).'</td>';
	$contenido .= '</tr>';
}

mssql_free_result($stmt);
mssql_close($cnx);
$contenido .= '</table>';
$contenido .= '</body>';
echo $contenido .= '</html>';
?>
