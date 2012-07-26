<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];

$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OC', $numero", $cnx);
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
	$docpago=$rst["dblDocPago"];
}
mssql_free_result($stmt);

if($factor==''){
	$stmt = mssql_query("SELECT dblFactor FROM Impuesto WHERE id = $docpago", $cnx);
	if($rst=mssql_fetch_array($stmt)) $factor=$rst["dblFactor"];
	mssql_free_result($stmt);
}

$stmt = mssql_query("EXEC Bodega..sp_getContrato '$strCargo'", $cnx);
if($rst=mssql_fetch_array($stmt)) $contrato=$rst["strDetalle"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'OCA', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)) $nombsol=$rst["nombre"]; 
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	self.focus();
	self.print();
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleOrdenCompra 0, $numero", $cnx);
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
								<td valign="top" width="16%" align="center">
									<img border="0" src="../images/logo.jpg" />
									<br />
									[Interno: <?php echo $numero;?>]
								</td>
								<td align="center">
									<h1>
										Orden de Compra
										<?php
											if($estado==5) echo '<br>NULA';?>
									</h1>
									<br />
									<?php
									if($rst["dblNumSol"]!=''){ 
										$stmt2=mssql_query("SELECT dblNum FROM CaratulaSM WHERE dblNumero=".$rst["dblNumSol"], $cnx);
										if($rst2=mssql_fetch_array($stmt2)) echo '[N&deg; Solicitud de Materiales: '.$rst2["dblNum"].']';
										mssql_free_result($stmt2);
									}?>								</td>
								<td align="center" width="18%">
									Constructora<br>
									<b>EDECO S.A.</b><br>
									82.637.800-K<br>
									Carmencita 25 OF.41<br>
									fono:8991000<br>
									Las Condes								</td>
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
							<tr>
								<td align="left">&nbsp;<b>Observacion</b></td>
								<td>:</td>
								<td align="left" colspan="9">&nbsp;<?php echo $nota;?></td>
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
				<?php
				if($tipodoc=='O'){?>
					<td width="10%" align="center"><b>F.Inicio</b></td>
					<td width="10%" align="center"><b>F.T&eacute;rmino</b></td>
				<?php
				}?>
					<td width="10%" align="center"><b>Cantidad</b></td>
					<td width="10%" align="center"><b>Valor</b></td>
					<td width="10%" align="center"><b>Total</b></td>
				</tr>
				<tr>
				  <td height="1px" colspan="9"><hr /></td>
				</tr>
		<?php
			}?>
				<tr>
					<td align="right" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="center"><?php echo $rst["strUnidad"];?></td>
				<?php
				if($tipodoc=='O'){?>
					<td align="center"><?php echo $rst["dtmFchIni"];?></td>
					<td align="center"><?php echo $rst["dtmFchTer"];?></td>
				<?php
				}?>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblValor"],0,',','.');?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblCantidad"]*$rst["dblValor"],0,',','.');?>&nbsp;</td>
				</tr>
			<?php		
				$neto+=$rst["dblCantidad"]*$rst["dblValor"];
			}
			mssql_free_result($stmt);?>
				<tr><td colspan="9" height="1px"><hr /></td></tr>
				<tr>
					<td colspan="9" align="right">
						<table width="100%" border="0" cellpadding="0" cellspacing="1">
							<tr>
								<td width="75%" rowspan="3" align="left" valign="top">&nbsp;<?php echo $nombsol;?></td>
								<td width="14%" align="right"><b><?php echo $docpago == 2 ? 'A PAGAR' : 'NETO';?></b></td>
								<td width="1%">:</td>
								<td width="10%" align="right"><?php echo number_format($neto, 0, '', '.');?></td>
							</tr>
							<tr>
								<td align="right" nowrap="nowrap"><b><?php echo $docpago == 2 ? '(-)Impuesto 10%' : 'I.V.A.';?></b></td>
								<td>:</td>
								<td align="right"><?php echo number_format($neto*$factor, 0, '', '.');?></td>
							</tr>
							<tr>
								<td align="right"><b>TOTAL<?php echo $docpago == 2 ? ' HONORARIOS' : '';?></b></td>
								<td>:</td>
								<td align="right"><?php echo number_format($neto*($factor+1), 0, '', '.');?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="9" height="1px"><hr /></td></tr>
				<tr>
					<td align="center" colspan="9">
					<?php
					$sw=0;
					if($tipodoc=='O') $tipo='pieoco'; else $tipo='pieoca';
					$stmt = mssql_query("SELECT strDetalle FROM General..Tablon WHERE strTabla='$tipo' ORDER BY strCodigo", $cnx);
					while($rst=mssql_fetch_array($stmt)){
						echo '<b>'.$rst["strDetalle"].'</b>';
						if($sw==0){
							echo '<br />';
							$sw=1;
						}
					}
					mssql_free_result($stmt);?>
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