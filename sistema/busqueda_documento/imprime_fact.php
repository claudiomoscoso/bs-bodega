<?php
include '../conexion.inc.php';
include '../globalvar.inc.php';

$directo = $_GET["directo"];
$cargo = $_GET["cargo"];
$numero = $_GET["numero"];
$mes = $_GET["mes"];
$ano = $_GET["ano"];
$bodega = $_GET["bodega"];

$stmt = mssql_query("EXEC Bodega..sp_getFacturas 2, $mes, $ano, $numero, NULL, NULL, NULL, NULL, '$cargo'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$fecha = $rst["dtmFecha"];
	$intoc = $rst["dblNumOC"];
	$numoc = $rst["dblUltima"];
	$tipodoc = $rst["dblTipoDoc"];
	$numdoc = $rst["dblNumDoc"];
	$monto = $rst["dblMonto"];
	$ci = $rst["strCI"];
	$nombprov = $rst["strNombProv"];
	$usuario = $rst["strNombUsu"];
	$descestado = $rst["strDescEstado"];
	$archivo = $rst["strArchivo"];
}
$sql = "Select strPrint, strLogo From General..Contrato Where strCodigo='$bodega'";
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
	if(<?php echo $directo;?>) parent.Deshabilita(false);
}
-->
</script>
<body id="cuerpo" style="background-color:#FFFFFF" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
if($fecha != ''){?>
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
											<td width="10%" align="left">&nbsp;
											<?php 
											if($intoc != ''){?>
												<a href="#" title="Ver orden de compra N&deg; <?php echo $numoc;?>..."
													onclick="javascript: 
														parent.document.getElementById('btnImprimir3').disabled = false;
														AbreDialogo('divDocOrigen', 'frmDocOrigen', 'imprime_oc.php?numero=<?php echo $intoc;?>', true);
													"
													onmouseover="javascript: window.status='Ver orden de compra Nº <?php echo $numoc;?>...'; return true;"
												><?php echo $numoc;?></a>
											<?php
											}else
												echo 'N/A';
											?>
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
									<div style="overflow:auto; width:100%; height:51px; ">
										<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<?php
										if($intoc != ''){
											$col = 0;
											$stmt = mssql_query("EXEC Bodega..sp_getGuiaIngreso 'BDOC', $intoc", $cnx);
											if($rst = mssql_fetch_array($stmt)){
												do{
													$col++;
													if($col == 1){
														echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
													}elseif($col > 10){
														$cont++;
														$col = 1;
														echo '</tr><tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
													}
													echo '<td width="10%" align="left">
															 <a href="#" title="Ver gu&iacute;a de ingreso N&deg; '.$rst["dblNum"].'..."
																onclick="javascript: 
																	parent.Deshabilita(true);
																	parent.document.getElementById(\'btnImprimir3\').disabled = false;
																	AbreDialogo(\'divDocOrigen\',\'frmDocOrigen\',\'imprime_ing.php?numero='.$rst["dblNumero"].'\', true);
																"
																onmouseover="javascript: window.status=\'Ver guía de ingreso Nº '.$rst["dblNum"].'...\'; return true;"
															 >'.$rst["dblNum"].'</a></td>';
												}while($rst = mssql_fetch_array($stmt));
												echo '</tr>';
											}
											mssql_free_result($stmt);
										}?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;T.Documento</b></td>
								<td><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="28%" align="left">&nbsp;<?php echo $tipodoc == 0 ? 'Factura' : 'Boleta';?></td>
											<td width="1%">&nbsp;</td>
											<td width="13%" align="left" ><b>&nbsp;N&deg; Documento</b></td>
											<td width="1%"><b>:</b></td>
											<td width="28%" align="left">&nbsp;
											<?php 
											if(trim($archivo) != ''){?>
												<a href="#" title="Ver documento adjunto..."
													onclick="javascript: 
														parent.document.getElementById('btnImprimir3').disabled = true;
														AbreDialogo('divDocOrigen', 'frmDocOrigen', '<?php echo $dtn_documento.'/'.$archivo;?>', true);
													"
													onmouseover="javascript: window.status='Ver documento adjunto...'; return true;"
												><?php echo $numdoc;?></a>
											<?php
											}else
												echo $numdoc;
											?>
											</td>
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
											<th width="13%" >N&deg; Comprobante</th>
											<th width="10%" align="left">&nbsp;Tipo</th>
											<th width="10%" >Fecha</th>
											<th  align="left" >&nbsp;Glosa</th>
											<th width="10%" align="right">Debe&nbsp;</th>
											<th width="10%" align="right">Haber&nbsp;</th>
											<th width="10%" align="left" >&nbsp;Estado</th>
											<th width="2%">&nbsp;</th>
										</tr>
									</table>
									<div style="overflow:scroll; width:100%; height:75px; ">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<?php
									$stmt = mssql_query("EXEC Bodega..sp_getComprobantes 0, $numdoc, '$ci'", $cnx);
									if($rst=mssql_fetch_array($stmt)){
										do{
											$cont++;
											echo '<tr bgcolor="'.($cont%2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
											echo '<td width="13%" align="center" >'.$rst["CpbNum"].'</td>';
											echo '<td width="10%" align="left" >&nbsp;'.$rst["Tipo"].'</td>';
											echo '<td width="10%" align="center" >'.$rst["dtmFecha"].'</td>';
											echo '<td align="left" >&nbsp;'.$rst["MovGlosa"].'</td>';
											echo '<td width="10%" align="right">'.number_format($rst["MovDebe"], 0, '', '.').'&nbsp;</td>';
											echo '<td width="10%" align="right">'.number_format($rst["MovHaber"], 0, '', '.').'&nbsp;</td>';
											echo '<td width="10%" align="left" >&nbsp;'.$rst["Estado"].'</td>';
											echo '</tr>';
										}while($rst=mssql_fetch_array($stmt));
									}else{
										echo '<tr><td align="center" colspan="7" style="color:#FF0000"><b>No existe se registran movimientos.</b></td></tr>';
									}
									mssql_free_result($stmt);?>
									</table>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<input type="hidden" id="documento" value="<?php echo $numero;?>" />
<?php
}else{?>
	<tr><td align="center" style="color:#FF0000"><b>El documento que busca no existe.</b></td></tr>
<?php
}?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
