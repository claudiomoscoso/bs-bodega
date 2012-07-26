<?php
include '../globalvar.inc.php';

$estado = $_GET["estado"];
$cont = $_GET["cont"];
$cont++;
switch($estado){
	case 0:
		if(is_dir($tmp_documento)){
			$dir = opendir($tmp_documento);
			while($archs = readdir($dir)){
				if($archs != '.' && $archs != '..' && $archs != 'Thumbs.db'){
					$proveedor = $_GET["proveedor"];
					$documento = $_GET["documento"];
					$archorig = $archs;
					$archivo = $proveedor.'_'.$documento.'_'.$archs;
					if(file_exists($dtn_documento.'/'.$archs)) unlink($dtn_documento.'/'.$archs);
					copy($tmp_documento.'/'.$archs, $dtn_documento.'/'.$archivo);
					$estado = 1;
				}
			}
			closedir($dir);
		}else
			$estado = 4;
		break;
	case 1:
		$archivo = $_GET["archivo"];
		$archorig = $_GET["archorig"];
		if(file_exists($dtn_documento.'/'.$archivo)){
			unlink($tmp_documento.'/'.$archorig);
			$estado = 2;
		}
		break;
	case 2:
		$archivo = $_GET["archivo"];
		$archorig = $_GET["archorig"];
		if(!file_exists($tmp_documento.'/'.$archorig)) $estado = 3;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
var intervalo = setInterval("buscarArchivo()", 3000);

function Load(){
	switch(parseInt('<?php echo $estado;?>')){
		case 1:
			parent.document.getElementById('hdnArchivo').value = '<?php echo $archivo;?>';			
			break;
		case 3:
			clearInterval(intervalo);
			document.getElementById('btnAceptar').disabled = false;
			window.status = 'Transferencia finalizada.';
			break;
		case 4:
			clearInterval(intervalo);
			break;
	}
}

function buscarArchivo(){
	self.location.href = '<?php echo $_SERVER['PHP_SELF']."?estado=$estado&archivo=$archivo&archorig=$archorig&cont=$cont";?>';
}
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="1" cellspacing="2">
	<tr>
		<td width="50%" align="center" valign="middle" style="font-size:13px;font-weight:bold" >
			<?php
			switch($estado){
				case 0:
					echo "Buscando archivo... ($cont)";
					break;
				case 1:
					break;
				case 2:
					echo 'Transfiriendo archivo ['.$archivo.']...';
					break;
				case 3:
					echo 'Transferencia finalizada.';
					break;
				case 4:
					echo 'Error de acceso.';
			}
			?>
		</td>
		<td width="0%" align="center">
			<input type="button" name="btnAceptar" id="btnAceptar" class="boton" style="width:70px" disabled="disabled" value="Aceptar" 
				onclick="javascript:
					parent.Deshabilita(false);
					parent.CierraDialogo('divScanner', 'frmScanner');
				"
			/>
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width:70px" <?php echo $estado == 3 ? 'disabled="disabled"' : '';?> value="Cancelar" 
				onclick="javascript:
					clearInterval(intervalo);
					parent.Deshabilita(false);
					parent.CierraDialogo('divScanner', 'frmScanner');
				"
			/>
		</td>
	</tr>
	<tr><td colspan="2"><iframe frameborder="0" width="100%" height="100px" src="<?php echo $dtn_documento.'/'.$archivo;?>"></iframe></td></tr>
</table>
</body>
</html>
