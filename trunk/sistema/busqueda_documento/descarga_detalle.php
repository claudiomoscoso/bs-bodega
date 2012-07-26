<?php
include '../conexion.inc.php';

$bodega=$_GET["bodega"];
$tipodoc=$_GET["tipodoc"]; 
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$material=$_GET["material"] != '' ? "'".$_GET["material"]."'" : 'NULL';
$proveedor=$_GET["proveedor"] != '' ? "'".$_GET["proveedor"]."'" : 'NULL'; 
$tbusqueda=$_GET["tbusqueda"] != '' ? $_GET["tbusqueda"] : 'NULL'; 
$observacion=$_GET["observacion"] != '' ? "'".$_GET["observacion"]."'" : 'NULL';
$numero=$_GET["numero"] != '' ? $_GET["numero"] : 'NULL';

if($observacion!='NULL' && $tbusqueda==0 && ($tipodoc==0 || $tipodoc==1 || $tipodoc==2 || $tipodoc==6))
	$sql= "EXEC Bodega..sp_getOrdenCompra 'NUM', $observacion, '%', '$bodega'";
elseif($observacion!='NULL' && $tbusqueda==1 && $tipodoc==6)
	$sql= "EXEC Bodega..sp_getGuiaIngreso 'NUM', $observacion,  '$bodega'";


if($sql!=''){
	$stmt = mssql_query($sql, $cnx);
	if($rst=mssql_fetch_array($stmt)) $observacion=$rst["dblNumero"];
	mssql_free_result($stmt);
}

$contenido='<html xmlns="http://www.w3.org/1999/xhtml">';
$contenido.='<body marginwidth="0" marginheight="1px">';
$contenido.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$contenido.='<tr>';
$contenido.='<td>';
$contenido.='<table border="1" width="100%" cellpadding="0" cellspacing="0">';
switch($tipodoc){
	case 0:
		$nombarch='solicitus_materiales';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="10%">N&deg; Solicitud</th>';
		$contenido.='<th width="30%" align="left">&nbsp;Cargo</th>';
		$contenido.='<th width="45%" align="left">&nbsp;Observaci&oacute;n</th>';
		$contenido.='</tr>';
		break;
	case 1:
		$nombarch='orden_compra';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="8%">Fecha</th>';
		$contenido.='<th width="8%">N&deg; O.Compra</th>';
		$contenido.='<th width="7%">Tipo Doc.</th>';
		$contenido.='<th width="15%" align="left">&nbsp;Proveedor</th>';
		$contenido.='<th width="8%" >Factura</th>';
		$contenido.='<th width="10%" align="right">Monto&nbsp;</th>';
		$contenido.='<th width="10%" align="left">&nbsp;Cargo</th>';
		$contenido.='<th width="20%" align="left">&nbsp;Observaci&oacute;n</th>';
		$contenido.='<th width="9%" align="right">Neto&nbsp;</th>';
		$contenido.='</tr>';
		break;
	case 2:
		$nombarch='guia_ingreso';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="10%">N&deg; G.Ingreso</th>';
		$contenido.='<th width="30%" align="left">&nbsp;Bodega</th>';
		$contenido.='<th width="10%">N&deg; O.Compra</th>';
		$contenido.='<th width="20%" align="left">&nbsp;T.Documento</th>';
		$contenido.='<th width="15%">Referencia</th>';
		$contenido.='</tr>';
		break;
	case 3:
	case 4:
		$nombarch='guia_'.($tipodoc==3 ? 'despacho' : 'devolucion');
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="11%">N&deg; G.'.($tipodoc==3 ? 'Despacho' : 'Devoluci&oacute;n').'</th>';
		$contenido.='<th width="45%" align="left">&nbsp;Movil</th>';
		$contenido.='<th width="29%" align="left">&nbsp;Bodega</th>';
		$contenido.='</tr>';
		break;
	case 5:
		$nombarch='vale_consumo';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="11%">N&deg; V.Consumo</th>';
		$contenido.='<th width="37%" align="left">&nbsp;Obra</th>';
		$contenido.='<th width="37%" align="left">&nbsp;Responsable</th>';
		$contenido.='</tr>';
		break;
	case 6:
		$nombarch='factura_boleta';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="10%">N&deg; Documento</th>';
		$contenido.='<th width="15%" align="left">&nbsp;T. Documento</th>';
		$contenido.='<th width="30%" align="left">&nbsp;Proveedor</th>';
		$contenido.='<th width="10%">N&deg; O.Compra</th>';
		$contenido.='<th width="10%">N&deg; Recepci&oacute;n</th>';
		$contenido.='<th width="10%" align="right">Monto&nbsp;</th>';
		$contenido.='<th width="10%" align="center">Ing.Softland</th>';
		$contenido.='</tr>';
		break;
	case 7:
		$nombarch='caja_chica';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="8%">Fecha</th>';
		$contenido.='<th width="12%">N&deg; Documento</th>';
		$contenido.='<th width="20%" align="left">&nbsp;Cargo</th>';
		$contenido.='<th width="50%" align="left">&nbsp;Nota</th>';
		$contenido.='<th width="10%" align="right">Neto&nbsp;</th>';
		$contenido.='</tr>';
		break;
	case 8:
		$nombarch='guia_cargo';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="8%">Fecha</th>';
		$contenido.='<th width="12%">N&deg; Gu&iacute;a</th>';
		$contenido.='<th width="15%" align="left">&nbsp;Cargo</th>';
		$contenido.='<th width="30%" align="left">&nbsp;Nombre</th>';
		$contenido.='<th width="30%" align="left">&nbsp;Bodega</th>';
		$contenido.='</tr>';
		break;
	case 9:
		$nombarch='devolucion_cargo';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="8%">Fecha</th>';
		$contenido.='<th width="12%">N&deg; Gu&iacute;a</th>';
		$contenido.='<th width="15%" align="left">&nbsp;Cargo</th>';
		$contenido.='<th width="20%" align="left">&nbsp;Nombre</th>';
		$contenido.='<th width="20%" align="left">&nbsp;Bodega</th>';
		$contenido.='<th width="20%" align="left">&nbsp;Usuario</th>';
		$contenido.='</tr>';
		break;
	case 10:
		$nombarch='termino_bodega';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="13%">N&deg; T.bodega</th>';
		$contenido.='<th width="72%" align="left">&nbsp;Usuario</th>';
		$contenido.='</tr>';
		break;
	case 11:
		$nombarch='factura_interna';
		$contenido.='<tr>';
		$contenido.='<th width="3%">N&deg;</th>';
		$contenido.='<th width="10%">Fecha</th>';
		$contenido.='<th width="10%">N&deg;F.Interna</th>';
		$contenido.='<th width="65%" align="left">&nbsp;Cargo</th>';
		$contenido.='<th width="10%">Total&nbsp;</th>';
		$contenido.='</tr>';
		break;
}

