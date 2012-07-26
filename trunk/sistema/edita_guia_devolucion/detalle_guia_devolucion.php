<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$interno = $_GET["interno"];
mssql_query("EXEC Bodega..sp_getTMPDetalleGuiaDevolucion 1, NULL, '$usuario', $interno", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Despacho</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl, id){
	CambiaColor(ctrl.id, false);
	var valant = document.getElementById('hdnCantidad' + id).value;
	if(parseFloat(ctrl.value) != parseFloat(valant)){
		if(document.getElementById('txtUnidad' + id).value == 'Nº' && parseFloat(document.getElementById('txtCantidad' + id).value) > 1000){
			parent.Deshabilita(true);
			document.getElementById('fila').value = id;
			parent.document.getElementById('txtConfirma').value = '';
			AbreDialogo('divAlerta', 'frmAlerta', '../blank.html', true);
			parent.document.getElementById('txtConfirma').focus();
		}else
			Guarda(id);
	}
}

function KeyPress(evento, ctrl, id){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var totfil = document.getElementById('totfil').value;
		id++;
		if(parseInt(id) > parseInt(totfil))
			parent.document.getElementById('btnGuardar').focus();
		else
			document.getElementById('txtCantidad' + id).focus();
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
	parent.Deshabilita(false);
}

function Guarda(id){
	var codigo = document.getElementById('txtCodigo' + id).value;
	var cantidad = document.getElementById('txtCantidad' + id).value;
	document.getElementById('hdnCantidad' + id).value = cantidad;
	parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + cantidad;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleGuiaDevolucion 2, NULL, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><input name="txtCodigo<?php echo $cont;?>" id="txtCodigo<?php echo $cont;?>" class="txt-sborde" readonly="true" style="width:99%;text-align:center;background-color:<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>" value="<?php echo trim($rst["strCodigo"]);?>"/></td>
		<td width="65%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="10%" align="center"><input name="txtUnidad<?php echo $cont;?>" id="txtUnidad<?php echo $cont;?>" class="txt-sborde" readonly="true" style="width:99%;text-align:center;background-color:<?php echo ($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF');?>" value="<?php echo trim($rst["strUnidad"]);?>"/></td>
		<td width="10%" >
			<input type="hidden" name="hdnCantidad<?php echo $cont;?>" id="hdnCantidad<?php echo $cont;?>" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>" />
			<input name="txtCantidad<?php echo $cont;?>" id="txtCantidad<?php echo $cont;?>" class="txt-plano" style="width:99%;text-align:right" disabled="disabled" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>" 
				onblur="javascript: Blur(this, '<?php echo $cont;?>');"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this, '<?php echo $cont;?>');"
				onkeyup="javascript: if(this.value == '') this.value = 0;"
			/>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="fila" id="fila" />
</body>
</html>
<?php
mssql_close($cnx);
?>