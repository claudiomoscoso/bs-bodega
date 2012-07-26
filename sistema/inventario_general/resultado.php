<?php
include '../conexion.inc.php';

$cargo = $_GET["cargo"];
$equipo = $_GET["equipo"];
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

function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 0, '$equipo', '$cargo'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo $cont % 2 ==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center" nowrap="nowrap"><?php echo $cont;?></td>
		<td width="10%" align="center">&nbsp;
			<a href="#" title="Editar centro de costo: <?php echo $rst["strCCosto"];?>"
				onclick="javascript:
					parent.Deshabilita(true);
					AbreDialogo('divCCosto', 'frmCCosto', 'centro_costo.php?ccosto=<?php echo $rst["strCCosto"];?>&accion=M', 'true');	
				"
				onmouseover="javascript:
					window.status='Editar centro de costo: <?php echo $rst["strCCosto"];?>...';
					return true;
				"
			><?php echo $rst["strCCosto"];?></a>
		</td>
		<td width="25%" align="left">
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
		<td width="20%" align="left">&nbsp;<?php echo $rst["strMarca"];?></td>
		<td width="20%" align="left"><?php echo $rst["strModelo"];?></td>
		<td width="10%" align="center"><?php echo $rst["dtmRTecnica"];?></td>
		<td width="10%" align="center">
		<?php
		if($rst["strEstado"] != ''){?>
			<a href="#" title="Ver historial de centro de costo <?php echo $rst["strCCosto"];?>..."
				onclick="javascript: 
					parent.document.getElementById('txtCCosto').value='<?php echo $rst["strCCosto"];?>';
					parent.Deshabilita(true);
					AbreDialogo('divReport', 'frmReport', 'reporte.php?ccosto=<?php echo $rst["strCCosto"];?>', true);
					
				"
				onmouseover="javascript: window.status='Ver historial de centro de costo <?php echo $rst["strCCosto"];?>...'; return true;"
			>Ver reporte...&nbsp;</a>
		<?php
		}else
			echo '&nbsp;';
		?>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>
