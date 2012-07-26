<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$usuario = $_GET["usuario"];
$accion = $_POST["accion"]!='' ? $_POST["accion"] : $_GET["accion"];
$ocompra = $_GET["ocompra"];
mssql_query("EXEC Bodega..sp_setTMPDetalleDevolucionProveedor 3, '$bodega', '$usuario'", $cnx);
if($ocompra != ''){	
	$stmt = mssql_query("SELECT dblNumero FROM CaratulaOC WHERE dblUltima = $ocompra AND strBodega = '$bodega'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $interno = $rst["dblNumero"];
	mssql_free_result($stmt);

	if($interno != ''){
		mssql_query("EXEC Bodega..sp_getTMPDetalleDevolucionProveedor 0, '$bodega', '$usuario', $interno", $cnx);	
		$stmt = mssql_query("EXEC Bodega..sp_getProveedor 1, NULL, '$bodega', '$interno'", $cnx);
		if($rst=mssql_fetch_array($stmt)){
			$cod_proveedor = $rst["strCodigo"];
			$nomb_proveedor = $rst["strNombre"];
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
function Blur(ctrl, id){
	var stock = document.getElementById('hdnStock' + id).value;
	CambiaColor(ctrl.id, false);
	if(parseFloat(ctrl.value) > parseFloat(stock)){
		alert('La cantidad duevuelta debe ser menor o igual al stock.');
		ctrl.value = stock;
	}else{
		var codigo = document.getElementById('txtCodigo' + id).value;
		var cantidad = document.getElementById('txtCDevuelta' + id).value;
		parent.document.getElementById('transaccion').src = 'valida.php?modulo=1&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + cantidad;
	}
}

function KeyPress(evento, ctrl, id){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var totfil = document.getElementById('totfil').value;
		id++;
		if(parseInt(id) <= parseInt(totfil))
			document.getElementById('chkDevuelve' + id).focus();
		else
			parent.document.getElementById('btnGrabar').focus();
		return true;
	}else{
		switch(document.getElementById('txtUnidad' + id).value){
			case 'Nº':
			case 'JGO':
			case 'LATA':
			case 'N':
			case 'PAR':
			case 'GLOBAL':
			case 'PZA':
				return ValNumeros(evento, ctrl.id, false);
				break;
			default:
				return ValNumeros(evento, ctrl.id, true);
				break;
		}
	}
}

function Load(){
	if('<?php echo $ocompra;?>' != '' && '<?php echo $interno;?>' == ''){
		parent.document.getElementById('txtOCompra').value = '';
		alert('El número de orden de compra no existe.');
	}
	parent.document.getElementById('txtProveedor').value = '<?php echo $nomb_proveedor;?>';
	parent.document.getElementById('hdnProveedor').value = '<?php echo $cod_proveedor;?>';
	parent.document.getElementById('hdnOCompra').value = '<?php echo $interno;?>';
}

function Selecciona(id){
	var codigo = document.getElementById('txtCodigo' + id).value;
	var cantidad = document.getElementById('hdnCantidad' + id).value;
	if(document.getElementById('chkDevuelve' + id).checked){
		document.getElementById('txtCDevuelta' + id).disabled = false;
		document.getElementById('txtCDevuelta' + id).value = cantidad;
		parent.document.getElementById('transaccion').src = 'valida.php?modulo=1&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + cantidad;
		document.getElementById('txtCDevuelta' + id).focus();
		document.getElementById('txtCDevuelta' + id).select();
	}else{
		document.getElementById('txtCDevuelta' + id).disabled = true;
		document.getElementById('txtCDevuelta' + id).value = '0.00';
		parent.document.getElementById('transaccion').src = 'valida.php?modulo=1&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=0';
		document.getElementById('chkDevuelve' + id).focus();
		document.getElementById('chkDevuelve' + id).select();
	}
}
-->
</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onload="javascript: Load();">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$cont=0;
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleDevolucionProveedor 1, '$bodega', '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'" bordercolor="#000000" style="width:20px;'.($rst["dblStock"] < 1 ? 'color:#FF0000' : '').'">';
	echo '<td width="10%"><input name="txtCodigo'.$cont.'" id="txtCodigo'.$cont.'" class="txt-sborde" style="width:99%;text-align:center;background-color:'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'" readonly="true" value="'.trim($rst["strCodigo"]).'"/></td>';
	echo '<td width="49%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	echo '<td width="10%" ><input name="txtUnidad'.$cont.'" id="txtUnidad'.$cont.'" class="txt-sborde" style="width:99%;text-align:center;background-color:'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'" readonly="true" value="'.trim($rst["strUnidad"]).'" /></td>';
	echo '<td width="10%" align="right">';
	echo '<input type="hidden" name="hdnCantidad'.$cont.'" id="hdnCantidad'.$cont.'" value="'.number_format($rst["dblCantidad"], 2, '.', '').'" />';
	echo number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;';
	echo '</td>';
	echo '<td width="10%" align="right">';
	echo '<input type="hidden" name="hdnStock'.$cont.'" id="hdnStock'.$cont.'" value="'.number_format($rst["dblStock"], 2, '.', '').'" />';
	echo number_format($rst["dblStock"], 2, ',', '.').'&nbsp;';
	echo '</td>';
	echo '<td width="1%" align="center">';
	if($rst["dblStock"] > 0){
		echo '<input type="checkbox" name="chkDevuelve'.$cont.'" id="chkDevuelve'.$cont.'" ';
		echo 'onclick="javascript: Selecciona(\''.$cont.'\');"';
		echo '/>';
	}
	echo '</td>';
	echo '<td width="10%" align="right">';
	if($rst["dblStock"] > 0){
		echo '<input type="text" name="txtCDevuelta'.$cont.'" id="txtCDevuelta'.$cont.'" class="txt-plano" style="width:96%;text-align:right" disabled="true" value="'.number_format($rst["dblDevuelve"], 2, '.', '').'" ';
		echo 'onblur="javascript: Blur(this, '.$cont.');"';
		echo 'onfocus="javascript: CambiaColor(this.id, true);"';
		echo 'onkeypress="javascript: return KeyPress(event, this, '.$cont.');"';
		echo 'onkeyup="if(this.value == \'\') this.value = 0;"';
		echo '/>';
	}else
		echo '0,00';
	echo '</td>';
	echo '</tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />

<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
<input type="hidden" name="accion" id="accion"/>
</body>
</html>
<?php
mssql_close($cnx);
?>