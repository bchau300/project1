from datetime import datetime as dt, date, timedelta
import os
import mysql.connector
import constant
import Decrypt
import time
from twilio.rest import Client

'''
Just sends a text message to the user
'''

#Fake data, purpose of this is just showing the work I done for this project
account_sid = Decrypt.TW_SID
auth_token = Decrypt.TW_TOKEN
Office_PN = "+15555555555"
client = Client(account_sid, auth_token)

#Initalizing data
Today = dt.now()
Test = Today.time()
Hours = Today - timedelta(hours=1)
OfficePH = ""
OfficeLoc = ""

#Data Format
dateformat = "%m-%d-%Y"

#connects the database
#Change to PatientAcct user
mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)
mycursor = mydb.cursor()
mycursor.execute("SELECT * FROM patients")
myresult = mycursor.fetchall()
ListMax = mycursor.rowcount


#Reading data
ID = []
#Input data from database into array
#If any patient are status code = 0 and missed the time they will be set to 3 (missed time)
for patients in myresult:
  if(Hours.time() > (dt.strptime(patients[constant.APPOINTMENT_TIME], "%I:%M %p").time()) and (patients[constant.PATSTATUS] == 0) and Today.date() >= (dt.strptime(patients[constant.APPOINTMENT_DATE], '%m-%d-%Y').date())):
    ID.append(patients[constant.ID])

for i in range(len(ID)):
  mycursor.execute("UPDATE patients SET PATSTATUS = 3 where Patientid = " + str(ID[i]))
  mydb.commit()

mydb.close()