<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"]!='' ? $_GET["bodega"] : $_POST["bodega"];
$usuario=$_GET["usuario"]!='' ? $_GET["usuario"] : $_POST["usuario"];
$perfil=$_GET["perfil"]!='' ? $_GET["perfil"] : $_POST["perfil"];

$cargo=$_GET["cargo"]!='' ? $_GET["cargo"] : $_POST["cargo"];
$estado=$_GET["estado"]!='' ? $_GET["estado"] : $_POST["estado"];
$mes=$_GET["mes"]!='' ? $_GET["mes"] : $_POST["mes"];
$ano=$_GET["ano"]!='' ? $_GET["ano"] : $_POST["ano"];
$periodo=$_GET["periodo"]!='' ? $_GET["periodo"] : $_POST["periodo"];

$numero=$_POST["numero"];
$numsol=$_POST["numsol"];
$observacion=$_POST["observacion"];
$pwd=$_POST["pwd"];

if($pwd!=''){ 
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND clave=General.dbo.fn_md5('$pwd')", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		if($estado=='A')
			$est_oc=2;
		elseif($estado=='R')
			$est_oc=4;
		elseif($estado=='N')
			$est_oc=5;
		mssql_query("EXEC sp_CambiaEstado 'OC', $numero, $est_oc, '$usuario', '', $numsol", $cnx);
		if($observacion!='') mssql_query("EXEC sp_GrabaObservacion $numero,'OC','".Reemplaza($observacion)."','$usuario'", $cnx);		
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
var Intervalo=0;

function Load(){
	parent.Deshabilitar(false);
}

function Envia(estado){
	document.getElementById('estado').value='';
	document.getElementById('estado').value=estado;
	window.open('contrasena.php','','toolbar=no, resizable=no, menubar=no, width=275px, height=115px, left='+(screen.width-275)/2+', top='+(screen.height-115)/2);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
if($perfil != 'informatica'){
	if($bodega == 12000) $strTipo='O'; else $strTipo='A';
}else{
	$strTipo='%';
}

$stmt = mssql_query("EXEC sp_getOrdenCompra 'IOC', NULL, '$strTipo', '$cargo', '$estado', '$mes', $ano, NULL, $periodo, '$usuario'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2) == 0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="2%" align="center">
		<?php
		if($rst["strTipoDoc"] == 'I'){
			echo '<img border="0" width="15px" align="middle" src="../images/bola.gif">';	
		}else{
			echo '&nbsp;';
		}
		?>
		</td>
		<td width="20%" align="left">
			<input type="hidden" name="hdnCargo<?php echo $cont;?>" id="hdnCargo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim(($rst["strTipoDoc"] == 'I' ? '[Arr.Modelo]' : '').' '.$rst["Cargo"]));?>" />
			<input name="txtCargo<?php echo $cont;?>" id="txtCargo<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim(($rst["strTipoDoc"] == 'I' ? '[Arr.Modelo]' : '').' '.$rst["Cargo"]));?>"
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnCargo<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnCargo<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center">
			<a href="#" 
				onclick="javascript: 
					parent.Deshabilitar(true);
					parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
					parent.CierraDialogo('divEstado', 'frmEstado');
					parent.CierraDialogo('divObservacion', 'frmObservacion');
					AbreDialogo('divOrdenCompra', 'frmOrdenCompra','orden_compra.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&numero=<?php echo $rst["dblNumero"];?>', true);
				"
				onmouseover="javascript: 
					window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; 
					return true;
				"
			><?php echo $rst["dblUltima"];?></a>
		</td>
		<td width="8%" align="center"><?php echo $rst["dtmFch"];?></td>
		<td width="25%" align="left">
			<input type="hidden" name="hdnNota<?php echo $cont;?>" id="hdnNota<?php echo $cont;?>" value="<?php echo htmlentities(trim($rst["strObservacion"]));?>" />
			<input name="txtNota<?php echo $cont;?>" id="txtNota<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities(trim($rst["strObservacion"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNota<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="8%" align="center"><?php echo $rst["dtmSolicitud"];?></td>
		<td width="15%" align="center">
			<a href="#" title="Orden de Compra Nº <?php echo $rst["dblUltima"];?>" 
				onclick="javascript: 
					parent.Deshabilitar(true);
					parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
					parent.CierraDialogo('divEstado', 'frmEstado');
					parent.CierraDialogo('divObservacion', 'frmObservacion');
					AbreDialogo('divEstado', 'frmEstado', 'estados.php?numero=<?php echo $rst["dblNumero"];?>', true);
				"
				onmouseover="javascript: 
					window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; 
					return true;
				"
			><?php echo htmlentities($rst["Estado"]);?></a>
		</td>
		<td width="7%" align="center">
			<a href="#" title="Orden de Compra Nº <?php echo $rst["dblUltima"];?>"
				onclick="javascript: 
					parent.Deshabilitar(true);
					parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
					parent.CierraDialogo('divEstado', 'frmEstado');
					parent.CierraDialogo('divObservacion', 'frmObservacion');
					AbreDialogo('divObservacion', 'frmObservacion', 'observaciones.php?numero=<?php echo $rst["dblNumero"];?>', true);
				"
				onmouseover="javascript: 
					window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; 
					return true;
				"
			>ver m&aacute;s...</a>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
<input type="hidden" name="cargo" id="cargo" value="<?php echo $cargo;?>">
<input type="hidden" name="estado" id="estado" value="<?php echo $estado;?>">
<input type="hidden" name="mes" id="mes" value="<?php echo $mes;?>">
<input type="hidden" name="ano" id="ano" value="<?php echo $ano;?>">
<input type="hidden" name="numsol" id="numsol">
</body>
</html>
<?php
mssql_close($cnx);
?>
