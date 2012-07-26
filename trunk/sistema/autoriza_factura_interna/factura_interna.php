<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
$stmt = mssql_query("EXEC Bodega..sp_getFacturaInterna 1, $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$fecha = $rst["dtmFecha"];
	$cargo = $rst["strCargo"];
	$estado = $rst["dblEstado"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('divDetalle').style.height = (window.innerHeight - 100) + 'px';
}

function Deshabilita(sw){
	document.getElementById('btnVB').disabled = sw;
	document.getElementById('btnRechaza').disabled = sw;
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="6%"><b>&nbsp;N&deg;F.Interna</b></td>
					<td width="1%" align="center">:</td>
					<td width="10%"><input name="txtNumero" id="txtNumero" class="txt-sborde" style="width:99%;background-color:#ececec" readonly="true" value="&nbsp;<?php echo $numero;?>" /></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>&nbsp;Fecha</b></td>
					<td width="1%" align="center">:</td>
					<td width="10%">&nbsp;<?php echo $fecha;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>&nbsp;Cargo</b></td>
					<td width="1%" align="center">:</td>
					<td width="59%">&nbsp;<?php echo $cargo;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="25%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">F.Inicio</th>
					<th width="10%">F.T&eacute;rmino</th>
					<th width="10%">C.Costo</th>
					<th width="10%" align="right">Cantidad&nbsp;</th>
					<th width="10%" align="right">Valor&nbsp;</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="2%" >&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="divDetalle" style="height:100px; overflow:scroll; position:relative; width:100%">
				<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<?php
				$stmt = mssql_query("EXEC Bodega..sp_getDetalleFacturaInterna 0, $numero", $cnx);
				while($rst = mssql_fetch_array($stmt)){
					$cont++;
					echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
					echo '<td width="3%" align="center">'.$cont.'</td>';
					echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
					echo '<td width="25%" >&nbsp;'.$rst["strDescripcion"].'</td>';
					echo '<td width="10%" align="center">'.$rst["dtmFInicio"].'</td>';
					echo '<td width="10%" align="center">'.$rst["dtmFTermino"].'</td>';
					echo '<td width="10%" align="center">'.$rst["strCCosto"].'</td>';
					echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'</td>';
					echo '<td width="10%" align="right">'.number_format($rst["dblPrecio"], 0, '', '.').'</td>';
					echo '<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '.').'</td>';
					echo '</tr>';
					$total += $rst["dblTotal"];
				}
				mssql_free_result($stmt);
				?>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr valign="bottom" style="height:22px">
					<td width="0%" align="right"><b>TOTAL&nbsp;</b></td>
					<td width="1%" align="center">:</td>
					<td width="10%" align="right"><?php echo number_format($total, 0, '', '.');?>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnVB" id="btnVB" class="boton" style="width:80px" value="V&deg;B&deg;..." <?php echo ($estado != 0 ? 'disabled' : '');?> 
				onclick="javascript:
					Deshabilita(true);
					AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?numero=<?php echo $numero;?>&estado=1', true);
				"
			/>
			<input type="button" name="btnRechaza" id="btnRechaza" class="boton" style="width:80px" value="Rechazar..." <?php echo ($estado != 0 ? 'disabled' : '');?> 
				onclick="javascript:
					Deshabilita(true);
					AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?numero=<?php echo $numero;?>&estado=2', true);
				"
			/>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>