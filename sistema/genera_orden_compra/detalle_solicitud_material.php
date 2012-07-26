<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$numSM = $_GET["numSM"];
$nguia = $_GET["nguia"];
if($nguia != ''){
	$stmt = mssql_query("SELECT strBodega FROM Despacho WHERE dblNumero = $nguia", $cnx);
	if($rst = mssql_fetch_array($stmt)) $bodega = $rst["strBodega"];
	mssql_free_result($stmt);
}

mssql_query("EXEC sp_setTMPDetalleOrdenCompra 0, '$usuario', '$bodega', $numSM", $cnx);
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
function Load(){
	var totfil=document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		if(!document.getElementById('chk'+i).disabled){ 
			document.getElementById('chk'+i).focus();
			document.getElementById('chk'+i).select();
			break;
		}
	}
	parent.document.getElementById('btnSgte').disabled = false;
}

function Blur(ctrl, ln){
	CambiaColor(ctrl.id, false);
	if(ComparaNumeros(document.getElementById('cant_oc' + ln).value, '<', ctrl.value)){
		alert('El valor ingresado debe ser menor a ' + document.getElementById('cant_oc' + ln).value);
		ctrl.value = 0;
		ctrl.focus();
	}else{
		var codigo = document.getElementById('codigo' + ln).value;
		document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + ctrl.value;
	}
}

function KeyPress(evento, ctrl, id){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var totln = document.getElementById('totfil').value;
		id++;
		if(parseInt(id) < parseInt(totln)){
			document.getElementById('chk' + id).focus();
			document.getElementById('chk' + id).select();
		}else
			parent.document.getElementById('btnSgte').focus();
	}else{
		var unidad = document.getElementById('unidad' + id).value;
		switch(unidad.toUpperCase()){
			case 'GLOBAL':
			case 'JGO':
			case 'LATA':
			case 'N':
			case 'Nº':
			case 'PAR':
			case 'PZA':
				return ValNumeros(evento, ctrl, false);
				break;
			default:
				return ValNumeros(evento, ctrl, true);
				break;
		}
	}
}

function Selecciona(id){
	var codigo = document.getElementById('codigo' + id).value;
	if(document.getElementById('chk' + id).checked){ 
		document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + document.getElementById('hdnCantidad' + id).value;
		document.getElementById('cant' + id).value = document.getElementById('hdnCantidad' + id).value;		
		document.getElementById('cant' + id).disabled = false;
		document.getElementById('cant' + id).focus();
	}else{
		document.getElementById('cant' + id).value = 0;
		document.getElementById('cant' + id).disabled = true;
		document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=0';
		document.getElementById('chk' + id).focus();
		document.getElementById('chk' + id).select();
	}
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table width="100%" border="0" cellpadding="0" cellspacing="1">
<?php
$ln=0;
$stmt = mssql_query("EXEC sp_getTMPDetalleOrdenCompra 0, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;?>
	<tr bgcolor="<?php echo ($ln % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $ln;?>&nbsp;</td>
		<td width="10%" align="center"><input name="codigo<?php echo $ln;?>" id="codigo<?php echo $ln;?>" class="txt-sborde" style="width:99%; text-align:center; background-color:<?php echo ($ln % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strCodigo"]);?>"></td>
		<td width="53%" align="left">&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?></td>
		<td width="10%" align="center"><input name="unidad<?php echo $ln;?>" id="unidad<?php echo $ln;?>" class="txt-sborde" style="width:99%; text-align:center; background-color:<?php echo ($ln % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strUnidad"]);?>" /></td>
		<td width="10%" align="center">
			<input type="hidden" name="hdnCantidad<?php echo $ln;?>" id="hdnCantidad<?php echo $ln;?>" value="<?php echo number_format($rst["dblCSolicitud"], 2, '.', '');?>" />
			<?php echo number_format($rst["dblCSolicitud"], 2, ',', '.');?>
		</td>
		<td width="2%">
			<input type="checkbox" name="chk<?php echo $ln;?>" id="chk<?php echo $ln;?>" <?php echo ($rst["dblCSolicitud"]==0) ? 'disabled="disabled"' : '';?>
				onclick="javascript: Selecciona('<?php echo $ln;?>');"
			>
		</td>
		<td width="10%" align="right">
		<?php
			if($rst["dblCSolicitud"]>0){?>
			<input type="hidden" name="cant_oc<?php echo $ln;?>" id="cant_oc<?php echo $ln;?>" value="<?php echo $rst["dblCSolicitud"];?>">
			<input name="cant<?php echo $ln;?>" id="cant<?php echo $ln;?>" class="txt-plano" style="width:95%; text-align:right" value="0" disabled="true" 
				onblur="javascript: Blur(this, <?php echo $ln;?>);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id, <?php echo $ln;?>);"
				onkeyup="javascript: if(this.value == '') this.value = 0;"
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
<input type="hidden" name="totfil" id="totfil" value="<?php echo $ln;?>">
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>