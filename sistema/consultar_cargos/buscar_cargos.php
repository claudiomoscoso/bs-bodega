<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$texto = $_GET["texto"];
$ctrl = $_GET["ctrl"];
$foco = $_GET["foco"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cargos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){	
	document.getElementById('lista').selectedIndex=0;
	document.getElementById('lista').focus();
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<select name="lista" id="lista" class="sel-plano" style="width:100%;" size="14" 
				onkeypress="javascript: return KeyPress(event, this.selectedIndex);"
			>
			<?php
				$stmt = mssql_query("EXEC General..sp_getPersonalObra 1, '$bodega', '$texto'", $cnx);
				if($rst=mssql_fetch_array($stmt)){
					do{
						$arrDatos.="'".trim($rst["strRut"]).'&&&'.trim($rst["strNombre"])."',";
						echo '<option value="'.trim($rst["strRut"]).'">['.trim($rst["strRut"]).'] '.trim($rst["strNombre"]).'</option>';
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
var arrDatos=new Array(<?php echo $arrDatos;?>);

function KeyPress(evento, ind){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false);
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		parent.document.getElementById('<?php echo $ctrl;?>').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false);
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		if(document.getElementById('lista').value!='none'){
			var arrPaso=arrDatos[ind].split('&&&');
			parent.document.getElementById('hdnCargo').value = arrPaso[0];
			parent.document.getElementById('txtCargo').value = arrPaso[1];
			parent.document.getElementById('<?php echo $foco;?>').focus();
		}else{
			parent.document.getElementById('<?php echo $ctrl;?>').focus();
			parent.document.getElementById(ctrl).select();
		}
	}
}
-->
</script>
<?php
mssql_close($cnx);
?>