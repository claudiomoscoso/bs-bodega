<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$numero = $_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reinformar Orden</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getDetalleMaterialInformado 0, '$usuario', $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="4%" align="center">
		<?php 
		$imagen='';
		switch($rst["strEstado"]){
			case 37001:
				$imagen = 'ok.gif';
				break;
			case 37009:
				$imagen = 'nula.gif';
				break;
			case 37020:
				$imagen = 'resuelta.gif';
				break;
			case 37021:
				$imagen = 'pres_noaprob.gif';
				break;
			case 37022: 
				$imagen = 'no_pres.gif';
				break;
			case 37023:
			case 37024:
				$imagen = 'error.gif';
				break;
		}
		if($imagen != ''){?>
		<img border="0" align="middle" src="../images/<?php echo $imagen;?>" />
		<?php
		}
		?>
		</td>
		<td width="35%" align="left">&nbsp;<?php echo $rst["strNombre"];?></td>
		<td width="15%" align="center"><?php echo trim($rst["CodRoss"]).' ['.trim($rst["strCodigo"]).']';?></td>
		<td width="31%" align="left">
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" />
			<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="12%" align="right"><?php echo number_format($rst["dblCantidad"], 2, '.', '');?>&nbsp;</td>
	</tr>
<?php
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>