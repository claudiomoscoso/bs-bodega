<?php
include '../conexion.inc.php';
$usuario = $_GET["usuario"];

$stmt = mssql_query("SELECT id, dblFactor FROM Impuesto ORDER BY id", $cnx);
while($rst = mssql_fetch_array($stmt)){ 
	if($rst["id"] == 1) $fact_iva = $rst["dblFactor"]; else $fact_10 = $rst["dblFactor"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra Autom&aacute;tica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl, ln){
	var codigo = document.getElementById('codigo' + ln).value;
	CambiaColor(ctrl.id, false);
	document.getElementById('transaccion').src = 'transaccion.php?modulo=2&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + ctrl.value;
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		if(ctrl.substr(0,5) == 'valor'){
			var ind = ctrl.substr(5);
			if(ind < document.getElementById('totfil').value){
				ind++;
				document.getElementById('valor' + ind).focus();
			}else
				parent.document.getElementById('btnFin').focus();
		}
	}else
		if(ctrl.substr(0,5) == 'valor') return ValNumeros(evento, ctrl, true);
}

function Load(){
	var total = document.getElementById('hdnTotal').value;
	if(parseFloat(total) > 0) Calcular(1);
	parent.document.getElementById('btnFin').disabled = false;
}

function Calcular(fil){
	var tdocumento = parent.document.getElementById('tipodoc').value;
	var totfil = document.getElementById('totfil').value;
	var cant = document.getElementById('cant' + fil).value;
	var valor = document.getElementById('valor' + fil).value;
	var imp = 0, neto = 0; 
	
	if(tdocumento == 1)
		imp = parseFloat('<?php echo $fact_iva;?>');
	else if(tdocumento == 2)
		imp = parseFloat('<?php echo $fact_10;?>');

	document.getElementById('total' + fil).value = Math.round(cant * valor);
	for(i = 1;i <= totfil; i++){ 
		neto = parseInt(neto) + parseInt(document.getElementById('total' + i).value);
	}
	parent.document.getElementById('neto').value = neto;
	parent.document.getElementById('iva').value = Math.round(Math.round((neto * imp) * 100) / 100);
	parent.document.getElementById('totalOC').value = Math.round(((parseInt(parent.document.getElementById('neto').value) + parseFloat(parent.document.getElementById('iva').value)) * 100) / 100);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table width="100%" border="0" cellpadding="0" cellspacing="1" >
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleOrdenCompra 0, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;?>
	<tr bgcolor="<?php echo ($ln % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $ln;?></td>
		<td width="10%"><input name="codigo<?php echo $ln;?>" id="codigo<?php echo $ln;?>" class="txt-sborde" style="width:99%; text-align:center; background-color:<?php echo ($ln % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strCodigo"]);?>" /></td>
		<td width="45%">
			&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>
		</td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" ><input name="cant<?php echo $ln;?>" id="cant<?php echo $ln;?>" class="txt-sborde" style="width:99%; text-align:center; background-color:<?php echo ($ln % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" value="<?php echo $rst["dblCAutorizada"];?>" /></td>
		<td width="10%">
			<input name="valor<?php echo $ln;?>" id="valor<?php echo $ln;?>" class="txt-plano" style="width:97%; text-align:right" maxlength="9" value="<?php echo $rst["dblValor"];?>" 
				onKeyPress="javascript: return KeyPress(event, this.id);" 
				onKeyUp="javascript: 
					if(this.value == '') this.value=0;
					Calcular('<?php echo $ln;?>');
				"
				onfocus="javascript: CambiaColor(this.id, true);" 
				onblur="javascript: Blur(this, <?php echo $ln;?>);"
			>
		</td>
		<td width="10%"><input name="total<?php echo $ln;?>" id="total<?php echo $ln;?>" class="txt-plano" style="width:97%; text-align:right" readonly="true" value="<?php echo ($rst["dblCAutorizada"] * $rst["dblValor"]);?>"></td>
	</tr>
<?php
	$total += $rst["dblCAutorizada"] * $rst["dblValor"];
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $ln;?>">
<input type="hidden" name="hdnTotal" id="hdnTotal" value="<?php echo $total;?>">
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>