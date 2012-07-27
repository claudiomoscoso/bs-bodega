CREATE TABLE dbo.ActividadUsuario ( 
	id        	numeric(18,0) IDENTITY(1,1) NOT NULL,
	strUsuario	varchar(50) NULL,
	dtmFecha  	datetime NULL,
	strIP     	varchar(15) NULL,
	strHost   	varchar(50) NULL,
	dtmLogin  	datetime NULL 
	)
GO
CREATE TABLE dbo.BodegaUsuario ( 
	strUsuario	varchar(50) NOT NULL,
	strBodega 	char(5) NOT NULL,
	intVigente	numeric(18,0) NOT NULL 
	)
GO
CREATE TABLE dbo.Contrato ( 
	strCodigo   	char(10) NOT NULL,
	strDetalle  	varchar(100) NOT NULL,
	strContrato 	char(5) NOT NULL,
	strBodega   	char(5) NOT NULL,
	intVigente  	smallint NULL,
	strDireccion	varchar(200) NULL,
	dblMatriz   	numeric(18,0) NULL CONSTRAINT DF_Contrato_dblMatriz  DEFAULT (0),
	dblTipo     	numeric(18,0) NULL,
	strPrint    	varchar(200) NULL,
	strLogo     	varchar(25) NULL 
	)
GO
CREATE TABLE dbo.ContratoUsuario ( 
	strUsuario 	varchar(50) NULL,
	strContrato	char(5) NULL,
	intVigente 	numeric(18,0) NULL 
	)
GO
CREATE TABLE dbo.Correlativo ( 
	strCodigo 	nvarchar(5) NULL,
	dblNumero 	int NULL,
	strDetalle	nvarchar(100) NULL 
	)
GO
CREATE TABLE dbo.EncargadoFondoFijo ( 
	strUsuario    	varchar(50) NULL,
	strRut        	varchar(10) NULL,
	strNombre     	varchar(100) NULL,
	strBodega     	varchar(50) NULL,
	dblCorrelativo	numeric(18,0) NULL,
	dblFondoFijo  	numeric(18,0) NULL,
	dblVigente    	numeric(18,0) NULL 
	)
GO
CREATE TABLE dbo.informativos ( 
	dblId    	numeric(18,0) IDENTITY(1,1) NOT NULL,
	dblActiva	numeric(18,0) NULL CONSTRAINT DF_informativos_dblActiva  DEFAULT (1),
	dtmFecha 	datetime NULL,
	strTitulo	varchar(100) NULL,
	strCuerpo	varchar(1000) NULL 
	)
GO
CREATE TABLE dbo.Listados ( 
	Id           	numeric(18,0) IDENTITY(1,1) NOT NULL,
	strSP        	varchar(100) NOT NULL,
	strNombre    	varchar(100) NOT NULL,
	intParametros	numeric(18,0) NOT NULL,
	strParametros	varchar(200) NOT NULL,
	strCampos    	varchar(500) NULL 
	)
GO
CREATE TABLE dbo.Menu ( 
	id         	char(5) NULL,
	strGrupo   	varchar(50) NOT NULL,
	strMenu    	varchar(150) NULL,
	strLink    	varchar(150) NULL,
	strTipo    	char(1) NULL,
	intPosicion	numeric(18,0) NULL,
	nuevo      	numeric(18,0) NULL,
	strClase   	char(1) NULL 
	)
GO
CREATE TABLE dbo.Movil ( 
	strMovil   	char(10) NOT NULL,
	strRut     	char(10) NULL,
	intVigente 	smallint NULL,
	strContrato	char(5) NULL,
	strTipo    	char(1) NULL,
	strBodega  	char(5) NULL 
	)
GO
CREATE TABLE dbo.Partida ( 
	strCodigo     	varchar(20) NOT NULL,
	strDescripcion	varchar(100) NOT NULL,
	strUnidad     	varchar(5) NOT NULL,
	strBodega     	char(5) NOT NULL 
	)
