<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"];
$bodega=$_GET["bodega"];
$perfil=$_GET["perfil"];

$numero=$_GET["numero"];
$estado=$_GET["estado"];
$obs=$_GET["obs"];
$despachar=$_GET["despachar"];


$stmt = mssql_query("EXEC sp_getOrdenCompra 'OC', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
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
	$cenco=$rst["dblCalificacion"];
}
mssql_free_result($stmt);

if($estado!=4 && $estado!=11 && $estado!=12){
	if($tipodoc!="I"){
		if($cenco==0){
			mssql_query("EXEC sp_CambiaEstado 'OCV', $numero, 10, '$usuario'", $cnx);
		} else {
			mssql_query("EXEC sp_CambiaEstado 'OCV', $numero, 12, '$usuario'", $cnx);
		}
	} else {
		mssql_query("EXEC sp_CambiaEstado 'OCV', $numero, 12, '$usuario'", $cnx);
	}	
	if($obs!='') mssql_query("EXEC sp_GrabaObservacion $numero,'OC','".Reemplaza($obs)."','$usuario'", $cnx);
}

if($estado==10 && $cenco==1){
	mssql_query("EXEC sp_CambiaEstado 'OCV', $numero, 12, '$usuario'", $cnx);
}

if($factor == ''){
	$stmt = mssql_query("SELECT dblFactor FROM Impuesto WHERE id = $docpago", $cnx);
	if($rst=mssql_fetch_array($stmt)) $factor=$rst["dblFactor"];
	mssql_free_result($stmt);
}

$stmt = mssql_query("EXEC sp_getContrato '$strCargo'", $cnx);
if($rst=mssql_fetch_array($stmt)) $contrato=$rst["strDetalle"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getDatosUsuario 'OCA', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$firma=$rst["firma"];
	$nombsol=$rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getFirmaGerencia $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$firmagnte=$rst["firma"];
	$nombgnte=$rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strAutoriza FROM Autorizaciones WHERE dblNumero=$numero AND strAccion=1",$cnx);
if($rst=mssql_fetch_array($stmt)) $usuvb = $rst["strAutoriza"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getDatosUsuario 'GNR', NULL, '$usuvb'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$firmavb = $rst["firma"];
	$nombvb = $rst["nombre"];
}
mssql_free_result($stmt);

$despachar_en='';
if($despachar==1){
	$stmt = mssql_query("SELECT strDireccion FROM General..Contrato WHERE strCodigo='$strCargo'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $despachar_en=$rst["strDireccion"];
	mssql_free_result($stmt);
}
$sql = "Select strPrint, strLogo From General..Contrato Where strCodigo='$strCargo'";
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
<title>Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	self.focus();
	self.print();
	parent.parent.Deshabilitar(false);
	var cargo=parent.parent.document.getElementById('cargo').value,
	estado=parent.parent.document.getElementById('estado').value,
	mes=parent.parent.document.getElementById('mes').value,
	ano=parent.parent.document.getElementById('ano').value,
	periodo=parent.parent.document.getElementById('periodo').value;
	
	//parent.parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');

	parent.parent.document.getElementById('detalle').src='resultado.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&cargo='+
	cargo+'&estado='+estado+'&mes='+mes+'&ano='+ano+'&periodo='+periodo;
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();">
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
								<td valign="top" width="16%" align="center">
									<img border="0" src="../images/<?php echo $logo;?>" />
									<br />
									[Interno: <?php echo $numero;?>]
								</td>
								<td align="center">
									<h1>
									<?php
									if($tipodoc == 'I')
										echo 'Orden Interna';
									else
										echo 'Orden de Compra';
										
									if($estado==5) echo '<br>NULA';?>
									</h1>
									<br />
									<?php
									if($rst["dblNumSol"]!=''){ 
										$stmt2=mssql_query("SELECT dblNum FROM CaratulaSM WHERE dblNumero=".$rst["dblNumSol"], $cnx);
										if($rst2=mssql_fetch_array($stmt2)) echo '[N&deg; Solicitud de Materiales: '.$rst2["dblNum"].']';
										mssql_free_result($stmt2);
									}?>
								</td>
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
								<td align="left" valign="top"><b>&nbsp;Nota</b></td>
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
							<?php
							if($despachar_en!=''){?>
							<tr>
								<td align="left">&nbsp;<b>Observacion</b></td>
								<td>:</td>
								<td align="left" colspan="9">&nbsp;<?php echo $nota;?></td>
							</tr>
							<?php
							}?>
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
				if($tipodoc=='O' || $tipodoc == 'I'){?>
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
				if($tipodoc=='O' || $tipodoc == 'I'){?>
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
								<td  align="right"><b><?php echo $docpago == 2 ? 'A PAGAR' : 'NETO';?></b></td>
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
				<?php
				if($estado==2||$estado==10||$estado==11||$estado==12){?>
				<tr>
					<td colspan="9" align="center" valign="top">
						<table width="60%" height="120px" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td id="firma_solicitante" width="20%" align="center" valign="bottom">
								<?php
								if($tipodoc == 'O' || $tipodoc == 'I'){
									if($firmavb != '') echo '<img border="0" src="../images/'.$firmavb.'"/><br />';
								}
								echo $tipodoc != 'I' ? '<b>V&deg;B&deg;</b>' : '<b>Autoriza</b>';
								?>
								</td>
								<td id="firma_gerencia" width="20%" align="center" valign="bottom">
								<?php
								if($firma != '') echo '<img border="0" src="../images/'.$firma.'"/><br />';
								echo "<b>$nombsol</b>";?>
								</td>
								<td width="20%" align="center" valign="bottom">
								<?php
								if($tipodoc != 'I'){
									if($firmagnte != '')
										echo '<img border="0" src="../images/'.$firmagnte.'"/><br />';
									else
										echo 'No existe registro de firma digital';
									echo "<b>$nombgnte</b>";
								}
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				}?>
				<tr><td colspan="9">&nbsp;</td></tr>
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
