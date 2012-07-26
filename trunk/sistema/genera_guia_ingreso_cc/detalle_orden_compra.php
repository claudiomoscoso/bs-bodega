<?php
include '../conexion.inc.php';
$numOC=$_GET["numOC"];
$usuario=$_GET["usuario"];
mssql_query("EXEC Bodega..sp_DetalleGOC 'GGI', $numOC, '$usuario'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Genera Guia de Ingreso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	var totfil=document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		if(!document.getElementById('chk'+i).disabled){ 
			document.getElementById('chk'+i).focus();
			document.getElementById('chk'+i).select();
			break;
		}
	}
}

function Selecciona(id){
	if(document.getElementById('chk'+id).checked){
		document.getElementById('cant'+id).disabled=false;
		document.getElementById('cant'+id).focus();
	}else{ 
		document.getElementById('cant'+id).disabled=true;
		document.getElementById('chk'+id).focus();
		document.getElementById('chk'+id).select();
	}
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table id="tbl" width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
$fil=0;
$stmt = mssql_query("EXEC Bodega..sp_getDetalleTMP '$usuario', 'GGI'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$fil++;?>
	<tr bgcolor="<?php echo ($fil % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="2%" align="center"><?php echo $fil;?></td>
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td align="left">&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
		<td width="1%">
			<input type="hidden" name="codigo<?php echo $fil;?>" id="codigo<?php echo $fil;?>" value="<?php echo $rst["strCodigo"];?>">
			<input type="checkbox" name="chk<?php echo $fil;?>" id="chk<?php echo $fil;?>" <?php echo ($rst["dblCantidad"]==0) ? 'disabled="disabled"' : '';?>
				onClick="javascript: Selecciona('<?php echo $fil;?>');"
			>
		</td>
		<td width="10%" align="right">
		<?php
			if($rst["dblCantidad"]>0){?>
			<input type="hidden" name="cant_oc<?php echo $fil;?>" id="cant_oc<?php echo $fil;?>" value="<?php echo $rst["dblCantidad"];?>">
			<input name="cant<?php echo $fil;?>" id="cant<?php echo $fil;?>" class="txt-plano" style="width:95%; text-align:right" value="<?php echo $rst["dblCantidad"];?>" disabled="true"
				onKeyPress="javascript: return ValNumeros(event, this.id, true);" 
				onfocus="javascript: CambiaColor(this.id, true);"
				onBlur="javascript: 
					CambiaColor(this.id, false);
					CompCant('cant_oc<?php echo $fil;?>','cant<?php echo $fil;?>');
				"
			>
		<?php
			}else
				echo '0,00 ';
			?>
		</td>
	</tr>
<?php	
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $fil;?>">
</body>
</html>
<?php
mssql_close($cnx);
?>