<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"] !='' ? $_GET["usuario"] : $_POST["usuario"];
$bodega=$_GET["bodega"] != '' ? $_GET["bodega"] : $_POST["bodega"];

$estado=$_GET["estado"] !='' ? $_GET["estado"] : $_POST["estado"];
$numero=$_GET["numero"] !='' ? $_GET["numero"] : $_POST["numero"];
$numsol=$_GET["numsol"] !='' ? $_GET["numsol"] : $_POST["numsol"];
$tipodoc=$_GET["tipodoc"] !='' ? $_GET["tipodoc"] : $_POST["tipodoc"];
$obs=$_GET["obs"] !='' ? $_GET["obs"] : $_POST["obs"];
$nivel=$_GET["nivel"] !='' ? $_GET["nivel"] : $_POST["nivel"];
$clave=$_POST["clave"];

if($clave != ''){ 
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND clave='$clave'", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		if($estado=='V'){
			if($tipodoc == 'I'){
				if($numsol=='') $modulo='OCV'; else $modulo='AOC';
				mssql_query("EXEC Bodega..sp_CambiaEstado '$modulo', $numero, 1, '$usuario', NULL, ".($numsol!='' ? $numsol : 'NULL'), $cnx);
				$est_oc = 10; 
			}else 
				$est_oc = ($nivel == 1 ? 15 : 1);
		}elseif($estado == 'N')
			$est_oc = 5;
		
		if($numsol=='') $modulo='OCV'; else $modulo='AOC';
		$sql = "EXEC Bodega..sp_CambiaEstado '$modulo', $numero, $est_oc, '$usuario', NULL, ".($numsol!='' ? $numsol : 'NULL');
		mssql_query("EXEC Bodega..sp_CambiaEstado '$modulo', $numero, $est_oc, '$usuario', NULL, ".($numsol!='' ? $numsol : 'NULL'), $cnx);
		if($obs!='') mssql_query("EXEC sp_GrabaObservacion $numero,'OC','".Reemplaza($obs)."','$usuario'", $cnx);
		?>
		<script language="javascript">
			parent.Deshabilita(false);
			parent.parent.Deshabilitar(false);
			parent.CierraDialogo('divContrasena', 'frmContrasena');
			parent.parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
			
			var cargo=parent.parent.document.getElementById('cargo').value,
			estado=parent.parent.document.getElementById('estado').value,
			mes=parent.parent.document.getElementById('mes').value,
			ano=parent.parent.document.getElementById('ano').value,
			periodo=parent.parent.document.getElementById('periodo').value;
						
			parent.parent.document.getElementById('detalle').src='resultado.php?usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&cargo='+
			cargo+'&estado='+estado+'&mes='+mes+'&ano='+ano+'&periodo='+periodo;
		</script>
		<?php
	}else{
	?><script language="javascript">alert('La contraseña ingresada no es valida.');</script><?php
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Maestro de Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('user').focus();
}
-->
</script>
<body topmargin="5px" onLoad="javascript: Load();">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="7%" align="left">&nbsp;Usuario</td>
		<td width="1%">:</td>
		<td align="left"><input name="user" id="user" class="txt-plano" style="width:99%" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr>
		<td width="7%" align="left">&nbsp;Contrase&ntilde;a</td>
		<td width="1%">:</td>
		<td align="left"><input type="password" name="clave" id="clave" class="txt-plano" style="width:99%" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr><td colspan="4"><hr /></td></tr>
	<tr>
		<td colspan="4" align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="estado" id="estado" value="<?php echo $estado;?>" />
			<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
			<input type="hidden" name="numsol" id="numsol" value="<?php echo $numsol;?>" />
			<input type="hidden" name="tipodoc" id="tipodoc" value="<?php echo $tipodoc;?>" />
			<input type="hidden" name="obs" id="obs" value="<?php echo $obs;?>" />
			<input type="hidden" name="nivel" id="nivel" value="<?php echo $nivel;?>" />
			
			<input type="submit" name="btnOk" id="btnOk" class="boton" style="width: 80px"  value="Aceptar" />
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width: 80px" value="Cancelar" 
				onclick="javascript: 
					parent.Deshabilita(false);
					parent.CierraDialogo('divContrasena', 'frmContrasena');
				"
			/>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>
