<?php
include '../autentica.php';
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$estado = $_GET["estado"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Maquinaria y Equipos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
}

function Solicitud(valor){
	self.location.href = 'solicitud.php?<?php echo $parametros;?>&solicitud='+valor;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table width="100%" border="0" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Operaciones..sp_getSolicitudes 'LMS', '$estado', '$contrato'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="8%" align="center">
		<?php
		if($rst["strEstado"]!=3){?>
			<a href="#" onclick="javascript: 
				parent.Deshabilita(true);
				AbreDialogo('divSolicitud','frmSolicitud','solicitud.php<?php echo $parametros;?>&solicitud=<?php echo $rst["dblNumero"];?>', true);
			"
			><?php echo $rst["dblNumero"];?></a>
		<?php
		}else
			echo $rst["dblNumero"];
		?>
		</td>
		<td width="10%" align="center">
		<?php 
		switch($perfil){
			case 'operaciones':
			case 'sg.operaciones':
			case 's.operaciones':
				echo $rst["dtmFHE"] != '' ? formato_fecha($rst["dtmFHE"], false, false) : formato_fecha($rst["dtmFch"], false, false);
				break;
			default:
				echo formato_fecha($rst["dtmFch"], false, false);
		}
		?>
		</td>
		<td width="14%" align="left">
			<input type="hidden" name="hdnCargo<?php echo $cont;?>" id="hdnCargo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDetalle"]));?>" />
			<input name="txtCargo<?php echo $cont;?>" id="txtCargo<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF3FE' : '#FFFFFF';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDetalle"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnCargo<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnCargo<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="14%" align="left">
			<input type="hidden" name="hdnSolicitante<?php echo $cont;?>" id="hdnSolicitante<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strSolicitante"]));?>" />
			<input name="txtSolicitante<?php echo $cont;?>" id="txtSolicitante<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF3FE' : '#FFFFFF';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strSolicitante"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnSolicitante<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnSolicitante<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="14%" align="left">
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?>" />
			<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF3FE' : '#FFFFFF';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="5%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="7%" align="center"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
		<td width="7%" align="center"><?php echo $rst["dtmDesde"];?></td>
		<td width="7%" align="center"><?php echo $rst["dtmHasta"];?></td>
		<td width="10%" align="center">
		<?php
		if($rst["strEstado"]==2){ 
			$observacion="Orden de Compra N&deg;: ".$rst["dblUltima"]."<br><br>Observaci&oacute;n: ".Reemplaza($rst["strObservacion"])."<br><br>Comentarios: ".$rst["strComentarios"];
			$resuelta=$rst["dblUltima"];
		}elseif($rst["strEstado"]==3){
			$observacion='Anulada por: '.$rst["nombre"];
			$resuelta=$rst["Estado"];
		}else
			$resuelta=$rst["Estado"];
		if($rst["strEstado"]==2 || $rst["strEstado"]==3){?>
			<a href="#" style="color:#FF0000" 
				onclick="javascript: 
					parent.Deshabilita(true);
					parent.VerObservacion('visible', '<?php echo $observacion;?>');
				"
			><?php echo $resuelta;?></a>
		<?php
		}else{?>
			<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($resuelta));?>" />
			<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF3FE' : '#FFFFFF';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($resuelta));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');
				"
			/>
		<?php
		}
		?>
		</td>
	</tr>
<?php	
}
mssql_free_result($stmt);
mssql_close($cnx);
?>	
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>