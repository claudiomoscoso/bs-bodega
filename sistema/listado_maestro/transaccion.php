<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
switch($modulo){
	case 0:?>
		<script language="javascript">
		<!--
		movil = parent.document.getElementById('cmbMovil');
		anexos = parent.document.getElementById('cmbAnexos');
		depto = parent.document.getElementById('cmbDepto');
		for(i = movil.length; i >= 0; i--){ 
			movil.remove(i);
			anexos.remove(i);
		}
		for(i = depto.length; i >= 0; i--) depto.remove(i);
		
		ttrabajo = parent.document.getElementById('cmbTAnexo');
		for(i = ttrabajo.length; i >= 0; i--) ttrabajo.remove(i);
		-->
		</script>
		<?php
		$contrato = $_GET["contrato"];
		$stmt = mssql_query("EXEC General..sp_getEstados 0, '$contrato'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
			<script language="javascript">
			<!--
			ttrabajo.options[ttrabajo.length] = new Option('Todos', 'all');
			-->
			</script>
			<?php
			do{?>
			<script language="javascript">
			<!--
			ttrabajo.options[ttrabajo.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strDetalle"];?>');
			-->
			</script>
			<?php
			}while($rst = mssql_fetch_array($stmt));
		}
		mssql_free_result($stmt);
		
		$stmt = mssql_query("EXEC General..sp_getMoviles 13, '$contrato'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
			<script language="javascript">
			<!--
			movil.options[movil.length] = new Option('Todos', 'all');
			anexos.options[anexos.length] = new Option('Todos', 'all');
			-->
			</script>
			<?php
			do{?>
			<script language="javascript">
			<!--
			movil.options[movil.length] = new Option('<?php echo $rst["strMovil"];?>', '<?php echo $rst["strMovil"];?>');
			anexos.options[anexos.length] = new Option('<?php echo $rst["strMovil"];?>', '<?php echo $rst["strMovil"];?>');
			-->
			</script>
		<?php
			}while($rst = mssql_fetch_array($stmt));
		}
		mssql_free_result($stmt);
		
		?>
		<script language="javascript">
		<!--
		depto.options[depto.length] = new Option('Todos', 'all');
		-->
		</script>
		<?php		
		$stmt = mssql_query("SELECT strCodigo, strDetalle FROM Orden..Tablon WHERE strTabla = 'depto' AND strContrato = '$contrato' ORDER BY strCodigo", $cnx);
		if($rst = mssql_fetch_array($stmt)){
			do{?>
				<script language="javascript">
				<!--
				depto.options[depto.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strCodigo"];?>');
				-->
				</script>
			<?php
			}while($rst = mssql_fetch_array($stmt));
		}
		mssql_free_result($stmt);
		?>
		<script language="javascript">
		<!--
		movil.disabled = false;
		anexos.disabled = false;
		ttrabajo.disabled = false;
		depto.disabled = false;
		-->
		</script>	
<?php	break;
	case 1:
		$sw = 0;
		$codigo = $_GET["codigo"];
		$stmt = mssql_query("EXEC Orden..sp_getFormatosInforme 1, NULL, $codigo", $cnx);
		if($rst = mssql_fetch_array($stmt)){ 
			$sw = 1;
			$campos = "'".str_replace(",", "','", $rst["strCampos"])."'";
			$criterio = $rst["strCriterios"];
			$orden = "'".str_replace(",", "','", $rst["strOrdenado"])."'";
		}
		mssql_free_result($stmt);?>
		<script language="javascript">
		<!--
		var seleccion = parent.document.getElementById('cmbCampos');
		var ordenar = parent.document.getElementById('cmbOrdenado');
		var ordenes = parent.document.getElementById('cmbDCOrden');
		var anexos = parent.document.getElementById('cmbDAnexos');
		for(i = (seleccion.length - 1); i >= 0; i--){		
			var valor = seleccion.options[i].value;
			if(valor.substring(0, 1) == 'O')
				ordenes.options[ordenes.length] = new Option(seleccion.options[i].text, seleccion.options[i].value);
			else
				anexos.options[anexos.length] = new Option(seleccion.options[i].text, seleccion.options[i].value);
			seleccion.remove(i);
		}
		for(i = (ordenar.length - 1); i >= 0; i--) ordenar.remove(i);
		
		parent.Desbloquea();
		
		if(parseInt('<?php echo $sw;?>') == 1){
			var valor = '', sw = 0;
			//Busca los campos
			var arrCampos = new Array(<?php echo $campos;?>);
			for(i = 0; i < arrCampos.length; i++){
				sw = 0;
				for(j = 0; j < ordenes.length; j++){
					valor = ordenes.options[j].value;
					if(parseInt(valor.substring(1)) == parseInt(arrCampos[i])){
						sw = 1;
						seleccion.options[seleccion.length] = new Option(ordenes.options[j].text, ordenes.options[j].value);
						ordenes.remove(j);
						break;
					}
				}
				if(sw == 0){
					for(j = 0; j < anexos.length; j++){
						valor = anexos.options[j].value;
						if(parseInt(valor.substring(1)) == parseInt(arrCampos[i])){
							sw = 1;
							seleccion.options[seleccion.length] = new Option(anexos.options[j].text, anexos.options[j].value);
							anexos.remove(j);
							break;
						}
					}
				}
			}
			
			//Busca Ordenamiento
			var arrOrden = new Array(<?php echo $orden;?>);
			for(i = 0; i < arrOrden.length; i++){
				for(j = 0; j < seleccion.length; j++){
					valor = seleccion.options[j].value;
					if(parseInt(valor.substring(1)) == parseInt(arrOrden[i])){
						ordenar.options[ordenar.length] = new Option(seleccion.options[j].text, seleccion.options[j].value);
						break;
					}
				}
			}
			
			//Busca los criterios
			var ocriterios = parent.document.getElementById('cmbOCriterios');
			for(i = 0; i <= ocriterios.length; i++){
				if(parseInt(ocriterios.options[i].value) == parseInt('<?php echo $criterio;?>')){
					ocriterios.selectedIndex = i;
					break;
				}
			}
			parent.Bloquea('<?php echo $criterio;?>');
		}
		-->
		</script>
<?php	break;
}

mssql_close($cnx);
?>