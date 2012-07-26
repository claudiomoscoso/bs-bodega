<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
$interno = $_GET["interno"];
mssql_query("EXEC Bodega..sp_setTMPDetalleValeConsumo 0, '$usuario', NULL, $interno", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vales de Consumo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl, ln){
	CambiaColor(ctrl.id, false);
	if(parseFloat(ctrl.value) > 0){
		var cantidad_ant = document.getElementById('hdnCantidad' + ln).value;
		var cantidad_nva = document.getElementById('txtCantidad' + ln).value;
		if(parseFloat(cantidad_nva) != parseFloat(cantidad_ant)){
			var codigo = document.getElementById('txtCodigo' + ln).value;
			var partida = document.getElementById('txtPartida' + ln).value;
			parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&codigo=' + codigo + '&partida=' + partida + '&cantidad=' + cantidad_nva;
		}
	}
}

function KeyPress(evento, ctrl, ln){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		ln++;
		if(parseInt(ln) < parseInt(document.getElementById('hdnFilas').value)){
			document.getElementById('txtCantidad' + ln).focus();
			document.getElementById('txtCantidad' + ln).select();
		}else
			parent.document.getElementById('btnGuardar').focus();
	}else{
		var unidad = document.getElementById('txtUnidad' + ln).value;
		switch(unidad.toUpperCase()){
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
	var filas = document.getElementById('hdnFilas').value;
	for(i = 1; i <= filas; i++) document.getElementById('txtCantidad' + i).disabled = false;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$cont=0;
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleValeConsumo 0, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>">
		<td width="3%" align="center" ><?php echo $cont;?></td>
		<td width="10%"><input name="txtCodigo<?php echo $cont;?>" id="txtCodigo<?php echo $cont;?>" class="txt-sborde" style="width:99%;text-align:center;background-color:<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>" value="<?php echo trim($rst["strCodigo"]);?>" /></td>
		<td width="57%">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="10%" align="center"><input name="txtUnidad<?php echo $cont;?>" id="txtUnidad<?php echo $cont;?>" class="txt-sborde" style="width:99%;text-align:center;background-color:<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>" value="<?php echo trim($rst["strUnidad"]);?>" /></td>
		<td width="10%" align="center"><input name="txtPartida<?php echo $cont;?>" id="txtPartida<?php echo $cont;?>" class="txt-sborde" style="width:99%;text-align:center;background-color:<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>" value="<?php echo trim($rst["strPartida"]);?>" /></td>
		<td width="10%" >
			<input type="hidden" name="hdnCantidad<?php echo $cont;?>" id="hdnCantidad<?php echo $cont;?>" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>" />
			<input name="txtCantidad<?php echo $cont;?>" id="txtCantidad<?php echo $cont;?>" class="txt-plano" style="width:99%;text-align:right" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>"  
				onblur="javascript: Blur(this, <?php echo $cont;?>);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this, <?php echo $cont;?>);"
				onkeyup="javascript: if(this.value == '') this.value = 0;"
			/>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);
?>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
<input type="hidden" name="hdnFilas" id="hdnFilas" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>