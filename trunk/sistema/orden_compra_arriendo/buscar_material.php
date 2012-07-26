<?php
include '../conexion.inc.php';
$texto = trim($_GET["texto"]);
$bodega = $_GET["bodega"];
$ocinterna = $_GET["ocinterna"];
$ctrl = $_GET["ctrl"];
$foco = $_GET["foco"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
<?php
if($ocinterna == 0){
	echo '<tr>';
	echo '<th align="right" colspan="3">';
	echo '<a href="../materiales/index.php?ctrl='.$ctrl.'&foco='.$foco.'&consulta='.$texto.'&tipounidad=T&tipo=A&interna=$ocinterna" ';
	echo 'onmouseover="javascript: window.status=\'Agregar material.\'; return true;" ';
	echo 'onmouseout="javascript: window.status=\'Listo\'; return true;"';
	echo '>Agregar</a>&nbsp;';
	echo '</th>';
	echo '</tr>';
}
?>
	<tr>
		<td colspan="3" valign="top">
			<select name="material" id="material" class="sel-plano" style="width:100%;" size="<?php echo ($ocinterna == 0 ? '15' : '16');?>" 
				onkeypress="javascript: return Saltar(event, this.selectedIndex, this.id);"
			>
			<?php
			if($ocinterna == 0)
				$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$texto', 'C', 'T', NULL, 'OC'",$cnx);
			else
				$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$texto', 'C', 'T', NULL, 'OCA'",$cnx);
			if($rst=mssql_fetch_array($stmt)){
				do{
					$arrDatos.="'".trim($rst["strCodigo"]).'&&&'.trim($rst["strDescripcion"]).'&&&'.trim($rst["strUnidad"]).'&&&'.trim($rst["dblStock"])."',";
					echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDescripcion"].' ['.$rst["strUnidad"].']</option>';
				}while($rst=mssql_fetch_array($stmt));
				$arrDatos=substr($arrDatos,0,-1);
			}else{
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
			}	
			mssql_free_result($stmt);
			?>
			</select>
		</td>
	</tr>
</table>
</body>
</html>
<script language="javascript">
<!--
var arrDatos = new Array(<?php echo $arrDatos;?>);

function Saltar(evento, ind, idCtrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false);
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		parent.document.getElementById('<?php echo $ctrl;?>').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false);
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		if(document.getElementById('material').value != 'none' && document.getElementById('material').value != ''){
			var arrPaso = arrDatos[ind].split('&&&');
			parent.document.getElementById('txtCodigo').value = arrPaso[0];
			parent.document.getElementById('txtDescripcion').value = ' ' + Reemplazar(arrPaso[1]);
			parent.document.getElementById('txtUnidad').value = arrPaso[2];
			parent.document.getElementById('hdnStock').value = arrPaso[3];
			parent.document.getElementById('<?php echo $foco;?>').focus();
		}else{
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnStock').value = '';
			parent.document.getElementById('<?php echo $ctrl;?>').focus();
			parent.document.getElementById('<?php echo $ctrl;?>').select();
		}
	}
}

document.getElementById('material').focus();
document.getElementById('material').selectedIndex = 0;
-->
</script>
<?php
mssql_close($cnx);
?>