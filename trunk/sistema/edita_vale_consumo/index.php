<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita Vale de Consumo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmValeConsumo').setAttribute('height', window.innerHeight - 35);
}

function Buscar(){
	if(document.getElementById('txtNumero').value==''){
		alert('Debe ingresar el número de la guía de ingreso');
		document.getElementById('txtNumero').focus();
	}else{
		Deshabilita(true);
		document.getElementById('frmValeConsumo').src = 'vale_consumo.php?usuario=<?php echo $usuario;?>&bodega=' + document.getElementById('cmbBodega').value + '&numero=' + document.getElementById('txtNumero').value
	}
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('txtNumero').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="6%">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="30%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="12%">&nbsp;Vale Consumo N&deg;</td>
					<td width="1%">:</td>
					<td width="13%">
						<input name="txtNumero" id="txtNumero" class="txt-plano" style="width:98%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td>
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td valign="top"><iframe name="frmValeConsumo" id="frmValeConsumo" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="no" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>