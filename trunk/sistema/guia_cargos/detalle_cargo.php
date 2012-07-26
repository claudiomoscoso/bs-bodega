<?php
include '../autentica.php';
include '../conexion.inc.php';
$accion=$_POST["accion"]!='' ? $_POST["accion"] : $_GET["accion"];

if($accion=='G'){
	$codigo=$_POST["codigo"];
	$cantidad=$_POST["cantidad"];
	mssql_query("EXEC sp_AgregaLineaDetalleTMP '$usuario', 'GC', '$codigo', NULL, $cantidad", $cnx);
}elseif($accion=='E'){
	$cod_material=$_POST["cod_material"];
	mssql_query("DELETE FROM Detalle_TMP WHERE strUsuario='$usuario' AND strTipoDoc='GC' AND strCodigo='$cod_material'", $cnx);
}elseif($accion=='B')
	mssql_query("DELETE FROM Detalle_TMP WHERE strUsuario='$usuario' AND strTipoDoc='GC'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Despacho</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalleCargo').setAttribute('height', window.innerHeight * 2);
	document.getElementById('codigo').focus();
}

function Saltar(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla==13){
		switch(ctrl){
			case 'codigo':
				parent.Deshabilita(true);
				LimpiaDetalle();
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?bodega=<?php echo $bodega;?>&texto='+ document.getElementById('codigo').value);
				break;
			case 'cantidad':
				if(document.getElementById('codigo').value==''){
					alert('Debe ingresar el código del material.');
					document.getElementById('codigo').focus();
				}else if(document.getElementById('cantidad').value==0){
					alert('Debe ingresar una cantidad mayor a cero.');
					document.getElementById('cantidad').focus();
				}else if(document.getElementById('descripcion').value==''){
					alert('El código del material ingresado no es valido.');
					document.getElementById('codigo').focus();
				}else{
					if(ValStock(document.getElementById(ctrl).value)){
						document.getElementById('accion').value='G';
						document.getElementById('frm').submit();
					}
				}
		}
	}else{
		if(ctrl == 'cantidad') return ValNumeros(evento, ctrl, true);
	}
}

function Busqueda_Rapida(tipo, bodega, valor){
	document.getElementById('valido').src='valida.php?tipo='+tipo+'&bodega='+bodega+'&valor='+valor;
}

function ValStock(valor){
	var sw=true;
	if(valor=='') valor=0;
	if(parseFloat(valor)>parseFloat(document.getElementById('stock').value)){
		alert('El stock actual es menor ('+document.getElementById('stock').value+')');
		sw=false;
	}
	return sw;
}

function LimpiaDetalle(){
	document.getElementById('descripcion').value='';
	document.getElementById('unidad').value='';
	document.getElementById('stock').value=0;
	document.getElementById('cantidad').value=0;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<div id="divMateriales" style="z-index:1; position:absolute; top:25px; left:20%; width:60%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript:
										parent.Deshabilita(false); 
										CierraDialogo('divMateriales', 'frmMateriales');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMateriales" id="frmMateriales" frameborder="0" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<tr>
		<th width="10%">C&oacute;digo</th>
		<th width="68">Descripci&oacute;n</th>
		<th width="10%">Unidad</th>
		<th width="10%">Cantidad</th>
		<th width="2%">&nbsp;</th>
	</tr>
	<tr>
		<td >
			<input name="codigo" id="codigo" class="txt-plano" style="width: 97%; text-align:center" 
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return Saltar(event, this.id);"  
				onblur="javascript: 
					Busqueda_Rapida('M', '<?php echo $bodega;?>', this.value); 
					CambiaColor(this.id, false);
				"
			/>
		</td>
		<td ><input name="descripcion" id="descripcion" class="txt-plano" style="width: 99%;" readonly="true"/></td>
		<td ><input name="unidad" id="unidad" class="txt-plano" style="width: 96%; text-align:center" readonly="true"/></td>
		<td >
			<input name="cantidad" id="cantidad" class="txt-plano" style="width: 97%; text-align:right" value="0"
				onkeypress="javascript: return Saltar(event, this.id);"
				onfocus="javascript: CambiaColor(this.id, true);" 
				onblur="javascript: 
					CambiaColor(this.id, false);
					if(this.value=='') this.value=0;
				"
			/>
		</td>
	</tr>
	<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
	<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
	<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
	<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />
	<input type="hidden" name="accion" id="accion"/>
	<input type="hidden" name="cod_material" id="cod_material"/>
	<input type="hidden" name="stock" id="stock" />
	</form>
	<tr><td colspan="5"><iframe name="frmDetalleCargo" id="frmDetalleCargo" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes" src="agrega.php<?php echo $parametros;?>"></iframe></td></tr>
</table>
<iframe name="valido" id="valido" frameborder="0" width="0px" height="0px"></iframe>
<input type="hidden" id="totfil" />
</body>
</html>
<?php
mssql_close($cnx);
?>