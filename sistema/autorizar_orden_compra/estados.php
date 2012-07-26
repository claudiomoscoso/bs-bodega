<?php
include '../conexion.inc.php';
$numero=$_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Estados</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
function Load(){
	document.getElementById('divDet').style.height=parent.document.getElementById('historia').height-20;
	if(document.getElementById('totfil').value>5)
		document.getElementById('tbl').width='95%';
	else
		document.getElementById('tbl').width='100%';
}
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<th width="30%">Fecha</th>
		<th width="38%" align="left">&nbsp;Usuario</th>
		<th width="30%" align="left">&nbsp;Estado</th>
		<th width="2%">&nbsp;</th>
	</tr>
</table>
<div id="divDet" style="position:absolute; width:100%; height:130px; overflow:scroll">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<?php
$stmt = mssql_query("EXEC sp_getEstados 'HST', NULL, $numero", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
	echo '<td width="30%" align="center">'.formato_fecha($rst["dtmFch"], false, false).'</td>';
	echo '<td width="38%" align="left">&nbsp;'.$rst["nombre"].'</td>';
	echo '<td width="30%" align="left">&nbsp;'.$rst["strDetalle"].'</td>';
	echo '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</div>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>