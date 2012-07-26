<?php
include '../conexion.inc.php';

$usuario=$_GET["usuario"];
$modulo=$_GET["modulo"];
$numSM=$_GET["numSM"];
$numusuSM=$_GET["numusuSM"];
$cargo=$_GET["cargo"];
$codigo=$_GET["codigo"];
$nombprov=$_GET["proveedor"];
$contacto=$_GET["contacto"];
$email_para=$_GET["email"];

mssql_query("EXEC sp_CambiaEstado 'CSM', $numSM, 3, '$usuario'", $cnx);

mssql_query("UPDATE proveedor SET strContacto='$contacto', strCorreo='$email_para' WHERE strCodigo='$codigo'", $cnx);

$stmt = mssql_query("EXEC sp_getDatosUsuario 'GNR', NULL, '$usuario'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$nombusu=$rst["nombre"];
	$email_de=$rst["email"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC General..sp_getCargos 0, NULL, '$cargo'", $cnx);
if($rst=mssql_fetch_array($stmt)) $desc_cargo=$rst["strCargo"];
mssql_free_result($stmt);

$cabecera ="MIME-Version: 1.0\r\n";
$cabecera.="Content-type: text/html; charset=iso-8859-1\r\n";
$cabecera.="To: $contacto <$email_para>\r\n";
$cabecera.="From: $nombusu <$email_de>\r\n";
$cabecera.="Cc: $email_de\r\n";

$mensaje ='<style>';
$mensaje.='body{';
$mensaje.='background:#FFFFFF;';
$mensaje.='font-family:Tahoma, Arial, "Times New Roman", "Arial Black";';
$mensaje.='}';
$mensaje.='.texto_normal{';
$mensaje.='font-family:Tahoma, Arial, "Times New Roman", "Arial Black";';
$mensaje.='font-size:11px;';
$mensaje.='}';
$mensaje.='.texto_negrita{';
$mensaje.='font-family:Tahoma, Arial, "Times New Roman", "Arial Black";';
$mensaje.='font-size:11px;';
$mensaje.='font-weight:bolder;';
$mensaje.='}';
$mensaje.='</style>';
$mensaje.='<html>';
$mensaje.='<body bgcolor="#FFFFFF">';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td>';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr>';
$mensaje.='<td width="75%" align="left" valign="top"><img border="0" src="../images/logo.jpg"></td>';
$mensaje.='<td width="25%" align="center" class="texto_normal">';
$mensaje.='Constructora<br />';
$mensaje.='<b>EDECO S.A.</b><br />';
$mensaje.='82.637.800-K<br />';
$mensaje.='Carmencita 25 Of.41<br />';
$mensaje.='Tel&eacute;fono: 8991000<br />';
$mensaje.='Las Condes';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td class="texto_normal">';
$mensaje.="<b>Sr(a). $contacto<br /><u>$nombprov</u>/</b>";
$mensaje.='<br /><br />';
$mensaje.='Solicito hacer llegar la cotizaci&oacute;n de los siguientes productos:';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td>&nbsp;</td></tr>';
$mensaje.='<tr>';
$mensaje.='<td align="center">';
$mensaje.='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$mensaje.='<tr class="texto_negrita">';
$mensaje.='<td width="5%" align="center">N&deg;</td>';
$mensaje.='<td width="75%" align="left">&nbsp;Producto</td>';
$mensaje.='<td width="10%" align="center">Unidad</td>';
$mensaje.='<td width="10%" align="right">Cantidad&nbsp;</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td colspan="4"><hr /></td></tr>';
//$stmt = mssql_query("EXEC sp_getDetalleTMP '$usuario', 'OCA'", $cnx);
$stmt = mssql_query("EXEC sp_getTMPDetalleOrdenCompra 0, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;
	$mensaje.='<tr class="texto_normal" bgcolor="'.($ln%2==0 ? '#FFFFFF' : '#EBF3FE').'">';
	$mensaje.='<td align="center">'.$ln.'</td>';
	$mensaje.='<td align="left">&nbsp;'.htmlentities($rst["strDescripcion"]).'</td>';
	$mensaje.='<td align="center">'.$rst["strUnidad"].'</td>';
	$mensaje.='<td align="right">'.number_format($rst["dblCAutorizada"], 2, ',', '.').'&nbsp;</td>';
	$mensaje.='</tr>';
}
mssql_free_result ($stmt);
$mensaje.='<tr><td colspan="4"><hr /></td></tr>';
$mensaje.='<tr><td colspan="4" align="left" class="texto_negrita">['.$desc_cargo.'/'.$numusuSM.']</td></tr>';
$mensaje.='</table>';
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr><td>&nbsp;</td></tr>';
$mensaje.='<tr>';
$mensaje.='<td width="100%" class="texto_normal">';
$mensaje.='Saluda atte.<br />';
$mensaje.=$nombusu;
$mensaje.='</td>';
$mensaje.='</tr>';
$mensaje.='<tr>';
$mensaje.='<td width="100%" align="right" class="texto_negrita">Santiago, '.date('d')." de ".mes(date('m'))." de ".date('Y').'&nbsp;</td>';
$mensaje.='</tr>';
$mensaje.='</table>';
$mensaje.='</body>';
$mensaje.='</html>';

mail($email_para.', '.$email_de, "Cotización $nombusu [Ref: $desc_cargo / $numusuSM]", $mensaje, $cabecera);

mssql_close($cnx);
?>
