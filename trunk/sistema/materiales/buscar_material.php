<?php
include '../conexion.inc.php';

$texto = trim($_GET["texto"]);
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
		<td>
			<select name="material" id="material" class="sel-plano" style="width:100%;" size="10" 
				onkeypress="javascript: return KeyPress(event, this.selectedIndex);"
			>
			<?php
			$stmt = mssql_query("EXEC sp_getMateriales '$texto','C', NULL, NULL, 'MAT'",$cnx);
			if($rst=mssql_fetch_array($stmt)){
				do{
					$arrDatos .= "'".trim($rst["strCodigo"]).'&&&'.trim($rst["strDescripcion"]).'&&&'.trim($rst["strUnidad"]).'&&&'.trim($rst["strFamilia"])."',";
					echo '<option value="'.trim($rst["strCodigo"]).'">['.trim($rst["strCodigo"]).'] '.trim($rst["strDescripcion"]).'</option>';
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

function KeyPress(evento, ind){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false);
		parent.CierraDialogo('divMateriales', 'frmMateriales');
		parent.document.getElementById('txtDescripcion').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false);
		parent.CierraDialogo('divMateriales', 'frmMateriales');
		if(document.getElementById('material').value != 'none' && document.getElementById('material').value != ''){
			var arrPaso = arrDatos[ind].split('&&&');
			parent.document.getElementById('hdnCodigo').value = arrPaso[0];
			parent.document.getElementById('txtDescripcion').value = ' ' + Reemplazar(arrPaso[1]);
			parent.document.getElementById('cmbUnidad').value = arrPaso[2];
			var familia = parent.document.getElementById('cmbFamilia');
			for(i = 0; i < familia.length; i++){
				if(familia.options[i].value == arrPaso[3]){
					familia.selectedIndex = i;
					break;
				}
			}
			familia.focus();
		}else{
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('cmbUnidad').value = '';
			parent.document.getElementById('cmbFamilia').selectedIndex = 0;
			parent.document.getElementById('txtDescripcion').focus();
			parent.document.getElementById('txtDescripcion').select();
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