<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
$numero = $_GET["numero"];
$codigo = $_GET["codigo"];
$tipo = $_GET["tipo"];
$contrato = $_GET["contrato"];
$cerrada = $_GET["cerrada"];

if($modulo == 0)
	mssql_query("EXEC Orden..sp_getTMPDetalleOrdenTrabajo 0, '$usuario', $numero", $cnx);
elseif($modulo == 1){
	$movil = $_GET["movil"];
	$cantidad = $_GET["cantidad"];

	$stmt = mssql_query("EXEC Orden..sp_setTMPDetalleOrdenTrabajo 1, '$usuario', '$codigo', '$movil', $cantidad, NULL, NULL, $tipo", $cnx);
	if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
	mssql_free_result($stmt);
}elseif($modulo == 2)
	mssql_query("EXEC Orden..sp_setTMPDetalleOrdenTrabajo 2, '$usuario', '$codigo'", $cnx);

if($perfil=='cobranza')
	$sql = "EXEC General..sp_getMoviles 6, NULL, '$contrato'";
else
	$sql = "EXEC General..sp_getMoviles 4, NULL, '$numero'";
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
var Intervalo = 0;

function KeyPress(evento, ctrl, ind){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl){
			case 'cmbMovil' + ind:
				document.getElementById('txtCantidad' + ind).focus();
				document.getElementById('txtCantidad' + ind).select();
				break;
			case 'txtCantidad' + ind:
				var totfil = document.getElementById('totfil').value;
				ind++;
				if(parseInt(ind)<=parseInt(totfil)) document.getElementById('cmbMovil' + ind).focus();
				break;
		}
	}else{
		if(ctrl = 'txtCantidad' + ind) return ValNumeros(evento, ctrl, true);
	}
}

function Load(){
	if(parseInt('<?php echo $cerrada;?>') != 0) parent.setSoloLectura();	
	if(parseInt('<?php echo $modulo;?>') == 1){
		if(parseInt('<?php echo $error;?>') == 1)
			alert('El movil no tiene stock para este material.');
		else if(parseInt('<?php echo $error;?>') == 2)
			alert('El movil no registra la cantidad solicitada.');
	}
}

function LostFocus(ctrl, ind){
	var codigo = document.getElementById('hdnCodigo' + ind).value;
	var movil_ant = document.getElementById('hdnMovil' + ind).value;
	var movil_nvo = document.getElementById('cmbMovil' + ind).value;
	var cantidad_ant = document.getElementById('hdnCantidad' + ind).value;
	var cantidad_nva = document.getElementById('txtCantidad' + ind).value;
	var unidad = document.getElementById('hdnUnidad' + ind).value;
	
	switch(ctrl){
		case 'cmbMovil' + ind:
			if(movil_nvo != movil_ant){
				var codigo = document.getElementById('hdnCodigo' + ind).value;
				var cantidad = document.getElementById('txtCantidad' + ind).value;
				document.getElementById('actualiza').src='transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&ind='+ind+'&material='+codigo+'&movil='+movil_nvo+'&cantidad='+cantidad_nva+'&movil_ant='+movil_ant+'&correlativo=<?php echo $numero;?>&tipo=<?php echo $tipo;?>';
			}
			break;
		case 'txtCantidad' + ind:
			if(document.getElementById(ctrl).value == '' || parseFloat(document.getElementById(ctrl).value) == 0){
				document.getElementById(ctrl).value = document.getElementById('hdnCantidad' + ind).value;
				alert('Debe ingresar la cantidad');
			}else{
				if(cantidad_ant != cantidad_nva){
					if(unidad == 'Nº') document.getElementById(ctrl).value = parseInt(cantidad_nva);
					document.getElementById('actualiza').src='transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&ind='+ind+'&material='+codigo+'&movil='+movil_nvo+'&cantidad='+cantidad_nva+'&movil_ant='+movil_ant+'&correlativo=<?php echo $numero;?>&tipo=<?php echo $tipo;?>';
				}
			}
			break;
	}
}

