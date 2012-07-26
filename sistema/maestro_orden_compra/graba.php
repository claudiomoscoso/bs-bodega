<?php
include '../conexion.inc.php';
$usuario=$_POST["usuario"]!='' ? $_POST["usuario"] : $_GET["usuario"];
$numero=$_POST["numero"]!='' ? $_POST["numero"] : $_GET["numero"];
$nivel=$_POST["nivel"]!='' ? $_POST["nivel"] : $_GET["nivel"];

$forma_pago=$_POST["forma_pago"]!='' ? $_POST["forma_pago"] : $_GET["forma_pago"];
$proveedor=$_POST["strCodigo"]!='' ? $_POST["strCodigo"] : $_GET["proveedor"];

$solicitante=$_POST["solicitante"]!='' ? $_POST["solicitante"] : $_GET["solicitante"];
$estado=$_POST["estado"]!='' ? $_POST["estado"] : $_GET["estado"];
$tdoc=$_POST["tdoc"]!='' ? $_POST["tdoc"] : $_GET["tdoc"];
$obs=$_POST["obs"]!='' ? $_POST["obs"] : $_GET["obs"];
$email=$_POST["email"] != '' ? $_POST["email"] : $_GET["email"];
$despachar_en=$_POST["despachar_en"] != '' ? $_POST["despachar_en"] : $_GET["despachar_en"];
$despachar_en=$despachar_en!='' ? 'Despachar en '.$despachar_en : '';

if($email!='') mssql_query("UPDATE proveedor SET strCorreo='$email' WHERE strCodigo='$proveedor'", $cnx);

mssql_query("EXEC sp_EditaOrdenCompra '$usuario', $numero, $forma_pago, '$proveedor', '$obs $despachar_en'", $cnx);

