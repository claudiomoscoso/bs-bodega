<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$cargo = $_GET["cargo"];
mssql_query("Bodega..sp_getTMPFacturaInterna 0, '$usuario', '$cargo'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Factura Interna</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('divResultado').style.height = (window.innerHeight - 55) + 'px';
}

function Aceptar(){
	var resultado = parent.document.getElementById('divDetalle');
	var ajax = new XMLHttpRequest();
	
	resultado.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
	parent.Deshabilita(false);
	parent.CierraDialogo('divBuscador', 'frmBuscador');
	ajax.open('GET', 'transaccion.php?modulo=2&usuario=<?php echo $usuario;?>', true);
	ajax.onreadystatechange = function(){
		if(ajax.readyState == 4){
			resultado.innerHTML = ajax.responseText;
			parent.document.getElementById('txtTFactura').value = parent.document.getElementById('hdnTotal').value;
		}
	}
	ajax.send(null);
}

function Selecciona(numero, codigo, sw){
	var totfil = document.getElementById('totfil').value;
	var transaccion = document.getElementById('divTransaccion');
	var ajax = new XMLHttpRequest();
	if(numero == 'none'){
		for(i = 1; i <= totfil; i++) document.getElementById('chkElige' + i).checked = sw;
		ajax.open('GET', 'transaccion.php?modulo=0&usuario=<?php echo $usuario;?>&sw=' + (sw ? 1 : 0), true);
	}else
		ajax.open('GET', 'transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&numero=' + numero + '&codigo=' + codigo + '&sw=' + (sw ? 1 : 0), true);
	
	ajax.onreadystatechange = function(){
		if(ajax.readyState == 4) transaccion.innerHTML = ajax.responseText;
	}
	ajax.send(null);
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="2%">
						<input type="checkbox" name="chkAll" id="chkAll" 
							onclick="javascript: Selecciona('none', 'none', this.checked);"
						/>
					</th>
					<th width="51%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="6%" >C.Costo</th>
					<th width="12%">F.Inicio</th>
					<th width="12%">F.T&eacute;rmino</th>
					<th width="10%" align="right">Total&nbsp;</th>
					<th width="3%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="divResultado" style="height:100px; overflow:scroll; position:relative; width:100%">
				<table border="0" width="100%" cellpadding="0" cellspacing="1">
				<?php
				$stmt = mssql_query("Bodega..sp_getTMPFacturaInterna 1, '$usuario'", $cnx);
				if($rst = mssql_fetch_array($stmt)){
					do{
						if($ocompra != $rst["dblUltima"]){
							echo '<tr style="background-image:url(../images/borde_menu.gif)"><td colspan="7"><b>&nbsp;O. Compra Nº'.$rst["dblUltima"].'</b></td></tr>';
							$ocompra = $rst["dblUltima"];
						}
						$cont++;
						echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
						echo '<td width="3%" align="center">'.$cont.'</td>';
						echo '<td width="2%" align="center"><input type="checkbox" name="chkElige'.$cont.'" id="chkElige'.$cont.'" ';
						echo 'onclick="javascript: Selecciona(\''.$rst["dblNumero"].'\', \''.$rst["strCodigo"].'\', this.checked)"';
						echo ' >';
						echo '</td>';
						echo '<td width="50%">&nbsp;'.$rst["strDescripcion"].'</td>';
						echo '<td width="9%" align="center">'.$rst["strCCosto"].'</td>';
						echo '<td width="12%" align="center">'.$rst["dtmFInicio"].'</td>';
						echo '<td width="12%" align="center">'.$rst["dtmFTermino"].'</td>';
						echo '<td width="10%" align="right">'.number_format($rst["dblTotal"], 0, '', '.').'&nbsp;</td>';
						echo '</tr>';
					}while($rst = mssql_fetch_array($stmt));
				}
				mssql_free_result($stmt);
				?>
				</table>
			</div>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<div id="divTransaccion" style="display:none"></div>
			<input type="button" name="btnAceptar" id="btnAceptar" class="boton" style="width:80px" value="Aceptar" 
				onclick="javascript: Aceptar();"
			/>
		</td>
	</tr>
</table>

<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>