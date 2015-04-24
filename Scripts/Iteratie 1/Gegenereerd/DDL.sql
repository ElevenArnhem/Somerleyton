/*==============================================================*/
/* DBMS name:      Microsoft SQL Server 2012                    */
/* Created on:     24-4-2015 13:27:01                           */
/*==============================================================*/


IF EXISTS (SELECT 1
   FROM SYS.SYSREFERENCES R JOIN SYS.SYSOBJECTS O ON (O.ID = R.CONSTID AND O.TYPE = 'F')
   WHERE R.FKEYID = OBJECT_ID('ANIMAL') AND O.NAME = 'FK_ANIMAL_ANIMALTOE_ENCLOSUR')
ALTER TABLE ANIMAL
   DROP CONSTRAINT FK_ANIMAL_ANIMALTOE_ENCLOSUR
go

IF EXISTS (SELECT 1
   FROM SYS.SYSREFERENCES R JOIN SYS.SYSOBJECTS O ON (O.ID = R.CONSTID AND O.TYPE = 'F')
   WHERE R.FKEYID = OBJECT_ID('ANIMAL') AND O.NAME = 'FK_ANIMAL_ANIMALTOS_SPECIES')
ALTER TABLE ANIMAL
   DROP CONSTRAINT FK_ANIMAL_ANIMALTOS_SPECIES
go

IF EXISTS (SELECT 1
   FROM SYS.SYSREFERENCES R JOIN SYS.SYSOBJECTS O ON (O.ID = R.CONSTID AND O.TYPE = 'F')
   WHERE R.FKEYID = OBJECT_ID('AREA') AND O.NAME = 'FK_AREA_HEADKEEPE_STAFF')
ALTER TABLE AREA
   DROP CONSTRAINT FK_AREA_HEADKEEPE_STAFF
go

IF EXISTS (SELECT 1
   FROM SYS.SYSREFERENCES R JOIN SYS.SYSOBJECTS O ON (O.ID = R.CONSTID AND O.TYPE = 'F')
   WHERE R.FKEYID = OBJECT_ID('ENCLOSURE') AND O.NAME = 'FK_ENCLOSUR_AREATOENC_AREA')
ALTER TABLE ENCLOSURE
   DROP CONSTRAINT FK_ENCLOSUR_AREATOENC_AREA
go

IF EXISTS (SELECT 1
   FROM SYS.SYSREFERENCES R JOIN SYS.SYSOBJECTS O ON (O.ID = R.CONSTID AND O.TYPE = 'F')
   WHERE R.FKEYID = OBJECT_ID('KEEPER') AND O.NAME = 'FK_KEEPER_KEEPERTOA_AREA')
ALTER TABLE KEEPER
   DROP CONSTRAINT FK_KEEPER_KEEPERTOA_AREA
go

IF EXISTS (SELECT 1
   FROM SYS.SYSREFERENCES R JOIN SYS.SYSOBJECTS O ON (O.ID = R.CONSTID AND O.TYPE = 'F')
   WHERE R.FKEYID = OBJECT_ID('KEEPER') AND O.NAME = 'FK_KEEPER_STAFFMEMB_STAFF')
ALTER TABLE KEEPER
   DROP CONSTRAINT FK_KEEPER_STAFFMEMB_STAFF
go

IF EXISTS (SELECT 1
            FROM  SYSINDEXES
           WHERE  ID    = OBJECT_ID('ANIMAL')
            AND   NAME  = 'ANIMALTOSPECIES_FK'
            AND   INDID > 0
            AND   INDID < 255)
   DROP INDEX ANIMAL.ANIMALTOSPECIES_FK
go

IF EXISTS (SELECT 1
            FROM  SYSINDEXES
           WHERE  ID    = OBJECT_ID('ANIMAL')
            AND   NAME  = 'ANIMALTOENCLOSURE_FK'
            AND   INDID > 0
            AND   INDID < 255)
   DROP INDEX ANIMAL.ANIMALTOENCLOSURE_FK
go

IF EXISTS (SELECT 1
            FROM  SYSOBJECTS
           WHERE  ID = OBJECT_ID('ANIMAL')
            AND   TYPE = 'U')
   DROP TABLE ANIMAL
go