GO
CREATE TABLE dbo.PersonalObras ( 
	strRut     	varchar(50) NOT NULL,
	strNombre  	varchar(100) NULL,
	strBodega  	varchar(50) NULL,
	strContrato	varchar(50) NULL,
	dblNivel   	numeric(18,0) NULL,
	dblVigente 	numeric(18,0) NULL 
	)
GO
CREATE TABLE dbo.Soporte ( 
	dblNumero     	numeric(18,0) IDENTITY(1,1) NOT NULL,
	dtmFecha      	datetime NULL,
	strMotivo     	varchar(1000) NULL,
	strSolicitante	varchar(100) NULL,
	strProblema   	varchar(1000) NULL,
	strEquipo     	varchar(50) NULL,
	strDiagnostico	varchar(1000) NULL,
	strSolucion   	varchar(1000) NULL,
	strEvaluacion 	varchar(1000) NULL,
	strNotas      	varchar(1000) NULL,
	dtmSolucion   	datetime NULL 
	)
GO
CREATE TABLE dbo.Tablon ( 
	strTabla       	char(10) NOT NULL,
	strCodigo      	char(5) NOT NULL,
	strDetalle     	varchar(1000) NOT NULL,
	strVigente     	char(1) NULL,
	strContrato    	char(5) NULL,
	strZona        	char(5) NULL,
	dblCalificacion	numeric(18,2) NULL 
	)
GO
CREATE TABLE dbo.Usuarios ( 
	id            	numeric(18,0) IDENTITY(1,1) NOT NULL,
	usuario       	char(20) NOT NULL,
	clave         	char(32) NULL,
	nombre        	varchar(100) NOT NULL,
	perfil        	varchar(20) NOT NULL,
	email         	varchar(255) NULL,
	telefono      	varchar(100) NULL,
	dtmUltimoLogin	datetime NULL,
	bodega        	char(5) NOT NULL,
	firma         	varchar(100) NULL,
	nivel         	numeric(18,0) NULL,
	login         	numeric(18,0) NULL,
	vigente       	numeric(18,0) NULL CONSTRAINT DF_Usuarios_vigente  DEFAULT (1),
	dblFondoFijo  	numeric(18,0) NULL,
	dblCorrelativo	numeric(18,0) NULL CONSTRAINT DF_Usuarios_dblCorrelativo  DEFAULT (0),
	strRut        	varchar(10) NULL,
	dblControl    	numeric(18,0) NULL,
	dblDpto       	numeric(18,0) NULL CONSTRAINT DF_Usuarios_dblDpto  DEFAULT (0) 
	)
GO

CREATE INDEX IX_ActividadUsuario
	ON dbo.ActividadUsuario(strUsuario)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_BodegaUsuario
	ON dbo.BodegaUsuario(strUsuario)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Contrato_2
	ON dbo.Contrato(strBodega)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Contrato_1
	ON dbo.Contrato(strContrato)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Contrato
	ON dbo.Contrato(strCodigo)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Correlativo
	ON dbo.Correlativo(strCodigo)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Menu_1
	ON dbo.Menu(strMenu)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Menu
	ON dbo.Menu(strGrupo)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Menu_3
	ON dbo.Menu(strMenu)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Menu_2
	ON dbo.Menu(strTipo)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Movil
	ON dbo.Movil(strMovil)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Movil_1
	ON dbo.Movil(strRut)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Partida
	ON dbo.Partida(strCodigo)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Partida_1
	ON dbo.Partida(strDescripcion)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_PersonalObras
	ON dbo.PersonalObras(strRut)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_PersonalObras_1
	ON dbo.PersonalObras(strBodega)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Tablon_2
	ON dbo.Tablon(strContrato)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Tablon_1
	ON dbo.Tablon(strCodigo)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Tablon
	ON dbo.Tablon(strTabla)
	WITH FILLFACTOR = 90
GO
CREATE INDEX IX_Usuarios
	ON dbo.Usuarios(usuario)
	WITH FILLFACTOR = 90
GO
