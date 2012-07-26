<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Ingreso por Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(bodega){
	document.getElementById('cmbResponsable').disabled = true;
	document.getElementById('transaccion').src = 'busca.php?bodega=' + bodega;
}

function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 88);
}

function Siguiente(){
	if(!resultado.document.getElementById('totfil')) return 0;
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('op' + i).checked){
			var numCC = resultado.document.getElementById('numCC' + i).value;
			break;
		}
	}
	if(numCC)
		self.location.href = 'guia_ingreso.php<?php echo $parametros;?>&numint=' + numCC;
	else
		alert('Debe seleccionar una caja chica.');
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('cmbResponsable').disabled = sw;
	document.getElementById('btnListar').disabled = sw;
	document.getElementById('btnSgte').disabled = sw;
}

function Imprime(){
	if(!resultado.document.getElementById('totfil')) return 0;
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('op' + i).checked){
			var numero = resultado.document.getElementById('numOC' + i).value;
			break;
		}
	}
	
	if(numero){
		document.getElementById('transaccion').focus();
		document.getElementById('transaccion').src = 'imprime_oc.php?numero='+numero;
	}else
		alert('Debe seleccionar una Orden de Compra.');
}

function Listar(){
	Deshabilita(true);
	var bodega = document.getElementById('cmbBodega').value;
	var responsable = document.getElementById('cmbResponsable').value;
	document.getElementById('resultado').src = 'resultado.php?bodega=' + bodega + '&responsable=' + responsable;
}
-->
</script>
<body onload="javascript: Load()">
<div id="divDetalleCC" style="position:absolute; top:100px; left:39%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="2" cellspacing="0">
							<tr><td align="center" class="menu_principal" >Detalle Caja Chica</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="2" cellspacing="0">
							<tr>
								<th width="2%">N&deg;</th>
								<th width="40%" align="left">&nbsp;Descripci&oacute;n</th>
								<th width="18%" >T.Documento</th>
								<th width="13%" align="right">Cantidad&nbsp;</th>
								<th width="13%" align="right">Precio&nbsp;</th>
								<th width="13%" align="right">Total&nbsp;</th>
								<th width="3%">&nbsp;</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmDetalleCC" id="frmDetalleCC" frameborder="0" style="border:thin" scrolling="auto" width="100%" height="135px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left">
			<table border="0" width="80%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="8%" align="left">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%"
							onchange="javascript: Change(this.value);"
						>
						<?php
						$sw=0;
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							if($bodsel == '') $bodsel = $rst["strBodega"];
							echo '<option value="'.$rst["strBodega"].'" '.($rst["strBodega"] == $bodega ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="8%" align="left">&nbsp;Responsable</td>
					<td width="1%">:</td>
					<td width="40%">
						<select name="cmbResponsable" id="cmbResponsable" class="sel-plano" style="width:100%">
							<option value="all">Todos</option>
						<?php
						$bodsel = ($bodsel != '' ? $bodsel : $bodega);
						
						$stmt = mssql_query("EXEC General..sp_getUsuarios 3, '$bodsel'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							$allresp .= trim($rst["strUsuario"]).',';
							echo '<option value="'.$rst["strUsuario"].'">'.$rst["strNombre"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td>
						<input type="button" name="btnListar" id="btnListar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Listar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="25%" align="left">&nbsp;Responsable</th>
					<th width="10%">Caja Chica N&deg;</th>
					<th width="10%">Fecha</th>
					<th width="20%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="10%">Fecha Envio</th>
					<th width="10%" align="left">&nbsp;Estado</th>
					<th width="10%">Seleccionar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><iframe name="resultado" id="resultado" width="100%" frameborder="0" scrolling="yes" marginwidth="0" marginheight="0" src="../blank.html"></iframe></td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<!--<input type="button" name="btnImpr" id="btnImpr" class="boton" style="width:90px" value="Imprimir" 
				onclick="javascript: Imprime();"
			/>-->
			<input type="button" name="btnSgte" id="btnSgte" class="boton" style="width:90px" value="Siguiente >>" 
				onClick="javascript: Siguiente();"
			/>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" frameborder="0" width="100%" height="100px" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>