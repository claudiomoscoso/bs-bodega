<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_POST["usuario"];
$contrato = $_POST["contrato"] != '' ? $_POST["contrato"] : $_GET["contrato"];
$correlativo = $_GET["envio"];
$epago = $_POST["txtEPago"];
$zona = $_POST["cmbZona"];

if($modulo == 0){
	$stmt = mssql_query("EXEC Orden..sp_setEnviaOrdenTrabajo 0, '$usuario', '$contrato', $epago", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$nvo = 1;
		$error = $rst["dblError"];
		$correlativo = $rst["dblCorrelativo"];
	}
	mssql_free_result($stmt);
}elseif($modulo == 1){
	$stmt = mssql_query("EXEC Orden..sp_setEnviaOrdenTrabajo 2, '$usuario', '$contrato', $epago", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$nvo = 0;
		$error = $rst["dblError"];
		$correlativo = $rst["dblCorrelativo"];
	}
	mssql_free_result($stmt);
}elseif($modulo == 2){
	//$stmt = mssql_query("SELECT DISTINCT dblEP FROM Orden..CaratulaOrden WHERE strContrato = '$contrato' AND dblCertificado = $correlativo", $cnx);
	$stmt = mssql_query("SELECT DISTINCT C.dblEP, Z.strZona-2019 as strZona FROM Orden..CaratulaOrden as C LEFT JOIN General..Tablon as Z on C.strComuna=Z.strCodigo and Z.strContrato=C.strContrato WHERE C.strContrato = '$contrato' AND dblCertificado= $correlativo", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$epago = $rst["dblEP"];
		$zona = $rst["strZona"];
	}
	mssql_free_result($stmt);
}