IF EXISTS (SELECT 1
            FROM  SYSINDEXES
           WHERE  ID    = OBJECT_ID('AREA')
            AND   NAME  = 'HEADKEEPER_FK'
            AND   INDID > 0
            AND   INDID < 255)
   DROP INDEX AREA.HEADKEEPER_FK
go

IF EXISTS (SELECT 1
            FROM  SYSOBJECTS
           WHERE  ID = OBJECT_ID('AREA')
            AND   TYPE = 'U')
   DROP TABLE AREA
go

IF EXISTS (SELECT 1
            FROM  SYSINDEXES
           WHERE  ID    = OBJECT_ID('ENCLOSURE')
            AND   NAME  = 'AREATOENCLOSURE_FK'
            AND   INDID > 0
            AND   INDID < 255)
   DROP INDEX ENCLOSURE.AREATOENCLOSURE_FK
go

IF EXISTS (SELECT 1
            FROM  SYSOBJECTS
           WHERE  ID = OBJECT_ID('ENCLOSURE')
            AND   TYPE = 'U')
   DROP TABLE ENCLOSURE
go

IF EXISTS (SELECT 1
            FROM  SYSINDEXES
           WHERE  ID    = OBJECT_ID('KEEPER')
            AND   NAME  = 'KEEPERTOAREA_FK'
            AND   INDID > 0
            AND   INDID < 255)
   DROP INDEX KEEPER.KEEPERTOAREA_FK
go

IF EXISTS (SELECT 1
            FROM  SYSOBJECTS
           WHERE  ID = OBJECT_ID('KEEPER')
            AND   TYPE = 'U')
   DROP TABLE KEEPER
go

IF EXISTS (SELECT 1
            FROM  SYSOBJECTS
           WHERE  ID = OBJECT_ID('SPECIES')
            AND   TYPE = 'U')
   DROP TABLE SPECIES
go

IF EXISTS (SELECT 1
            FROM  SYSOBJECTS
           WHERE  ID = OBJECT_ID('STAFF')
            AND   TYPE = 'U')
   DROP TABLE STAFF
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='ADDRESS')
   DROP TYPE ADDRESS
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='COMMENT')
   DROP TYPE COMMENT
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='DATE')
   DROP TYPE DATE
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='DATETIME')
   DROP TYPE DATETIME
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='DESCRIPTION')
   DROP TYPE DESCRIPTION
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='DIAGNOSE')
   DROP TYPE DIAGNOSE
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='EXCHANGETYPE')
   EXECUTE SP_UNBINDRULE EXCHANGETYPE
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='EXCHANGETYPE')
   DROP TYPE EXCHANGETYPE
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='GENDER')
   EXECUTE SP_UNBINDRULE GENDER
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='GENDER')
   DROP TYPE GENDER
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='IMAGE')
   DROP TYPE IMAGE
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='LOCATION')
   DROP TYPE LOCATION
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='NAME')
   DROP TYPE NAME
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='NUMBER')
   DROP TYPE NUMBER
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='PASSWORD')
   DROP TYPE PASSWORD
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='PRESCRIPTION')
   DROP TYPE PRESCRIPTION
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='PRICE')
   DROP TYPE PRICE
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='QUANTITY')
   EXECUTE SP_UNBINDRULE QUANTITY
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='QUANTITY')
   DROP TYPE QUANTITY
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='TELEPHONENUMBER')
   DROP TYPE TELEPHONENUMBER
go

IF EXISTS(SELECT 1 FROM SYSTYPES WHERE NAME='YESNO')
   DROP TYPE YESNO
go

IF EXISTS (SELECT 1 FROM SYSOBJECTS WHERE ID=OBJECT_ID('R_EXCHANGETYPE') AND TYPE='R')
   DROP RULE  R_EXCHANGETYPE
go

IF EXISTS (SELECT 1 FROM SYSOBJECTS WHERE ID=OBJECT_ID('R_GENDER') AND TYPE='R')
   DROP RULE  R_GENDER
go

IF EXISTS (SELECT 1 FROM SYSOBJECTS WHERE ID=OBJECT_ID('R_QUANTITY') AND TYPE='R')
   DROP RULE  R_QUANTITY
go

CREATE RULE R_EXCHANGETYPE AS
      @COLUMN IN ('Sent','Received')
go

CREATE RULE R_GENDER AS
      @COLUMN IN ('M','F')
go

CREATE RULE R_QUANTITY AS
      @COLUMN >= 0
go

