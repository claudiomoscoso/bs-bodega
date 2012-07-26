<?php
include '../conexion.inc.php';
include '../autentica.php';

$modulo = $_GET["modulo"];
$error = -1;
if($modulo == 1){
	$solicitud = $_POST["hdnSolicitud"];
	$cargo = $_POST["hdnCargo"];
	$ocompra = $_POST["txtOCompra"];
	$comentario = $_POST["txtComentario"];
	$stmt = mssql_query("SELECT dblNumero FROM Bodega..CaratulaOC WHERE strCargo = '$cargo' AND dblUltima = $ocompra", $cnx);
	if($rst = mssql_fetch_array($stmt)) $ocompra = $rst["dblNumero"];
	mssql_free_result($stmt);
	
	$stmt = mssql_query("EXEC Operaciones..sp_setCambiaEstado 0, '$usuario', $solicitud, '2', $ocompra, '".Reemplaza($comentario)."'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
	mssql_free_result($stmt);
}else{
	$solicitud = $_GET["solicitud"];
	$stmt = mssql_query("EXEC Operaciones..sp_getSolicitudes 'SME', NULL, NULL, '$solicitud'", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$fecha=$rst["dtmFecha"];
		$cargo = $rst["strCargo"];
		$contrato=$rst["Contrato"];
		$solicitante=$rst["strSolicitante"];
		$descripcion=$rst["strDescripcion"];
		$unidad=$rst["strUnidad"];
		$cantidad=$rst["dblCantidad"];
		$desde=$rst["dtmDsd"];
		$hasta=$rst["dtmHst"];
		$observacion=$rst["strObservacion"];
		$estado=$rst["strEstado"];
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Maquinaria y Equipos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	document.getElementById('transaccion').src = 'transaccion.php?cargo=<?php echo $cargo;?>&ocompra=' + ctrl.value;
}

function Load(){
	if('<?php echo $error;?>' == 0){
		parent.Deshabilita(false);
		parent.CierraDialogo('divSolicitud', 'frmSolicitud');
		parent.document.getElementById('pendientes').src = parent.document.getElementById('pendientes').src;
	}
}

function Deshabilita(sw){
	document.getElementById('txtOCompra').disabled=sw;
	document.getElementById('txtComentario').disabled=sw;
	if(document.getElementById('btnVB')) document.getElementById('btnVB').disabled=sw;
	if(document.getElementById('btnRechaza')) document.getElementById('btnRechaza').disabled=sw;
	if(document.getElementById('btnGuardar')) document.getElementById('btnGuardar').disabled=sw;
	if(document.getElementById('btnNull')) document.getElementById('btnNull').disabled=sw;
	if(document.getElementById('btnCerrar')) document.getElementById('btnCerrar').disabled=sw;
}

function Guarda(){
	if(document.getElementById('txtOCompra').value == ''){
		alert('Debe ingresar el número de Orden de Compra.');
	}else{
		document.getElementById('frm').submit();
	}
}

function Visado(estado){
	var solicitud = document.getElementById('hdnSolicitud').value;
	var comentario = document.getElementById('txtComentario').value;
	var desde = document.getElementById('hdnDesde').value;
	Deshabilita(true);
	AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>&estado=' + estado + '&solicitud=' + solicitud + '&desde=' + desde + '&comentario=' + comentario, true);
}
-->
</script>
<body onLoad="javascript: Load();">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?modulo=1';?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0" >
	<tr>
		<td width="8%" align="left"><b>&nbsp;N&deg;Solicitud</b></td>
		<td width="1%"><b>:</b></td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="10%" align="left">&nbsp;<?php echo $solicitud;?></td>
					<td width="1%">&nbsp;</td>
					<td width="6%" align="left"><b>&nbsp;Fecha</b></td>
					<td width="1%"><b>:</b></td>
					<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
					<td width="1%">&nbsp;</td>
					<td width="6%" align="left"><b>&nbsp;Cargo</b></td>
					<td width="1%"><b>:</b></td>
					<td width="36%" >
						<input type="hidden" name="hdnCargo<?php echo $cont;?>" id="hdnCargo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($contrato));?>" />
						<input name="txtCargo<?php echo $cont;?>" id="txtCargo<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="&nbsp;<?php echo htmlentities(trim($contrato));?>" 
							onmouseover="javascript:
								clearInterval(Intervalo); 
								Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnCargo<?php echo $cont;?>\')', 250);
							"
							onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnCargo<?php echo $cont;?>');"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="11%" ><b>&nbsp;N&deg;O.Compra</b></td>
					<td width="1%"><b>:</b></td>
					<td width="15%" align="left">
						<input name="txtOCompra" id="txtOCompra" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left"><b>&nbsp;Solicitante</b></td>
		<td><b>:</b></td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="46%" >
						<input type="hidden" name="hdnSolicitante<?php echo $cont;?>" id="hdnSolicitante<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($solicitante));?>" />
						<input name="txtSolicitante<?php echo $cont;?>" id="txtSolicitante<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="&nbsp;<?php echo htmlentities(trim($solicitante));?>" 
							onmouseover="javascript:
								clearInterval(Intervalo); 
								Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnSolicitante<?php echo $cont;?>\')', 250);
							"
							onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnSolicitante<?php echo $cont;?>');"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="left"><b>&nbsp;Descripci&oacute;n</b></td>
					<td width="1%"><b>:</b></td>
					<td width="47%" >
						<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($descripcion));?>" />
						<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="&nbsp;<?php echo htmlentities(trim($descripcion));?>" 
							onmouseover="javascript:
								clearInterval(Intervalo); 
								Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
							"
							onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td ><b>&nbsp;Unidad</b></td>
		<td><b>:</b></td>
		<td >
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="18%" align="left">&nbsp;<?php echo $unidad;?></td>
					<td width="1%">&nbsp;</td>
					<td width="9%" align="left"><b>&nbsp;Cantidad</b></td>
					<td width="1%"><b>:</b></td>
					<td width="18%" align="left">&nbsp;<?php echo $cantidad;?></td>
					<td width="1%">&nbsp;</td>
					<td width="7%" align="left"><b>&nbsp;Desde</b></td>
					<td width="1%"><b>:</b></td>
					<td width="18%" align="left">&nbsp;<?php echo $desde;?></td>
					<td width="1%">&nbsp;</td>
					<td width="6%" align="left"><b>&nbsp;Hasta</b></td>
					<td width="1%"><b>:</b></td>
					<td width="18%" align="left">&nbsp;<?php echo $hasta;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td ><b>&nbsp;Observaci&oacute;n</b></td>
		<td ><b>:</b></td>
		<td >
			<input type="hidden" name="hdnObservacion<?php echo $cont;?>" id="hdnObservacion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($observacion));?>" />
			<input name="txtObservacion<?php echo $cont;?>" id="txtObservacion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:#ececec" readonly="true" value="&nbsp;<?php echo htmlentities(trim($observacion));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObservacion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnObservacion<?php echo $cont;?>');"
			/>
		</td>
	</tr>
	<tr>
		<td><b>&nbsp;Comentarios</b></td>
		<td><b>:</b></td>
		<td >
			<input name="txtComentario" id="txtComentario" class="txt-plano" style="width:99%"  maxlength="1000"
				onblur="javascript: CambiaColor(this.id, false);"
				onfocus="javascript: CambiaColor(this.id, true);"
			/>
		</td>
	</tr>
	<tr><td style="height:60px" valign="bottom" colspan="3"><hr /></td></tr>
	<tr>
		<td align="right" colspan="3">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />
			
			<input type="hidden" name="hdnSolicitud" id="hdnSolicitud" value="<?php echo $solicitud;?>" />
			<input type="hidden" name="hdnCargo" id="hdnCargo" value="<?php echo $cargo;?>" />
			<input type="hidden" name="hdnDesde" id="hdnDesde" value="<?php echo $desde;?>" />
			<?php
			if($estado=='0' && ($perfil=='admin.contrato' || $perfil=='admin.contrato.m' || $perfil=='admin.cont.op' || $perfil=='informatica')){?>
			<input type="button" name="btnVB" id="btnVB" class="boton" style="width:90px" value="Visto Bueno" 
				onclick="javascript: Visado(1);"
			/>
			<input type="button" name="btnRechaza" id="btnRechaza" class="boton" style="width:90px" value="Rechazar" 
				onclick="javascript: 
					if(confirm('¿Está seguro que desea anular esta solicitud?')) Visado(4)
				"
			/>	
			<?php
			}elseif($perfil=='operaciones' || $perfil == 's.operaciones' || $perfil=='informatica'){?>
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guarda();"
			/>
			<input type="button" name="btnNull" id="btnNull" class="boton" style="width:90px" value="Anular" 
				onclick="javascript: 
					if(confirm('¿Está seguro que desea anular esta solicitud?')) Visado(3)
				"
			/>
			<?php
			}?>
			<input type="button" name="btnCerrar" id="btnCerrar" class="boton" style="width:90px" value="Cancelar" 
				onclick="javascript:
					parent.Deshabilita(false);
					parent.CierraDialogo('divSolicitud','frmSolicitud');
				"
			/>		
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>