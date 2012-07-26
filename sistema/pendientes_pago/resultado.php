<?php
include '../conexion.inc.php';
//$fchdsd = $_GET["fchdsd"];
//$fchhst = $_GET["fchhst"];
$fchdsd = formato_fecha($_GET["fchdsd"], false, true);
$fchhst = formato_fecha($_GET["fchhst"], false, true);
$contrato = $_GET["contrato"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pendientes de Pago</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('txtTotal').value = document.getElementById('hdnTotal').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$total = 0;
$stmt = mssql_query("EXEC Orden..sp_EP_PPXML '$fchdsd', '$fchhst', '$contrato'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	if($contrato != '13000'){
		do{
			$cont++;?>
		<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
			<td width="3%" align="center"><?php echo $cont;?></td>
			<td width="13%">
				<input type="hidden" name="hdnLocalidad<?php echo $cont;?>" id="hdnLocalidad<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Localidad"]));?>" />
				<input name="txtLocalidad<?php echo $cont;?>" id="txtLocalidad<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Localidad"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnLocalidad<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnLocalidad<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="13%">
				<input type="hidden" name="hdnInspector<?php echo $cont;?>" id="hdnInspector<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Inspector"]));?>" />
				<input name="txtInspector<?php echo $cont;?>" id="txtInspector<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Inspector"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnInspector<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnInspector<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="9%" align="center"><?php echo $rst["ODT"];?></td>
			<td width="8%" align="center"><?php echo $rst["Fecha"];?></td>
			<td width="5%" align="center"><?php echo $rst["Item"];?></td>
			<td width="14%"><?php echo htmlentities(trim($rst["Detalle"]));?></td>
			<td width="7%" align="right"><?php echo number_format($rst["Cantidad_I"], 2, ',', '.');?>&nbsp;</td>
			<td width="6%" align="center"><?php echo $rst["Unidad"];?></td>
			<td width="10%" align="right"><?php echo number_format($rst["Total_I"], 0, '', '.');?>&nbsp;</td>
			<td width="10%">
				<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Estado"]));?>" />
				<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Estado"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');
					"
				/>
			</td>
		</tr>
<?php	$total += $rst["Total_I"];
		}while($rst = mssql_fetch_array($stmt));
	}else{
		do{
			$cont++;?>
		<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
			<td width="3%" align="center"><?php echo $cont;?></td>
			<td width="10%" align="center"><?php echo $rst["ODT"];?></td>
			<td width="10%" align="center"><?php echo $rst["FAX"];?></td>
			<td width="15%">
				<input type="hidden" name="hdnDireccion<?php echo $cont;?>" id="hdnDireccion<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Direccion"]));?>" />
				<input name="txtDireccion<?php echo $cont;?>" id="txtDireccion<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Direccion"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDireccion<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnDireccion<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="10%">
				<input type="hidden" name="hdnComuna<?php echo $cont;?>" id="hdnComuna<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Comuna"]));?>" />
				<input name="txtComuna<?php echo $cont;?>" id="txtComuna<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Comuna"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnComuna<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnComuna<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="10%" align="right"><?php echo number_format($rst["Total_I"], 0, '', '.');?>&nbsp;</td>
			<td width="5%" align="center" ><?php echo $rst["EP"];?></td>
			<td width="8%" align="center"><?php echo $rst["Envio"];?></td>
			<td width="15%">
				<input type="hidden" name="hdnInspector<?php echo $cont;?>" id="hdnInspector<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Inspector"]));?>" />
				<input name="txtInspector<?php echo $cont;?>" id="txtInspector<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Inspector"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnInspector<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnInspector<?php echo $cont;?>');
					"
				/>
			</td>
			<td width="15%" >
				<input type="hidden" name="hdnTipo<?php echo $cont;?>" id="hdnTipo<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["Tipo"]));?>" />
				<input name="txtTipo<?php echo $cont;?>" id="txtTipo<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["Tipo"]));?>" 
					onmouseover="javascript:
						clearInterval(Intervalo); 
						Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnTipo<?php echo $cont;?>\')', 250);
					"
					onmouseout="javascript:
						DetieneTexto(Intervalo, this.id, 'hdnTipo<?php echo $cont;?>');
					"
				/>
			</td>
		</tr>
<?php	$total += $rst["Total_I"];
		}while($rst = mssql_fetch_array($stmt));
	}
}else{?>
	<tr><td align="center" style="color:#FF0000"><b>No se han encontrado registros</b></td></tr>
<?php
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnTotal" id="hdnTotal" value="<?php echo number_format($total, 0, '', '.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>