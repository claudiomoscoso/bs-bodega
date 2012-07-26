<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];

if($modulo == 0){
	$bodega = $_GET["bodega"];
	mssql_query("EXEC Bodega..sp_getTMPTerminoBodega 0, '$usuario', '$bodega'", $cnx);
}elseif($modulo == 1){
	$codigo = $_GET["codigo"];
	$valor = $_GET["valor"];
	$observacion = $_GET["observacion"];
	
	$stmt = mssql_query("EXEC Bodega..sp_setTMPTerminoBodega 3, '$usuario', '$codigo', '$valor', '$observacion'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		if($rst["dblError"] == 1){?>
		<script language="javascript">
		<!--
			alert('El material que intenta agregar ya existe.');
		-->
		</script>
	<?php
		}
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>T&eacute;rmino Bodega</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
}

function KeyPress(evento, ctrl, id){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		if(ctrl.substring(0, 11) == 'txtDevolver'){
			document.getElementById('txtObservacion' + id).focus();
			document.getElementById('txtObservacion' + id).select();
		}else if(ctrl.substring(0, 14) == 'txtObservacion'){
			var totfil = document.getElementById('hdnTotLn').value;
			var sgte = parseInt(id) + 1;
			if(sgte < parseInt(totfil)) document.getElementById('chkDevolver' + sgte).focus();
		}
	}else{
		if(ctrl.substring(0, 11) == 'txtDevolver') return ValNumeros(evento, ctrl, true);
	}
}

function setActiva(id){
	var estado = document.getElementById('chkDevolver' + id).checked;	
	document.getElementById('txtDevolver' + id).disabled = !estado;
	document.getElementById('txtObservacion' + id).disabled = !estado;
	if(estado){
		document.getElementById('txtDevolver' + id).focus();
		document.getElementById('txtDevolver' + id).select();
	}else{
		document.getElementById('txtDevolver' + id).value = 0;
		document.getElementById('txtObservacion' + id).value = '';
		document.getElementById('transaccion').src = 'transaccion.php?usuario=<?php echo $usuario;?>&codigo='+document.getElementById('hdnCodigo' + id).value+'&modulo=2';
	}
}

function Blur(ctrl, id){
	var sql = 'transaccion.php?usuario=<?php echo $usuario;?>&codigo='+document.getElementById('hdnCodigo' + id).value;
	CambiaColor(ctrl, false);
	if(ctrl.substring(0, 11) == 'txtDevolver'){
		if(document.getElementById('hdnDevolver' + id).value != document.getElementById(ctrl).value){
			sql+='&modulo=0&valor='+document.getElementById(ctrl).value;
			document.getElementById('transaccion').src = sql;
			document.getElementById('hdnDevolver' + id).value = document.getElementById(ctrl).value;
		}
	}else if(ctrl.substring(0, 14) == 'txtObservacion'){
		if(document.getElementById('hdnObservacion' + id).value != document.getElementById(ctrl).value){
			sql+='&modulo=1&valor='+document.getElementById(ctrl).value;
			document.getElementById('transaccion').src = sql;
			document.getElementById('hdnObservacion' + id).value = document.getElementById(ctrl).value;
		}
	}
}
-->
</script>
<body marginheight="0" marginwidth="0" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPTerminoBodega 1, '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 ==0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" ><input name="hdnCodigo<?php echo $cont;?>" id="hdnCodigo<?php echo $cont;?>" class="txt-sborde" style="width:100%; text-align:center; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" value="<?php echo trim($rst["strCodigo"]);?>" /></td>
		<td width="31%" >
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="<?php echo htmlentities($rst["strDescripcion"]);?>" />
			<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="<?php echo htmlentities($rst["strDescripcion"]);?>" 
				onmouseover="javascript: 
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');"
			/>
		</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblStock"], 2, ',','.');?>&nbsp;</td>
		<td width="2%" align="center">
			<input type="checkbox" name="chkDevolver<?php echo $cont;?>" id="chkDevolver<?php echo $cont;?>" <?php echo $rst["dblDevolucion"] > '0' ? 'checked="checked"' : '';?> 
				onclick="javascript: setActiva(<?php echo $cont;?>)"
			/>
		</td>
		<td width="10%" >
			<input type="hidden" name="hdnDevolver<?php echo $cont;?>" id="hdnDevolver<?php echo $cont;?>" value="<?php echo number_format($rst["dblDevolucion"], 2, '','.');?>" />
			<input name="txtDevolver<?php echo $cont;?>" id="txtDevolver<?php echo $cont;?>" class="txt-plano" style="width:97%; text-align:right"  <?php echo $rst["dblDevolucion"] > '0' ? '' : 'disabled="disabled"';?> value="<?php echo number_format($rst["dblDevolucion"], 2, '.',',');?>"
				onblur="javascript: Blur(this.id, <?php echo $cont;?>);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id, <?php echo $cont;?>);"
				onkeyup="javascript: 
					if(this.value == ''){
						this.value = 0;
						this.focus();
						this.select();
					}
				"
			/>
		</td>
		<td width="32%" >
			<input type="hidden" name="hdnObservacion<?php echo $cont;?>" id="hdnObservacion<?php echo $cont;?>" value="<?php echo $rst["strObservacion"];?>"/>
			<input name="txtObservacion<?php echo $cont;?>" id="txtObservacion<?php echo $cont;?>" class="txt-plano" style="width:99%;" maxlength="1000" <?php echo $rst["dblDevolucion"] > '0' ? '' : 'disabled="disabled"';?> value="<?php echo $rst["strObservacion"];?>"
				onblur="javascript: Blur(this.id, <?php echo $cont;?>);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this.id, <?php echo $cont;?>);"
			/>
		</td>
	</tr>
<?php
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="hdnTotLn" id="hdnTotLn" value="<?php echo $cont;?>" />
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>