/*==============================================================*/
/* Domain: ADDRESS                                              */
/*==============================================================*/
CREATE TYPE ADDRESS
   FROM VARCHAR(50)
go

/*==============================================================*/
/* Domain: COMMENT                                              */
/*==============================================================*/
CREATE TYPE COMMENT
   FROM VARCHAR(100)
go

/*==============================================================*/
/* Domain: DATE                                                 */
/*==============================================================*/
CREATE TYPE DATE
   FROM DATE
go

/*==============================================================*/
/* Domain: DATETIME                                             */
/*==============================================================*/
CREATE TYPE DATETIME
   FROM DATETIME
go

/*==============================================================*/
/* Domain: DESCRIPTION                                          */
/*==============================================================*/
CREATE TYPE DESCRIPTION
   FROM VARCHAR(1500)
go

/*==============================================================*/
/* Domain: DIAGNOSE                                             */
/*==============================================================*/
CREATE TYPE DIAGNOSE
   FROM VARCHAR(100)
go

/*==============================================================*/
/* Domain: EXCHANGETYPE                                         */
/*==============================================================*/
CREATE TYPE EXCHANGETYPE
   FROM VARCHAR(20)
go

EXECUTE SP_BINDRULE R_EXCHANGETYPE, EXCHANGETYPE
go

/*==============================================================*/
/* Domain: GENDER                                               */
/*==============================================================*/
CREATE TYPE GENDER
   FROM CHAR(1)
go

EXECUTE SP_BINDRULE R_GENDER, GENDER
go

/*==============================================================*/
/* Domain: IMAGE                                                */
/*==============================================================*/
CREATE TYPE IMAGE
   FROM VARCHAR(50)
go

/*==============================================================*/
/* Domain: LOCATION                                             */
/*==============================================================*/
CREATE TYPE LOCATION
   FROM VARCHAR(50)
go

/*==============================================================*/
/* Domain: NAME                                                 */
/*==============================================================*/
CREATE TYPE NAME
   FROM VARCHAR(50)
go

/*==============================================================*/
/* Domain: NUMBER                                               */
/*==============================================================*/
CREATE TYPE NUMBER
   FROM INT
go

/*==============================================================*/
/* Domain: PASSWORD                                             */
/*==============================================================*/
CREATE TYPE PASSWORD
   FROM VARCHAR(200)
go

/*==============================================================*/
/* Domain: PRESCRIPTION                                         */
/*==============================================================*/
CREATE TYPE PRESCRIPTION
   FROM VARCHAR(100)
go

/*==============================================================*/
/* Domain: PRICE                                                */
/*==============================================================*/
CREATE TYPE PRICE
   FROM MONEY
go

/*==============================================================*/
/* Domain: QUANTITY                                             */
/*==============================================================*/
CREATE TYPE QUANTITY
   FROM INT
go

EXECUTE SP_BINDRULE R_QUANTITY, QUANTITY
go

/*==============================================================*/
/* Domain: TELEPHONENUMBER                                      */
/*==============================================================*/
CREATE TYPE TELEPHONENUMBER
   FROM VARCHAR(20)
go

/*==============================================================*/
/* Domain: YESNO                                                */
/*==============================================================*/
CREATE TYPE YESNO
   FROM BIT
go

/*==============================================================*/
/* Table: ANIMAL                                                */
/*==============================================================*/
CREATE TABLE ANIMAL (
   ANIMALID             NUMBER               NOT NULL,
   ANIMALNAME           NAME                 NOT NULL,
   GENDER               GENDER               NOT NULL,
   BIRTHDATE            DATE                 NOT NULL,
   BIRTHPLACE           LOCATION             NOT NULL,
   AREANAME             NAME                 NOT NULL,
   ENCLOSUREID          NUMBER               NOT NULL,
   SPECIESNAME          NAME                 NOT NULL,
   DECEASEDDATE         DATE                 NULL,
   IMAGE                IMAGE                NULL,
   CONSTRAINT PK_ANIMAL PRIMARY KEY NONCLUSTERED (ANIMALID)
)
go

/*==============================================================*/
/* Index: ANIMALTOENCLOSURE_FK                                  */
/*==============================================================*/
CREATE INDEX ANIMALTOENCLOSURE_FK ON ANIMAL (
AREANAME ASC,
ENCLOSUREID ASC
)
go

