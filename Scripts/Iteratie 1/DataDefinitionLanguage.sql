/*==============================================================*/
/* DBMS:            Microsoft SQL Server 2012                   */
/* Datum:           22-4-2015                                   */
/*==============================================================*/

USE master
GO

IF DB_ID('SomerleytonAnimalPark') IS NOT NULL
  DROP DATABASE SomerleytonAnimalPark
GO

/*==============================================================*/
/* Database: SomerleytonAnimalPark                              */
/*==============================================================*/
CREATE DATABASE SomerleytonAnimalPark
GO

USE SomerleytonAnimalPark
GO

/*==============================================================*/
/* Table: Environment                                           */
/*==============================================================*/
CREATE TABLE Environment (
  EnvironmentName			VARCHAR(50)		NOT NULL,
  constraint PK_ENVIRONMENT PRIMARY KEY (EnvironmentName)
)
GO

/*==============================================================*/
/* Table: Area                                                  */
/*==============================================================*/
CREATE TABLE Area (
  EnvironmentName		VARCHAR(50)			NOT NULL,
  AreaName				VARCHAR(50)         NOT NULL,
  HeadkeeperID			INTEGER             NOT NULL,
  CONSTRAINT PK_AREA PRIMARY KEY (AreaName, EnvironmentName)
)
GO

/*==============================================================*/
/* Table: Enclosure                                             */
/*==============================================================*/
CREATE TABLE Enclosure (
  EnvironmentName		VARCHAR(50)			NOT NULL,
  EnclosureID			INTEGER             NOT NULL,
  AreaName				VARCHAR(50)         NOT NULL,
  CONSTRAINT PK_ENCLOSURE PRIMARY KEY (EnvironmentName, AreaName, EnclosureID)
)
GO

/*==============================================================*/
/* Table: Animal                                                */
/*==============================================================*/
CREATE TABLE Animal (
  AnimalID             INTEGER              NOT NULL		IDENTITY (1, 1),
  AnimalName           VARCHAR(50)          NOT NULL,
  Gender               CHAR(1)              NOT NULL,
  BirthDate            DATE                 NOT NULL,
  BirthPlace           VARCHAR(50)          NOT NULL,
  EnvironmentName	   VARCHAR(50)			NOT NULL,
  AreaName             VARCHAR(50)          NOT NULL,
  EnclosureID          INTEGER              NOT NULL,
  LatinName			   VARCHAR(50)          NOT NULL,
  SubSpeciesName	   VARCHAR(50)			NOT NULL,
  [Image]              VARCHAR(50)          NULL,
  CONSTRAINT PK_ANIMAL PRIMARY KEY (AnimalID),
  CONSTRAINT CK_GENDER CHECK (Gender IN ('M','F'))
)
GO

/*==============================================================*/
/* Table: Keeper                                                */
/*==============================================================*/
CREATE TABLE Keeper (
  StaffID              INTEGER              NOT NULL,
  CONSTRAINT PK_KEEPER PRIMARY KEY (StaffID)
)
GO

/*==============================================================*/
/* Table: KeeperToArea                                          */
/*==============================================================*/
CREATE TABLE KeeperToArea (
  StaffID				INTEGER				NOT NULL,
  EnvironmentName		VARCHAR(50)			NOT NULL,
  AreaName				VARCHAR(50)			NOT NULL,
  CONSTRAINT PK_KEEPERTOAREA PRIMARY KEY (StaffID, EnvironmentName, AreaName)
)
GO

/*==============================================================*/
/* Table: HeadSpecies                                           */
/*==============================================================*/
CREATE TABLE HeadSpecies (
  LatinName			VARCHAR(50)			NOT NULL,
  CONSTRAINT PK_HEADSPECIES PRIMARY KEY (LatinName)
)
GO

/*==============================================================*/
/* Table: SubSpecies                                            */
/*==============================================================*/
CREATE TABLE SubSpecies (
  LatinName		       VARCHAR(50)           NOT NULL,
  SubSpeciesName       VARCHAR(50)           NOT NULL,
  [Description]        VARCHAR(1500)         NOT NULL,
  [Image]              VARCHAR(50)           NULL,
  CONSTRAINT PK_SUBSPECIES PRIMARY KEY (LatinName, SubSpeciesName)
)
GO

/*==============================================================*/
/* Table: Staff                                                 */
/*==============================================================*/
CREATE TABLE Staff (
  StaffID				INTEGER             NOT NULL		IDENTITY (1, 1),
  StaffName				VARCHAR(50)         NOT NULL,
  [Password]			VARCHAR(200)        NOT NULL,
  IsActive				BIT                 NOT NULL,
  PhoneNumber			VARCHAR(20)			NOT NULL,
  [Address]				VARCHAR(50)			NOT NULL,
  ZipCode				VARCHAR(50)			NOT NULL,
  City					VARCHAR(200)		NOT NULL,
  Email					VARCHAR(200)		NULL,
  Birthdate				DATE				NOT NULL,
  CONSTRAINT PK_STAFF PRIMARY KEY (StaffID)
)
GO

