<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"];
$numero=$_GET["numero"];

$stmt = mssql_query("EXEC sp_getOrdenCompra 'OC', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$bodega=$rst["strBodega"];
	$numusu=$rst["dblUltima"];
	$forma_pago=$rst["Forma_Pago"];
	$fecha=$rst["dtmFecha"];
	$proveedor=$rst["strNombre"];
	$rut=$rst["strRut"];
	$direccion=$rst["strDireccion"];
	$comuna=$rst["Comuna"];
	$telefono=$rst["strTelefono"];
	$fax=$rst["strFax"];
	$nota=$rst["strObservacion"];
	$strCargo=$rst["strCargo"];
	$cargo=$rst["Cargo"];
	$contacto=$rst["strContacto"];
	$factor=$rst["dblIva"];
	$tipodoc=$rst["strTipoDoc"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC General..sp_getCargos 1, '$bodega'", $cnx);
if($rst=mssql_fetch_array($stmt)) $contrato=$rst["strDetalle"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getDatosUsuario 'OCA', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$firma=$rst["firma"];
	$nombsol=$rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strDireccion FROM General..Contrato WHERE strCodigo='$strCargo'", $cnx);
if($rst=mssql_fetch_array($stmt)) $despachar_en=$rst["strDireccion"];
mssql_free_result($stmt);

$asunto="Orden de Compra Nº $numusu";

$cabecera="MIME-Version: 1.0\r\n";
$cabecera.="Content-type: text/html; charset=iso-8859-1\r\n";
$cabecera.="To: Patricio Aranguiz <paranguiz@tyssa.cl>\r\n";
$cabecera.="From: EDECO S.A. <informatica@atacamasa.cl>\r\n";

$mensaje='<html>';
$mensaje.='<body>';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td align="center" valign="top">';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td align="center" valign="top" colspan="7">';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td valign="top" width="16%"><img border="0" src="../images/logo.jpg" /></td>';
$mensaje.='<td align="center"><h1>Orden de Compra</h1></td>';
$mensaje.='<td align="center" width="18%">Constructora<br><b>EDECO S.A.</b><br>82.637.800-K<br>Carmencita 25 OF.41<br>fono:8991000<br>Las Condes</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td height="5px" colspan="7"></td></tr>';
$mensaje.='<tr>';
$mensaje.='<td valign="top" align="center" colspan="7">';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td width="9%" align="left" nowrap="nowrap"><b>&nbsp;Orden de Compra N&deg;</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td width="20%" align="left">&nbsp;'.$numusu.'</td>';
$mensaje.='<td width="1%">&nbsp;</td>';
$mensaje.='<td width="15%" align="left" nowrap="nowrap"><b>&nbsp;Forma de Pago</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td width="36%" align="left">&nbsp;'.$forma_pago.'</td>';
$mensaje.='<td width="1%">&nbsp;</td>';
$mensaje.='<td width="5%" align="left"><b>&nbsp;Fecha</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td width="20%" align="left">&nbsp;'.$fecha.'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left"><b>&nbsp;Proveedor</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="left" colspan="5">&nbsp;'.$proveedor.'</td>';
$mensaje.='<td width="1%">&nbsp;</td>';
$mensaje.='<td width="5%" align="left">&nbsp;<b>R.U.T.</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td align="left" nowrap="nowrap">&nbsp;'.$rut.'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left"><b>&nbsp;Direcci&oacute;n</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td colspan="9" align="left">&nbsp;'.$direccion.'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left"><b>&nbsp;Comuna</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="left">&nbsp;'.$comuna.'</td>';
$mensaje.='<td width="1%"></td>';
$mensaje.='<td width="5%" align="left"><b>&nbsp;Tel&eacute;fono</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td align="left">&nbsp;'.$telefono.'</td>';
$mensaje.='<td width="1%"></td>';
$mensaje.='<td width="5%" align="left"><b>&nbsp;Fax</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td align="left">&nbsp;'.$fax.'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left"><b>&nbsp;Nota</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="left" colspan="9">&nbsp;'.$nota.'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left">&nbsp;<b>Contrato</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td colspan="9">';
$mensaje.='<table width="100%" border="0" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td width="50%" align="left">&nbsp;'.$contrato.'</td>';
$mensaje.='<td width="1%"></td>';
$mensaje.='<td width="5%" align="left">&nbsp;<b>Cargo</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td width="50%" align="left">&nbsp;'.$cargo.'</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left"><b>&nbsp;Contacto</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="left" colspan="9">&nbsp;'.$contacto.'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="left"><b>Despacha en</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="left" colspan="9">&nbsp;'.$despachar_en.'</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td height="5px"></td></tr>';
$mensaje.='</table>';
		
/* -------------------------------- Detalle -------------------------------- */
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td align="center"><b>N&deg;</b></td>';
$mensaje.='<td width="10%" align="center"><b>C&oacute;digo</b></td>';
$mensaje.='<td width="47%" align="center"><b>Descripci&oacute;n</b></td>';
$mensaje.='<td width="10%" align="center"><b>Unidad</b></td>';
if($tipodoc=='O'){
	$mensaje.='<td width="10%" align="center"><b>F.Inicio</b></td>';
	$mensaje.='<td width="10%" align="center"><b>F.T&eacute;rmino</b></td>';
}
$mensaje.='<td width="10%" align="center"><b>Cantidad</b></td>';
$mensaje.='<td width="10%" align="center"><b>Valor</b></td>';
$mensaje.='<td width="10%" align="center"><b>Total</b></td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td height="1px" colspan="9"><hr /></td>';
$mensaje.='</tr>';
$stmt = mssql_query("EXEC sp_getDetalleOrdenCompra 0, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$mensaje.='<tr>';
	$mensaje.='<td align="right" nowrap="nowrap">'.$cont.'&nbsp;</td>';
	$mensaje.='<td align="center">'.$rst["strCodigo"].'</td>';
	$mensaje.='<td align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	$mensaje.='<td align="center">'.$rst["strUnidad"].'</td>';
	if($tipodoc=='O'){
		$mensaje.='<td align="center">'.$rst["dtmFchIni"].'</td>';
		$mensaje.='<td align="center">'.$rst["dtmFchTer"].'</td>';
	}
	$mensaje.='<td align="right">'.number_format($rst["dblCantidad"],2,',','.').'&nbsp;</td>';
	$mensaje.='<td align="right">'.number_format($rst["dblValor"],0,',','.').'&nbsp;</td>';
	$mensaje.='<td align="right">'.number_format($rst["dblCantidad"]*$rst["dblValor"],0,',','.').'&nbsp;</td>';
	$mensaje.='</tr>';
	$neto+=$rst["dblCantidad"]*$rst["dblValor"];
}
mssql_free_result($stmt);
$mensaje.='<tr><td colspan="9" height="1px"><hr /></td></tr>';
$mensaje.='<tr>';
$mensaje.='<td colspan="9" align="right">';
$mensaje.='<table width="25%" border="0" cellpadding="0" cellspacing="1">';
$mensaje.='<tr>';
$mensaje.='<td width="14%" align="right"><b>NETO</b></td>';
$mensaje.='<td width="1%">:</td>';
$mensaje.='<td width="10%" align="right">'.number_format($neto, 0, '', '.').'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="right" nowrap="nowrap"><b>'.($factor==0.19 ? 'I.V.A.' : '(-)Impuesto 10%').'</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="right">'.number_format($neto*$factor, 0, '', '.').'</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td align="right"><b>TOTAL</b></td>';
$mensaje.='<td>:</td>';
$mensaje.='<td align="right">'.number_format($neto*($factor+1), 0, '', '.').'</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td colspan="9" height="1px"><hr /></td></tr>';
$mensaje.='<tr>';
$mensaje.='<td colspan="9" align="center" valign="top">';
$mensaje.='<table width="60%" height="120px" border="0" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td id="firma_solicitante" width="20%" align="center" valign="bottom">';
$mensaje.='<b>V&deg;B&deg;</b>';
$mensaje.='</td>';
$mensaje.='<td id="firma_gerencia" width="20%" align="center" valign="bottom">';
if($firma!='') $mensaje.='<img border="0" src="../images/'.$firma.'"/><br />';
$mensaje.='<b>'.$nombsol.'</b>';
$mensaje.='</td>';
$mensaje.='<td width="20%" align="center" valign="bottom">';
$mensaje.='<img border="0" src="../images/108981141011159910597.dot"/><br />';
$mensaje.='<b>GERENCIA</b>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td colspan="9">&nbsp;</td></tr>';
$mensaje.='<tr>';
$mensaje.='<td align="center" colspan="9">';
$sw=0;
if($tipodoc=='O') $tipo='pieoco'; else $tipo='pieoca';
$stmt = mssql_query("SELECT strDetalle FROM General..Tablon WHERE strTabla='$tipo' ORDER BY strCodigo", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$mensaje.='<b>'.$rst["strDetalle"].'</b>';
	if($sw==0){
		$mensaje.='<br />';
		$sw=1;
	}
}
mssql_free_result($stmt);
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</body>';
$mensaje.='</html>';

$para='paranguiz@tyssa.cl';

mail($para, $asunto, $mensaje, $cabecera);

mssql_close($cnx);
?>