/*==============================================================*/
/* Index: ANIMALTOSPECIES_FK                                    */
/*==============================================================*/
CREATE INDEX ANIMALTOSPECIES_FK ON ANIMAL (
SPECIESNAME ASC
)
go

/*==============================================================*/
/* Table: AREA                                                  */
/*==============================================================*/
CREATE TABLE AREA (
   AREANAME             NAME                 NOT NULL,
   HEADKEEPERID         NUMBER               NOT NULL,
   CONSTRAINT PK_AREA PRIMARY KEY NONCLUSTERED (AREANAME)
)
go

/*==============================================================*/
/* Index: HEADKEEPER_FK                                         */
/*==============================================================*/
CREATE INDEX HEADKEEPER_FK ON AREA (
HEADKEEPERID ASC
)
go

/*==============================================================*/
/* Table: ENCLOSURE                                             */
/*==============================================================*/
CREATE TABLE ENCLOSURE (
   AREANAME             NAME                 NOT NULL,
   ENCLOSUREID          NUMBER               NOT NULL,
   CONSTRAINT PK_ENCLOSURE PRIMARY KEY NONCLUSTERED (AREANAME, ENCLOSUREID)
)
go

/*==============================================================*/
/* Index: AREATOENCLOSURE_FK                                    */
/*==============================================================*/
CREATE INDEX AREATOENCLOSURE_FK ON ENCLOSURE (
AREANAME ASC
)
go

/*==============================================================*/
/* Table: KEEPER                                                */
/*==============================================================*/
CREATE TABLE KEEPER (
   STAFFID              NUMBER               NOT NULL,
   AREANAME             NAME                 NOT NULL,
   CONSTRAINT PK_KEEPER PRIMARY KEY (STAFFID)
)
go

/*==============================================================*/
/* Index: KEEPERTOAREA_FK                                       */
/*==============================================================*/
CREATE INDEX KEEPERTOAREA_FK ON KEEPER (
AREANAME ASC
)
go

/*==============================================================*/
/* Table: SPECIES                                               */
/*==============================================================*/
CREATE TABLE SPECIES (
   SPECIESNAME          NAME                 NOT NULL,
   SPECIESNAMELATIN     NAME                 NOT NULL,
   DESCRIPTION          DESCRIPTION          NOT NULL,
   ANIMALAMOUNT         QUANTITY             NOT NULL,
   IMAGE                IMAGE                NULL,
   CONSTRAINT PK_SPECIES PRIMARY KEY NONCLUSTERED (SPECIESNAME)
)
go

/*==============================================================*/
/* Table: STAFF                                                 */
/*==============================================================*/
CREATE TABLE STAFF (
   STAFFID              NUMBER               NOT NULL,
   STAFFNAME            NAME                 NOT NULL,
   PASSWORD             PASSWORD             NOT NULL,
   ISACTIVE             YESNO                NOT NULL,
   CONSTRAINT PK_STAFF PRIMARY KEY NONCLUSTERED (STAFFID)
)
go

ALTER TABLE ANIMAL
   ADD CONSTRAINT FK_ANIMAL_ANIMALTOE_ENCLOSUR FOREIGN KEY (AREANAME, ENCLOSUREID)
      REFERENCES ENCLOSURE (AREANAME, ENCLOSUREID)
go

ALTER TABLE ANIMAL
   ADD CONSTRAINT FK_ANIMAL_ANIMALTOS_SPECIES FOREIGN KEY (SPECIESNAME)
      REFERENCES SPECIES (SPECIESNAME)
go

ALTER TABLE AREA
   ADD CONSTRAINT FK_AREA_HEADKEEPE_STAFF FOREIGN KEY (HEADKEEPERID)
      REFERENCES STAFF (STAFFID)
go

ALTER TABLE ENCLOSURE
   ADD CONSTRAINT FK_ENCLOSUR_AREATOENC_AREA FOREIGN KEY (AREANAME)
      REFERENCES AREA (AREANAME)
go

ALTER TABLE KEEPER
   ADD CONSTRAINT FK_KEEPER_KEEPERTOA_AREA FOREIGN KEY (AREANAME)
      REFERENCES AREA (AREANAME)
go

ALTER TABLE KEEPER
   ADD CONSTRAINT FK_KEEPER_STAFFMEMB_STAFF FOREIGN KEY (STAFFID)
      REFERENCES STAFF (STAFFID)
go

