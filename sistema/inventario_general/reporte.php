<?php
include '../conexion.inc.php';

$ccosto = $_GET["ccosto"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Inventario General</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;
-->
</script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Operaciones..sp_getReporteDiario 0, '$ccosto'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo $cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center">
		<?php
		if($cont == 1){?>
			<a href="#" title="Edita report..."
				onclick="javascript: 
					AbreDialogo('divEReport', 'frmEReport', 'reporte_diario.php?numero=<?php echo $rst["dblNumero"];?>', true);
				"
			><?php echo $rst["dblNumero"];?></a>
		<?php
		}else{
			echo $rst["dblNumero"];
		}?>
		</td>
		<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
		<td width="13%" >
			<input type="hidden" name="hdnEquipo<?php echo $cont;?>" id="hdnEquipo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strEquipo"]);?>" />
			<input name="txtEquipo<?php echo $cont;?>" id="txtEquipo<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strEquipo"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEquipo<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEquipo<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblRecorrido"], 2, ',', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblOdometroFinal"], 2, ',', '.');?>&nbsp;</td>
		<td width="5%" align="center"><?php echo trim($rst["strTipoUso"]);?></td>
		<td width="13%" align="left">
			<input type="hidden" name="hdnObra<?php echo $cont;?>" id="hdnObra<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strObra"]);?>" />
			<input name="txtObra<?php echo $cont;?>" id="txtObra<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strObra"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObra<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnObra<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="left">
			<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strEstado"]);?>" />
			<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strEstado"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center"><?php echo $rst["dtmRTecnica"];?></td>
		<td width="14%" align="left">
			<input type="hidden" name="hdnObservacion<?php echo $cont;?>" id="hdnObservacion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strObservacion"]);?>" />
			<input name="txtObservacion<?php echo $cont;?>" id="txtObservacion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strObservacion"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObservacion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnObservacion<?php echo $cont;?>');
				"
			/>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center"><font color="#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></font></td></tr>';
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</body>
</html>