function setElimina(material){
	if(confirm('¿Está seguro que desea eliminar este ítem?'))
		self.location.href='detalle_orden_trabajo.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&modulo=2&numero=<?php echo $numero;?>&contrato=<?php echo $contrato;?>&codigo='+material+'&cerrada=<?php echo $cerrada;?>';
}

function setHabilita(ind, sel){
	if(sel){
		document.getElementById('txtCantidad' + ind).disabled = false;
		document.getElementById('txtCantidad' + ind).focus();
		document.getElementById('txtCantidad' + ind).select();
	}else{
		document.getElementById('txtCantidad' + ind).disabled = true;
		document.getElementById('txtCantidad' + ind).value = document.getElementById('hdnCantidad' + ind).value
	}
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getTMPDetalleOrdenTrabajo 1, '$usuario', NULL, '$contrato'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="40%" align="left">
		<?php
		if($rst["strEstado"] == 37001 || $rst["strEstado"] == 37009)
			echo $rst["strNombre"];
		else{?>
			<input type="hidden" id="hdnMovil<?php echo $cont;?>" value="<?php echo trim($rst["strMovil"]);?>" />
			<select id="cmbMovil<?php echo $cont;?>" class="sel-plano" style="width:100%"
				onblur="javascript: return LostFocus(this.id, <?php echo $cont;?>);"
				onkeypress="javascript: return KeyPress(event, this.id, <?php echo $cont;?>);"
			>
		<?php
			$stmt2 = mssql_query($sql, $cnx);
			while($rst2 = mssql_fetch_array($stmt2)){
				echo '<option value="'.trim($rst2["strMovil"]).'" '.(trim($rst2["strMovil"]) == trim($rst["strMovil"]) ? 'selected' : '').'>['.trim($rst2["strMovil"]).']'.$rst2["strNombre"].'</option>';
			}
			mssql_free_result($stmt2);?>
			</select>
		<?php
		}?>
		</td>
		<td width="10%" align="center">
			<input type="hidden" id="hdnCodigo<?php echo $cont;?>" value="<?php echo $rst["strCodigo"];?>" />
			<input type="hidden" id="hdnUnidad<?php echo $cont;?>" value="<?php echo $rst["strUnidad"];?>"  />
			<input type="hidden" id="hdnStock<?php echo $cont;?>" value="<?php echo number_format($rst["dblStock"], 2, '.', '');?>"  />
			<?php echo $rst["strCodigo"];?>
		</td>
		<td width="33%" align="left">
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" />
			<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%">
		<?php
		if($rst["strEstado"] == 37001 || $rst["strEstado"] == 37009)
			echo number_format($rst["dblCantidad"], 2, '.', '');
		else{?>
			<input type="hidden" id="hdnCantidad<?php echo $cont;?>" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>"  />
			<input id="txtCantidad<?php echo $cont;?>" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>" 
				onblur="javascript: LostFocus(this.id, <?php echo $cont;?>)"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id, <?php echo $cont;?>);"
			/>
		<?php
		}?>
		</td>
		<td width="2%" align="center">
			<a href="#" title="Borra &iacute;tem: <?php echo htmlentities($rst["strDescripcion"]);?>"
				onclick="javascript: setElimina('<?php echo trim($rst["strCodigo"]);?>')"
				onmouseover="javascript: window.status='Borra ítem: <?php echo htmlentities($rst["strDescripcion"]);?>'; return true;"
			><img id="img<?php echo $cont;?>" src="../images/borrar1.gif" border="0" align="middle" <?php echo ($rst["strEstado"] == 37001 || $rst["strEstado"] == 37009) ? 'style="visibility:hidden"' : '';?>/></a>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" id="totfil" name="totfil" value="<?php echo $cont;?>" />
<iframe id="actualiza" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>