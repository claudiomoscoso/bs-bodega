<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$nenvio = $_GET["nenvio"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Envio Orden de Trabajo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body style="background-color:#FFFFFF" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img border="0" align="absmiddle" src="../images/logo.jpg" /></td>
					<td width="18%">
						<b>
						N&deg; Envio: <?php echo $nenvio;?><br />
						Fecha: <?php echo date('d/m/Y H:i');?>
						</b>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
	<tr><td style="height:40px"></td></tr>
	<tr>
		<td align="left">
			<b>
			<!--Sr. Alejandro Salas Olave.<br />-->
			Sr. Alejandro P&eacute;rez G&oacute;mez.<br />
			Administrador Contrato de Mantenci&oacute;n Zona II.<br />
			ESVAL S.A.<br />
			<u>Presente</u><br />
			
			</b>
			</td>
	</tr>
	<tr><td style="height:15px">&nbsp;</td></tr>
	<tr><td>Ref.: Ingreso de Ordenes de pago para revisi&oacute;n.</td></tr>
	<tr><td style="height:15px">&nbsp;</td></tr>
	<tr>
		<td>
		De nuestra consideraci&oacute;n:<br />
		<br />
		Adjunto a la presente, s&iacute;rvase encontrar <?php echo '[env&iacute;o/reenv&iacute;o]';?> las siguientes Ordenes de Pago correspondientes al Estado de Pago N&deg; <?php echo $epago;?>, para el proceso de revisi&oacute;n por parte de vuestros inspectores.
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">Orden</th>
					<th width="7%">Fax</th>
					<th width="10%">Fecha/Fax</th>
					<th width="20%" align="left">&nbsp;Direcci&oacute;n</th>
					<th width="20%" align="left">&nbsp;Comuna</th>
					<th width="20%" align="left">&nbsp;Inspector</th>
					<th width="10%" align="right">Total&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">$cont</th>
					<th width="10%">$rst["strOrden"]</th>
					<th width="7%">$rst["strODS"]</th>
					<th width="10%">formato_fecha($rst["dtmOrden"], true, false)</th>
					<th width="20%" align="left">&nbsp;$rst["strDireccion"]</th>
					<th width="20%" align="left">&nbsp;$rst["strComuna"]</th>
					<th width="20%" align="left">&nbsp;$rst["strInspector"]</th>
					<th width="10%" align="right">number_format($rst["dblTotal"], 0, '', '.')&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="center">
			<table border="1" width="80%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
					</td>
					<td>
					</td>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
