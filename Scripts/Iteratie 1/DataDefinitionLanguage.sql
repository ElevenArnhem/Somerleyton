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
/* Table: Animal                                                */
/*==============================================================*/
CREATE TABLE Animal (
  AnimalID             INTEGER              NOT NULL		IDENTITY (1, 1),
  AnimalName           VARCHAR(50)          NOT NULL,
  Gender               CHAR(1)              NOT NULL,
  BirthDate            DATETIME             NOT NULL,
  BirthPlace           VARCHAR(50)          NOT NULL,
  AreaName             VARCHAR(50)          NOT NULL,
  EnclosureID          INTEGER              NOT NULL,
  SpeciesName          VARCHAR(50)          NOT NULL,
  DeceasedDate         DATETIME             NULL,
  Image                VARCHAR(50)          NULL,
  CONSTRAINT PK_ANIMAL PRIMARY KEY (AnimalID),
  CONSTRAINT CK_GENDER CHECK (Gender IN ('M','F'))
)
GO

/*==============================================================*/
/* Table: Area                                                  */
/*==============================================================*/
CREATE TABLE Area (
  AreaName             VARCHAR(50)           NOT NULL,
  HeadkeeperID         INTEGER               NOT NULL,
  CONSTRAINT PK_AREA PRIMARY KEY (AreaName)
)
GO

/*==============================================================*/
/* Table: Enclosure                                             */
/*==============================================================*/
CREATE TABLE Enclosure (
  EnclosureID          INTEGER               NOT NULL,
  AreaName             VARCHAR(50)           NOT NULL,
  CONSTRAINT PK_ENCLOSURE PRIMARY KEY (AreaName, EnclosureID)
)
GO

/*==============================================================*/
/* Table: Keeper                                                */
/*==============================================================*/
CREATE TABLE Keeper (
  StaffID              INTEGER               NOT NULL,
  AreaName             VARCHAR(50)           NOT NULL,
  CONSTRAINT PK_KEEPER PRIMARY KEY (StaffID)
)
GO

/*==============================================================*/
/* Table: Species                                               */
/*==============================================================*/
CREATE TABLE Species (
  SpeciesName          VARCHAR(50)           NOT NULL,
  SpeciesNameLatin     VARCHAR(50)           NOT NULL,
  Description          VARCHAR(1500)         NOT NULL,
  AnimalAmount         INTEGER               NOT NULL,
  Image                VARCHAR(50)           NULL,
  CONSTRAINT PK_SPECIES PRIMARY KEY (SpeciesName),
  CONSTRAINT CK_ANIMALAMOUNT CHECK (AnimalAmount >= 0)
)
GO

/*==============================================================*/
/* Table: Staff                                                 */
/*==============================================================*/
CREATE TABLE Staff (
  StaffID              INTEGER               NOT NULL		IDENTITY (1, 1),
  StaffName            VARCHAR(50)           NOT NULL,
  Password             VARCHAR(200)          NOT NULL,
  CONSTRAINT PK_STAFF PRIMARY KEY (StaffID)
)
GO

ALTER TABLE Animal
ADD CONSTRAINT FK_ANIMAL_ANIMALTOE_ENCLOSUR FOREIGN KEY (AreaName, EnclosureID)
  REFERENCES Enclosure (AreaName, EnclosureID)
GO

ALTER TABLE Animal
ADD CONSTRAINT FK_ANIMAL_ANIMALTOS_SPECIES FOREIGN KEY (SpeciesName)
  REFERENCES Species (SpeciesName)
GO

ALTER TABLE Area
ADD CONSTRAINT FK_AREA_HEADKEEPE_KEEPER FOREIGN KEY (HeadkeeperID)
  REFERENCES Staff (StaffID)
GO

ALTER TABLE Enclosure
ADD CONSTRAINT FK_ENCLOSUR_AREATOENC_AREA FOREIGN KEY (AreaName)
  REFERENCES Area (AreaName)
GO

ALTER TABLE Keeper
ADD CONSTRAINT FK_KEEPER_KEEPERTOA_AREA FOREIGN KEY (AreaName)
  REFERENCES Area (AreaName)
GO

ALTER TABLE Keeper
ADD CONSTRAINT FK_KEEPER_STAFFMEMB_STAFF FOREIGN KEY (StaffID)
  REFERENCES Staff (StaffID)
GO