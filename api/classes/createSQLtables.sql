--This document contains sample SQL queries for creating tables and inserting test results into the Database
--brackets [] indicate variables (or instructions) that need to be fulfilled

--For ALL tests
CREATE TABLE test(
  testID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  patientID int FOREIGN KEY,
  accountID int FOREIGN KEY,
  dateTaken datetime NOT NULL
  )
INSERT INTO test(patientID,accountID,dateTaken)
VALUES (getPatientID, [getAccountID], [make datetime variable])

--Pressure Wound test
CREATE TABLE pressureWoundTest(
  testID int FOREIGN KEY,
  PUSHScore int,
  BJScore int,
  SusScore int,
  size decimal(3,2),
  depth int,
  edges int,
  undermining int,
  necType int,
  necAmnt int,
  exAmnt int,
  SCSW int,
  perTisEd int,
  perTilnd int,
  granTis int,
  epith int
  )
INSERT

--Semmes test
CREATE TABLE semmesTest(
  testID int FOREIGN KEY,
  p1L boolean,
  p2L boolean,
  p3L boolean,
  p4L boolean,
  p5L boolean,
  p6L boolean,
  p7L boolean,
  p8L boolean,
  p9L boolean,
  p10L boolean,
  p1R boolean,
  p2R boolean,
  p3R boolean,
  p4R boolean,
  p5R boolean,
  p6R boolean,
  p7R boolean,
  p8R boolean,
  p9R boolean,
  p10R boolean
  )

--Wagner Test
CREATE TABLE wagnerTest(
  testID int FOREIGN KEY,
  grade int
  )

--Mini Nutritional Assessment
CREATE TABLE miniNutritionalTest(
  testID int FOREIGN KEY,
  a int,
  b int,
  c int,
  d int,
  e int,
  f int,
  f2 int,
  score int
  )
