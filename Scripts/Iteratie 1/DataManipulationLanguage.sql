USE SomerleytonAnimalPark
GO

DELETE FROM Animal
DELETE FROM Species
DELETE FROM Enclosure
DELETE FROM Keeper
DELETE FROM Area
DELETE FROM Staff
GO

/* IDENTITY RESET  */
DBCC CHECKIDENT (Staff, RESEED, 0)
DBCC CHECKIDENT (Animal, RESEED, 0)
GO

SET IDENTITY_INSERT Staff ON
GO

INSERT INTO Staff (StaffID, StaffName, [Password], IsActive) VALUES
    (1, 'Koen van Keulen', '1234567890', 1),
    (2, 'Dirk Lenders', '1234567890', 1),
    (3, 'Tony Nijenhuis', '1234567890', 1),
    (4, 'Thom Peele', '1234567890', 1),
    (5, 'Kayan Meijer', '1234567890', 1)
GO

SET IDENTITY_INSERT Staff OFF
GO

INSERT INTO Area (AreaName, HeadkeeperID) VALUES
    ('Monkey House', 1)
GO

INSERT INTO Keeper (StaffID, AreaName) VALUES
    (1, 'Monkey House'),
    (2, 'Monkey House'),
    (3, 'Monkey House'),
    (4, 'Monkey House'),
    (5, 'Monkey House')
GO

INSERT INTO Enclosure(EnclosureID, AreaName) VALUES
    (1, 'Monkey House')
GO

INSERT INTO Species (SpeciesName, SpeciesNameLatin, [Description], AnimalAmount, [Image]) VALUES
    ('Central American Squirrel Monkey', 'Saimiri oerstedii', 'The squirrel monkeys are the New World monkeys of the genus Saimiri. They are the only genus in the subfamily Saimirinae. Squirrel monkeys live in the tropical forests of Central and South America in the cano-py layer. Most species have para- or allopatric ranges in the Amazon, while S. oerstedii is found disjunctly in Costa Rica and Panama. Squirrel monkey fur is short and close, colored olive at the shoulders and yellowish or-ange on its back and extremities. Their throat and the ears are white and their mouths are black. The upper part of their head is hairy. This black-and-white face gives them the name "deaths head monkey" in several Germanic languages.Squirrel monkeys grow to 25 to 35 cm, plus a 35 to 42 cm tail. They weigh 750 to 1100g. The mating of the squirrel monkeys is subject to seasonal influences. Females give birth to young during the rainy season, after a 150- to 170-day gestation. The mothers exclu-sively care for the young. "Baker", an "astronaut" squirrel monkey, rode into space as part of the United States space program, and returned safely.', 1, 'PLACEHOLDER.PNG')
GO

SET IDENTITY_INSERT Animal ON
GO

/* TODO: BIRTHDATE NOTATIE IS VERKEERD OM --> MAAND/DAG/JAAR IPV DAG/MAAND/JAAR */
INSERT INTO Animal (AnimalID, AnimalName, AreaName, EnclosureID, BirthDate, BirthPlace, Gender, SpeciesName) VALUES
    (1, 'Jenny the Squirrel Monkey', 'Monkey House', 1, '6-22-2003', 'Somerleyton UK', 'F', 'Central American Squirrel Monkey')
GO

SET IDENTITY_INSERT Animal OFF
GO