<?php 
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$contrato = $_GET["contrato"];
$numero = $_GET["numero"];
$cerrada = $_GET["cerrada"];
$estado = $_GET["estado"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Anexos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Quitar(ind, sw, anexo){
	if(confirm('¿Está seguro que desea ' + (sw ? 'quitar' : 'incluir') + ' este anexos en la bitácora?')){
		parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=6&usuario=<?php echo $usuario;?>&correlativo=<?php echo $numero;?>&anexo=' + anexo + '&valor=' + (sw ? 2 : 0);
		document.getElementById('txtEstado' + ind).value = (sw ? 'Trabajo Terminado' : 'Pendiente');
	}
}
-->
</script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
//echo "EXEC Orden..sp_getAnexos 0, '$contrato', $numero";
$stmt = mssql_query("EXEC Orden..sp_getAnexos 0, '$contrato', $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
		<tr  bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
			<td width="3%" align="center"><?php echo $cont;?></td>
			<td width="20%">
				<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="&nbsp;<?php echo '['.trim($rst[strMovil]).'] '.htmlentities(trim($rst["strNombre"]));?>" />
				<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo '['.trim($rst[strMovil]).'] '.htmlentities(trim($rst["strNombre"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
			<td width="30%">
				<input type="hidden" name="hdnObservacion<?php echo $cont;?>" id="hdnObservacion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strObservacion"]));?>" />
				<input name="txtObservacion<?php echo $cont;?>" id="txtObservacion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strObservacion"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObservacion<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnObservacion<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="20%" align="left"><input id="txtEstado<?php echo $cont;?>" name="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strEstado"];?>" /></td>
			<td width="10%" align="center"><?php echo $rst["dtmFTermino"];?></td>
			<td width="5%" align="center">
			<?php
				if(($cerrada == 1 || $cerrada == 2 || $estado == '07009') && $rst["intOk"] <> 2){
					echo '<input type="checkbox" name="chkQuitar" id="chkQuitar" ';
					echo 'onclick="javascript: Quitar('.$cont.', this.checked, \''.trim($rst[strMovil]).'\')" ';
					echo '/>';
				}else
					echo '&nbsp;';
			?>
			</td>
		</tr>
<?php
	}while($rst = mssql_fetch_array($stmt));
}else{?>
	<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</body>
</html>
