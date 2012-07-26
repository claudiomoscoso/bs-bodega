<?php
include '../conexion.inc.php';
include '../globalvar.inc.php';

$bodega=$_GET["bodega"];
$tipodoc=$_GET["tipodoc"]; 
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$material=$_GET["material"] != '' ? "'".$_GET["material"]."'" : 'NULL';
$proveedor=$_GET["proveedor"] != '' ? "'".$_GET["proveedor"]."'" : 'NULL'; 
$tbusqueda=$_GET["tbusqueda"] != '' ? $_GET["tbusqueda"] : 'NULL'; 
$observacion=$_GET["observacion"] != '' ? "'".$_GET["observacion"]."'" : 'NULL';
$numero=$_GET["numero"] != '' ? $_GET["numero"] : 'NULL';

if($observacion!="NULL" && $tbusqueda==0 && ($tipodoc==0 || $tipodoc==1 ||  $tipodoc==6))
	$sql= "EXEC Bodega..sp_getOrdenCompra 'NUM', $observacion, '%', '$bodega'";
elseif($observacion!="NULL" && $tbusqueda==1 && $tipodoc==6)
	$sql= "EXEC Bodega..sp_getGuiaIngreso 'NUM', $observacion,  '$bodega'";

if($sql != ''){
	$stmt = mssql_query($sql, $cnx);
	if($rst=mssql_fetch_array($stmt)) $observacion=$rst["dblNumero"];
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Busqueda de Documentos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

function MuestraDocumento(numero){
	var url=''
	var mes = parent.parent.document.getElementById('cmbMes').value;
	var ano = parent.parent.document.getElementById('cmbAno').value;
	switch(parseInt('<?php echo $tipodoc;?>')){
		case 0:
			url = 'imprime_sm.php';
			break;
		case 1:
			url = 'imprime_oc.php';
			break;
		case 2:
			url = 'imprime_ing.php';
			break;
		case 3:
			url = 'imprime_desp.php';
			break;
		case 4:
			url = 'imprime_dev.php';
			break;
		case 5:
			url = 'imprime_vc.php';
			break;
		case 6:
			url = 'imprime_fact.php';
			break;
		case 7:
			url = 'imprime_cc.php';
			break;
		case 8:
			url = 'imprime_gcargo.php';
			break;
		case 9:
			url = 'imprime_gdevcargo.php';
			break;
		case 10:
			url = 'imprime_tb.php';
			break;
		case 11:
			url = 'imprime_factint.php';
			break;
	}
	parent.AbreDialogo('divDocumento', 'frmDocumento', url + '?bodega=<?php echo $bodega;?>&numero=' + numero + '&mes=' + mes + '&ano=' + ano, true);
}

function Load(){
	parent.parent.document.getElementById('txtTotal').value = document.getElementById('hdnTotal').value;
	parent.parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="1" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getBuscaDocumento '$bodega', $tipodoc, $mes, '$ano', $material, $proveedor, $observacion, $numero, $tbusqueda", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;
		switch($tipodoc){
			case 0:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="9%" align="center">
					<a href="#" <?php echo ($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="30%" align="left">&nbsp;<?php echo $rst["strDescCargo"];?></td>
				<td width="47%" align="left">
					<input type="hidden" id="hdnObs<?php echo $cont;?>" value="<?php echo trim($rst["strObservacion"]);?>" />
					<input id="txtObs<?php echo $cont;?>" class="txt-sborde" style="width: 100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strObservacion"]);?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObs<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnObs<?php echo $cont;?>');
						"
					/>
				</td>
			</tr>
		<?php	break;
			case 1:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" >
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="8%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="8%" align="center">
					<a href="#" <?php echo ($rst["strEstado"]==4 || $rst["strEstado"]==5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="3%" align="center"><?php echo $rst["strTipoDoc"];?></td>
				<td width="15%" align="left">
					<input type="hidden" id="hdnNomb<?php echo $cont;?>" value="<?php echo $rst["strNombre"];?>" />
					<input id="txtNomb<?php echo $cont;?>" class="txt-sborde" style="width: 99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strNombre"];?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNomb<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNomb<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="10%" align="center"><?php echo $rst["dblCEgreso"];?></td>
				<td width="2%" align="center">
				<?php
				if(trim($rst["strArchivo"]) != ''){
					echo '<a href="#" title="Ver documento adjunto..."';
					echo 'onclick="javascript: ';
					echo 'parent.parent.Deshabilita(true);';
					echo "parent.parent.AbreDialogo('divDocOrigen', 'frmDocOrigen', '$dtn_documento/".$rst["strArchivo"]."');";
					echo '"';
					echo 'onmouseover="javascript: window.status=\'Ver documento adjunto...\'; return true;"';
					echo '><img border="0" align="absmiddle" src="../images/archivo.gif" /></a>';
				}else
					echo '&nbsp;';
				?>
				</td>
				<td width="8%" align="center"><?php echo $rst["dblNumDoc"];?></td>
				<td width="10%" align="right"><?php echo number_format($rst["dblMonto"], 0, '', '.');?></td>
				<td width="10%" align="left">
					<input type="hidden" id="hdnCargo<?php echo $cont;?>" value="<?php echo $rst["strDescCargo"];?>" />
					<input id="txtCargo<?php echo $cont;?>" class="txt-sborde" style="width: 99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strDescCargo"];?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnCargo<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnCargo<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="12%" align="left">
					<input type="hidden" id="hdnObs<?php echo $cont;?>" value="<?php echo $rst["strObservacion"];?>" />
					<input id="txtObs<?php echo $cont;?>" class="txt-sborde" style="width: 99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strObservacion"];?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObs<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnObs<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="9%" align="right">
				<?php
					if($rst["strEstado"] != 5 && $numoc != $rst["dblNumero"]){
						$numoc=$rst["dblNumero"];
						$total+=$rst["dblNeto"];
						echo number_format($rst["dblNeto"], 0, '', '.');
					}elseif($rst["strEstado"]==5){
						echo '0';
					}
				?>
					&nbsp;
				</td>
			</tr>
		<?php	break;
			case 2:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="10%" align="center">
					<a href="#" <?php echo ($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a></td>
				</td>
				<td width="31%" align="left">&nbsp;<?php echo $rst["strDescBodega"];?></td>
				<td width="10%" align="center"><?php echo $rst["dblUltima"];?></td>
				<td width="20%" align="left">&nbsp;<?php echo $rst["strTDoc"];?></td>
				<td width="17%" align="center"><?php echo $rst["strReferencia"];?></td>
			</tr>
		<?php	break;
			case 3:
			case 4:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="11%" align="center">
					<a href="#" <?php echo ($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="45%" align="left">&nbsp;<?php echo $rst["strNombre"] != '' ? $rst["strNombre"] : $rst["strMovil"];?></td>
				<td width="31%" align="left">&nbsp;<?php echo $rst["strDescBodega"];?></td>
			</tr>
		<?php	break;
			case 5:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="11%" align="center">
					<a href="#" <?php echo ($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="37%" align="left">&nbsp;<?php echo $rst["strDescObra"];?></td>
				<td width="39%" align="left">&nbsp;<?php echo $rst["strNombre"];?></td>
			</tr>
		<?php	break;
			case 6:
				$total+=$rst["dblMonto"];?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="10%" align="center">
					<a href="#" <?php echo ($rst["strEstado"]==5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNumDoc"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNumDoc"];?>...'; return true;"
					><?php echo $rst["dblNumDoc"];?></a>
				</td>
				<td width="3%" align="center">
				<?php
				if(trim($rst["strArchivo"]) != ''){
					echo '<a href="#" title="Ver documento adjunto..."';
					echo 'onclick="javascript: ';
					echo 'parent.parent.Deshabilita(true);';
					echo "parent.parent.AbreDialogo('divDocOrigen', 'frmDocOrigen', '$dtn_documento/".$rst["strArchivo"]."');";
					echo '"';
					echo 'onmouseover="javascript: window.status=\'Ver documento adjunto...\'; return true;"';
					echo '><img border="0" align="absmiddle" src="../images/archivo.gif" /></a>';
				}else
					echo '&nbsp;';
				?>
				</td>
				<td width="12%" align="left">&nbsp;<?php echo $rst["strTipoDoc"];?></td>
				<td width="30%" align="left">
					<input type="hidden" id="hdnNomb<?php echo $cont;?>" value="<?php echo $rst["strNombre"];?>" />
					<input id="txtNomb<?php echo $cont;?>" class="txt-sborde" style="width: 99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strNombre"];?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNomb<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNomb<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="10%" align="center"><?php echo $rst["dblUltima"];?></td>
				<td width="10%" align="center"><?php echo $rst["dblNumero"];?></td>
				<td  align="right"><?php echo number_format($rst["dblMonto"], 0, '', '.');?>&nbsp;</td>
			</tr>
		<?php	break;
			case 7:
				$total+=$rst["dblNeto"];?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%"><?php echo $cont;?></td>
				<td width="8%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="12%" align="center">
					<a href="#" <?php echo ($rst["strEstado"] == 5 ? 'style="color:#FF0000"' : '');?> title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="20%" align="left">
					<input type="hidden" id="hdnNomb<?php echo $cont;?>" value="<?php echo $rst["strNombre"];?>" />
					<input id="txtNomb<?php echo $cont;?>" class="txt-sborde" style="width: 99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strNombre"];?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNomb<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNomb<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="45%" align="left">
					<input type="hidden" id="hdnNota<?php echo $cont;?>" value="<?php echo $rst["strNota"];?>" />
					<input id="txtNota<?php echo $cont;?>" class="txt-sborde" style="width: 99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo $rst["strNota"];?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNota<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="12%" align="right"><?php echo number_format($rst["dblNeto"], 0, '', '.');?>&nbsp;</td>
			</tr>
		<?php	break;
			case 8:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="8%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="12%" align="center">
					<a href="#" title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="15%" align="left">&nbsp;<?php echo $rst["strCargo"];?></td>
				<td width="30%" align="left">
					<input type="hidden" id="hdnNomb<?php echo $cont;?>" value="<?php echo trim($rst["strNombre"]);?>" />
					<input id="txtNomb<?php echo $cont;?>" class="txt-sborde" style="width: 100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strNombre"]);?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNomb<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNomb<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="30%" align="left">
					<input type="hidden" id="hdnBod<?php echo $cont;?>" value="<?php echo trim($rst["strDescBodega"]);?>" />
					<input id="txtBod<?php echo $cont;?>" class="txt-sborde" style="width: 100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strDescBodega"]);?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnBod<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnBod<?php echo $cont;?>');
						"
					/>
				</td>
			</tr>
		<?php	break;
			case 9:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="8%" align="center"><?php echo $rst["dtmFecha"];?></td>
				<td width="12%" align="center">
					<a href="#" title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="15%" align="left">&nbsp;<?php echo $rst["strCargo"];?></td>
				<td width="20%" align="left">
					<input type="hidden" id="hdnNomb<?php echo $cont;?>" value="<?php echo trim($rst["strNombre"]);?>" />
					<input id="txtNomb<?php echo $cont;?>" class="txt-sborde" style="width: 100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strNombre"]);?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNomb<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNomb<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="20%" align="left">
					<input type="hidden" id="hdnBod<?php echo $cont;?>" value="<?php echo trim($rst["strDescBodega"]);?>" />
					<input id="txtBod<?php echo $cont;?>" class="txt-sborde" style="width: 100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strDescBodega"]);?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnBod<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnBod<?php echo $cont;?>');
						"
					/>
				</td>
				<td width="20%" align="left">
					<input type="hidden" id="hdnNombUsu<?php echo $cont;?>" value="<?php echo trim($rst["strNombUsuario"]);?>" />
					<input id="txtNombUsu<?php echo $cont;?>" class="txt-sborde" style="width: 100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strNombUsuario"]);?>" 
						onmouseover="javascript:
							clearInterval(Intervalo); 
							Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombUsu<?php echo $cont;?>\')', 250);
						"
						onmouseout="javascript:
							DetieneTexto(Intervalo, this.id, 'hdnNombUsu<?php echo $cont;?>');
						"
					/>
				</td>
			</tr>
		<?php	break;
			case 10:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
				<td width="13%" align="center">
					<a href="#" title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="75%" align="left">&nbsp;<?php echo $rst["strNombre"];?></td>
			</tr>
		<?php	break;
			case 11:?>
			<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
				<td width="3%" align="center"><?php echo $cont;?></td>
				<td width="10%" align="center"><?php echo $rst["dtmFecha"];?></td>
				<td width="9%" align="center">
					<a href="#" title="Ver documento Nº <?php echo $rst["dblNum"];?>..."
						onclick="javascript: 
							parent.parent.Deshabilita(true);
							MuestraDocumento('<?php echo $rst["dblNumero"];?>');
						"
						onmouseover="javascript: window.status='Ver documento Nº <?php echo $rst["dblNum"];?>...'; return true;"
					><?php echo $rst["dblNum"];?></a>
				</td>
				<td width="66%" align="left">&nbsp;<?php echo $rst["strDetalle"];?></td>
				<td width="10%" align="right"><?php echo number_format($rst["dblTotal"], 0, '', '.');?></td>
			</tr>
		<?php	break;
		}
	}while($rst=mssql_fetch_array($stmt));
}else{?>
	<tr><td align="center" style="color:#FF0000"><b>No se han encontrado documentos.</b></td></tr>
<?php
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" id="hdnTotal" value="<?php echo number_format($total, 0, '', '.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>