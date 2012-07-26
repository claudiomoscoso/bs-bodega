<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$texto = $_GET["texto"];
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
	<tr>
		<td colspan="3" valign="top">
			<select name="lista" id="lista" class="sel-plano" style="width:100%;" size="12" 
				onkeypress="javascript: return KeyPress(event, this.selectedIndex, this.id);"
			>
			<?php
			if(trim($texto) != '')
				$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 3, '$texto', '$usuario', 'C'", $cnx);
			else
				$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 4, '$usuario'", $cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$arrDatos .= "'".trim($rst["strCCosto"]).'&&&'.trim($rst["strDescripcion"]).'&&&'.trim($rst["dblOdometro"]).'&&&'.trim($rst["strObra"]).'&&&'.trim($rst["strFalla"])."',";
					echo '<option value="'.$rst["strCCosto"].'">['.trim($rst["strCCosto"]).'] '.$rst["strDescripcion"].'</option>';
				}while($rst = mssql_fetch_array($stmt));
				$arrDatos = substr($arrDatos, 0, -1);
			}else
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
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

function KeyPress(evento, ind, idCtrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false);
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		parent.document.getElementById('<?php echo $ctrl;?>').focus();
	}else if(tecla == 13){
		switch(idCtrl){
			case 'lista':
				parent.Deshabilita(false);
				parent.CierraDialogo('divBuscador', 'frmBuscador');
				if(document.getElementById('lista').value != 'none' && document.getElementById('lista').value != ''){
					var arrPaso = arrDatos[ind].split('&&&');
					if(arrPaso[3] == 'S/A'){
						if(!confirm('El centro de costo seleccionado no se encuentra asignado a su obra. ¿Esta seguro que desea continuar?')){
							parent.document.getElementById('txtCCosto').value = '';
							parent.document.getElementById('txtEquipo').value = '';
							parent.document.getElementById('txtUltLectura').value = 0;
							parent.document.getElementById('divFallas').innerHTML = '';
							parent.document.getElementById('<?php echo $ctrl;?>').focus();
							parent.document.getElementById('<?php echo $ctrl;?>').select();
							return false;
						}
					}
					parent.document.getElementById('txtCCosto').value = arrPaso[0];
					parent.document.getElementById('txtEquipo').value = ' ' + Reemplazar(arrPaso[1]);
					parent.document.getElementById('txtUltLectura').value = (arrPaso[2] != '' ? arrPaso[2] : 0);
					var fallas = parent.document.getElementById('divFallas');
					var ajax = new XMLHttpRequest();
					ajax.open('GET', 'fallas.php?ccosto=' + arrPaso[0], true);
					ajax.onreadystatechange = function(){
					if(ajax.readyState == 4) fallas.innerHTML = ajax.responseText;
					}
					ajax.send(null);
					parent.document.getElementById('<?php echo $foco;?>').focus();
				}else{
					parent.document.getElementById('txtCCosto').value = '';
					parent.document.getElementById('txtEquipo').value = '';
					parent.document.getElementById('txtUltLectura').value = '';
					parent.document.getElementById('divFallas').innerHTML = '';
					parent.document.getElementById('<?php echo $ctrl;?>').focus();
					parent.document.getElementById('<?php echo $ctrl;?>').select();
				}
				break;
		}
	}
}

document.getElementById('lista').focus();
document.getElementById('lista').selectedIndex = 0;
-->
</script>
<?php
mssql_close($cnx);
?>