if($estado==4){
	if($tdoc=='O')
		$nvo_estado=($nivel==1 ? 15 : 0);
	elseif($tdoc=='A')
		$nvo_estado=1;
	mssql_query("EXEC sp_CambiaEstado 'OCV', $numero, $nvo_estado, '$usuario'", $cnx);
	if($obs!='') mssql_query("EXEC sp_GrabaObservacion $numero,'OC','".Reemplaza($obs)."','$usuario'", $cnx);
}

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
	$cargo=$rst["Cargo"];
	$contacto=$rst["strContacto"];
	$factor=$rst["dblIva"];
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
<title>Listado Maestro de Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function AlCargar(){
	alert('Se ha guardado la Orden de Compra número' + String.fromCharCode(13) + '<?php echo $numusu;?>')
	parent.parent.Deshabilitar(false);
	parent.parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
	parent.location.href = parent.location.href;
}
-->
</script>
<body id="cuerpo" onLoad="javascript: AlCargar();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC sp_getDetalleOrdenCompra 0, $numero", $cnx);
		while($rst=mssql_fetch_array($stmt)){
			$fil++;
			$cont++;
			if($fil==39){
				$fil=1;
				if($sw==0){ 
					echo '</table><table borde="0"><tr><td><H3></H3></td></tr></table>';
					$sw=1;
				}
				$sw=0;?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" valign="top" colspan="7">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Orden de Compra</h1></td>
								<td align="center" width="18%" nowrap><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="7"></td></tr>
				<tr>
					<td valign="top" align="center" colspan="7">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%" align="left" nowrap="nowrap"><b>&nbsp;Orden de Compra N&deg;</b></td>
								<td width="1%">:</td>
								<td width="20%" align="left">&nbsp;<?php echo $numusu;?></td>
								<td width="1%">&nbsp;</td>
								<td width="15%" align="left" nowrap="nowrap"><b>&nbsp;Forma de Pago</b></td>
								<td width="1%">:</td>
								<td width="36%" align="left">&nbsp;<?php echo $forma_pago;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
								<td width="1%">:</td>
								<td width="20%" align="left">&nbsp;<?php echo $fecha;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Proveedor</b></td>
								<td>:</td>
								<td align="left" colspan="5">&nbsp;<?php echo $proveedor;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;<b>R.U.T.</b></td>
								<td width="1%">:</td>
								<td align="left" nowrap="nowrap">&nbsp;<?php echo $rut;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Direcci&oacute;n</b></td>
								<td>:</td>
								<td colspan="9" align="left">&nbsp;<?php echo $direccion;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Comuna</b></td>
								<td>:</td>
								<td align="left">&nbsp;<?php echo $comuna;?></td>
								<td width="1%"></td>
								<td width="5%" align="left"><b>&nbsp;Tel&eacute;fono</b></td>
								<td width="1%">:</td>
								<td align="left">&nbsp;<?php echo $telefono;?></td>
								<td width="1%"></td>
								<td width="5%" align="left"><b>&nbsp;Fax</b></td>
								<td width="1%">:</td>
								<td align="left">&nbsp;<?php echo $fax;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Nota</b></td>
								<td>:</td>
								<td align="left" colspan="9">&nbsp;<?php echo $nota;?></td>
							</tr>
							<tr>
								<td align="left">&nbsp;<b>Contrato</b></td>
								<td>:</td>
								<td colspan="9">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="50%" align="left">&nbsp;<?php echo $contrato;?></td>
											<td width="1%"></td>
											<td width="5%" align="left">&nbsp;<b>Cargo</b></td>
											<td width="1%">:</td>
											<td width="50%" align="left">&nbsp;<?php echo $cargo;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Contacto</b></td>
								<td>:</td>
								<td align="left" colspan="9">&nbsp;<?php echo $contacto;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px"></td></tr>
			</table>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="47%" align="center"><b>Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="center"><b>Cantidad</b></td>
					<td width="10%" align="center"><b>Valor</b></td>
					<td width="10%" align="center"><b>Total</b></td>
				</tr>
				<tr><td height="1px" colspan="7"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="right" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="center"><?php echo $rst["strUnidad"];?></td>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblValor"],0,',','.');?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblCantidad"]*$rst["dblValor"],0,',','.');?>&nbsp;</td>
				</tr>
			<?php		
				$neto+=$rst["dblCantidad"]*$rst["dblValor"];
			}
			mssql_free_result($stmt);?>
				<tr><td colspan="7" height="1px"><hr /></td></tr>
				<tr>
					<td colspan="7" align="right">
						<table width="25%" border="0" cellpadding="0" cellspacing="1">
							<tr>
								<td width="14%" align="right"><b>NETO</b></td>
								<td width="1%">:</td>
								<td width="10%" align="right"><?php echo number_format($neto, 0, '', '.');?></td>
							</tr>
							<tr>
								<td align="right" nowrap="nowrap"><b><?php echo $tipodoc==1 ? 'I.V.A.' : '(-)Impuesto 10%';?></b></td>
								<td>:</td>
								<td align="right"><?php echo number_format($neto*$factor, 0, '', '.');?></td>
							</tr>
							<tr>
								<td align="right"><b>TOTAL</b></td>
								<td>:</td>
								<td align="right"><?php echo number_format($neto*($factor+1), 0, '', '.');?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="7" height="1px"><hr /></td></tr>
				<tr>
					<td colspan="7" align="center" valign="top">
						<table width="60%" height="60px" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="20%" align="center" valign="bottom"><b>V&deg;B&deg;</b></td>
								<td width="20%" align="center" valign="bottom">
									<b><?php echo $nombsol;?></b>
								</td>
								<td width="20%" align="center" valign="bottom">
									
									<b>GERENCIA</b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="7">&nbsp;</td></tr>
				<tr>
					<td align="center" colspan="7">
						<b>Adjuntar a la Factura, Orden de Compra con VºBº  Gerencia y Adquisiciones mas las guías de despacho.</b>
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
