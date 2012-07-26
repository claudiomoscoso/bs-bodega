<?php
include '../autentica.php';
include '../conexion.inc.php';

$cargo = $_POST["cmbCargo"];
$bsolicitud = $_POST["cmbBodega"];
$solicitante = $_POST["solicitante"];
$nota = $_POST["chkBCentral"] == 'on' ? 'Materiales de bodega central. '.$_POST["txtNota"] : $_POST["txtNota"];
$nguia = $_POST["txtNAjuste"] != '' ? $_POST["txtNAjuste"] : 'NULL';

$stmt = mssql_query("EXEC Bodega..sp_setSolicitudMaterial 0, '$usuario', '$bsolicitud', '$cargo', '$nota', $nguia", $cnx);
if($rst = mssql_fetch_array($stmt)){ 
	$error = $rst["dblError"];
	$dblInterno = $rst["dblInterno"];
	$dblNumero = $rst["dblNumero"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getSolicitudMaterial 0, $dblInterno", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$fecha = $rst["dtmSolicitud"];
	$descbodega = $rst["strDescBodega"];
	$desccargo = $rst["strDescCargo"];
}
mssql_free_result($stmt);
$stmt = mssql_query("Select strPrint, strLogo From General..Contrato Where strCodigo='$cargo'", $cnx);
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
<title>Solicitud</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	switch(parseInt('<?php echo $error;?>')){
		case 0:
			alert('La solicitud de materiales ha sido guardada con el Nº <?php echo $dblNumero;?>.');
			self.focus();
			self.print();
			parent.location.href='index.php<?php echo $parametros;?>';
			break;
		case 1:
			alert('No ha sido posible obtener el número correlativo.');
			break;
		case 2:
			alert('No ha sido posible obtener información del usuario.');
			break;
	}
}
-->
</script>
<body id="cuerpo" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleSolicitudMaterial 0, $dblInterno", $cnx);
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
					<td align="center" valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="16%" align="center" valign="top" >
									<img border="0" src="../images/<?php echo $logo;?>"/>
									<br />
									[Interno: <?php echo $dblInterno;?>]
								</td>
								<td align="center"><h1>Solicitud de Materiales</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td >
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left" nowrap="nowrap"><b>&nbsp;Solicitud N&deg;</b></td>
											<td width="1%">:</td>
											<td width="20%" align="left">&nbsp;<?php echo $dblNumero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%">:</td>
											<td align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="5%" align="left"><b>&nbsp;Bodega</b></td>
											<td width="1%">:</td>
											<td align="left">&nbsp;<?php echo $descbodega;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td >
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<b>Cargo</b></td>
											<td width="1%">:</td>
											<td width="42%" align="left">&nbsp;<?php echo $desccargo;?></td>
											<td width="1%"></td>
											<td width="5%" align="left">&nbsp;<b>Nota</b></td>
											<td width="1%">:</td>
											<td align="left">&nbsp;<?php echo $nota;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td ></td></tr>
				<tr>
					<td >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center"><b>N&deg;</b></td>
								<td width="10%" align="center"><b>C&oacute;digo</b></td>
								<td width="67%" align="center"><b>Descripci&oacute;n</b></td>
								<td width="10%" align="center"><b>Unidad</b></td>
								<td width="10%" align="center"><b>Cantidad</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td ><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td >
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="right" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
								<td align="center"><?php echo $rst["strCodigo"];?></td>
								<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
								<td align="center"><?php echo $rst["strUnidad"];?></td>
								<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td ><hr /></td></tr>
				<tr>
					<td align="center" valign="top">
						<table width="60%" height="60px" border="1" cellpadding="0" cellspacing="0">
							<tr>
								<td width="20%" align="center" valign="bottom"><b>V&deg;B&deg;</b></td>
								<td width="20%" align="center" valign="bottom"><b><?php echo $solicitante!='' ? $solicitante : 'Solicitante';?></b></td>
								<td width="20%" align="center" valign="bottom"><b>Adquisiciones</b></td>
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
