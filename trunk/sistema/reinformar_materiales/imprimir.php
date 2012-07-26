<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$interno = $_GET["interno"];
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 3, '', '', NULL, $interno", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$inspector = trim($rst["strNombInspector"]);
	$orden = trim($rst["strOrden"]);
	$movil = trim($rst["strMovil"]);
	$ods = trim($rst["strODS"]);
	$prioridad = trim($rst["strDescPrioridad"]);
	$direccion = trim($rst["strDireccion"]);
	$forden = trim($rst["dtmFchOrden"]).' '.trim($rst["dtmHrOrden"]);
	$entrecalles = trim($rst["strEntreCalle"]);
	$fvence = trim($rst["dtmFchVcto"]).' '.trim($rst["dtmHVcto"]);
	$comuna = trim($rst["strDescComuna"]);
	$ttrabajo = trim($rst["strDescODT"]);
	$trabajo = trim($rst["strMotivo"]);
}
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strHI, strHT FROM Orden..ControlHR WHERE dblCorrelativo = $interno", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$finicio = $rst["strHI"];
	$ftermino = $rst["strHT"];
}
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strObservacion FROM Orden..Observaciones WHERE dblCorrelativo = $interno", $cnx);
if($rst = mssql_fetch_array($stmt)) $observacion = $rst["strObservacion"];
mssql_free_result($stmt);

$stmt = mssql_query("SELECT nombre, email FROM General..Usuarios WHERE usuario = '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){ 
	$nombre = $rst["nombre"];
	$email_de = $rst["email"];
}
mssql_free_result($stmt);
?>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	self.focus();
	window.print();
}
-->
</script>
<title>Orden de Trabajo</title><body style="background-color:#FFFFFF" onLoad="javascript: Load()">
<table border="0" cellpadding="0" width="100%" >
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="33%"><img border="0" src="../images/logo.jpg"></td>
					<td align="center"><font size="5">Hoja de Ruta</font></td>
					<td width="33%">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:10px"></td></tr>
	<tr><td><b>INSPECTOR: </b><?php echo $inspector;?></td></tr>
	<tr><td><hr></td></tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr><td colspan="7" ><b>&nbsp;DATOS DE LA ORDEN</b></td></tr>
				<tr>
					<td width="10%"><b>&nbsp;ODT</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="38%">&nbsp;<?php echo $orden;?></td>
					<td width="1%">&nbsp;</td>
					<td width="11%"><b>&nbsp;Movil</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="38%">&nbsp;<?php echo $movil;?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;ODS</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $ods;?></td>
					<td >&nbsp;</td>
					<td ><b>&nbsp;Prioridad</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $prioridad;?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Direcci&oacute;n</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $direccion;?></td>
					<td >&nbsp;</td>
					<td ><b>&nbsp;F.Emisi&oacute;n</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $forden;?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Entre Calles</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $entrecalles;?></td>
					<td >&nbsp;</td>
					<td ><b>&nbsp;F.T&eacute;rmino</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $fvence;?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;Comuna</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $comuna;?></td>
					<td >&nbsp;</td>
					<td ><b>&nbsp;Tipo Trabajo</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $ttrabajo;?></td>
				</tr>
				<tr>
					<td rowspan="2" valign="top"><b>&nbsp;Trabajo</b></td>
					<td rowspan="2" align="center" valign="top"><b>:</b></td>
					<td rowspan="2" valign="top">&nbsp;<?php echo $trabajo;?></td>
					<td rowspan="2">&nbsp;</td>
					<td ><b>&nbsp;I.Hidráulico</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $finicio;?></td>
				</tr>
				<tr>
					<td ><b>&nbsp;T.Hidráulico</b></td>
					<td align="center"><b>:</b></td>
					<td >&nbsp;<?php echo $ftermino;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr><td colspan="7"><b>&nbsp;INFORME DE LA ORDEN</b></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="15%" align="center"><b>&Iacute;tem</b></td>
					<td width="0%" ><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
					<td width="10%" align="center"><b>F.Informe</b></td>
					<td width="0%" ><b>&nbsp;Observ.</b></td>
				</tr>
			<?php
			$cont=0;
			$stmt = mssql_query("EXEC Orden..sp_getDetalleInformeOrden 0, NULL, $interno", $cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$cont++;
					echo '<tr>';
					echo '<td align="center">'.$cont.'</td>';
					echo '<td align="center">'.trim($rst["strItem"]).'</td>';
					echo '<td align="left">&nbsp;'.trim($rst["strDescripcion"]).'</td>';
					echo '<td align="center">'.trim($rst["strUnidad"]).'</td>';
					echo '<td align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
					echo '<td align="center">&nbsp;'.trim($rst["strMovil"]).'</td>';
					echo '<td align="left">&nbsp;</td>';
					echo '</tr>';
				}while($rst = mssql_fetch_array($stmt));
			}else{
				echo '<tr><td colspan="7" align="center">No registra informe.</td></tr>';
			}
			mssql_free_result($stmt);?>
			</table>
		</td>
	</tr>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr><td colspan="6"><b>&nbsp;DETALLE DE MATERIALES</b></td></tr>
				<tr>
					<td width="3%" align="center"><b>N&deg;</b></td>
					<td width="15%" align="center"><b>C&oacute;digo</b></td>
					<td width="0%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="10%" align="center"><b>Unidad</b></td>
					<td width="10%" align="right"><b>Cantidad&nbsp;</b></td>
					<td width="10%" align="center"><b>Movil</b></td>
				</tr>
		<?php
		$stmt = mssql_query("EXEC Orden..sp_getDetalleMaterialInformado 0, '', $interno", $cnx);
		if($rst = mssql_fetch_array($stmt)){
			do{
				$cont++;
				echo '<tr>';
				echo '<td align="center">'.$cont.'</td>';
				echo '<td align="center">'.trim($rst["CodRoss"]).' ['.trim($rst["strCodigo"]).']</td>';
				echo '<td align="left">&nbsp;'.trim($rst["strDescripcion"]).'</td>';
				echo '<td align="center">'.trim($rst["strUnidad"]).'</td>';
				echo '<td align="right">'.$rst["dblCantidad"].'&nbsp;</td>';
				echo '<td align="center">'.trim($rst["strMovil"]).'</td>';
				echo '</tr>';
			}while($rst = mssql_fetch_array($stmt));
		}else{
			echo '<tr><td colspan="7" align="center">No registra materiales.</td></tr>';
		}
		mssql_free_result($stmt);?>
			</table>
		</td>
	</tr>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr><td colspan="6"><b>&nbsp;OBSERVACION</b></td></tr>
				<tr><td>&nbsp;<?php echo $observacion;?></td></tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
