<?php
include '../conexion.inc.php';

$directo=$_GET["directo"];
if($directo){
	$bodega=$_GET["bodega"];
	$numero=$_GET["numero"];
	$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'NUM', $numero, '%', '$bodega'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $interno=$rst["dblNumero"];
	mssql_free_result($stmt);
}else {
	$interno=$_GET["numero"];
	$bodega=$_GET["bodega"];
	$directo='false';
}
if($interno!=''){
	$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OC', $interno", $cnx);
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
		$estado=$rst["strDescEstado"];
		$usuario=$rst["strNombUsua"];
		$docpago=$rst["dblDocPago"];
		$documento=$rst["strDocumento"];
		$numdoc=$rst["dblNumDoc"];
		$codestado=$rst["strEstado"];
	}	
	mssql_free_result($stmt);
	
	if($factor==''){
		$stmt = mssql_query("SELECT dblFactor FROM Impuesto WHERE id=1", $cnx);
		if($rst=mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
		mssql_free_result($stmt);
	}

	$stmt = mssql_query("EXEC Bodega..sp_getContrato '$strCargo'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $contrato=$rst["strDetalle"];
	mssql_free_result($stmt);
	
	$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'OCA', $interno", $cnx);
	if($rst=mssql_fetch_array($stmt)) $nombsol=$rst["nombre"]; 
	mssql_free_result($stmt);
}
$stmt = mssql_query("EXEC sp_getDatosUsuario 'OCA', $interno", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$firma=$rst["firma"];
	$nombsol=$rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getFirmaGerencia $interno", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$firmagnte=$rst["firma"];
	$nombgnte=$rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strAutoriza FROM Autorizaciones WHERE dblNumero=$interno AND strAccion=1",$cnx);
if($rst=mssql_fetch_array($stmt)) $usuvb = $rst["strAutoriza"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getDatosUsuario 'GNR', NULL, '$usuvb'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$firmavb = $rst["firma"];
	$nombvb = $rst["nombre"];
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
<title>Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(<?php echo $directo;?>){ 
		parent.Deshabilita(false);
	}
	estado=<?php echo $codestado;?>;
	if(estado==2 || estado==10 || estado==11 || estado==12){
		parent.DeshabilitaImprimirOC(false);
	} else {
		parent.DeshabilitaImprimirOC(true);
		document.getElementById('Firmas').style.display='none';
	}
}
-->
</script>
<script type="text/javascript" language="Javascript">



<!-- Begin



document.oncontextmenu = function(){return false}



// End -->



</script>
<body id="cuerpo" style="background-color:#FFFFFF" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
if($interno!=''){?>
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleOrdenCompra 0,$interno", $cnx);
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
								<td valign="top" width="16%" align="center"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center">
									<h1>Orden de Compra</h1>
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
								<td width="10%" align="left"><b>&nbsp;N&deg; Interno</b></td>
								<td width="1%"><b>:</b></td>
								<td colspan="9" >
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $interno;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Estado</b></td>
											<td width="1%"><b>:</b></td>
											<td width="29%" align="left">&nbsp;<?php echo $estado;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Usuario</b></td>
											<td width="1%"><b>:</b></td>
											<td width="30%" align="left">&nbsp;<?php echo $usuario;?></td>
											<?php
											if($documento!=''){?>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;<?php echo $documento;?></b></td>
											<td width="1%"><b>:</b></td>
											<td width="10%" align="left" style="background-color:#66CCFF">&nbsp;<?php echo $numdoc;?></td>
											<?php
											}?>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td  align="left" ><b>&nbsp;O.Compra</b></td>
								<td ><b>:</b></td>
								<td colspan="9">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $numusu;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" nowrap="nowrap"><b>&nbsp;Forma de Pago</b></td>
											<td width="1%"><b>:</b></td>
											<td width="27%" align="left">&nbsp;<?php echo $forma_pago;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="18%" align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Tipo</b></td>
											<td width="1%"><b>:</b></td>
											<td width="18%" align="left">&nbsp;
												<?php echo $tipodoc=='O' ? 'Operaciones' : 'Adquisiciones';?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr valign="top">
								<td align="left"><b>&nbsp;Proveedor</b></td>
								<td width="1%"><b>:</b></td>
								<td width="52%" align="left">&nbsp;<?php echo $proveedor;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;<b>R.U.T.</b></td>
								<td width="1%"><b>:</b></td>
								<td align="left" colspan="5">&nbsp;<?php echo $rut;?></td>
							</tr>
							<tr valign="top">
								<td align="left"><b>&nbsp;Direcci&oacute;n</b></td>
								<td><b>:</b></td>
								<td align="left" colspan="9">&nbsp;<?php echo $direccion;?></td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Comuna</b></td>
								<td><b>:</b></td>
								<td colspan="9">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="29%" align="left">&nbsp;<?php echo $comuna;?></td>
											<td width="1%"></td>
											<td width="5%" align="left"><b>&nbsp;Tel&eacute;fono</b></td>
											<td width="1%"><b>:</b></td>
											<td width="28%" align="left">&nbsp;<?php echo $telefono;?></td>
											<td width="1%"></td>
											<td width="5%" align="left"><b>&nbsp;Fax</b></td>
											<td width="1%"><b>:</b></td>
											<td width="28%" align="left">&nbsp;<?php echo $fax;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr valign="top">
								<td align="left"><b>&nbsp;Nota</b></td>
								<td><b>:</b></td>
								<td align="left" colspan="9">&nbsp;<?php echo $nota;?></td>
							</tr>
							<tr valign="top">
								<td align="left">&nbsp;<b>Contrato</b></td>
								<td><b>:</b></td>
								<td colspan="9">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr valign="top">
											<td width="50%" align="left">&nbsp;<?php echo $contrato;?></td>
											<td width="1%"></td>
											<td width="5%" align="left">&nbsp;<b>Cargo</b></td>
											<td width="1%"><b>:</b></td>
											<td width="50%" align="left">&nbsp;<?php echo $cargo;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr valign="top">
								<td align="left"><b>&nbsp;Contacto</b></td>
								<td><b>:</b></td>
								<td align="left" colspan="9">&nbsp;<?php echo $contacto;?></td>
							</tr>
							<tr valign="top">
								<td align="left">&nbsp;<b>Observacion</b></td>
								<td><b>:</b></td>
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
				if($tipodoc == 'O' || $tipodoc == 'I'){?>
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
								<td width="50%" rowspan="3" align="left" valign="top">&nbsp;<?php echo $nombsol;?></td>
								<td width="39%" align="right"><b><?php echo $docpago == 2 ? 'A PAGAR' : 'NETO';?></b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%" align="right"><?php echo number_format($neto, 0, '', '.');?></td>
							</tr>
							<tr>
								<td align="right" nowrap="nowrap"><b><?php echo $docpago==2 ?  '(-)Impuesto 10%' : 'I.V.A.';?></b></td>
								<td><b>:</b></td>
								<td align="right"><?php echo number_format($neto * $factor, 0, '', '.');?></td>
							</tr>
							<tr>
								<td align="right"><b>TOTAL<?php echo $docpago == 2 ? ' HONORARIOS' : '';?></b></td>
								<td><b>:</b></td>
								<td align="right"><?php echo number_format($neto*($factor+1), 0, '', '.');?></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td colspan="9" height="1px"><hr /></td></tr>
				
				<tr>
					<td colspan="9" align="center" valign="top">
						<table width="60%" height="120px" border="0" cellpadding="0" cellspacing="0" id='Firmas'>
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
	<input type="hidden" id="documento" value="1" />
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
