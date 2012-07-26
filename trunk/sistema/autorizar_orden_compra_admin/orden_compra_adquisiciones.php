<?php
include '../conexion.inc.php';
$perfil = $_GET["perfil"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autorizar Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC sp_getOrdenCompra 'AOA', NULL, 'A'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="2%" align="center">
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: 
					AbreDialogo('divOCompra', 'frmOCompra', 'autoriza_rechaza_oc.php?numero=<?php echo $rst["dblNumero"];?>', true);
					parent.document.getElementById('numero').value='<?php echo $rst["dblNumero"];?>';
					parent.document.getElementById('numsol').value='<?php echo $rst["dblNumSol"] != '' ? $rst["dblNumSol"] : 0;?>';
				"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $cont;?></a></td>
		<td width="20%" align="left">&nbsp;
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: 
					AbreDialogo('divOCompra', 'frmOCompra', 'autoriza_rechaza_oc.php?numero=<?php echo $rst["dblNumero"];?>', true);
					parent.document.getElementById('numero').value='<?php echo $rst["dblNumero"];?>';
					parent.document.getElementById('numsol').value='<?php echo $rst["dblNumSol"] != '' ? $rst["dblNumSol"] : 0;?>';
				"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $rst["Cargo"];?></a>
		</td>
		<td width="10%" align="center">
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: 
					AbreDialogo('divOCompra', 'frmOCompra', 'autoriza_rechaza_oc.php?numero=<?php echo $rst["dblNumero"];?>', true);
					parent.document.getElementById('numero').value='<?php echo $rst["dblNumero"];?>';
					parent.document.getElementById('numsol').value='<?php echo $rst["dblNumSol"] != '' ? $rst["dblNumSol"] : 0;?>';
				"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $rst["dblUltima"];?></a>
		</td>
		<td width="10%" align="center">
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: 
					AbreDialogo('divOCompra', 'frmOCompra', 'autoriza_rechaza_oc.php?numero=<?php echo $rst["dblNumero"];?>', true);
					parent.document.getElementById('numero').value='<?php echo $rst["dblNumero"];?>';
					parent.document.getElementById('numsol').value='<?php echo $rst["dblNumSol"] != '' ? $rst["dblNumSol"] : 0;?>';
				"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $rst["dtmFch"];?></a>
		</td>
		<td width="35%" align="left">&nbsp;
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: 
					AbreDialogo('divOCompra', 'frmOCompra', 'autoriza_rechaza_oc.php?numero=<?php echo $rst["dblNumero"];?>', true);
					parent.document.getElementById('numero').value='<?php echo $rst["dblNumero"];?>';
					parent.document.getElementById('numsol').value='<?php echo $rst["dblNumSol"] != '' ? $rst["dblNumSol"] : 0;?>';
				"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $rst["strObservacion"];?></a>
		</td>
		<td width="10%" align="center">&nbsp;
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: 
					AbreDialogo('divOCompra', 'frmOCompra', 'autoriza_rechaza_oc.php?numero=<?php echo $rst["dblNumero"];?>', true);
					parent.document.getElementById('numero').value='<?php echo $rst["dblNumero"];?>';
					parent.document.getElementById('numsol').value='<?php echo $rst["dblNumSol"] != '' ? $rst["dblNumSol"] : 0;?>';
				"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $rst["dtmSolicitud"];?></a>
		</td>
		<td width="10%" align="center">&nbsp;
		<?php if($perfil <> "sg.operaciones"){?>
			<a href="#" 
				onclick="javascript: AbreDialogo('divEstado', 'frmEstado', 'estados.php?numero=<?php echo $rst["dblNumero"];?>', true);"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			>
		<?php }?>	
			<?php echo $rst["Estado"];?></a>
		</td>
	</tr>
<?php 	}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil1" id="totfil1" value="<?php echo $cont;?>" />
</body>
</html>