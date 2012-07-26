<?php
include '../autentica.php';
include '../conexion.inc.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informar Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
//EVENTOS
function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 88);
}

function Siguiente(){
	if(!resultado.document.getElementById('totfil')) return 0;
	setDeshabilita(true);
	var totfil = resultado.document.getElementById('totfil').value;
	var sw = false;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chk' + i).checked){
			numot = resultado.document.getElementById('hdnNumOT' + i).value;
			break;
		}
	}
	if(numot) self.location.href='orden_trabajo.php<?php echo $parametros;?>&numero=' + numot;
	setDeshabilita(false);
}

//FUNCIONES GET
function getBuscar(){
	setDeshabilita(true);
	var movil = '';
	if(document.getElementById('cmbMovil')){
		movil = document.getElementById('cmbMovil').value;
		if(movil == 'none' && document.getElementById('txtNumOT').value == ''){
			alert('Debe selecciona un movil o ingresar el n�mero de la orden de trabajo.');
			setDeshabilita(false);
			return false;
		}
	}else if(document.getElementById('txtNumOT').value == ''){
		alert('Debe ingresar el n�mero de orden.');
		setDeshabilita(false);
		return false;
	}
	document.getElementById('resultado').src='resultado.php<?php echo $parametros;?>&contrato=' + document.getElementById('cmbContrato').value + '&movil=' + movil + '&numot=' + document.getElementById('txtNumOT').value;
}

//FUNCIONES SET
function setCargaMovil(valor){
	if(document.getElementById('cmbMovil')) document.getElementById('transaccion').src='transaccion.php?modulo=0&contrato='+valor;
}

function setDeshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	if(document.getElementById('cmbMovil')) document.getElementById('cmbMovil').disabled = sw;
	document.getElementById('txtNumOT').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnSiguiente').disabled = sw;
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Contrato</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%"
							onchange="javascript: setCargaMovil(this.value);"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							if($bodsel == '') $bodsel = $rst["strContrato"];
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<?php
					if($perfil == 'coordinacion' || $perfil == 'j.coordinacion' || $perfil == 'j.coord.nomaterial' || $perfil == 'informatica'){?>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Movil</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbMovil" id="cmbMovil" class="sel-plano" style="width:100%">
							<option value="none">&nbsp;</option>
						<?php
						$stmt = mssql_query("EXEC General..sp_getMoviles 6, NULL, '$bodsel'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.trim($rst["strMovil"]).'">['.trim($rst["strMovil"]).'] '.$rst["strNombre"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<?php
					}?>
					<td width="1%">&nbsp;</td>
					<td width="7%">&nbsp;N&deg; Orden</td>
					<td width="1%">:</td>
					<td width="10%">
						<input id="txtNumOT" class="txt-plano" style="width: 99%; text-align:center"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar();"
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
					<th width="8%">Fch.Orden</th>
					<th width="10%">N&deg; Orden</th>
					<th width="15%" align="left">&nbsp;Contrato</th>
					<th width="15%" align="left">&nbsp;Movil</th>
					<th width="8%">Fch.Vcto.</th>
					<th width="29%" align="left">&nbsp;Motivo</th>
					<th width="10%">Seleccionar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnSiguiente" id="btnSiguiente" class="boton" style="width:90px" value="Siguiente &gt;&gt;" 
				onclick="javascript: Siguiente();"
			/>
		</td>
	</tr>
</table>
<iframe id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
