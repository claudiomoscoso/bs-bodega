<?php
include '../conexion.inc.php';

$texto = trim($_GET["texto"]);
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
			$stmt = mssql_query("EXEC General..sp_getPersonalObra 4, NULL, '$texto'",$cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$arrDatos.="'".trim($rst["strRut"]).'&&&'.trim($rst["strNombre"])."',";
					echo '<option value="'.$rst["strRut"].'">['.trim($rst["strRut"]).'] '.$rst["strNombre"].'</option>';
				}while($rst=mssql_fetch_array($stmt));
				$arrDatos = substr($arrDatos,0,-1);
			}else{
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
			}
			mssql_free_result($stmt);
			?>
        </select></td>
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
					parent.document.getElementById('hdnOperador').value = arrPaso[0];
					parent.document.getElementById('txtOperador').value = ' ' + Reemplazar(arrPaso[1]);
					parent.document.getElementById('<?php echo $foco;?>').focus();
				}else{
					parent.document.getElementById('hdnOperador').value = '';
					parent.document.getElementById('txtOperador').value = '';
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