/*==============================================================*/
/* Table: Radio                                                 */
/*==============================================================*/
CREATE TABLE Radio (
  RadioNumber            INTEGER			NOT NULL,
  StaffID				 INTEGER            NULL,
  CONSTRAINT PK_RADIO PRIMARY KEY (RadioNumber)
)
GO

/*==============================================================*/
/* Table: SpeciesInEnclosure                                    */
/*==============================================================*/
CREATE TABLE SpeciesInEnclosure (
  EnvironmentName		VARCHAR(50)			NOT NULL,
  EnclosureID			INTEGER             NOT NULL,
  AreaName				VARCHAR(50)         NOT NULL,
  LatinName				VARCHAR(50)         NOT NULL,
  SubSpeciesName		VARCHAR(50)         NOT NULL,
  CONSTRAINT PK_SPECIESINENCLOSURE PRIMARY KEY (EnvironmentName, EnclosureID, AreaName, LatinName, SubSpeciesName)
)
GO

/*==============================================================*/
/* Table: DeceasedInfo		                                    */
/*==============================================================*/
CREATE TABLE DeceasedInfo (
  AnimalID				INTEGER             NOT NULL,
  DeceasedDate			DATE				NOT NULL,
  Comment				VARCHAR(1000)		NULL,
  CONSTRAINT PK_DECEASEDINFO PRIMARY KEY (AnimalID)
)
GO


ALTER TABLE Animal
ADD CONSTRAINT FK_ANIMAL_ANIMALTO_ENCLOSURE FOREIGN KEY (EnvironmentName, AreaName, EnclosureID)
REFERENCES Enclosure (EnvironmentName, AreaName, EnclosureID)
GO

ALTER TABLE Animal
ADD CONSTRAINT FK_ANIMAL_SUBSPECIES FOREIGN KEY (LatinName, SubSpeciesName)
REFERENCES SubSpecies (LatinName, SubSpeciesName)
GO

ALTER TABLE Area
ADD CONSTRAINT FK_AREA_HEADKEEPER FOREIGN KEY (HeadkeeperID)
REFERENCES Staff (StaffID)
GO

ALTER TABLE Area
ADD CONSTRAINT FK_AREA_ENVIRONMENT FOREIGN KEY (EnvironmentName)
REFERENCES Environment (EnvironmentName)
GO

ALTER TABLE Enclosure
ADD CONSTRAINT FK_ENCLOSURE_AREA FOREIGN KEY (EnvironmentName, AreaName)
REFERENCES Area (AreaName, EnvironmentName)
GO

ALTER TABLE KeeperToArea
ADD CONSTRAINT FK_KEEPERTOAREA_AREA FOREIGN KEY (AreaName, EnvironmentName)
REFERENCES Area (AreaName, EnvironmentName)
GO

ALTER TABLE KeeperToArea
ADD CONSTRAINT FK_KEEPERTOAREA_KEEPER FOREIGN KEY (StaffID)
REFERENCES Keeper (StaffID)
GO

ALTER TABLE Keeper
ADD CONSTRAINT FK_KEEPER_STAFF FOREIGN KEY (StaffID)
REFERENCES Staff (StaffID)
GO

ALTER TABLE Radio
ADD CONSTRAINT FK_Radio_Staff FOREIGN KEY (StaffID)
REFERENCES Staff (StaffID)
GO

ALTER TABLE DeceasedInfo
ADD CONSTRAINT FK_DECEASEDINFO_ANIMAL FOREIGN KEY (AnimalId)
REFERENCES Animal (AnimalId)
GO

ALTER TABLE SpeciesInEnclosure
ADD CONSTRAINT FK_SPECIESINENCLOSURE_ENCLOSURE FOREIGN KEY (EnvironmentName, AreaName, EnclosureId)
REFERENCES Enclosure (EnvironmentName, AreaName, EnclosureId)
GO

ALTER TABLE SpeciesInEnclosure
ADD CONSTRAINT FK_SPECIESINENCLOSURE_SUBSPECIES FOREIGN KEY (LatinName, SubSpeciesName)
REFERENCES Subspecies (LatinName, SubSpeciesName)
GO

ALTER TABLE SubSpecies
ADD CONSTRAINT FK_SUBSPECIES_HEADSPECIES FOREIGN KEY (LatinName)
REFERENCES HeadSpecies (LatinName)
GO

