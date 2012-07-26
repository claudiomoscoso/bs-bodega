<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita Reportes Diarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmReporte').setAttribute('height', window.innerHeight - 35);
}

function Buscar(){
	var numero = document.getElementById('txtNumero').value;
	if(numero == '')
		alert('Debe ingresar el número del reporte diario a editar.');
	else{
		//Deshabilita(true)
		document.getElementById('frmReporte').src = 'reporte_diario.php?numero=' + numero
	}
}
-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<!--
					<td width="3%">&nbsp;Obra</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbObras" id="cmbObras" class="sel-plano" style="width:100%">
						<?php
						/*$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strCargo"].'</option>';
						}
						mssql_free_result($stmt);*/
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>-->
					<td width="5%">&nbsp;N&uacute;mero</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtNumero" id="txtNumero" class="txt-plano" style="width:99%;text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td align="center"><iframe name="frmReporte" id="frmReporte" frameborder="0" width="99%" scrolling="no" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>