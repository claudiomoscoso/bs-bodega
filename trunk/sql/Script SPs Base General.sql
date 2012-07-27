/*
FECHA: 19/12/2007
VER.: 1.5
*/
CREATE            PROC sp_getBodega
@dblModulo NUMERIC,
@strValor VARCHAR(50) = NULL
AS
IF @dblModulo = 0
	SELECT * FROM Contrato WHERE strContrato = @strValor
ELSE IF @dblModulo = 1
	SELECT Tablon.strCodigo, Tablon.strDetalle FROM Tablon INNER JOIN Contrato ON (Tablon.strCodigo = Contrato.strBodega AND Tablon.strTabla='bodeg')
	WHERE Tablon.strVigente = '1' AND Contrato.dblTipo = 0 ORDER BY Tablon.strDetalle
ELSE IF @dblModulo = 3
	SELECT BodegaUsuario.strBodega, Tablon.strDetalle
	FROM BodegaUsuario INNER JOIN Tablon ON (BodegaUsuario.strBodega = Tablon.strCodigo AND Tablon.strTabla = 'bodeg' AND BodegaUsuario.intVigente = 1)
	WHERE BodegaUsuario.strUsuario = @strValor ORDER BY Tablon.strDetalle
ELSE IF @dblModulo = 4
	SELECT BodegaUsuario.strBodega, Tablon.strDetalle, BodegaUsuario.intVigente
	FROM BodegaUsuario INNER JOIN Tablon ON (BodegaUsuario.strBodega = Tablon.strCodigo AND Tablon.strTabla = 'bodeg' AND BodegaUsuario.intVigente >= 1)
	WHERE BodegaUsuario.strUsuario = @strValor ORDER BY Tablon.strDetalle
ELSE IF @dblModulo = 5
	SELECT BodegaUsuario.strBodega, Tablon.strDetalle
	FROM BodegaUsuario INNER JOIN Tablon ON (BodegaUsuario.strBodega = Tablon.strCodigo AND Tablon.strTabla = 'bodeg' AND BodegaUsuario.intVigente >= 1)
	ORDER BY Tablon.strDetalle
ELSE IF  @dblModulo = 6
	SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla = 'bodeg' AND strVigente = '1' ORDER BY strDetalle

GO
/*
FECHA: 20/05/2008
VER.: 2.16
*/
CREATE PROCEDURE dbo.sp_getCargos
@dblModulo NUMERIC,
@strBodega VARCHAR(10) = NULL,
@strCodigo VARCHAR(50) = NULL,
@strUsuario VARCHAR(50) = NULL,
@strTBusq VARCHAR(1) = NULL
AS
DECLARE @strSQL VARCHAR(5000)
IF @dblModulo = 0
BEGIN
   SET @strSQL = 'SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle, Contrato.strDireccion
   FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato=Tablon.strCodigo)
   WHERE Contrato.strCodigo='''+@strCodigo+''''
   IF NOT @strBodega IS NULL SET @strSQL = @strSQL + ' AND Contrato.strBodega='''+@strBodega+''''
   EXEC(@strSQL)
END
ELSE IF @dblModulo = 1
BEGIN
	IF @strBodega = '12001' OR @strBodega = '12000' OR @strBodega = '12027'
		SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle
		FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo)
		WHERE Contrato.intVigente = 1 
		ORDER BY Contrato.strDetalle
	ELSE
		SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle
		FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo)
		WHERE Contrato.intVigente = 1 AND Contrato.strBodega = @strBodega
		ORDER BY Contrato.strDetalle
END
ELSE IF @dblModulo = 2
BEGIN
   SET @strSQL = 'SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle
   FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato=Tablon.strCodigo)
   WHERE Contrato.intVigente=1' 
   IF @strBodega<>'12027' AND @strBodega<>'12001' SET @strSQL = @strSQL +' AND Contrato.strBodega='''+@strBodega+''''
   SET @strSQL = @strSQL +' ORDER BY Contrato.strDetalle'
   EXEC(@strSQL)
END
ELSE IF @dblModulo = 3
BEGIN
	SET @strSQL = 'SELECT DISTINCT strCodigo, strDetalle FROM Contrato'
	IF @strCodigo <> ''
		SET @strSQL = @strSQL + ' WHERE (strCodigo LIKE '''+@strCodigo + '%'' OR strDetalle LIKE ''%' + @strCodigo + '%'')'
	SET @strSQL = @strSQL + ' ORDER BY strDetalle'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 4