$stmt1 = mssql_query("SELECT CONVERT(VARCHAR, dtmFecha, 120) AS dtmFecha FROM Orden..Envio WHERE dblNumero = $correlativo and strContrato = $contrato", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $fecha = formato_fecha($rst1["dtmFecha"], true, false);
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'CABENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $cab = str_replace('$fecha', $fecha, str_replace('$correlativo', $correlativo, $rst1["strHtml"]));
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'DIRENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $dir = $rst1["strHtml"];
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'REFENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $ref = $rst1["strHtml"];
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'LEYENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $ley = str_replace('$zona', $zona, str_replace('$ano', date('Y'), str_replace('$mes' , mes(date('m')), str_replace('$tipo', ($nvo == 1 ? 'env&iacute;o' : 'reenv&iacute;o'), str_replace('$epago', $epago, trim($rst1["strHtml"]))))));
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'FC1ENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $fcarta1 = trim($rst1["strHtml"]);
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'FC2ENV'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $fcarta2 = trim($rst1["strHtml"]);
mssql_free_result($stmt1);

$stmt1 = mssql_query("SELECT strHtml FROM Orden..FormatoCartas WHERE strContrato = '$contrato' AND strCodigo = 'CABORD'", $cnx);
if($rst1 = mssql_fetch_array($stmt1)) $cabord = trim($rst1["strHtml"]);
mssql_free_result($stmt1);
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
	if(parseInt('<?php echo $modulo;?>') != 2){
		if(parseInt('<?php echo $error;?>') == 0){
			if(confirm('El envio de orden de trabajo se ha guardado con el número <?php echo $correlativo;?>. ¿Desea imprimirlo?')){
				self.focus();
				self.print();
			}
			parent.parent.CierraDialogo('divEnvio', 'frmEnvio');
			parent.parent.Deshabilita(false);
		}else if(parseInt('<?php echo $error;?>') == 1){
			alert('El número de estado de pago ya existe.');
		}else if(parseInt('<?php echo $error;?>') == 2){
			alert('No ha sido posible obtener el número correlativo.');
		}
		parent.Bloquea(false);
	}else{
		self.focus();
		self.print();
	}
}
-->
</script>
<body topmargin="0px" style="background-color:#FFFFFF" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td >
<?php
$stmt = mssql_query("EXEC Orden..sp_getEnvioOrdenTrabajo 0, '$contrato', $correlativo", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	$ln++;
	if($cont == 1 || $cont > 25){
		$cont = 1;?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td><img border="0" align="absmiddle" src="../images/logo.jpg" /></td>
								<td width="21%" valign="top"><?php echo $cab;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td style="height:40px">&nbsp;</td></tr>
				<?php if($contrato == "13001") $dir = str_replace("Tongoy - Guanaqueros", $rst["strComuna"], str_replace("Carlos Gallardo", $rst["strInspector"], $dir));?>
				<tr><td><?php echo $dir;?></td></tr>
				<?php
				if($ref != ''){
					echo '<tr><td style="height:20px">&nbsp;</td></tr>';
					echo '<tr><td>'.$ref.'</td></tr>';
				}
				?>
				<tr><td style="height:20px">&nbsp;</td></tr>
				<tr><td><?php echo $ley;?></td></tr>
				<tr><td><hr /></td></tr>
			</table>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="8%" align="center"><b>Orden</b></td>
					<td width="10%" align="center"><b>Fecha</b></td>
					<?php if($contrato == "13001") echo '<td width="8%" align="center"><b>N.Cliente</b></td>';?>
					<td width="40%" align="left"><b>&nbsp;Direcci&oacute;n</b></td>
					<td width="13%" align="left"><b>&nbsp;Comuna</b></td>
					<td width="13%" align="left"><b>&nbsp;Inspector</b></td>
					<td width="9%" align="right"><b>Total&nbsp;</b></td>
				</tr>
			</table>
	<?php
	}?>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="3%" align="center" style="font-size:10px"><?php echo $ln;?></td>
					<td width="8%" align="center" style="font-size:10px"><?php echo substr($rst["strOrden"], 2);?></td>
					<td width="10%" align="left" style="font-size:10px"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo formato_fecha($rst["dtmOrden"]);?>" /></td>
					<?php if($contrato == "13001") echo '<td width="8%" align="center" style="font-size:10px">'.$rst["strNumCliente"].'</td>';?>
					<td width="40%" align="left" style="font-size:10px"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo strtolower($rst["strDireccion"]);?>" /></td>
					<td width="13%" align="left" style="font-size:10px">&nbsp;<?php echo $rst["strComuna"];?></td>
					<td width="13%" align="left" style="font-size:10px"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strInspector"];?>" /></td>
					<td width="9%" align="right" style="font-size:10px"><?php echo number_format($rst["dblTotal"], 0, '', '.');?>&nbsp;</td>
				</tr>
			</table></TD>
<?php
	$total += $rst["dblTotal"];
	if($cont == 25) echo '<H3>&nbsp;</H3>';
}
mssql_free_result($stmt);?>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="0%" align="right">TOTAL</td>
					<td width="1%" align="center">:</td>
					<td width="10%" align="right"><?php echo number_format($total, 0, '', '.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="center">
			<table border="0" width="50%" cellpadding="0" cellspacing="0">
				<tr style="height:60px">
					<td width="50%" style="<?php echo $fcarta1 != '' ? 'border-bottom:solid 1px' : '';?>">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="50%" style="border-bottom:solid 1px">&nbsp;</td>
				</tr>
				<tr align="center">
					<td width="50%"><b><?php echo $fcarta1;?></b></td>
					<td width="2%">&nbsp;</td>
					<td width="50%"><b><?php echo $fcarta2;?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<H3>&nbsp;</H3>
<?php
$stmt = mssql_query("EXEC Orden..sp_getEnvioOrdenTrabajo 1, '$contrato', $correlativo", $cnx);
while($rst = mssql_fetch_array($stmt)){?>
<?php if($contrato == "13000" || $contrato == "13054" ){ ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr><td><img border="0" align="absmiddle" src="../images/logo.jpg" /><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTRATO MANTENIMIENTO ESVAL ZONA: <?php echo $rst["strZona"];?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center"><b><?php echo $cabord;?></b></td>
				</tr>
			</table>
		</td>
	</tr>
<div style=position:relative;top:130px;left:520px;width:120px>
	<center><h1><b><?php echo substr($rst["strOrden"], 2);?></b></h1><center>
</div>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr><td colspan="6"><b>&nbsp;Datos de la Orden</b></td></tr>
				<tr>
					<td width="12%"><b>&nbsp;N&deg; Orden</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="37%">&nbsp;<?php echo substr($rst["strOrden"], 2);?></td>
					<td width="10%"><b>&nbsp;Movil</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="39%">&nbsp;<?php echo $rst["strMovil"];?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Fax</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $rst["strODS"];?></td>
					<td ><b>&nbsp;Prioridad</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $rst["strPrioridad"];?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Direcci&oacute;n</b></td>
					<td align="center"><b>:</b></td>
					<td ><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strDireccion"];?>" /></td>
					<td ><b>&nbsp;F. Emisi&oacute;n</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo formato_fecha($rst["dtmOrden"], true, false);?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Entre Calles</b></td>
					<td align="center"><b>:</b></td>
					<td ><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strEntreCalle"];?>" /></td>
					<td ><b>&nbsp;Zona</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $rst["strDistrito"];?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Comuna</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $rst["strComuna"];?></td>
					<td ><b>&nbsp;T. Trabajo</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $rst["strTipoODT"];?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Motivo</b></td>
					<td align="center"><b>:</b></td>
					<td colspan="4"><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strMotivo"];?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<?php
	$ln = 0;
	$pag = 0;
	$cont = 0;
	$total = 0;

	$stmt1 = mssql_query("EXEC Orden..sp_getDetalleInformeOrden 1, '$contrato', ".$rst["dblCorrelativo"], $cnx);
	while($rst1 = mssql_fetch_array($stmt1)){
		$cont++;
		$ln++;?>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
			<?php
				if($cont == 1){
					$pag++;?>
				<tr>
					<td colspan="7">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td ><b>&nbsp;Informe de la Orden</b></td>
								<td width="30%" align="right">N&deg;Orden: <?php echo $rst["strOrden"];?> - Pag.: <?php echo $pag;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr >
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>Item</b></td>
					<td width="47%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
				</tr>
				<?php
				}?>
				<tr>
					<td width="3%" align="center"><?php echo $ln;?></td>
					<td width="10%" align="center"><?php echo $rst1["strItem"];?></td>
					<td width="47%" ><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo trim($rst1["strDescripcion"]);?>" /></td>
					<td width="10%" align="center"><?php echo $rst1["strUnidad"];?></td>
					<td width="10%" align="right"><?php echo number_format($rst1["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td >
			<b>
			<?php 
				$stmt2 = mssql_query("SELECT CONVERT(VARCHAR, dtmEnvio, 103) AS dtmEnvio, dblNumero FROM Orden..RegistroEnvio WHERE dblCorrelativo = ".$rst["dblCorrelativo"]." ORDER BY dblNumero DESC", $cnx);
				while($rst2 = mssql_fetch_array($stmt2)){
					echo '['.$rst2["dtmEnvio"].'->'.$rst2["dblNumero"].'] ';
				}
				mssql_free_result($stmt2);
				?>
			</b>
		</td>
	</tr>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center">
			<table border="1" width="75%" cellpadding="0" cellspacing="0">
				<tr align="center" valign="bottom" style="height:50px">
					<td width="50%"><b>Inspector: <?php echo $rst["strInspector"];?></b></td>
					<td width="50%"><b>Coordinador Contrato</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td align="center"><b>Para ser incluido en el Estado de Pago N&deg; <?php echo $rst["dblEP"];?></b></td></tr>
</table>
<?php } else {?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr><td><img border="0" align="absmiddle" src="../images/logo.jpg" /></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" colspan=6><b><?php echo $cabord;?></b></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center" colspan=4><b>PRESUPUESTO</b></td>
					<td align="right" style="border:solid 1px"><b>&nbsp;</b></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="left"><b>CONTRATISTA</b></td>
					<td align="left" style="border:solid 1px" colspan=2>Constructora EDECO S.A.</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="left">Fecha Ejecución</td>
					<td align="center" style="border:solid 1px"><?php echo formato_fecha($rst["dtmFInicio"]) ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan=2><b>Nº Memo Contratista&nbsp;<b></td>
				</tr>
				<tr>
					<td align="left">NºODT y Fecha</td>
					<td align="center" style="border:solid 1px"><?php echo $rst["strOrden"] ?></td>
					<td align="center" style="border:solid 1px"><?php echo formato_fecha($rst["dtmOrden"], false, false) ?></td>
					<td align="right">Unidad Solicitante</td>
					<td align="center" style="border:solid 1px"><?php if($rst["strTipoODT"] == "Agua Potable") { echo 'Redes de Distribución'; } else echo 'Redes de Recolección';?></td>
				<tr>
				<tr>
					<td align="left">Descripción de la Faena</td>
					<td align="left" style="border:solid 1px" colspan=4><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strMotivo"];?>" /></td>
				<tr>
				<tr>
					<td align="left">Dirección de la Faena / Sector</td>
					<td style="border:solid 1px" colspan=4><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo $rst["strDireccion"]." - ".$rst["strComuna"];?>" /></td>
				<tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<?php
	$ln = 0;
	$pag = 0;
	$cont = 0;
	$total = 0;

	$stmt1 = mssql_query("EXEC Orden..sp_getDetalleInformeOrden 1, '$contrato', ".$rst["dblCorrelativo"], $cnx);
	while($rst1 = mssql_fetch_array($stmt1)){
		$cont++;
		$ln++;?>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
			<?php
				if($cont == 1){
					$pag++;?>
				<tr>
					<td colspan="7">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td ><b>&nbsp;Informe de la Orden</b></td>
								<td width="30%" align="right">N&deg;Orden: <?php echo $rst["strOrden"];?> - Pag.: <?php echo $pag;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr >
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>Item</b></td>
					<td width="47%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
					<td width="10%" align="right"><b>P.Unitario&nbsp;</b></td>
					<td width="10%" align="right"><b>Total&nbsp;</b></td>
				</tr>
				<?php
				}?>
				<tr>
					<td width="3%" align="center"><?php echo $ln;?></td>
					<td width="10%" align="center"><?php echo $rst1["strItem"];?></td>
					<td width="47%" ><input class="txt-sborde" style="width:99%" value="&nbsp;<?php echo trim($rst1["strDescripcion"]);?>" /></td>
					<td width="10%" align="center"><?php echo $rst1["strUnidad"];?></td>
					<td width="10%" align="right"><?php echo number_format($rst1["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
					<td width="10%" align="right"><?php echo number_format($rst1["dblPrecio"], 0, '', '.');?>&nbsp;</td>
					<td width="10%" align="right"><?php echo number_format($rst1["dblCantidad"] * $rst1["dblPrecio"], 0, '', '.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
		$total += $rst1["dblCantidad"] * $rst1["dblPrecio"];
	}
	mssql_free_result($stmt1);
	if($contrato == '13001'){?>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="0%" align="right">TOTAL NETO&nbsp;</td>
					<td width="1%" align="center">:</td>
					<td width="10%" align="right"><?php echo number_format($total, 0, '', '.');?>&nbsp;</td>
				</tr>
				<tr>
					<td width="0%" align="right">19% I.V.A.&nbsp;</td>
					<td width="1%" align="center">:</td>
					<td width="10%" align="right"><?php echo number_format($total * 0.19, 0, '', '.');?>&nbsp;</td>
				</tr>
				<tr>
					<td width="0%" align="right"><b>TOTAL C/I.V.A.&nbsp;</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="10%" align="right"><b><?php echo number_format($total * 1.19, 0, '', '.');?>&nbsp;</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td >
			<b>
			<?php 
				$stmt2 = mssql_query("SELECT CONVERT(VARCHAR, dtmEnvio, 103) AS dtmEnvio, dblNumero FROM Orden..RegistroEnvio WHERE dblCorrelativo = ".$rst["dblCorrelativo"]." ORDER BY dblNumero DESC", $cnx);
				while($rst2 = mssql_fetch_array($stmt2)){
					echo '['.$rst2["dtmEnvio"].'->'.$rst2["dblNumero"].'] ';
				}
				mssql_free_result($stmt2);
				?>
			</b>
		</td>
	</tr>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center">
			<table border="1" width="75%" cellpadding="0" cellspacing="0">
				<tr align="center" valign="bottom" style="height:50px">
					<td width="50%"><b>Inspector: <?php echo $rst["strInspector"];?></b></td>
					<td width="50%"><b>Coordinador Contrato</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td align="center"><b>Para ser incluido en el Estado de Pago N&deg; <?php echo $rst["dblEP"];?></b></td></tr>
</table>
<?php }?>
<H3>&nbsp;</H3>
<?php
}
mssql_free_result($stmt);?>
</body>
</html>
<?php
mssql_close($cnx);
?>
