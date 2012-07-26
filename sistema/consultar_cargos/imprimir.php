<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$cargo = $_GET["cargo"];

$stmt = mssql_query("SELECT strDetalle FROM General..Tablon WHERE strCodigo = '$bodega'", $cnx);
if($rst = mssql_fetch_array($stmt)) $descbodega = $rst["strDetalle"];
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strNombre FROM General..PersonalObras WHERE strRut = '$cargo'", $cnx);
if($rst = mssql_fetch_array($stmt)) $nombcargo = $rst["strNombre"];
mssql_free_result($stmt);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Ficha de Cargos</title></head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
	self.focus();
	self.print();
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="30%"><img border="0" src="../images/logo.jpg"></td>
					<td align="center"><h1>Ficha de Cargos</h1></td>
					<td width="30%">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><b>Fecha</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="10%">&nbsp;<?php echo date('d/m/Y');?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>Obra</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="30%">&nbsp;<?php echo $descbodega;?></td>
					<td width="1%">&nbsp;</td>
					<td width="5%"><b>Cargo</b></td>
					<td width="1%" align="center"><b>:</b></td>
					<td width="40%">&nbsp;<?php echo $nombcargo;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="65%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Ult.Entrega</th>
					<th width="10%" align="right">Cargos&nbsp;</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr><td colspan="6"><hr></td></tr>
			<?php
			$stmt = mssql_query("EXEC Bodega..sp_getDevolucionesPendientes 4, NULL, '$bodega', '$cargo'", $cnx);
			if($rst=mssql_fetch_array($stmt)){
				do{
					$cont++;
					echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
					echo '<td width="3%" align="center">'.$cont.'</td>';
					echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
					echo '<td width="65%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
					echo '<td width="10%" align="center">'.($rst["dtmFch"] != '' ? $rst["dtmFch"] : 'S/Registro').'</td>';
					echo '<td width="10%" align="right">'.$rst["dblSaldo"].'&nbsp;</td>';
					echo '</tr>';
				}while($rst=mssql_fetch_array($stmt));
			}else{
				echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
			}
			mssql_free_result($stmt);?>
			</table>
		</td>
	</tr>
	<tr><td><hr></td></tr>
	<tr>
		<td align="center">
			<table border="0" width="25%" cellpadding="0" cellspacing="0">
				<tr><td style="height:50px; border-bottom:solid 1px">&nbsp;</td></tr>
				<tr><td align="center"><?php echo $nombcargo;?></td></tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>