<?php
include '../autentica.php';
include '../conexion.inc.php';

$numero = $_GET["numero"];
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 3, '', '', NULL, '$numero'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$fchorden = $rst["dtmFchOrden"];
	$numot = $rst["strOrden"];
	$contrato = $rst["strContrato"];
	$desccontrato = $rst["strDescContrato"];
	$movil = $rst["strMovil"];
	$descmovil = $rst["strNombre"];
	$fchvcto = $rst["dtmFchVcto"];
	$motivo = $rst["strMotivo"];
	$estado = $rst["strEstado"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reinformar Trabajos y Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Accion(obj){
	if(obj.id == 'btnAtras')
		self.location.href='index.php<?php echo $parametros;?>';
	else if(obj.id == 'btnRMateriales')
		document.getElementById('transaccion').src='transaccion.php<?php echo $parametros;?>&modulo=1&numero=<?php echo $numero;?>&orden=<?php echo $numot;?>&';
	else if(obj.id == 'btnRTrabajos')
		document.getElementById('transaccion').src='transaccion.php<?php echo $parametros;?>&modulo=2&numero=<?php echo $numero;?>&orden=<?php echo $numot;?>&';
	else if(obj.id == 'btnActivar'){
		document.getElementById('transaccion').src='transaccion.php<?php echo $parametros;?>&modulo=3&numero=<?php echo $numero;?>';
	}
}

function Load(){
	var alto = (window.innerHeight - 171) / 2;
	document.getElementById('trabajos').setAttribute('height', alto);
	document.getElementById('materiales').setAttribute('height', alto);
	
	if(('<?php echo $estado;?>' == '07009') || (parseInt('<?php echo $cerrada;?>') == 1 || parseInt('<?php echo $cerrada;?>') == 2) || (parseInt('<?php echo $cerrada;?>') == 0 && parseInt('<?php echo $certificado;?>') > 0))
		document.getElementById('divMensaje').style.display = '';
	
	document.getElementById('trabajos').src ='trabajos.php?usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>';
	document.getElementById('materiales').src = 'materiales.php?usuario=<?php echo $usuario;?>&numero=<?php echo $numero;?>';
}

function Imprimir(){
	document.getElementById('transaccion').src = 'imprimir.php?usuario=<?php echo $usuario;?>&interno=<?php echo $numero;?>';
}
-->
</script>
<body onload="javascript: Load()">
<div id="divMensaje" style="position:absolute;width:20%;height:10%;left:40%;top:10%;opacity:0.5;display:none">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" valign="middle" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:36px; font-weight:bold; color:#FF0000;">
		<?php
		if($estado == '07009') 
			echo 'ANULADA';
		elseif($cerrada == 1 || $cerrada == 2)
			echo "CERRADA E.P.: $epago";
		elseif($cerrada == 0 && $certificado > 0)
			echo 'VALORIZADA';
		?>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="7%"><b>&nbsp;N&deg; Orden</b></td>
					<td width="1%"><b>:</b></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%">&nbsp;<?php echo $numot;?></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;Fch. Orden</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%">&nbsp;<?php echo $fchorden;?></td>
								<td width="1%">&nbsp;</td>
								<td width="4%"><b>&nbsp;Movil</b></td>
								<td width="1%"><b>:</b></td>
								<td width="35%" >&nbsp;<?php echo $descmovil;?></td>
								<td width="1%">&nbsp;</td>
								<td width="8%"><b>&nbsp;Fch. Vcto.</b></td>
								<td width="1%"><b>:</b></td>
								<td width="10%">&nbsp;<?php echo $fchvcto;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>&nbsp;Contrato</b></td>
					<td><b>:</b></td>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="46%"><?php echo $desccontrato;?></td>
								<td width="1%">&nbsp;</td>
								<td width="5%"><b>&nbsp;Motivo</b></td>
								<td width="1%"><b>:</b></td>
								<td width="47%" ><input class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="&nbsp;<?php echo $motivo;?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="4%">&nbsp;</th>
					<th width="35%" align="left">&nbsp;Movil</th>
					<th width="15%">C&oacute;digo</th>
					<th width="31%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="background:url(../images/borde_menu.gif); color:#FFFFFF">&nbsp;Trabajos</td></tr>
	<tr><td><iframe id="trabajos" frameborder="0" width="100%" scrolling="yes" src="../cargando.php"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnRTrabajos" id="btnRTrabajos" class="boton" style="width:90px" value="Reinformar" 
				onclick="javascript: Accion(this);"
			/>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr><td style="background:url(../images/borde_menu.gif); color:#FFFFFF">&nbsp;Materiales</td></tr>
	<tr><td><iframe id="materiales" frameborder="0" width="100%" scrolling="yes" src="../cargando.php"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<input type="button" id="btnAtras" class="boton" style="width:90px" value="&lt;&lt; Atras" 
							onclick="javascript: Accion(this);"
						/>
					</td>
					<td width="50%" align="right">
					<?php
					if($estado == '07009'){
						echo '<input type="button" id="btnActivar" class="boton" style="width:90px" value="Activar" 
							onclick="javascript: Accion(this);"
						/>';
					}
					?>
						<input type="button" id="btnImprimir" class="boton" style="width:90px" value="Imprimir..." 
							onclick="javascript: Imprimir(this);"
						/>
						<input type="button" id="btnRMateriales" class="boton" style="width:90px" value="Reinformar" 
							onclick="javascript: Accion(this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>