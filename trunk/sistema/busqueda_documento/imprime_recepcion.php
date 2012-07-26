<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
$bodega = $_GET["bodega"];
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 2, 0, 0, $numero, NULL, 0, 0", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$fecha=$rst["dtmFecha"];
	$intoc=$rst["dblNumOC"];
	$numoc=$rst["dblUltima"];
	$tipodoc=$rst["dblTipoDoc"];
	$numdoc=$rst["dblNumDoc"];
	$monto=$rst["dblMonto"];
	$ci=$rst["strCI"];
	$nombprov=$rst["strNombProv"];
	$usuario=$rst["strNombUsu"];
	$descestado=$rst["strDescEstado"];
}
mssql_free_result($stmt);
$sql = "Select strPrint, strLogo From General..Contrato Where strBodega='$bodega'";
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	$encabezado = $rst["strPrint"];
	$logo = $rst["strLogo"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recepci&oacute;n de Facturas</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	self.focus();
	self.print();
}
-->
</script>
<body id="cuerpo" style="background-color:#FFFFFF" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" valign="top" colspan="4">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%" align="center"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Recepci&oacute;n de Facturas</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="4"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="13%" align="left" nowrap="nowrap"><b>&nbsp;Recepci&oacute;n N&deg;</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $numero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left" ><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left" ><b>&nbsp;Usuario</b></td>
											<td width="1%"><b>:</b></td>
											<td width="27%" align="left">&nbsp;<?php echo $usuario;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left" ><b>&nbsp;Estado</b></td>
											<td width="1%"><b>:</b></td>
											<td width="27%" align="left">&nbsp;<?php echo $descestado;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>O.Compra N&deg;</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $intoc != '' ? $numoc : 'N/A';?>
											</td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" ><b>&nbsp;Proveedor</b></td>
											<td width="1%"><b>:</b></td>
											<td width="78%" align="left">&nbsp;<?php echo $nombprov;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" valign="top"><b>&nbsp;Ing.Asociados</b></td>
								<td valign="top"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<?php
									$col=0;
									$stmt = mssql_query("EXEC Bodega..sp_getGuiaIngreso 'BDOC', $intoc", $cnx);
									if($rst=mssql_fetch_array($stmt)){
										do{
											$col++;
											if($col==1){
												echo '<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
											}elseif($col>10){
												$cont++;
												$col=1;
												echo '</tr><tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
											}
											echo '<td width="10%" align="left">'.$rst["dblNum"].'</td>';
										}while($rst=mssql_fetch_array($stmt));
										echo '</tr>';
									}
									mssql_free_result($stmt);?>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;T.Documento</b></td>
								<td><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="28%" align="left">&nbsp;<?php echo $tipodoc==0 ? 'Factura' : 'Boleta';?></td>
											<td width="1%">&nbsp;</td>
											<td width="13%" align="left" nowrap="nowrap"><b>&nbsp;N&deg; Documento</b></td>
											<td width="1%"><b>:</b></td>
											<td width="28%" align="left">&nbsp;<?php echo $numdoc;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left" ><b>&nbsp;Monto</b></td>
											<td width="1%"><b>:</b></td>
											<td width="29%" align="left">&nbsp;<?php echo number_format($monto, 0, '', '.');?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="3">&nbsp;</td></tr>
							<tr>
								<td ><b>&nbsp;Datos Softland</b></td>
								<td colspan="2"><hr /></td>
							</tr>
							<tr><td colspan="3">&nbsp;</td></tr>
							<tr>
								<td colspan="3">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="15%" align="center" ><b>N&deg; Comprobante</b></td>
											<td width="10%" align="left"><b>&nbsp;Tipo</b></td>
											<td width="10%" align="center" ><b>Fecha</b></td>
											<td align="left" ><b>&nbsp;Glosa</b></td>
											<td width="10%" align="right"><b>Debe&nbsp;</b></td>
											<td width="10%" align="right"><b>Haber&nbsp;</b></td>
											<td width="10%" align="left" ><b>&nbsp;Estado</b></td>
										</tr>
										<tr><td colspan="7"><hr /></td></tr>
									<?php
									$stmt = mssql_query("EXEC Bodega..sp_getComprobantes 0, $numdoc, '$ci'", $cnx);
									if($rst=mssql_fetch_array($stmt)){
										do{
											$cont++;
											echo '<tr >';
											echo '<td align="center" >'.$rst["CpbNum"].'</td>';
											echo '<td align="left" >&nbsp;'.$rst["Tipo"].'</td>';
											echo '<td align="center" >'.$rst["dtmFecha"].'</td>';
											echo '<td align="left" >&nbsp;'.$rst["MovGlosa"].'</td>';
											echo '<td align="right">'.number_format($rst["MovDebe"], 0, '', '.').'&nbsp;</td>';
											echo '<td align="right">'.number_format($rst["MovHaber"], 0, '', '.').'&nbsp;</td>';
											echo '<td align="left" >&nbsp;'.$rst["Estado"].'</td>';
											echo '</tr>';
										}while($rst=mssql_fetch_array($stmt));
									}else{
										echo '<tr><td align="center" colspan="7" ><b>No existe se registran movimientos.</b></td></tr>';
									}
									mssql_free_result($stmt);?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
