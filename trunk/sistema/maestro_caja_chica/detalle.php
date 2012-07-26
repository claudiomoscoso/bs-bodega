<?php
include '../conexion.inc.php';
$modulo=$_GET["modulo"];
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$factor=$_GET["factor"];
$rut=$_GET["rut"];
$estado=$_GET["estado"];

switch($modulo){
	case 1:
		$codigo=$_GET["codigo"];
		$documento=$_GET["documento"];
		$numdoc=$_GET["numdoc"];
		mssql_query("EXEC Bodega..sp_setTMPDetalleCajaChica 4, '$bodega', '$usuario', '$codigo', $documento, $numdoc", $cnx);
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

// Eventos
function KeyPress(evento, idCtrl){
	var tecla = getCodigoTecla(evento), totfil = document.getElementById('hdnTotFil').value, ind = 0;
	
	if(tecla == 13){
		if(idCtrl.substring(0, 11)=='txtCantidad'){
			ind = idCtrl.substring(11, idCtrl.length);
			document.getElementById('txtPrecio' + ind).focus();
			document.getElementById('txtPrecio' + ind).select();
		}else if(idCtrl.substring(0, 9)=='txtPrecio'){
			ind = idCtrl.substring(9, idCtrl.length);
			document.getElementById('txtTotal' + ind).focus();
			document.getElementById('txtTotal' + ind).select();
		}else if(idCtrl.substring(0, 8)=='txtTotal'){
			ind = idCtrl.substring(8, idCtrl.length); ind++;
			if(ind <= parseInt(totfil)){
				document.getElementById('txtCantidad' + ind).focus();
				document.getElementById('txtCantidad' + ind).select();
			}else
				parent.document.getElementById('btnGuardar').focus();
		}
	}else{
		if(idCtrl.substring(0, 11)=='txtCantidad' || idCtrl.substring(0, 9)=='txtPrecio' || idCtrl.substring(0, 8)=='txtTotal')
			return ValNumeros(evento, idCtrl, false)
	}
}

function Load(){
	parent.document.getElementById('txtTotGnral').value = document.getElementById('hdnTotGral').value;
}

// Funciones GET
function getPrecioUnitario(ind){
	var total = document.getElementById('txtTotal' + ind).value
	var cantidad = document.getElementById('txtCantidad' + ind).value;
	var precio =document.getElementById('txtPrecio' + ind);
	if(cantidad !=0 ){
		if(document.getElementById('hdnDocumento' + ind).value == 0)
			precio.value = Math.round((parseInt(total) / parseInt(cantidad)) / parseFloat('<?php echo $factor;?>'));
		else
			precio.value = Math.round(parseInt(total) / parseInt(cantidad));
	}
	getTotalGral();
}

function getTotalGral(){
	var totfil = document.getElementById('hdnTotFil').value;
	var totgral = 0;
	for(i=1; i<=totfil; i++){
		totgral+=parseInt(document.getElementById('txtTotal' + i).value);
	}
	parent.document.getElementById('txtTotGnral').value = totgral;
}

function getTotalLinea(ind){
	var precio = document.getElementById('txtPrecio' + ind).value
	var cantidad = document.getElementById('txtCantidad' + ind).value;
	var total =document.getElementById('txtTotal' + ind);
	if(document.getElementById('hdnDocumento' + ind).value == 0)
		total.value = Math.round((parseFloat(cantidad) * parseInt(precio)) * parseFloat('<?php echo $factor;?>'));
	else
		total.value = Math.round(parseFloat(cantidad) * parseInt(precio));
	getTotalGral();
}

// Funciones SET
function setActualizaItem(ind){
	var nva_cantidad = document.getElementById('txtCantidad' + ind);
	var nva_precio = document.getElementById('txtPrecio' + ind);
	var nva_total = document.getElementById('txtTotal' + ind);

	var ant_cantidad = document.getElementById('hdnCantidad' + ind);
	var ant_precio = document.getElementById('hdnPrecio' + ind);
	var ant_total = document.getElementById('hdnTotal' + ind);
	var estado = 'NULL';
	if(document.getElementById('cmbRechazo' + ind)){
		var nva_estado = document.getElementById('cmbRechazo' + ind);
		var ant_estado = document.getElementById('hdnRechazo' + ind);
		var estado = '', sw = 0;
		if(ant_estado.value == nva_estado.value){
			estado = ant_estado.value; 
		}else{ 
			estado = nva_estado.value;
			sw = 1;
		}
	}
	
	if((parseFloat(ant_cantidad.value) != parseFloat(nva_cantidad.value)) || (parseInt(ant_precio.value) != parseInt(nva_precio.value)) || (parseInt(ant_total.value) != parseInt(nva_total.value)) || (sw == 1)){
		document.getElementById('transaccion').src='transaccion.php?bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&rut=<?php echo $rut;?>&factor=<?php echo $factor;?>&codigo='+document.getElementById('txtCodigo' + ind).value+
		'&tipodoc='+document.getElementById('hdnDocumento' + ind).value+'&numdoc='+document.getElementById('txtNumDoc' + ind).value+'&cantidad='+nva_cantidad.value+'&precio='+nva_precio.value+
		'&estado='+estado+'&cantant='+ant_cantidad.value+'&totant='+ant_total.value+'&ind='+ind;
	}
}

function setBloqueaDetalle(){
	var totfil = document.getElementById('hdnTotFil').value;
	for(i=1; i<=totfil; i++){
		document.getElementById('txtCantidad' + i).readOnly=true;
		document.getElementById('txtPrecio' + i).readOnly=true;
		document.getElementById('txtTotal' + i).readOnly=true;
		if(document.getElementById('imgBorrar' + i)) document.getElementById('imgBorrar' + i).style.display = 'none';
	}
	
}

function setEliminaItem(codigo, tipodoc, numdoc){
	if(confirm('¿Está seguro que desea eliminar este ítem?'))
		self.location.href='<?php echo $_SERVER['PHP_SELF'];?>?modulo=1&bodega=<?php echo $bodega;?>&usuario=<?php echo $usuario;?>&&numero=<?php echo $numero;?>&codigo='+codigo+'&documento='+tipodoc+'&numdoc='+numdoc+'&factor=<?php echo $factor;?>&rut=<?php echo $rut;?>';
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleCajaChica 2, '$bodega', '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#EBF1FF' : '#FFFFFF' ?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><input name="txtCodigo<?php echo $cont;?>" id="txtCodigo<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF1FF' : '#FFFFFF' ?>; text-align:center" readonly="true" value="<?php echo $rst["strCodigo"];?>" /></td>
		<td width="<?php echo $estado==1 || $estado==3 ? '15%' : '22%';?>" align="left">
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" />
			<input name="txtDescipcion<?php echo $cont;?>" id="txtDescipcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF1FF' : '#FFFFFF' ;?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>		</td>
		<td width="10%" align="left">&nbsp;
			<input type="hidden" name="hdnDocumento<?php echo $cont;?>" id="hdnDocumento<?php echo $cont;?>" value="<?php echo $rst["dblTipoDoc"];?>" />
			<?php echo $rst["strTipoDoc"];?>		
		</td>
		<td width="10%" align="center"><input name="txtNumDoc<?php echo $cont;?>" id="txtNumDoc<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#EBF1FF' : '#FFFFFF' ?>; text-align:center" readonly="true" value="<?php echo $rst["dblNumDoc"];?>" /></td>
		<td width="10%" align="center"><?php echo $rst["dblUltima"];?></td>
		<td width="10%" align="right">
			<input type="hidden" name="hdnCantidad<?php echo $cont;?>" id="hdnCantidad<?php echo $cont;?>" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>" />
			<input name="txtCantidad<?php echo $cont;?>" id="txtCantidad<?php echo $cont;?>" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($rst["dblCantidad"], 2, '.', '');?>" 
				onblur="javascript: 
					CambiaColor(this.id, false);
					if(this.value=='') this.value = 0;
					setActualizaItem('<?php echo $cont;?>');
				"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id);"
				onkeyup="javascript: getTotalLinea('<?php echo $cont;?>');"
			/>
		</td>
		<td width="10%" align="right">
			<input type="hidden" name="hdnPrecio<?php echo $cont;?>" id="hdnPrecio<?php echo $cont;?>" value="<?php echo number_format($rst["dblPrecio"], 0, '', '');?>"  />
			<input name="txtPrecio<?php echo $cont;?>" id="txtPrecio<?php echo $cont;?>" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($rst["dblPrecio"], 0, '', '');?>" 
				onblur="javascript: 
					CambiaColor(this.id, false);
					if(this.value=='') this.value = 0;
					setActualizaItem('<?php echo $cont;?>');
				"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id);"
				onkeyup="javascript: getTotalLinea('<?php echo $cont;?>');"
			/>		
		</td>
		<td width="10%" align="right">
			<input type="hidden" name="hdnTotal<?php echo $cont;?>" id="hdnTotal<?php echo $cont;?>" value="<?php echo number_format($rst["dblTotal"], 0, '', '');?>"  />
			<input name="txtTotal<?php echo $cont;?>" id="txtTotal<?php echo $cont;?>" class="txt-plano" style="width:99%; text-align:right" value="<?php echo number_format($rst["dblTotal"], 0, '', '');?>" 
				onblur="javascript: 
					CambiaColor(this.id, false);
					if(this.value=='') this.value = 0;
					setActualizaItem('<?php echo $cont;?>');
				"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id);"
				onkeyup="javascript: getPrecioUnitario('<?php echo $cont;?>');"
			/>		
		</td>
	<?php
	if($estado==1){?>
		<td width="17%">
			<input type="hidden" name="hdnRechazo<?php echo $cont;?>" id="hdnRechazo<?php echo $cont;?>" value="<?php echo $rst["dblRechazo"];?>"  />
			<select name="cmbRechazo<?php echo $cont;?>" id="cmbRechazo<?php echo $cont;?>" class="sel-plano" style="width:100%"
				onblur="javascript: setActualizaItem('<?php echo $cont;?>');"
			>
				<option value="">--</option>
			<?php
			$stmt2=mssql_query("EXEC General..sp_getRechazos 0", $cnx);
			while($rst2=mssql_fetch_array($stmt2)){
				echo '<option value="'.$rst2["strCodigo"].'" '.(trim($rst["dblRechazo"]) == trim($rst2["strCodigo"]) ? 'selected' : '').'>'.$rst2["strCodigo"].' - '.$rst2["strDetalle"].'</option>';
			}
			mssql_free_result($stmt2);
			?>
			</select>
		</td>
	<?php
	}elseif($estado == 3){?>
		<td width="17%" align="center"><?php echo $rst["strDescEstado"];?></td>
	<?php
	}else{?>
		<td width="2%" align="center">
			<a href="#" title="Elimina l&iacute;nea <?php echo $cont;?>, &iacute;tem: <?php echo htmlentities($rst["strDescripcion"]);?>"
				onclick="javascript: setEliminaItem('<?php echo $rst["strCodigo"];?>', '<?php echo $rst["strTipoDoc"] == 'Factura' ? 0 : 1;?>', '<?php echo $rst["dblNumDoc"];?>');"
				onmouseover="javascript: window.status='Elimina línea <?php echo $cont;?>, ítem: <?php echo htmlentities($rst["strDescripcion"]);?>'; return true;"
			><img id="imgBorrar<?php echo $cont;?>" border="0" align="middle" src="../images/borrar0.gif" /></a>		
		</td>
	<?php
	}?>
	</tr>
<?php
	if(trim($rst["dblRechazo"])=='') $totgral+=$rst["dblTotal"];
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="hdnTotGral" id="hdnTotGral" value="<?php echo number_format($totgral, 0, '', '');?>" />
<input type="hidden" name="hdnTotFil" id="hdnTotFil" value="<?php echo $cont;?>" />
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>