$stmt = mssql_query("EXEC Bodega..sp_getBuscaDocumento '$bodega', $tipodoc, $mes, '$ano', $material, $proveedor, $observacion, $numero, $tbusqueda", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	switch($tipodoc){
		case 0:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'" >';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="9%" align="center" '.($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').'>'.$rst["dblNum"].'</td>';
			$contenido.='<td width="30%" align="left">'.$rst["strDescCargo"].'</td>';
			$contenido.='<td width="47%" align="left">'.trim($rst["strObservacion"]).'</td>';
			$contenido.='</tr>';
			break;
		case 1:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'" >';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="8%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="8%" align="center" '.($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').'>'.$rst["dblNum"].'</td>';
			$contenido.='<td width="7%" align="center">'.$rst["strTipoDoc"].'</td>';
			$contenido.='<td width="15%" align="left">'.$rst["strNombre"].'</td>';
			$contenido.='<td width="8%" align="center">'.$rst["dblNumDoc"].'</td>';
			$contenido.='<td width="10%" align="right">'.number_format($rst["dblMonto"], 0, '', '.').'</td>';
			$contenido.='<td width="10%" align="left">'.$rst["strDescCargo"].'</td>';
			$contenido.='<td width="20%" align="left">'.$rst["strObservacion"].'</td>';
			
			if($rst["strEstado"] != 5 && $numoc != $rst["dblNumero"]){
				$numoc=$rst["dblNumero"];
				$total+=$rst["dblNeto"];
				$contenido.='<td width="9%" align="right">'.number_format($rst["dblNeto"], 0, '', '').'</td>';
			}elseif($rst["strEstado"]==5){
				$contenido.='<td width="9%" align="right">0</td>';
			}
			$contenido.='</tr>';
			break;
		case 2:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="10%" align="center" '.($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').'>'.$rst["dblNum"].'</td>';
			$contenido.='<td width="31%" align="left">'.$rst["strDescBodega"].'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dblOrdenC"].'</td>';
			$contenido.='<td width="20%" align="left">'.$rst["strTDoc"].'</td>';
			$contenido.='<td width="17%" align="center">'.$rst["strReferencia"].'</td>';
			$contenido.='</tr>';
			break;
		case 3:
		case 4:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="11%" align="center" '.($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').'>'.$rst["dblNum"].'</td>';
			$contenido.='<td width="45%" align="left">'.($rst["strNombre"] != '' ? $rst["strNombre"] : $rst["strMovil"]).'</td>';
			$contenido.='<td width="31%" align="left">'.$rst["strDescBodega"].'</td>';
			$contenido.='</tr>';
			break;
		case 5:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="11%" align="center" '.($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').'>'.$rst["dblNum"].'</td>';
			$contenido.='<td width="37%" align="left">'.$rst["strDescObra"].'</td>';
			$contenido.='<td width="39%" align="left">'.$rst["strNombre"].'</td>';
			$contenido.='</tr>';
			break;
		case 6:
			$total+=$rst["dblMonto"];
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="10%" align="center" '.($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').'>'.$rst["dblNumDoc"].'</td>';
			$contenido.='<td width="15%" align="left">'.$rst["strTipoDoc"].'</td>';
			$contenido.='<td width="30%" align="left">'.$rst["strNombre"].'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dblUltima"].'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dblNumero"].'</td>';
			$contenido.='<td  align="right">'.number_format($rst["dblMonto"], 0, '', '').'</td>';
			if($rst["strDescEstado"] == "Sin datos en Softland") $contenido.='<td align="center">N</td>'; else  $contenido.='<td align="center">S</td>'; 
			$contenido.='</tr>';
			break;
		case 7:
			$total+=$rst["dblNeto"];
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%">'.$cont.'</td>';
			$contenido.='<td width="8%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="12%" align="center">'.$rst["dblNum"].'</td>';
			$contenido.='<td width="20%" align="left">'.$rst["strNombre"].'</td>';
			$contenido.='<td width="50%" align="left">'.$rst["strNota"].'</td>';
			$contenido.='<td width="12%" align="right">'.number_format($rst["dblNeto"], 0, '', '').'</td>';
			$contenido.='</tr>';
			break;
		case 8:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%">'.$cont.'</td>';
			$contenido.='<td width="8%">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="12%">'.$rst["dblNum"].'</td>';
			$contenido.='<td width="15%" >'.$rst["strCargo"].'</td>';
			$contenido.='<td width="30%">'.trim($rst["strNombre"]).'</td>';
			$contenido.='<td width="30%">'.trim($rst["strDescBodega"]).'</td>';
			$contenido.='</tr>';
			break;
		case 9:
			$contenido.='<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%">'.$cont.'</td>';
			$contenido.='<td width="8%">'.$rst["dtmFecha"].'</td>';
			$contenido.='<td width="12%">'.$rst["dblNum"].'</td>';
			$contenido.='<td width="15%">'.$rst["strCargo"].'</td>';
			$contenido.='<td width="20%">'.trim($rst["strNombre"]).'</td>';
			$contenido.='<td width="20%" >'.trim($rst["strDescBodega"]).'</td>';
			$contenido.='<td width="20%" >'.trim($rst["strNombUsuario"]).'</td>';
			$contenido.='</tr>';
			break;
		case 10:
			$contenido.='<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
			$contenido.='<td width="13%" align="center">'.$rst["dblNumero"].'</td>';
			$contenido.='<td width="75%" align="left">&nbsp;'.$rst["strNombre"].'</td>';
			$contenido.='</tr>';
			break;
		case 11:
			$total += $rst["dblTotal"];
			$contenido.='<tr bgcolor="'.($cont % 2  == 0 ? '#FFFFFF' : '#EBF3FE').'">';
			$contenido.='<td width="3%" align="center">'.$cont.'</td>';
			$contenido.='<td width="10%" align="center">'.$rst["dtmFecha"].'</td>';
			$contenido.='<td width="9%" align="center">'.$rst["dblNum"].'</td>';
			$contenido.='<td width="66%" align="left">&nbsp;'.$rst["strDetalle"].'</td>';
			$contenido.='<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '').'</td>';
			$contenido.='</tr>';
			break;
	}
}
mssql_free_result($stmt);

if($tipodoc == 1 || $tipodoc == 6 || $tipodoc == 7 || $tipodoc == 11){
	if($tipodoc == 1) 
		$col = 9; 
	elseif($tipodoc == 7) 
		$col = 5;
	elseif($tipodoc == 11)
		$col = 4;
	else 
		$col = 6;
	$contenido.='<tr>';
	$contenido.='<td align="right" colspan="'.$col.'"><b>TOTAL</b></td>';
	$contenido.='<td align="right">'.number_format($total, 0, '', '').'</td>';
	$contenido.='</tr>';
}
$contenido.='</table>';
$contenido.='</td>';
$contenido.='</tr>';
$contenido.='</table>';
$contenido.='</body>';
$contenido.='</html>';
$nombarch.=date('d-m-Y').'.xls';

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".$nombarch."");
print($contenido);

mssql_close($cnx);
?>