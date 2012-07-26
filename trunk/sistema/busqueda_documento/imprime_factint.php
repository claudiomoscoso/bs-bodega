<?php
include '../conexion.inc.php';

$directo = $_GET["directo"];
$interno = $_GET["numero"];
$cargo = $_GET["cargo"];
$stmt = mssql_query("EXEC Bodega..sp_getFacturaInterna 1, $interno, '$cargo'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numero = $rst["dblNumero"];
	$fecha = $rst["dtmFecha"];
	$cargo = $rst["strCargo"];
	$estado = $rst["strDescEstado"];
	$usuario = $rst["strUsuario"];
	$nombre = $rst["strNombUsuario"];
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
<title>Factura Interna</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(<?php echo $directo;?>) parent.Deshabilita(false);
}
-->
</script>
<body id="cuerpo" style="background-color:#FFFFFF" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
if($fecha != ''){?>
	<tr>
		<td align="center" valign="top">
		<?php
		$sw = 1; $fil = 38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleFacturaInterna 0, $interno", $cnx);
		while($rst = mssql_fetch_array($stmt)){
			$fil++;
			$cont++;
			if($fil == 39){
				$fil = 1;
				if($sw == 0){ 
					echo '</table><table borde="0"><tr><td><H3></H3></td></tr></table>';
					$sw = 1;
				}
				$sw = 0;
			?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Factura Interna</h1></td>
								<td align="center" width="18%"><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px"></td></tr>
				<tr>
					<td valign="top" align="center">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="7%"><b>&nbsp;N&deg;F.Interno</b></td>
								<td width="1%" align="center"><b>:</b></td>
								<td width="0%">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="0%" ><?php echo $numero;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left"><b>&nbsp;Estado</b></td>
											<td width="1%" align="center"><b>:</b></td>
											<td width="0%" align="left">&nbsp;<?php echo $estado;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left"><b>&nbsp;Usuario</b></td>
											<td width="1%" align="center"><b>:</b></td>
											<td width="0%" align="left">&nbsp;<?php echo $nombre;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left"><b>&nbsp;Fecha</b></td>
								<td align="center"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left">&nbsp;<b>Cargo</b></td>
											<td width="1%" align="center"><b>:</b></td>
											<td width="0%" align="left">&nbsp;<?php echo $cargo;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td ><hr /></td></tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="3%" align="center"><b>N&deg;</b></td>
								<td width="10%" align="center"><b>C&oacute;digo</b></td>
								<td width="27%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
								<td width="10%" align="center"><b>F.Inicio</b></td>
								<td width="10%" align="center"><b>F.T&eacute;rmino</b></td>
								<td width="10%" align="center"><b>C.Costo</b></td>
								<td width="10%" align="right"><b>Cantidad</b></td>
								<td width="10%" align="right"><b>Precio</b></td>
								<td width="10%" align="right"><b>Total</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td ><hr /></td></tr>
			<?php
			}
			?>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="3%" align="center"><?php echo $cont;?></td>
								<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
								<td width="27%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
								<td width="10%" align="center"><?php echo $rst["dtmFInicio"];?></td>
								<td width="10%" align="center"><?php echo $rst["dtmFTermino"];?></td>
								<td width="10%" align="center"><?php echo $rst["strCCosto"];?></td>
								<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"], 2, ',', '.');?>&nbsp;</td>
								<td width="10%" align="right"><?php echo number_format($rst["dblPrecio"], 0, '', '.');?>&nbsp;</td>
								<td width="10%" align="right"><?php echo number_format($rst["dblTotal"], 0, '', '.');?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
<?php		
			$total += $rst["dblTotal"];
		}
		mssql_free_result($stmt);?>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="0%" align="right"><b>TOTAL</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="10%" align="right"><?php echo number_format($total, 0, '', '.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="center">
			<table border="0" width="20%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="0%" align="center" style="height:50px;border-left:solid 1px;border-right:solid 1px;border-top:solid 1px">
					<?php
					$stmt = mssql_query("SELECT DISTINCT Usuarios.firma FROM General..Usuarios AS Usuarios INNER JOIN Autorizaciones ON (Usuarios.usuario = Autorizaciones.strAutoriza AND Autorizaciones.strTipoDocto = 'FI' AND Autorizaciones.strAccion = '1') WHERE Autorizaciones.strAutoriza  = '$usuario'", $cnx);
					if($rst = mssql_fetch_array($stmt)) echo $firma = $rst["firma"];
					mssql_free_result($stmt);
					?>
						<img border="0" align="absmiddle" src="../images/<?php echo $firma;?>" />
					</td>
				</tr>
				<tr>
					<td width="0%" align="center" style="border-left:solid 1px;border-right:solid 1px;border-bottom:solid 1px"><b>V&deg;B&deg;</b></td>
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
