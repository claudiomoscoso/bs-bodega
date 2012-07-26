<?php
include '../conexion.inc.php';

$directo=$_GET["directo"];
if($directo){
	$bodega=$_GET["bodega"];
	$numero=$_GET["numero"];
	$stmt = mssql_query("EXEC Bodega..sp_getGuiaIngreso 'NUM', $numero,  '$bodega'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $interno=$rst["dblNumero"];
	mssql_free_result($stmt);
}else
	$interno=$_GET["numero"];
	$bodega=$_GET["bodega"];

if($interno!=''){
	$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'GI', $interno", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$nombresp=$rst["nombre"];
		$firmresp=$rst["firma"];
	}
	mssql_free_result($stmt);


	$stmt = mssql_query("EXEC Bodega..sp_getGuiaIngreso 'GI', $interno", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$numing=$rst["dblNum"];
		$numoc=$rst["dblUltima"];
		$nombprov=$rst["strNombre"];
		$fecha=$rst["dtmFecha"];
		$proveedor=$rst["strNombre"];
		$referencia=$rst["strReferencia"];
		$estado=$rst["strDescEstado"];
		$usuario=$rst["strNombUsua"];
		$tdocumento=$rst["intTipoDoc"];
		$escajachica=$rst["intCC"];
		$descbodega=$rst["strDescBodega"];
		$cajachica=$rst["dblCajaChica"];
	}
	mssql_free_result($stmt);
}
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
<title>Guia de Ingreso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if(<?php echo $directo;?>) parent.Deshabilita(false);
}
-->$bodega=$_GET["bodega"];
</script>
<body id="cuerpo" style="background-color:#FFFFFF" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
if($interno!=''){?>
	<tr>
		<td align="center" valign="top">
	<?php
		$sw=1; $fil=38;
		$stmt = mssql_query("EXEC Bodega..sp_getDetalleGuiaIngreso $interno", $cnx);
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
					<td align="center" valign="top" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" width="16%" align="center"><img border="0" src="../images/<?php echo $logo;?>" /></td>
								<td align="center"><h1>Gu&iacute;a de Ingreso</h1></td>
								<td align="center" width="18%" nowrap><?php echo $encabezado;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td valign="top" align="center" colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%" align="left"><b>&nbsp;N&deg;Interno</b></td>
								<td width="1%"><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="10%" align="left">&nbsp;<?php echo $interno;?></td>
											<td width="1%">&nbsp;</td>
											<td width="8%" align="left" ><b>&nbsp;Estado</b></td>
											<td width="1%"><b>:</b></td>
											<td width="15%" align="left">&nbsp;<?php echo $estado;?></td>
											<td width="1%">&nbsp;</td>
											<td width="8%" align="left" ><b>&nbsp;Bodega</b></td>
											<td width="1%"><b>:</b></td>
											<td width="25%" align="left">&nbsp;<?php echo $descbodega;?></td>
											<td width="1%">&nbsp;</td>
											<td width="8%" align="left" ><b>&nbsp;Usuario</b></td>
											<td width="1%"><b>:</b></td>
											<td width="20%" align="left">&nbsp;<?php echo $usuario;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" nowrap="nowrap"><b>&nbsp;G.Ingreso N&deg;</b></td>
								<td ><b>:</b></td>
								<td colspan="9">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="25%" align="left">&nbsp;<?php echo $numing;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left" nowrap="nowrap"><b>&nbsp;O.Compra N&deg;</b></td>
											<td width="1%"><b>:</b></td>
											<td width="25%" align="left">&nbsp;<?php echo $numoc;?></td>
											<td width="1%">&nbsp;</td>
											<td width="10%" align="left"><b>&nbsp;Fecha</b></td>
											<td width="1%"><b>:</b></td>
											<td width="26%" align="left">&nbsp;<?php echo $fecha;?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr valign="top">
								<td align="left">&nbsp;<b>Proveedor</b></td>
								<td><b>:</b></td>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td width="42%" align="left">&nbsp;<?php echo $nombprov;?></td>
											<td width="1%">&nbsp;</td>
											<td width="15%" align="left" ><b>&nbsp;
												<?php if($escajachica==1) echo 'Caja Chica'; 
												else echo ($tdocumento==0) ? 'G.Despacho N&deg;' : 'Factura N&deg;';?></b></td>
											<td width="1%"><b>:</b></td>
											<td width="41%" align="left">&nbsp;<?php if($escajachica==0) echo $referencia; else echo $cajachica;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="5px" colspan="5"></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="10%" align="center"><b>C&oacute;digo</b></td>
					<td width="67%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
					<td width="10%" align="right"><b>Precio&nbsp;</b></td>
				</tr>
				<tr><td colspan="5"><hr /></td></tr>
		<?php
			}?>
				<tr>
					<td align="center" nowrap="nowrap"><?php echo $cont;?>&nbsp;</td>
					<td align="center"><?php echo $rst["strCodigo"];?></td>
					<td align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
					<td align="right"><?php echo number_format($rst["dblValor"],0,'','.');?>&nbsp;</td>
				</tr>
<?php	}
		mssql_free_result($stmt);?>
				<tr><td colspan="5"><hr /></td></tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<?php
				if($nombresp!=''){?>
				<tr>
					<td colspan="5" align="center">
						<table border="0" width="20%" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center" valign="bottom">
								<?php
									if($firmresp!='') 
										echo '<img border="0" src="../images/'.$firmresp.'" />';
									else
										echo '<br /><br /><br /><br />';
									echo $nombresp;
								?>
									<br />Responsable
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				}?>
			</table>
		</td>
	</tr>
	<input type="hidden" id="documento" value="<?php echo $interno;?>" />
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
