<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$usuario = $_GET["usuario"];
$numero = $_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita Gu&iacute;a de Ingreso</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Blur(ctrl, id){
	var codigo = document.getElementById('txtCodigo' + id).value;
	var cantidad = document.getElementById('txtCantidad' + id).value;
	var valor = document.getElementById('txtValor' + id).value;
	
	CambiaColor(ctrl.id, false)
	if(ctrl.id.substr(0, 11) == 'txtCantidad'){
		var cantant = document.getElementById('hdnCantidad' + id).value;
		if(parseFloat(cantidad) != parseFloat(cantant)){
			parent.document.getElementById('valido').src = 'transaccion.php?modulo=1&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + cantidad + '&valor=' + valor + '&cantant=' + cantant + '&id=' + id;
			
		}
	}else if(ctrl.id.substr(0, 8) == 'txtValor'){
		var valant = document.getElementById('hdnValor' + id).value;
		if(parseFloat(valor) != parseFloat(valant)){
			parent.document.getElementById('valido').src = 'transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&codigo=' + codigo + '&cantidad=' + cantidad + '&valor=' + valor;
			document.getElementById('hdnValor' + id).value = document.getElementById('txtValor' + id).value;
		}
	}
}

function KeyPress(evento, ctrl, id){
	var tecla = getCodigoTecla(evento);
	var totfil = document.getElementById('totfil').value, sw=true;
	
	if(tecla == 13){
		if(ctrl.id.substr(0, 11) == 'txtCantidad'){
			document.getElementById('txtValor' + id).focus();
			document.getElementById('txtValor' + id).select();
		}else if(ctrl.id.substr(0, 8) == 'txtValor'){
			id++;
			if(parseInt(id) <= parseInt(totfil)){
				document.getElementById('txtCantidad' + id).focus();
				document.getElementById('txtCantidad' + id).select();
			}else
				parent.document.getElementById('btnGuardar').focus();
		}
	}else{
		if(ctrl.id.substr(0, 11) == 'txtCantidad'){
			var unidad = document.getElementById('txtUnidad' + id).value;
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
		}else if(ctrl.id.substr(0, 8) == 'txtValor')
			return ValNumeros(evento, ctrl.id, false);
	}
}
-->
</script>
<body marginheight="0" marginwidth="0">
<table width="100%" border="0" cellpadding="0" cellspacing="1">
<?php	
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleGuiaIngreso 3, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$ln++;?>
	<tr bgcolor="<?php echo ($ln % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="3%" align="center"><?php echo $ln;?></td>
		<td width="10%" align="center"><input name="txtCodigo<?php echo $ln;?>" id="txtCodigo<?php echo $ln;?>" class="txt-sborde" style="width:99%;text-align:center;background-color:<?php echo ($ln % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="<?php echo trim($rst["strCodigo"]);?>" /></td>
		<td width="55%">&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?></td>
		<td width="10%" align="center"><input name="txtUnidad<?php echo $ln;?>" id="txtUnidad<?php echo $ln;?>" class="txt-sborde" style="width:99%;text-align:center;background-color:<?php echo ($ln % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="<?php echo trim($rst["strUnidad"]);?>" /></td>
	  <td width="10%">
			<input type="hidden" name="hdnCantidad<?php echo $ln;?>" id="hdnCantidad<?php echo $ln;?>" value="<?php echo number_format($rst["dblCIngresada"], 2, '.', '');?>"  />
			<input name="txtCantidad<?php echo $ln;?>" id="txtCantidad<?php echo $ln;?>" class="txt-plano" style="width:98%; text-align:right" value="<?php echo number_format($rst["dblCIngresada"], 2, '.', '');?>" 
				onblur="javascript: Blur(this, <?php echo $ln;?>);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this, <?php echo $ln;?>);"
			/>
		</td>
		<td width="10%">
			<input type="hidden" name="hdnValor<?php echo $ln;?>" id="hdnValor<?php echo $ln;?>" value="<?php echo $rst["dblValor"];?>"  />
			<input name="txtValor<?php echo $ln;?>" id="txtValor<?php echo $ln;?>" class="txt-plano" style="width:98%; text-align:right" value="<?php echo $rst["dblValor"];?>" 
				onblur="javascript: Blur(this, <?php echo $ln;?>);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this, <?php echo $ln;?>);"
			/>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $ln;?>">
</body>
</html>