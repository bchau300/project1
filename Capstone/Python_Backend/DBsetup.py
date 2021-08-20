import mysql.connector
#This file handles the table creations

mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)

mycursor = mydb.cursor()

#mycursor.execute("CREATE DATABASE APPOINTMENTS")

#This table handles the patients
mycursor.execute("CREATE TABLE PATIENTS(\
Patientid int NOT NULL AUTO_INCREMENT,\
PatientFNAME VARCHAR(255) NOT NULL, \
PatientLNAME VARCHAR(255) NOT NULL, \
APPDATE VARCHAR(10) NOT NULL, \
PatientTIME VARCHAR(8) NOT NULL, \
PatientLOCATION VARCHAR(20) NOT NULL, \
PatientPN VARCHAR(15), \
PATSTATUS int NOT NULL DEFAULT 0,\
HYGIENIST int NOT NULL DEFAULT 0, \
PRIMARY KEY (PatientID))")

#This table will handle alert stuff
mycursor.execute("CREATE TABLE SixMonthReminder(\
Patientid int NOT NULL AUTO_INCREMENT, \
PatientFNAME VARCHAR(255) NOT NULL, \
PatientLNAME VARCHAR(255) NOT NULL, \
APPDATE VARCHAR(10) NOT NULL, \
PatientTIME VARCHAR(8) NOT NULL, \
PatientLOCATION VARCHAR(20) NOT NULL, \
PatientPN VARCHAR(15), \
MSGSENT BOOLEAN DEFAULT 0, \
PRIMARY KEY (PatientID))")

#Driving day
mycursor.execute("CREATE TABLE DocLoc(\
DriverID int NOT NULL AUTO_INCREMENT, \
DocLocation VARCHAR(20) NOT NULL, \
DateFrom VARCHAR(10) NOT NULL, \
DateUntil VARCHAR(10) NOT NULL,\
PRIMARY KEY (DriverID))")



#Creating password for the database
mycursor.execute("CREATE TABLE PORTSMOUTH(Pass int(6) PRIMARY KEY)")
mycursor.execute("CREATE TABLE BUFFALO(Pass int(6) PRIMARY KEY)")
mycursor.execute("CREATE TABLE DocInfo(Pass VARCHAR(50) PRIMARY KEY, LimitPW int(1) default 0 not null")
mycursor.execute("INSERT INTO PORTSMOUTH VALUE('Fake')")
mycursor.execute("INSERT INTO BUFFALO VALUE('Fake')")
mydb.commit()


mydb.close()