BEGIN
   SET @strSQL = 'SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle, Contrato.strDireccion
   FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato=Tablon.strCodigo)
   WHERE Contrato.intVigente = 1 AND (Contrato.strCodigo LIKE '''+@strCodigo + '%'' OR Tablon.strDetalle LIKE ''%' + @strCodigo + '%'')'
   IF @strBodega<>'12027' SET @strSQL = @strSQL + ' AND Contrato.strBodega='''+@strBodega+''''
   SET @strSQL = @strSQL + ' ORDER BY Contrato.strDetalle'
   EXEC(@strSQL)
END
ELSE IF @dblModulo = 5
	SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo  
	FROM Contrato INNER JOIN BodegaUsuario ON (Contrato.strBodega = BodegaUsuario.strBodega AND Contrato.intVigente = 1 AND BodegaUsuario.intVigente = 1)
	WHERE BodegaUsuario.strUsuario = @strCodigo
	ORDER BY Contrato.strDetalle
ELSE IF @dblModulo = 6
BEGIN
	DECLARE @dblCtrl NUMERIC
	SET @dblCtrl = 0
	DECLARE personal_cursor CURSOR FOR
	SELECT dblControl FROM General..Usuarios WHERE usuario = @strCodigo
	OPEN personal_cursor
	FETCH NEXT FROM personal_cursor INTO @dblCtrl
	IF @@FETCH_STATUS <> 0 SET @dblCtrl = 0
	CLOSE personal_cursor
	DEALLOCATE personal_cursor
	
	IF @dblCtrl = 1
		SELECT strCodigo, strDetalle AS strCargo
		FROM Contrato
   		WHERE intVigente = 1
   		ORDER BY strDetalle
	ELSE
		SELECT strCodigo, strDetalle AS strCargo
		FROM Contrato
   		WHERE intVigente = 1 AND strBodega IN (SELECT strBodega FROM BodegaUsuario WHERE strUsuario = @strCodigo)
   		ORDER BY strDetalle
END
ELSE IF @dblModulo = 7
	SELECT DISTINCT Contrato.strCodigo, Contrato.strDetalle AS strCargo  
	FROM Contrato INNER JOIN BodegaUsuario ON (Contrato.strBodega = BodegaUsuario.strBodega)
	WHERE Contrato.intVigente = 1 OR Contrato.intVigente = 2
	ORDER BY Contrato.strDetalle
ELSE IF @dblModulo = 8

	SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo  
	FROM Contrato INNER JOIN BodegaUsuario ON (Contrato.strBodega = BodegaUsuario.strBodega)
	WHERE Contrato.intVigente >= 1 AND BodegaUsuario.strUsuario = @strCodigo
	ORDER BY Contrato.strDetalle
ELSE IF @dblModulo = 9
	SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle
      FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo)
      WHERE Contrato.intVigente >= 1 AND Contrato.strBodega = @strBodega
      ORDER BY Contrato.strDetalle
ELSE IF @dblModulo = 10
BEGIN
	SET @strSQL = 'SELECT Contrato.strCodigo, Contrato.strDetalle
	FROM Contrato INNER JOIN BodegaUsuario ON (Contrato.strBodega = BodegaUsuario.strBodega AND Contrato.intVigente = 1 AND BodegaUsuario.intVigente = 1)
	WHERE BodegaUsuario.strUsuario = ''' + @strUsuario + ''''
	IF @strTBusq = 'C'
		SET @strSQL = @strSQL + ' AND (Contrato.strCodigo LIKE ''' + @strCodigo + '%'' OR Contrato.strDetalle LIKE ''%' + @strCodigo + '%'')'
	ELSE
		SET @strSQL = @strSQL + ' AND Contrato.strCodigo = ''' + @strCodigo + ''''
	SET @strSQL = @strSQL + ' ORDER BY Contrato.strDetalle'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 11
BEGIN
	SET @strSQL = 'SELECT strCodigo, strDetalle FROM Contrato WHERE intVigente = 1'
	IF @strTBusq = 'C'
		SET @strSQL = @strSQL + ' AND (strCodigo LIKE ''' + @strCodigo + '%'' OR strDetalle LIKE ''%' + @strCodigo + '%'')'
	ELSE
		SET @strSQL = @strSQL + ' AND strCodigo = ''' + @strCodigo + ''''
	SET @strSQL = @strSQL + ' ORDER BY strDetalle'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 12
	SELECT strCodigo, strDetalle FROM Contrato WHERE intVigente = 1 ORDER BY strDetalle
ELSE IF @dblModulo = 13
BEGIN
	SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo  
	FROM Contrato INNER JOIN BodegaUsuario ON (Contrato.strBodega = BodegaUsuario.strBodega AND Contrato.intVigente > 0 AND BodegaUsuario.intVigente > 0)
	WHERE BodegaUsuario.strUsuario = @strCodigo
	ORDER BY Contrato.strDetalle
END
ELSE IF @dblModulo=14
		SELECT Contrato.strCodigo, Contrato.strDetalle AS strCargo, Contrato.strContrato, Tablon.strDetalle
		FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo)
		WHERE Contrato.intVigente = 1 AND Contrato.strCodigo = @strCodigo
		ORDER BY Contrato.strDetalle
GO
/*
AUTOR: RFGP
FECHA: 11-12-2006
MODIF: 0
*/
CREATE PROCEDURE dbo.sp_getCentroCosto
AS
SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla='cenco' AND strVigente='1' ORDER BY strDetalle

GO

/*
AUTOR: RFGP
FECHA: 26/07/2007
VER: 1.0
*/
CREATE  PROC sp_getComunas
@dblModulo NUMERIC,
@strContrato VARCHAR(50)
AS
IF @dblModulo = 0
	SELECT strCodigo, strDetalle FROM Tablon 
	WHERE strTabla = 'comun' AND strVigente = '1' AND strContrato = @strContrato
	ORDER BY strDetalle


GO
/*
FECHA: 07/02/2008
VER.: 2.2
*/
CREATE PROCEDURE dbo.sp_getContratos
@dblModulo NUMERIC, 
@strValor CHAR(50) = NULL
AS
DECLARE @strSQL VARCHAR(1000)
IF @dblModulo = 99 --Borrar al terminar la migracion
   SELECT Tablon.strCodigo, Tablon.strDetalle
   FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato=Tablon.strCodigo)
   WHERE Contrato.strCodigo=@strValor
   ORDER BY Contrato.dblMatriz DESC, Tablon.strDetalle
ELSE IF @dblModulo = 0
BEGIN
   SET @strSQL = 'SELECT * FROM Contrato WHERE intVigente=1'
   IF @strValor<>'12027' AND @strValor<>'12001' SET @strSQL = @strSQL + ' AND strBodega=''' + @strValor + ''''
   SET @strSQL = @strSQL + ' ORDER BY dblMatriz DESC, strDetalle'
   EXEC(@strSQL)
END
ELSE IF @dblModulo = 1
BEGIN
   	SELECT DISTINCT ContratoUsuario.strContrato, Tablon.strDetalle
	FROM ContratoUsuario INNER JOIN Tablon ON (ContratoUsuario.strContrato = Tablon.strCodigo AND ContratoUsuario.intVigente = 1 AND Tablon.strTabla = 'contr' AND Tablon.strVigente = '1')
	WHERE ContratoUsuario.strUsuario = @strValor
	ORDER BY Tablon.strDetalle
END
ELSE IF @dblModulo = 2
   	SELECT DISTINCT Contrato.strContrato, Tablon.strDetalle 
   	FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo AND Tablon.strTabla = 'contr' AND Contrato.intVigente = 1 AND Tablon.strVigente = 1)
	INNER JOIN BodegaUsuario ON (Contrato.strBodega = BodegaUsuario.strBodega AND BodegaUsuario.intVigente = 1)
   	WHERE BodegaUsuario.strUsuario = @strValor
   	ORDER BY Tablon.strDetalle
ELSE IF @dblModulo = 3
	SELECT DISTINCT Contrato.strContrato, Tablon.strDetalle 
   	FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo AND Tablon.strTabla = 'contr' AND Contrato.intVigente = 1 AND Tablon.strVigente = 1)
	WHERE Contrato.dblTipo = 1 ORDER BY Tablon.strDetalle 
ELSE IF @dblModulo = 4
	SELECT Contrato.strContrato, Tablon.strDetalle FROM Contrato INNER JOIN Tablon ON (Contrato.strContrato = Tablon.strCodigo)
	WHERE Contrato.intVigente = 1 AND Contrato.dblMatriz = 1 AND Contrato.dblTipo in (0, 1) ORDER BY Tablon.strDetalle
ELSE IF @dblModulo = 5
	SELECT strCodigo, strDetalle FROM Tablon WHERE strVigente = '1' AND strTabla = 'contr' ORDER BY strDetalle
GO
/*
AUTOR: RFGP
FECHA: 10/10/2007
VER: 1.1
*/
CREATE  PROC sp_getEncargadoFondoFijo
@dblModulo NUMERIC,
@strBodega VARCHAR(50) = NULL,
@strUsuario VARCHAR(50) = NULL
AS
IF @dblModulo = 0
	SELECT strUsuario, strNombre FROM EncargadoFondoFijo WHERE strBodega = @strBodega ORDER BY strNombre
ELSE IF @dblModulo = 1
	SELECT strUsuario, strNombre, strBodega FROM EncargadoFondoFijo WHERE strUsuario = @strUsuario

GO
/*
AUTOR: RFGP
FECHA: 31/10/2007
VER: 1.3
*/
CREATE      PROC sp_getEstados
@dblModulo NUMERIC,
@strContrato VARCHAR(50) = NULL
AS
IF @dblModulo = 0
 	SELECT strCodigo, strDetalle FROM Orden..Tablon 
 	WHERE strTabla = 'tanex' AND intVigente = 1 AND strDetalle <> '' AND strContrato = @strContrato 
 	ORDER BY strDetalle
ELSE IF @dblModulo = 1
	SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla = 'estfa' ORDER BY strCodigo
ELSE IF @dblModulo = 2
 	SELECT strCodigo, strDetalle FROM Orden..Tablon 
 	WHERE strTabla = 'tanex' AND strContrato = @strContrato 
 	ORDER BY strDetalle

GO


/*
AUTOR: RFGP
FECHA: 09/08/2007
VER: 1.0
*/
create PROC sp_getFormatosInforme
@dblModulo NUMERIC,
@strUsuario VARCHAR(50),
@dblCodigo VARCHAR(50) = NULL
AS
IF @dblModulo = 0
	SELECT dblCodigo, strNombre FROM FormatosInforme 
	WHERE strUsuario = @strUsuario ORDER BY strNombre
ELSE IF @dblModulo = 1
	SELECT strCampos, strNombre FROM FormatosInforme 
	WHERE strUsuario = @strUsuario AND dblCodigo = CONVERT(NUMERIC, @dblCodigo)



GO


/*
AUTOR: RFGP
FECHA: 07/02/2007
VER: 1.0
*/
CREATE   PROC sp_getInformativo
@dblModulo NUMERIC,
@dblId NUMERIC = NULL
AS
IF @dblModulo=0
   SELECT MIN(dblId) AS dblMinId, MAX(dblId) AS dblMaxId FROM Informativos WHERE dblActiva=1
ELSE IF @dblModulo=1
   SELECT TOP 1 dblId, CONVERT(VARCHAR, dtmFecha, 103) AS dtmFch, strTitulo, strCuerpo 
   FROM Informativos WHERE dblActiva=1 AND dblId = @dblId 
   ORDER BY dtmFecha DESC



GO
/*
AUTOR: RGP
FECHA: 28/11/2007
VER: 1.0
*/
CREATE PROCEDURE dbo.sp_getMenus
@strUsuario VARCHAR(100),
@strTipo VARCHAR(1)
AS
SELECT Usuarios.usuario, Usuarios.perfil, Menu.strMenu, Menu.strLink, Menu.strTipo, Menu.strClase
FROM Menu INNER JOIN Usuarios ON (Menu.strGrupo = Usuarios.perfil)
WHERE Usuarios.usuario = @strUsuario AND Menu.strTipo = @strTipo AND Menu.Nuevo = 1
ORDER BY Menu.strClase, Menu.strMenu

GO
/*
FECHA: 15/02/2008
VER: 1.0
*/
CREATE PROC sp_getModulo
@dblModulo NUMERIC
AS
IF @dblModulo = 0
	SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla = 'modep' ORDER BY strDetalle

GO
/*
FECHA: 11/12/2007
VER.: 1.12
*/
CREATE PROCEDURE dbo.sp_getMoviles
@dblModulo NUMERIC,
@strBodega VARCHAR(50) = NULL,
@strValor VARCHAR(100) = NULL,
@strTipoBusq VARCHAR(1) = NULL, 
@strPerfil VARCHAR(50) = NULL
AS
DECLARE @strSQL VARCHAR(1000)
SET @strSQL=''
IF @dblModulo=0
BEGIN
   SET @strSQL='SELECT Moviles.strMovil, Personal.strNombre
   FROM General..Movil AS Moviles INNER JOIN General..PersonalObras AS Personal ON (Moviles.strRut = Personal.strRut)
   WHERE Moviles.intVigente=1 AND Moviles.strBodega = ''' + @strBodega + ''''
   IF @strTipoBusq='C' 
      SET @strSQL = @strSQL + ' AND (Moviles.strMovil LIKE '''+@strValor+'%'' OR Personal.strNombre LIKE ''%'+@strValor+'%'')'
   ELSE
      SET @strSQL = @strSQL + ' AND (Moviles.strMovil = '''+@strValor+''' OR Personal.strNombre = '''+@strValor+''')'
   EXEC(@strSQL)
END
ELSE IF @dblModulo=1
   SELECT Personal.strRut, Personal.strNombre, Personal.dblNivel
   FROM General..PersonalObras AS Personal
   WHERE Personal.strContrato = @strBodega AND Personal.strRut = @strValor
ELSE IF @dblModulo=2
BEGIN
	SELECT DISTINCT Moviles.strMovil, Personal.strNombre, Moviles.strContrato, Contratos.strDetalle AS strDescContrato, Moviles.strBodega, Bodegas.strDetalle AS strDescBodega, Moviles.intVigente 
	FROM General..Movil AS Moviles INNER JOIN general..PersonalObras AS Personal ON (Moviles.strRut = Personal.strRut AND Moviles.strBodega = Personal.strBodega)
	INNER JOIN General..Tablon AS Bodegas ON (Moviles.strBodega=Bodegas.strCodigo)
	INNER JOIN General..Tablon AS Contratos ON (Moviles.strContrato=Contratos.strCodigo)
	WHERE Moviles.strBodega = @strBodega ORDER BY Personal.strNombre
END
ELSE IF @dblModulo=3
BEGIN
   	SET @strSQL='SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre
	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND PersonalObras.dblVigente = 1 AND Movil.intVigente=1)
	INNER JOIN BodegaUsuario ON (Movil.strBodega = BodegaUsuario.strBodega AND BodegaUsuario.intVigente = 1)
	WHERE BodegaUsuario.strBodega = ''' + @strBodega + ''''
   	IF @strTipoBusq='C' 
      	SET @strSQL = @strSQL + ' AND (Movil.strMovil LIKE '''+@strValor+'%'' OR PersonalObras.strNombre LIKE ''%'+@strValor+'%'')'
   	ELSE
    		SET @strSQL = @strSQL + ' AND (Movil.strMovil = '''+@strValor+''' OR PersonalObras.strNombre = '''+@strValor+''')'

   	EXEC(@strSQL)
END
ELSE IF @dblModulo = 4
BEGIN
	CREATE TABLE #tmp_movil (strMovil VARCHAR(50), strNombre VARCHAR(200))
	
	INSERT #tmp_movil 
	SELECT CaratulaOrden.strMovil, PersonalObras.strNombre
	FROM Orden..CaratulaOrden AS CaratulaOrden INNER JOIN Movil ON (CaratulaOrden.strMovil = Movil.strMovil AND Movil.intVigente = 1)
	INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND PersonalObras.dblVigente = 1 AND Movil.strContrato = PersonalObras.strContrato)
	WHERE Movil.strTipo IN ('0', '1') AND CaratulaOrden.dblCorrelativo = CONVERT(NUMERIC, @strValor)
	
	INSERT #tmp_movil 
	SELECT DISTINCT Anexos.strMovil, PersonalObras.strNombre 
	FROM Orden..Anexos AS Anexos INNER JOIN Movil ON (Anexos.strMovil = Movil.strMovil AND Movil.intVigente = 1 AND Movil.strTipo IN ('0', '1'))
	INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND PersonalObras.dblVigente = 1 AND Movil.strContrato = PersonalObras.strContrato)
	WHERE Anexos.dblCorrelativo = CONVERT(NUMERIC, @strValor) AND NOT Anexos.strMovil IN (SELECT strMovil FROM Orden..CaratulaOrden WHERE dblCorrelativo = CONVERT(NUMERIC, @strValor))
	
	SELECT * FROM #tmp_movil 
	
	DROP TABLE #tmp_movil 
END
ELSE IF @dblModulo=5
	SELECT Movil.strMovil, PersonalObras.strNombre
	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND Movil.intVigente = 1 AND PersonalObras.dblVigente = 1)
	INNER JOIN BodegaUsuario ON (Movil.strBodega = BodegaUsuario.strBodega AND BodegaUsuario.intVigente = 1)
	WHERE BodegaUsuario.strUsuario = @strValor ORDER BY PersonalObras.strNombre
ELSE IF @dblModulo=6
BEGIN
   	SET @strSQL = 'SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre
   	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND Movil.strTipo IN (''0'', ''1'') AND Movil.intVigente = 1 AND PersonalObras.dblVigente = 1 AND PersonalObras.strContrato='''+@strValor+''')'   	
 	IF @strValor <> 'all' SET @strSQL = @strSQL + ' WHERE Movil.strContrato = ''' + @strValor + ''''
   	SET @strSQL = @strSQL + ' ORDER BY PersonalObras.strNombre'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 7
BEGIN
   	SET @strSQL = 'SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre
   	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut)
   	WHERE Movil.intVigente = 1 AND PersonalObras.dblVigente = 1 AND Movil.strTipo=''2'''
	IF @strTipoBusq='C' 
      	SET @strSQL = @strSQL + ' AND (Movil.strMovil LIKE ''' + @strValor + '%'' OR PersonalObras.strNombre LIKE ''%' + @strValor + '%'')'
   	ELSE
    		SET @strSQL = @strSQL + ' AND Movil.strMovil = '''+@strValor+''''

   	SET @strSQL = @strSQL + ' ORDER BY PersonalObras.strNombre'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 8
BEGIN
   	SET @strSQL = 'SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre
   	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND Movil.strTipo IN (''0'', ''1'') AND Movil.intVigente = 1 AND PersonalObras.dblVigente = 1)'
	IF @strTipoBusq='C' 
      	SET @strSQL = @strSQL + ' WHERE (Movil.strMovil LIKE ''' + @strValor + '%'' OR PersonalObras.strNombre LIKE ''%' + @strValor + '%'')'
   	ELSE
    		SET @strSQL = @strSQL + ' WHERE Movil.strMovil = '''+@strValor+''''

   	SET @strSQL = @strSQL + ' AND Movil.strContrato = '''+@strBodega+''' ORDER BY PersonalObras.strNombre'
	EXEC(@strSQL)
END
ELSE IF @dblModulo=9
BEGIN
   	CREATE TABLE #tmp_tabla (strMovil VARCHAR(50), strNombre VARCHAR(200), strTipo VARCHAR(10))
	IF @strBodega = '12001' AND @strPerfil = 'j.bodega' 	
	BEGIN
		SET @strSQL = 'INSERT #tmp_tabla SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre, Movil.strTipo
		FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND PersonalObras.dblVigente = 1 AND Movil.intVigente=1)
		WHERE Movil.strBodega = ''12001'' AND Movil.strTipo <> 2'
		IF @strTipoBusq='C' 
	      	SET @strSQL = @strSQL + ' AND (Movil.strMovil LIKE '''+@strValor+'%'' OR PersonalObras.strNombre LIKE ''%'+@strValor+'%'')'
	   	ELSE
	    		SET @strSQL = @strSQL + ' AND (Movil.strMovil = '''+@strValor+''' OR PersonalObras.strNombre = '''+@strValor+''')'
		EXEC(@strSQL)
		
		SET @strSQL= 'INSERT #tmp_tabla SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre, Movil.strTipo
		FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND PersonalObras.dblVigente = 1 AND Movil.intVigente=1)
		WHERE Movil.strTipo = 2'
		IF @strTipoBusq='C' 
	      	SET @strSQL = @strSQL + ' AND (Movil.strMovil LIKE '''+@strValor+'%'' OR PersonalObras.strNombre LIKE ''%'+@strValor+'%'')'
	   	ELSE
	    		SET @strSQL = @strSQL + ' AND (Movil.strMovil = '''+@strValor+''' OR PersonalObras.strNombre = '''+@strValor+''')'
		EXEC(@strSQL)
	END
	ELSE
	BEGIN
--24/08!2007 P.A.T.
-- He comentareado la condicion Movi.strTipo <> 2 , debido a que el Bodeguero debe poder descargar materiales a su obra y obras anexas
		SET @strSQL = 'INSERT #tmp_tabla SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre, Movil.strTipo
		FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND PersonalObras.dblVigente = 1 AND Movil.intVigente=1)
		WHERE Movil.strBodega = ''' + @strBodega + ''''
	   	IF @strTipoBusq='C' 
	      	SET @strSQL = @strSQL + ' AND (Movil.strMovil LIKE '''+@strValor+'%'' OR PersonalObras.strNombre LIKE ''%'+@strValor+'%'')'
	   	ELSE
	    		SET @strSQL = @strSQL + ' AND (Movil.strMovil = '''+@strValor+''' OR PersonalObras.strNombre = '''+@strValor+''')'
		EXEC(@strSQL)
	END
	
   	SELECT * FROM #tmp_tabla ORDER BY strNombre
	DROP TABLE #tmp_tabla
END
ELSE IF @dblModulo = 10
BEGIN

   	SET @strSQL = 'SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre, Movil.strTipo
	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND Movil.strTipo IN (''0'', ''1'') AND Movil.intVigente = 1 AND PersonalObras.dblVigente = 1)
   	WHERE Movil.strContrato = ''' + @strBodega + ''''
	IF @strTipoBusq='C' 
      	SET @strSQL = @strSQL + ' AND (Movil.strMovil LIKE ''' + @strValor + '%'' OR PersonalObras.strNombre LIKE ''%' + @strValor + '%'')'
   	ELSE
    		SET @strSQL = @strSQL + ' AND Movil.strMovil = '''+@strValor+''''

   	SET @strSQL = @strSQL + ' ORDER BY PersonalObras.strNombre'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 11
	SELECT Movil.strMovil, PersonalObras.strNombre 
	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND Movil.strBodega = PersonalObras.strBodega)
	WHERE Movil.intVigente = 1 AND PersonalObras.dblVigente = 1 AND Movil.strBodega = '1200A'
ELSE IF @dblModulo = 12
	SELECT DISTINCT Moviles.strMovil, Personal.strNombre
	FROM General..Movil AS Moviles INNER JOIN general..PersonalObras AS Personal ON (Moviles.strRut = Personal.strRut AND Moviles.strBodega = Personal.strBodega)
	WHERE Moviles.strBodega = @strBodega ORDER BY Personal.strNombre
ELSE IF @dblModulo = 13
	SELECT DISTINCT strMovil FROM Movil WHERE Movil.strTipo IN ('0', '1') AND Movil.strContrato = @strBodega ORDER BY strMovil
ELSE IF @dblModulo = 14
	SELECT DISTINCT Moviles.strMovil, Personal.strNombre 
	FROM General..Movil AS Moviles INNER JOIN General..PersonalObras AS Personal ON (Moviles.strRut = Personal.strRut) 
	WHERE Moviles.strTipo IN ('0', '1') AND Moviles.strContrato = @strBodega ORDER BY Personal.strNombre
ELSE IF @dblModulo = 15
	SELECT DISTINCT Movil.strMovil, PersonalObras.strNombre
   	FROM Movil INNER JOIN PersonalObras ON (Movil.strRut = PersonalObras.strRut AND Movil.strTipo IN ('0', '1', '2') AND Movil.intVigente = 1 AND PersonalObras.dblVigente = 1)
 	WHERE Movil.strContrato = @strValor ORDER BY PersonalObras.strNombre
GO

CREATE PROCEDURE dbo.sp_getObras
/*RFGP*/
@strBodega CHAR(5) = NULL
AS

IF(@strBodega IS NOT NULL)
   SELECT T2.strCodigo, T2.strDetalle FROM General..Tablon as T1
   INNER JOIN General..Tablon AS T2 ON (T1.strContrato=T2.strCodigo)
   WHERE T1.strTabla='bodeg' AND T2.strVigente='1' AND T1.strCodigo=@strBodega
ELSE
   SELECT T2.strCodigo, T2.strDetalle FROM General..Tablon as T1
   INNER JOIN General..Tablon AS T2 ON (T1.strContrato=T2.strCodigo)
   WHERE T1.strTabla='bodeg' AND T2.strVigente='1'



GO
/*
FECHA: 13/12/2007
VER: 2.5
*/
CREATE   PROC sp_getPersonalObra
@dblModulo NUMERIC,
@strBodega VARCHAR(50) = NULL, 
@strTexto VARCHAR(100) = NULL
AS
DECLARE @strSQL VARCHAR(1000)
SET @strSQL = ''
IF @dblModulo=0
BEGIN
   SET @strSQL='SELECT DISTINCT PersonalObras.strRut, PersonalObras.strNombre, PersonalObras.strContrato, Contrato.strBodega 
   FROM PersonalObras INNER JOIN Contrato ON (PersonalObras.strContrato = Contrato.strContrato)
   WHERE PersonalObras.dblVigente=1 AND PersonalObras.dblNivel IN (1, 3)
   AND PersonalObras.strBodega IN (SELECT strBodega FROM BodegaUsuario WHERE strUsuario = ''' + @strBodega + ''')
   ORDER BY PersonalObras.strNombre'
END
ELSE IF @dblModulo=1
BEGIN
   SET @strSQL='SELECT DISTINCT strRut, strNombre, strContrato FROM PersonalObras WHERE dblVigente=1 AND strBodega IN (''' + @strBodega + ''', ''1200A'')
   AND (strRut LIKE ''' + @strTexto + '%'' OR strNombre LIKE ''%' + @strTexto + '%'') ORDER BY strNombre'
END
ELSE IF @dblModulo=2
BEGIN
   SET @strSQL='SELECT DISTINCT strRut, strNombre, strContrato FROM PersonalObras WHERE dblVigente=1 AND strBodega=''' + @strBodega + '''
   AND (strRut = ''' + @strTexto + ''' OR strNombre LIKE ''%' + @strTexto + '%'') ORDER BY PersonalObras.strNombre'
END
ELSE IF @dblModulo=3
BEGIN
   SET @strSQL='SELECT strRut, strNombre, strContrato FROM PersonalObras WHERE dblVigente=1
   AND strBodega IN (SELECT strBodega FROM BodegaUsuario WHERE strUsuario = '''+@strBodega+''')'
   SET @strSQL = @strSQL + ' AND (strRut LIKE '''+@strTexto+'%'' OR strNombre LIKE ''%'+@strTexto+'%'')
   ORDER BY strNombre'
END
ELSE IF @dblModulo=4
BEGIN
   SET @strSQL='SELECT DISTINCT strRut, strNombre FROM PersonalObras WHERE dblVigente = 1'
   IF NOT @strTexto IS NULL SET @strSQL = @strSQL + ' AND (strRut LIKE ''' + @strTexto + '%'' OR strNombre LIKE ''%' +@strTexto + '%'')'
   SET @strSQL = @strSQL + ' ORDER BY strNombre'
END
ELSE IF @dblModulo = 5
BEGIN
	SET @strSQL = 'SELECT strRut, strNombre FROM PersonalObras WHERE dblVigente = 1 AND dblNivel = 1 AND strBodega = ''' + @strBodega + ''' ORDER BY strNombre'
END
ELSE IF @dblModulo=6
BEGIN
   Set @strSQL='SELECT strRut, strNombre, Contrato.strDetalle as strDescContrato, dblVigente, PersonalObras.strContrato FROM PersonalObras 
   Left Join Contrato on PersonalObras.strContrato=Contrato.strContrato
   WHERE PersonalObras.strContrato = ''' + @strBodega + ''' and dblNivel=0
   ORDER BY strNombre'
END
ELSE IF @dblModulo=7
BEGIN
   Set @strSQL='SELECT strRut, strNombre, Contrato.strDetalle as strDescContrato, dblVigente, PersonalObras.strContrato FROM PersonalObras 
   Left Join Contrato on PersonalObras.strContrato=Contrato.strContrato
   WHERE PersonalObras.strRut = ''' + @strTexto + ''''
END

EXEC(@strSQL)
GO
-- Se agregaron codigos 111, 112 y 113 para Obras del Liceo Alemán------
-- Se modifican os query para Obras del Liceo Alemán, el CC.codiCC se saca del left join y se deja como where
-- Se agrego codigo 0003 a San Pedro
CREATE    proc sp_getPersonalSoftland
as
set nocount on
------------------------ESSBIO-------------------------------------
--delete from PersonalObras where strBodega='12010' AND dblNivel not in (2,3)
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12010' as strBodega, '13043' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[8aregion].softland.sw_personal as P
--Left Join cmodelo.[8aregion].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
-------------------------A.VALLE 2007-----------------------------------------------------
delete from PersonalObras where strBodega='12033' AND dblNivel <> 2
insert into PersonalObras
select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
nombres as strNombre, '12033' as strBodega, '13060' as strContrato ,  0,
dblVigente=case
	when Max(P.fechafiniquito) > getdate() then 1
	else 0
end
from cmodelo.[4aregion].softland.sw_personal as P
Left Join cmodelo.[4aregion].softland.sw_cargoper as C
On P.ficha=C.ficha
Group By P.rut, P.nombres
order by strrut
--------------------------ESVAL-------------------------------------------
delete from PersonalObras where strBodega='12022' AND dblNivel <> 2
insert into PersonalObras
select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
nombres as strNombre, '12022' as strBodega, '13049' as strContrato, 0,
dblVigente=Case
	when Max(P.fechafiniquito) > getdate() then 1
	else 0
end
from cmodelo.[5aregion].softland.sw_personal as P
Left Join cmodelo.[5aregion].softland.sw_cargoper as C
On P.ficha=C.ficha
Group By P.rut, P.nombres
order by strrut
-------------------CRUZ DE LORENA--------------------------------------------------
--delete from PersonalObras where strBodega='12015' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12015' as strBodega, '13048' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[clorena3].softland.sw_personal as P
--Left Join cmodelo.[clorena3].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
-------------------UNAB--------------------------------------------------
--delete from PersonalObras where strBodega='12023' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12023' as strBodega, '13050' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[vinaunab2].softland.sw_personal as P
--Left Join cmodelo.[vinaunab2].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
-------------------UCINF--------------------------------------------------
--delete from PersonalObras where strBodega='12024' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12024' as strBodega, '13051' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[ucinf].softland.sw_personal as P
--Left Join cmodelo.[ucinf].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
-------------------CASONA--------------------------------------------------
--delete from PersonalObras where strBodega='12025' AND dblNivel  not in (2,3)
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12025' as strBodega, '13052' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[casona].softland.sw_personal as P
--Left Join cmodelo.[casona].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
-------------------P.NOBEL--------------------------------------------------
--delete from PersonalObras where strBodega='12026' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12026' as strBodega, '13053' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[pnobel].softland.sw_personal as P
--Left Join cmodelo.[pnobel].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
--set nocount off
-------------------S.PEDRO ATACAMA--------------------------------------------------
--delete from PersonalObras where strBodega = '12028' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12028' as strBodega, '13055' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	when C.carcod='0003' then 1
--	when C.carcod='0111' then 1
--	when C.carcod='0112' then 1
--	when C.carcod='0113' then 1
--	else 0
--end, 
--dblVigente=case
--	when Max(P.fechafiniquito) > getdate() then 1
--	else 0
--end
--from cmodelo.[SANPEDRO].softland.sw_personal as P
--Left Join cmodelo.[SANPEDRO].softland.sw_cargoper as C
--On P.ficha=C.ficha
--Group By P.rut, P.nombres, C.carCod
--order by strrut
--set nocount off
-------------------L.ALEMAN1--------------------------------------------------
--delete from PersonalObras where strBodega = '12029' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12029' as strBodega, '13056' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	when C.carcod='0111' then 1
--	when C.carcod='0112' then 1
--	when C.carcod='0113' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[LALEMAN].softland.sw_personal as P
--Left Join cmodelo.[LALEMAN].softland.sw_cargoper as C On P.ficha=C.ficha
--Left Join cmodelo.[LALEMAN].softland.sw_ccostoper as CC On P.ficha=CC.ficha 
--where CC.codiCC='0098'
--order by strrut
--set nocount off
-------------------C.E. Andres Bello--------------------------------------------------
--delete from PersonalObras where strBodega = '12037' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12037' as strBodega, '13064' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	when C.carcod='0111' then 1
--	when C.carcod='0112' then 1
--	when C.carcod='0113' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[LALEMAN].softland.sw_personal as P
--Left Join cmodelo.[LALEMAN].softland.sw_cargoper as C On P.ficha=C.ficha
--Left Join cmodelo.[LALEMAN].softland.sw_ccostoper as CC On P.ficha=CC.ficha 
--where CC.codiCC='0104'
--order by strrut
-------------------S.V y Piloto Torre Bellavista--------------------------------------------------
--delete from PersonalObras where strBodega = '12039' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12039' as strBodega, '13066' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	when C.carcod='0111' then 1
--	when C.carcod='0112' then 1
--	when C.carcod='0113' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[LALEMAN].softland.sw_personal as P
--Left Join cmodelo.[LALEMAN].softland.sw_cargoper as C On P.ficha=C.ficha
--Left Join cmodelo.[LALEMAN].softland.sw_ccostoper as CC On P.ficha=CC.ficha 
--where CC.codiCC='0105'
--order by strrut
-------------------Torre Bellavista--------------------------------------------------
--delete from PersonalObras where strBodega = '12035' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12035' as strBodega, '13062' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	when C.carcod='0111' then 1
--	when C.carcod='0112' then 1
--	when C.carcod='0113' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[LALEMAN].softland.sw_personal as P
--Left Join cmodelo.[LALEMAN].softland.sw_cargoper as C On P.ficha=C.ficha
--Left Join cmodelo.[LALEMAN].softland.sw_ccostoper as CC On P.ficha=CC.ficha 
--where CC.codiCC='0103'
--order by strrut

--Delete From General..PersonalObras
--Where strRut in ( select strRut
--from General..PersonalObras
--group by strRut, strBodega
--having count(strRut) > 1) and dblVigente=0
-------------------POST VENTA CRUZ DE LORENA--------------------------------------------------
delete from PersonalObras where strBodega='12040' AND dblNivel <> 2
insert into PersonalObras
select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
nombres as strNombre, '12040' as strBodega, '13067' as strContrato ,  
dblNivel=case
	when C.carcod='0105' then 1
	when C.carcod='0015' then 1
	when C.carcod='0102' then 1
	else 0
end, 
dblVigente=case
	when Max(P.fechafiniquito) > getdate() then 1
	else 0
end
from cmodelo.[clorena3].softland.sw_personal as P
Left Join cmodelo.[clorena3].softland.sw_cargoper as C
On P.ficha=C.ficha
Group By P.rut, P.nombres, C.carCod
order by strrut
-------------------U.S.S.--------------------------------------------------
--delete from PersonalObras where strBodega='12036' AND dblNivel <> 2
--insert into PersonalObras
--select Distinct cast(cast(substring(replace(P.rut,'.',''),1,9) as numeric) as varchar) + '-' + substring(replace(P.rut,'.',''),11,1) as strRut, 
--nombres as strNombre, '12036' as strBodega, '13063' as strContrato ,  
--dblNivel=case
--	when C.carcod='0105' then 1
--	when C.carcod='0015' then 1
--	when C.carcod='0102' then 1
--	else 0
--end, 
--dblVigente=case
--	when P.fechafiniquito > getdate() then 1
--	else 0
--end
--from cmodelo.[sanseba].softland.sw_personal as P
--Left Join cmodelo.[sanseba].softland.sw_cargoper as C
--On P.ficha=C.ficha
--order by strrut
----------------------------------------------------------------------------------------------------------
set nocount off
GO


/*
AUTOR: RFGP
FECHA: 05/04/2007
RECUPERADO: 05/04/2007
VER.: 1.0
*/
CREATE   PROC sp_getRechazos
@dblModulo NUMERIC
AS
IF @dblModulo = 0
   SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla = 'rech' AND strVigente = '1' ORDER BY strCodigo



GO
/*
AUTOR: RFGP
FECHA: 10/10/2007
VER: 1.1
*/
CREATE    PROC sp_getSoporte
@dblModulo NUMERIC,
@strUsuario VARCHAR(50) = NULL,
@strProblema VARCHAR(100) = NULL,
@dtmFecha VARCHAR(10) = NULL,
@dblSolucionado NUMERIC = NULL,
@dblNumero NUMERIC = NULL
AS
DECLARE @strSQL VARCHAR(2000)
IF @dblModulo = 0
BEGIN
	SET @strSQL = 'SELECT Soporte.dblNumero, CONVERT(VARCHAR, Soporte.dtmFecha, 120) AS dtmFch, Soporte.strSolicitante, Usuarios.nombre AS strNombre, Soporte.strMotivo, Soporte.strSolucion, CONVERT(VARCHAR, dtmSolucion, 120) AS dtmFSolucion
	FROM Soporte LEFT JOIN Usuarios ON (Soporte.strSolicitante = Usuarios.usuario)
	WHERE Soporte.strSolicitante <> '''' AND'
	IF @dblSolucionado = 1 
		SET @strSQL = @strSQL + ' NOT Soporte.dtmSolucion IS NULL'
	ELSE
		SET @strSQL = @strSQL + ' Soporte.dtmSolucion IS NULL'
	IF @strUsuario <> 'all' SET @strSQL = @strSQL + ' AND Soporte.strSolicitante = ''' + @strUsuario + ''''
	IF @strProblema <> '' SET @strSQL = @strSQL + ' AND Soporte.strProblema LIKE ''%' + @strProblema + '%'''
	IF @dtmFecha <> '' SET @strSQL = @strSQL + ' AND CONVERT(VARCHAR, Soporte.dtmFecha, 103) = ''' + @dtmFecha + ''''
	SET @strSQL = @strSQL + ' ORDER BY Soporte.dblNumero'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 1
	SELECT CONVERT(VARCHAR, Soporte.dtmFecha, 120) AS dtmFch, Soporte.strSolicitante, Usuarios.nombre AS strNombre, Soporte.strEquipo, CONVERT(TEXT, Soporte.strMotivo) AS strMotivo, 
	CONVERT(TEXT, Soporte.strProblema) AS strProblema, CONVERT(TEXT, Soporte.strDiagnostico) AS strDiagnostico, CONVERT(TEXT, Soporte.strSolucion) AS strSolucion, 
	CONVERT(TEXT, Soporte.strEvaluacion) AS strEvaluacion, CONVERT(TEXT, Soporte.strNotas) AS strNota, CONVERT(VARCHAR, dtmSolucion, 120) AS dtmFSolucion
	FROM Soporte LEFT JOIN Usuarios ON (Soporte.strSolicitante = Usuarios.usuario)
	WHERE Soporte.dblNumero = @dblNumero


GO
/*
FECHA: 24/01/2008
VER: 1.1
*/
CREATE  PROC sp_getUnidades
@dblModulo NUMERIC
AS
IF @dblModulo = 0
	SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla = 'uni' AND strVigente = 1 ORDER BY strDetalle
ELSE IF @dblModulo = 1
	SELECT strCodigo, strDetalle FROM Tablon WHERE strTabla = 'uni' AND strVigente = 1 AND dblCalificacion = 1 ORDER BY strDetalle


GO
/*
FECHA: 12/02/2008
VER: 1.2
*/
CREATE              PROC sp_getUsuarios
@dblModulo NUMERIC,
@strBodega VARCHAR(50) = NULL,
@strEstado VARCHAR(50) = NULL,
@strVigente VARCHAR(5) = 'all',
@strUsuario VARCHAR(10) = NULL
AS
DECLARE @strSQL VARCHAR(8000), @strWhere VARCHAR(8000)
SET @strWhere = ''
IF @dblModulo = 0
BEGIN
	SET @strSQL='SELECT  Usuarios.usuario, Usuarios.nombre, Usuarios.perfil, CONVERT(VARCHAR, Usuarios.dtmUltimoLogin, 120) AS dtmFch, Usuarios.bodega, Bodegas.strDetalle AS strDescBodega, 
	Usuarios.dtmUltimoLogin, Usuarios.login,  DATEDIFF(minute, Usuarios.dtmUltimoLogin, GETDATE()) AS dblSesion, Usuarios.vigente
	FROM Usuarios INNER JOIN General..Tablon AS Bodegas ON (Usuarios.bodega=Bodegas.strCodigo)'
	IF @strVigente <> 'all' SET @strWhere = ' WHERE Usuarios.vigente=' + @strVigente
	IF @strBodega<>'all'
	BEGIN
	IF @strWhere = '' SET @strWhere = @strWhere + ' WHERE' ELSE SET @strWhere = @strWhere + ' AND'
	SET @strWhere = @strWhere + ' bodega='''+@strBodega+''''
	END
	IF @strEstado<>'all'
	BEGIN
	IF @strWhere = '' SET @strWhere = @strWhere + ' WHERE' ELSE SET @strWhere = @strWhere + ' AND'
		IF @strEstado = 'login'
			SET @strWhere = @strWhere + ' (NOT login IS NULL AND DATEDIFF(minute, Usuarios.dtmUltimoLogin, GETDATE())<2)'
		ELSE IF @strEstado = 'logout'
			SET @strWhere = @strWhere + ' (login IS NULL OR DATEDIFF(minute, Usuarios.dtmUltimoLogin, GETDATE())>2)'
	END
	SET @strSQL = @strSQL + @strWhere + ' ORDER BY Usuarios.usuario'
	EXEC(@strSQL)
END
ELSE IF @dblModulo = 1
   SELECT usuario, nombre FROM Usuarios WHERE vigente=1 ORDER BY nombre
ELSE IF @dblModulo = 2
   SELECT * FROM Usuarios WHERE usuario = @strUsuario
ELSE IF @dblModulo = 3
   SELECT strUsuario, strNombre FROM EncargadoFondoFijo WHERE dblVigente = 1 AND strBodega = @strBodega ORDER BY strNombre

GO


CREATE PROCEDURE dbo.sp_ListarComunas
AS
 SELECT DISTINCT * 
 FROM Tablon 
 WHERE strTabla="comun" or strTabla="comu2"
 ORDER BY strDetalle



GO

/*
AUTOR: RFGP
FECHA: 25/01/2007
VER: 1.0
*/
CREATE  PROC sp_setActividadUsuario
@dblModulo NUMERIC,
@strUsuario VARCHAR(50),
@strIP VARCHAR(15),
@strHost VARCHAR(50),
@dblLogin NUMERIC = 0
AS
IF @dblModulo=0
   IF @dblLogin = 1
      INSERT ActividadUsuario VALUES (@strUsuario, GETDATE(), @strIP, @strHost, GETDATE())
   ELSE
      INSERT ActividadUsuario VALUES (@strUsuario, GETDATE(), @strIP, @strHost, NULL)


GO


/*
AUTOR: RFGP
FECHA: 23/05/2007
VER: 1.0
*/
create PROC sp_setAnexo
@dblModulo NUMERIC,
@strUsuario VARCHAR(50),
@dblCorrelativo NUMERIC,
@strMovil VARCHAR(50),
@strObservacion VARCHAR(8000),
@strTAnexo VARCHAR(50)
AS
DECLARE @tmp_cursor CURSOR, @dblError NUMERIC, @dblNumero NUMERIC
SET @dblError = 0
SET @dblNumero = 0

IF @dblModulo = 0
BEGIN
	SET @tmp_cursor = CURSOR FOR
	SELECT Id FROM Anexos WHERE dblCorrelativo = @dblCorrelativo AND strMovil = @strMovil
	OPEN @tmp_cursor
	FETCH NEXT FROM @tmp_cursor INTO @dblNumero
	IF @@FETCH_STATUS = 0 SET @dblError = 1
	CLOSE @tmp_cursor
	
	IF @dblError = 0
	BEGIN
		INSERT CorrelativoDoc
		VALUES (@strUsuario, 'ANX', @dblCorrelativo, GETDATE())
		
		SET @tmp_cursor = CURSOR FOR
		SELECT MAX(dblNumero) AS dblNumero FROM CorrelativoDoc WHERE strTDoc = 'ANX' AND strUsuario = @strUsuario AND dblCorrelativo = @dblCorrelativo
		OPEN @tmp_cursor
		FETCH NEXT FROM @tmp_cursor INTO @dblNumero
		IF @@FETCH_STATUS <> 0 SET @dblError = 2
		CLOSE @tmp_cursor
	
		IF @dblError = 0
			INSERT Anexos
			VALUES (@dblNumero, @dblCorrelativo, GETDATE(), @strMovil, @strObservacion, 0, NULL, @strTAnexo)
	END
	SELECT @dblError AS dblError, @dblNumero AS dblNumero
END



GO
/*
PAAT
v1.1
*/
CREATE  Proc sp_setCreaCCosto
@strNombre as varchar(50),
@strDireccion as varChar(50),
--0=edificacion, 1=mantenimiento, 2=ferrovias
@dblTipo as numeric
As
Declare @strContrato as char(5)
Declare @strBodega as char(5)
Declare @strCargo as char(5)
set @strContrato=(Select dblNumero+1 From Correlativo Where strCodigo='contr')
Update Correlativo set dblNumero=dblNumero+1 Where strCodigo='contr'
Insert Into Tablon
Select 'contr',@strContrato,@strNombre,1,@strContrato,null,null
Set @strBodega=(Select dblNumero+1 From Correlativo Where strCodigo='bodeg')
Update Correlativo set dblNumero=dblNumero+1 Where strCodigo='bodeg'
Insert Into Tablon
Select 'bodeg',@strBodega,@strNombre,1,@strContrato,null,null
Update Correlativo set dblNumero=dblNumero+1 Where strCodigo='cargo'
Update Correlativo set dblNumero=dblNumero+1 Where strCodigo='MOV'
Set @strcargo=(Select dblNumero From Correlativo Where strCodigo='cargo')
Insert Into Contrato
Select 'M' + cast(@strCargo as VarChar), @strNombre, @strContrato, @strBodega, 1, @strDireccion, 1, @dblTipo, '',''
Insert Into Bodega..Correlativo
Select 'CCH', 0, 'Cor.C.Chica - ' + @strNombre, @strBodega
Insert Into Bodega..Correlativo
Select 'DC', 0, 'Cor.Dev.Cargos - ' + @strNombre, @strBodega
Insert Into Bodega..Correlativo
Select 'DEV', 0, 'Cor.Devolucion - ' + @strNombre, @strBodega
Insert Into Bodega..Correlativo
Select 'ING', 0, 'Cor.Ingresos - ' + @strNombre, @strBodega
Insert Into Bodega..Correlativo
Select 'OCA', 0, 'Cor.O.Compra - ' + @strNombre, @strBodega
Insert Into Bodega..Correlativo
Select 'SM', 0, 'Cor.Sol.Mat. - ' + @strNombre, @strBodega
Insert Into Bodega..Correlativo
Select 'VC', 0, 'Cor.VC. - ' + @strNombre, @strBodega
GO

/*
AUTOR: RFGP
FECHA: 25/01/2007
VER: 1.0
*/
CREATE        PROC sp_setDesconecta
@strUsuario VARCHAR(50)
AS
DECLARE @dblSesion NUMERIC, @dblEstado NUMERIC
SET @dblSesion = 0
SET @dblEstado = 0
DECLARE Sesion_cursor CURSOR FOR
SELECT DATEDIFF(minute, dtmUltimoLogin, GETDATE()) AS dblSesion FROM Usuarios WHERE usuario=@strUsuario
OPEN Sesion_cursor
FETCH NEXT FROM Sesion_cursor INTO @dblSesion
IF @@FETCH_STATUS<>0 SET @dblSesion=0
CLOSE Sesion_cursor
DEALLOCATE Sesion_cursor
IF @dblSesion > 2
BEGIN
	UPDATE Usuarios SET login=NULL WHERE usuario=@strUsuario
	SET @dblEstado = 1
END
SELECT @dblEstado AS dblEstado


GO

/*
AUTOR: RFGP
FECHA: 26/01/2007
VER: 1.0
OBS.
0 - PENDIENTE
1 - Cambia la vigencia de un usuario
2 - Desconecta o libera la sesion de un usuario del sistema
*/
CREATE  PROC sp_setEditaUsuario
@dblModulo NUMERIC,
@strUsuario VARCHAR(50),
@strPassword VARCHAR(10) = NULL,
@strNombre VARCHAR(200) = NULL,
@strPerfil VARCHAR(50) = NULL,
@strEMail VARCHAR(100) = NULL,
@strTelefono VARCHAR(50) = NULL,
@strBodega VARCHAR(50) = NULL,
@strFirma VARCHAR(100) = NULL,
@dblNivel NUMERIC = NULL,
@dblVigente NUMERIC = NULL
AS
IF @dblModulo=0
BEGIN
   --PENDIENTE
   print 'pendiente'
END
ELSE IF @dblModulo=1
   UPDATE Usuarios SET vigente=@dblVigente, login=NULL WHERE usuario=@strUsuario
ELSE IF @dblModulo=2
   UPDATE Usuarios SET login=NULL WHERE usuario=@strUsuario   



GO



/*
AUTOR: RFGP
FECHA: 09/08/2007
VER: 1.0
*/
CREATE PROC sp_setFormatosInforme
@dblModulo NUMERIC,
@strUsuario VARCHAR(50),
@strNombre VARCHAR(50),
@strCampos VARCHAR(200)
AS
DECLARE  @tmp_cursor CURSOR, @dblError NUMERIC, @dblNumero NUMERIC
SET @dblError = 0
IF @dblModulo = 0
BEGIN
	SET @tmp_cursor = CURSOR FOR
	SELECT dblCodigo FROM FormatosInforme WHERE strUsuario = @strUsuario AND strNombre = @strNombre
	OPEN @tmp_cursor
	FETCH NEXT FROM @tmp_cursor INTO @dblNumero
	IF @@FETCH_STATUS = 0 SET @dblError = 1
	CLOSE @tmp_cursor
	
	IF @dblError = 0
	BEGIN
		INSERT FormatosInforme
		VALUES(@strUsuario, @strNombre, @strCampos)
		
		SET @tmp_cursor = CURSOR FOR
		SELECT MAX(dblCodigo) AS dblCodigo FROM FormatosInforme WHERE strUsuario = @strUsuario
		OPEN @tmp_cursor
		FETCH NEXT FROM @tmp_cursor INTO @dblNumero
		IF @@FETCH_STATUS <> 0 SET @dblError = 2
		CLOSE @tmp_cursor
	END
END
ELSE IF @dblModulo = 1
	UPDATE FormatosInforme SET strCampos = @strCampos WHERE strUsuario = @strUsuario AND dblCodigo = CONVERT(NUMERIC, @strNombre)
SELECT @dblError AS dblError, @dblNumero AS dblNumero






GO

/*
AUTOR: RFGP
FECHA: 20/11/2007
VER: 3.0
*/
CREATE  PROC sp_setMoviles
@dblModulo NUMERIC,
@strContrato VARCHAR(50) = NULL,
@strBodega VARCHAR(50),
@strMovil VARCHAR(50) = NULL,
@strRut VARCHAR(10) = NULL,
@strNombre VARCHAR(100) = NULL,
@dblVigente NUMERIC = NULL
AS
DECLARE @tmp_cursor CURSOR, @dblNumero NUMERIC, @dblError NUMERIC, @strPaso VARCHAR(50)
SET @dblNumero = 0
SET @dblError = 0

IF @dblModulo = 0 OR @dblModulo = 1
BEGIN
	SET @tmp_cursor = CURSOR FOR
	SELECT strBodega FROM Contrato WHERE strContrato = @strContrato
	OPEN @tmp_cursor
	FETCH NEXT FROM @tmp_cursor INTO @strBodega
	IF @@FETCH_STATUS <> 0 SET @dblError = 3
	CLOSE @tmp_cursor
END

IF @dblModulo = 0 AND @dblError = 0
BEGIN
	UPDATE Correlativo SET dblNumero = dblNumero + 1 WHERE strCodigo = 'MOV'
	
	SET @tmp_cursor = CURSOR FOR
	SELECT dblNumero FROM Correlativo WHERE strCodigo = 'MOV'
	OPEN @tmp_cursor
	FETCH NEXT FROM @tmp_cursor INTO @dblNumero
	IF @@FETCH_STATUS <> 0 SET @dblError = 1
	CLOSE @tmp_cursor
	
	IF @dblError = 0
	BEGIN
		SET @strMovil = 'M' + CONVERT(VARCHAR(50), @dblNumero)
		INSERT Movil 
		VALUES (@strMovil, CONVERT(VARCHAR(50), @dblNumero), 1, @strContrato, 1, @strBodega)
		
		INSERT PersonalObras 
		VALUES (CONVERT(VARCHAR(50), @dblNumero), @strNombre, @strBodega, @strContrato, 2, 1)
	END
END
ELSE IF @dblModulo = 1 AND @dblError = 0
BEGIN
	IF EXISTS(SELECT strMovil FROM Movil WHERE strContrato = @strContrato AND strMovil = @strMovil)
		SET @dblError = 2
	ELSE
	BEGIN
		SET @tmp_cursor = CURSOR FOR
		SELECT strMovil FROM Movil WHERE strContrato = @strContrato AND strRut = @strRut AND intVigente = 1
		OPEN @tmp_cursor
		FETCH NEXT FROM @tmp_cursor INTO @strPaso
		IF @@FETCH_STATUS = 0 SET @dblError = 2
		CLOSE @tmp_cursor
		
		IF @dblError = 0
		BEGIN
			INSERT Movil 
			VALUES (@strMovil, @strRut, 1, @strContrato, 0, @strBodega)
			
			/*INSERT PersonalObras 
			VALUES (@strRut, @strNombre, @strBodega, @strContrato, 2, 1)*/
		END
	END
END
ELSE IF @dblModulo = 2
	UPDATE Movil SET intVigente = @dblVigente WHERE strBodega = @strBodega AND strMovil = @strMovil

SELECT @dblError AS dblError, @strMovil AS strMovil


GO
/*
AUTOR: PAAT
FECHA: 11/02/2010
VER: 1.0
*/
CREATE  PROC sp_setPersonalObras
@dblModulo NUMERIC,
@strContrato VARCHAR(50),
@strBodega VARCHAR(50),
@strRut VARCHAR(12),
@strNombre VARCHAR(50),
@dblVigente NUMERIC
AS
IF @dblModulo = 0
BEGIN
    INSERT PersonalObras 
    VALUES (@strRut, @strNombre, @strBodega, @strContrato, 0, @dblVigente)
END
ELSE IF @dblModulo = 1
	UPDATE PersonalObras SET dblVigente = @dblVigente WHERE strContrato = @strContrato AND strRut = @strRut
GO
/*
AUTOR: RFGP
FECHA: 03/10/2007
VER: 1.0
*/
create PROC sp_setSoporte
@dblModulo NUMERIC,
@strMotivo VARCHAR(1000) = NULL,
@strSolicitante VARCHAR(100) = NULL,
@strProblema VARCHAR(1000) = NULL,
@strEquipo VARCHAR(50) = NULL,
@strDiagnostico VARCHAR(1000) = NULL,
@strSolucion VARCHAR(1000) = NULL,
@strEvaluacion VARCHAR(1000) = NULL,
@strNota VARCHAR(1000) = NULL,
@dblSolucionado NUMERIC = NULL,
@dblNumero NUMERIC = NULL
AS
DECLARE @dtmFSolucion DATETIME
IF @dblSolucionado = 1 SET @dtmFSolucion = GETDATE() ELSE SET @dtmFSolucion = NULL
IF @dblModulo = 0
	INSERT Soporte
	VALUES(GETDATE(), @strMotivo, @strSolicitante, @strProblema, @strEquipo, @strDiagnostico, @strSolucion, @strEvaluacion, @strNota, @dtmFSolucion)
ELSE IF @dblModulo = 1
	UPDATE Soporte 
	SET strDiagnostico = @strDiagnostico, strSolucion = @strSolucion, strEvaluacion = @strEvaluacion, strNotas = @strNota, dtmSolucion = @dtmFSolucion 
	WHERE dblNumero = @dblNumero

GO
/*
FECHA: 07/02/2008
VER: 1.2
*/
CREATE    PROC sp_setUsuarios
@dblModulo NUMERIC,
@strUsuario VARCHAR(50),
@strUsuarioAnt VARCHAR(50),
@strClave VARCHAR(50),
@strClaveAnt VARCHAR(200),
@strNombre VARCHAR(150),
@strPerfil VARCHAR(150),
@strEMail VARCHAR(255),
@strTelefono VARCHAR(150),
@strBodegas VARCHAR(5000),
@strVigentes VARCHAR(5000),
@strContratos VARCHAR(5000),
@strFirma VARCHAR(255),
@dblNivel NUMERIC,
@dblVigencia NUMERIC,
@dblMonto NUMERIC,
@dblCorrelativo NUMERIC,
@strRut VARCHAR(10),
@dblControl NUMERIC,
@dblDpto NUMERIC
AS
IF @dblModulo = 0
BEGIN
	DECLARE @dblP1 INT, @dblP2 INT, @strBLogueo VARCHAR(10), @strCBodega VARCHAR(10), @dblBVigente NUMERIC, @strCContrato VARCHAR(10)
	SET @dblP1 = 0
	SET @dblP2 = 0
	SET @strBLogueo = ''
	SET @strCBodega = ''
	SET @dblBVigente = 0
	SET @strCContrato = ''
	
	IF @strUsuarioAnt <> ''
	BEGIN
		DELETE FROM BodegaUsuario WHERE strUsuario = @strUsuarioAnt
		DELETE FROM ContratoUsuario WHERE strUsuario = @strUsuarioAnt
		DELETE FROM Usuarios WHERE usuario = @strUsuarioAnt
	END
	ELSE
	BEGIN
		DELETE FROM BodegaUsuario WHERE strUsuario = @strUsuario
		DELETE FROM ContratoUsuario WHERE strUsuario = @strUsuario
		DELETE FROM Usuarios WHERE usuario = @strUsuario
	END
	
	WHILE @strBodegas <> ''
	BEGIN
		SET @dblP1 = CHARINDEX(';', @strBodegas)
		SET @dblP2 = CHARINDEX(';', @strVigentes)
		IF @dblP1 > 0
		BEGIN
			IF @strBLogueo = '' SET @strBLogueo = SUBSTRING(@strBodegas, 1, @dblP1 - 1)
			SET @strCBodega = SUBSTRING(@strBodegas, 1, @dblP1 - 1)
			SET @dblBVigente = CONVERT(NUMERIC, SUBSTRING(@strVigentes, 1, @dblP2 - 1))
			SET @strBodegas = SUBSTRING(@strBodegas, @dblP1 + 1, LEN(@strBodegas))
			SET @strVigentes = SUBSTRING(@strVigentes, @dblP2 + 1, LEN(@strVigentes))
		END
		ELSE
		BEGIN
			IF @strBLogueo = '' SET @strBLogueo = SUBSTRING(@strBodegas, 1, LEN(@strBodegas))
			SET @strCBodega = SUBSTRING(@strBodegas, 1, LEN(@strBodegas))
			SET @dblBVigente = CONVERT(NUMERIC, SUBSTRING(@strVigentes, 1, LEN(@strVigentes)))
			SET @strBodegas = ''
			SET @strVigentes = ''
		END
		INSERT BodegaUsuario VALUES (@strUsuario, @strCBodega, @dblBVigente)
	END
	
	WHILE @strContratos <> ''
	BEGIN
		SET @dblP1 = CHARINDEX(';', @strContratos)
		IF @dblP1 > 0
		BEGIN
			SET @strCContrato = SUBSTRING(@strContratos, 1, @dblP1 - 1)
			SET @strContratos = SUBSTRING(@strContratos, @dblP1 + 1, LEN(@strContratos))
		END
		ELSE
		BEGIN
			SET @strCContrato = SUBSTRING(@strContratos, 1, LEN(@strContratos))
			SET @strContratos = ''
		END
		INSERT ContratoUsuario VALUES (@strUsuario, @strCContrato, 1)
	END
	
	IF @strClave <> ''
		SET @strClave = (SELECT General.dbo.fn_md5(@strClave))
	ELSE IF @strClaveAnt <> ''
		SET @strClave = @strClaveAnt
	ELSE
		SET @strClave = NULL

	INSERT Usuarios
	VALUES(@strUsuario, @strClave, @strNombre, @strPerfil, @strEMail, @strTelefono, NULL, @strBLogueo, @strFirma, @dblNivel, NULL, @dblVigencia, @dblMonto, @dblCorrelativo, @strRut, @dblControl, @dblDpto)
END

GO

