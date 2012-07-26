<?php
include '../conexion.inc.php';

$usuario=$_GET["usuario"];
$modulo=$_GET["modulo"];
$material = $_GET["material"];
$movil = $_GET["movil"];
$tipo = $_GET["tipo"];

switch($modulo){
	case 0:?>
	<script language="javascript">
	<!--
	var moviles = parent.document.getElementById('cmbMovil');
	for(i = moviles.length; i > 0; i--) moviles.remove(i);
	-->
	</script>
	<?php
		$contrato = $_GET["contrato"];
		$stmt = mssql_query("EXEC General..sp_getMoviles 6, NULL, '$contrato'", $cnx);
		while($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
			moviles.options[moviles.length] = new Option('<?php echo '['.trim($rst["strMovil"]).'] '.$rst["strNombre"];?>', '<?php echo trim($rst["strMovil"]);?>')
		</script>
	<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$ind = $_GET["ind"];
		$cantidad = $_GET["cantidad"];
		$movil_ant = $_GET["movil_ant"];
		$correlativo = $_GET["correlativo"];
		
		$stmt = mssql_query("EXEC Orden..sp_setTMPDetalleOrdenTrabajo 0, '$usuario', '$material', '$movil', $cantidad, '$movil_ant', $correlativo, $tipo", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			var moviles = parent.document.getElementById('cmbMovil<?php echo $ind;?>');
			if(parseInt('<?php echo $rst["dblError"];?>') == 0){
				parent.document.getElementById('hdnMovil<?php echo $ind;?>').value = '<?php echo $movil;?>';
				parent.document.getElementById('hdnCantidad<?php echo $ind;?>').value = <?php echo $cantidad;?>;
			}else if(parseInt('<?php echo $rst["dblError"];?>') == 1){
				alert('El movil no tiene stock para este material.');
				for(i = 0; moviles.length - 1; i++){
					if(moviles.options[i].value == parent.document.getElementById('hdnMovil<?php echo $ind;?>').value){
						moviles.selectedIndex = i;
						break;
					}
				}
				parent.document.getElementById('txtCantidad<?php echo $ind;?>').value = parent.document.getElementById('hdnCantidad<?php echo $ind;?>').value;
			}else if(parseInt('<?php echo $rst["dblError"];?>') == 2)
				alert('El movil no registra la cantidad solicitada.');
				for(i = 0; moviles.length - 1; i++){
					if(moviles.options[i].value == parent.document.getElementById('hdnMovil<?php echo $ind;?>').value){
						moviles.selectedIndex = i;
						break;
					}
				}
				parent.document.getElementById('txtCantidad<?php echo $ind;?>').value = parent.document.getElementById('hdnCantidad<?php echo $ind;?>').value;
		-->
		</script>
		<?php 
		}
		mssql_free_result($stmt);
		break;
	case 3:
		$contrato = $_GET["contrato"];
		if($tipo == 1)
			$sql = "EXEC Bodega..sp_getMateriales '$material', 'E', 'T', '$movil', 'INF'";
		else
			$sql = "EXEC Bodega..sp_getMateriales '$material', 'E', 'T', '$contrato', 'INF2'";
		$stmt = mssql_query($sql, $cnx);
		if($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
			parent.document.getElementById('txtDescripcion').value = '<?php echo $rst["strDescripcion"];?>';
			parent.document.getElementById('hdnUnidad').value = '<?php echo $rst["strUnidad"];?>';
			parent.document.getElementById('hdnCantidad').value = '<?php echo $rst["dblCantidad"];?>';
			parent.document.getElementById('txtCantidad').focus();
			parent.document.getElementById('txtCantidad').select();
		</script>
		<?php
		}else{?>
		<script language="javascript">
			alert('El código ingresado no se encuentra asignado a este movil');
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('hdnUnidad').value = '';
			parent.document.getElementById('hdnCantidad').value = 0;
			parent.document.getElementById('txtCodigo').focus();
			parent.document.getElementById('txtCodigo').select();
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 4:
		$numero = $_GET["numero"];
		$perfil = $_GET["perfil"];
		$bodega = $_GET["bodega"];
		$nivel = $_GET["nivel"];
		$login = $_GET["login"];

		mssql_query("EXEC Orden..sp_setDetalleOrdenTrabajo 0, '$usuario', $numero", $cnx);?>
		<script language="javascript">
		parent.parent.CambiaPestana(1);
		</script>
<?php	break;
	case 5:
		$numero = $_GET["numero"];
		mssql_query("EXEC Orden..sp_setDetalleOrdenTrabajo 1, '$usuario', $numero", $cnx);?>
		<script language="javascript">
		alert('La orden Nº <?php echo $numero;?> ha sido reinformada.');
		</script>
	<?php
}

mssql_close($cnx);
?>