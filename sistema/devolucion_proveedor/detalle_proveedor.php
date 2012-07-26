<?php
include '../autentica.php';
include '../conexion.inc.php';

$accion=$_POST["accion"]!='' ? $_POST["accion"] : $_GET["accion"];
$numOC=$_GET["numOC"];
$existe='vacio';

if($numOC!=''){	
	$stmt = mssql_query("SELECT dblNumero FROM CaratulaOC WHERE dblUltima=$numOC AND strBodega='$bodega'", $cnx);
	if($rst=mssql_fetch_array($stmt)){ 
		$interno=$rst["dblNumero"];
		$existe='true';
	}else
		$existe='false';
	mssql_free_result($stmt);

	if($interno!=''){
		mssql_query("EXEC sp_DetalleGOC 'GD', $interno, '$usuario', '$bodega'", $cnx);	
		$stmt = mssql_query("sp_getProveedor '$interno', '$bodega', 'GD'", $cnx);
		if($rst=mssql_fetch_array($stmt)){
			$cod_proveedor=$rst["strCodigo"];
			$nomb_proveedor=$rst["strNombre"];
		}
		mssql_free_result($stmt);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Devolucion a Proveedor</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $existe;?>'=='false'){ 
		parent.document.getElementById('numOC').value='';
		alert('El número de orden de compra no existe.');
	}
	parent.document.getElementById('nomb_proveedor').value='<?php echo $nomb_proveedor;?>';
	parent.document.getElementById('proveedor').value='<?php echo $cod_proveedor;?>';
}

function Selecciona(linea){
	if(document.getElementById('sel_'+linea).checked){
		document.getElementById('cant_devuelta'+linea).disabled=false;
		document.getElementById('cant_devuelta'+linea).focus();
		document.getElementById('cant_devuelta'+linea).select();
	}else{
		document.getElementById('cant_devuelta'+linea).disabled=true;
		document.getElementById('sel_'+linea).focus();
		document.getElementById('sel_'+linea).select();
	}
}
-->
</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onload="javascript: Load();">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$cont=0;
$stmt = mssql_query("EXEC sp_getDetalleTMP '$usuario', 'GD'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>" bordercolor="#000000" style="width:20px; color:<?php echo $rst["dblValor"]<1 ? '#FF0000' : '';?>">
		<td width="10%" align="center">
			<input type="hidden" name="cod_<?php echo $cont;?>" id="cod_<?php echo $cont;?>" value="<?php echo trim($rst["strCodigo"]);?>"/>
			<?php echo $rst["strCodigo"];?>
		</td>
		<td width="49%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" align="right">
			<input type="hidden" name="cantidad<?php echo $cont;?>" id="cantidad<?php echo $cont;?>" value="<?php echo $rst["dblCantidad"];?>" />
			<?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;
		</td>
		<td width="10%" align="right">
			<input type="hidden" name="stock<?php echo $cont;?>" id="stock<?php echo $cont;?>" value="<?php echo $rst["dblValor"];?>" />
			<?php echo number_format($rst["dblValor"],2,',','.');?>&nbsp;
		</td>
		<td width="1%" align="center">
	<?php
	if($rst["dblValor"]>0){?>
			<input type="checkbox" name="sel_<?php echo $cont;?>" id="sel_<?php echo $cont;?>" 
				onclick="javascript: Selecciona('<?php echo $cont;?>');"
			/>
	<?php
	}?>
		</td>
		<td width="10%" align="right">
	<?php
	if($rst["dblValor"]>0){?>
			<input type="text" name="cant_devuelta<?php echo $cont;?>" id="cant_devuelta<?php echo $cont;?>" class="txt-plano" style="width:96%; text-align:right" disabled="true" value="<?php echo number_format($rst["dblCantidad"],2,'.','');?>" 
				onkeypress="javascript: return ValNumeros(event, this.id, true);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onblur="javascript: 
					CambiaColor(this.id, false);
					if(parseFloat(this.value)>parseFloat(document.getElementById('stock<?php echo $cont;?>').value)){
						alert('La cantidad duevuelta debe ser menor o igual al stock.');
						this.value=document.getElementById('stock<?php echo $cont;?>').value;
					}
					
				"
			/>
	<?php
	}else
		echo '0,00';?>
		</td>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);
?>
</table>
<iframe name="valido" id="valido" frameborder="0" width="0%" height="0px"></iframe>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />

<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
<input type="hidden" name="accion" id="accion"/>
</body>
</html>
<?php
mssql_close($cnx);
?>