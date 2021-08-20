from datetime import datetime as dt, date, timedelta
import os
import mysql.connector
import constant
import Decrypt
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
Today = dt.now().date()
Sixmonth = Today - timedelta(days=185)
OfficePH = ""
OfficeLoc = ""

#Data Format
dateformat = "%m-%d-%Y"

#connects the database
#Change to Reminder user
mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)
mycursor = mydb.cursor()
mycursor.execute("SELECT * FROM SixMonthReminder")
myresult = mycursor.fetchall()
ListMax = mycursor.rowcount


#Reading data
Fname = []
Lname = []
date = []
time = []
location = []
PhoneNumber = []
ID = []

#Input data from database into array
for patients in myresult:
  if(Sixmonth > (dt.strptime(patients[constant.APPOINTMENT_DATE], '%m-%d-%Y').date())):
    ID.append(patients[constant.ID])
    Fname.append(patients[constant.FNAME])
    Lname.append(patients[constant.LNAME])
    date.append(dt.strptime(patients[constant.APPOINTMENT_DATE], '%m-%d-%Y').date())
    time.append(patients[constant.APPOINTMENT_TIME])
    location.append(patients[constant.LOCATION])
    PhoneNumber.append(patients[constant.PATIENTPN])



#Loop to send a message for a 6 month reminder and will delete from the list
for i in range(len(date)):
    if(PhoneNumber[i] is not None or PhoneNumber != 'Null'):
        #Phone Number for Office
        if(location[i] == "Portsmouth"):
            OfficePH = "(757)485-2222"
            OfficeLoc = "Portsmouth Family Dental"
        elif(location[i] == "Buffalo"):
            OfficePH = "(716)893-2211"
            OfficeLoc = "Lesinski Family Dental"

        client.api.account.messages.create(
        to=PhoneNumber[i],
        from_=Office_PN,
        body= "Hello " + Fname[i] + " " + Lname[i] +", Just a friendly reminder that it has been 6 months since your last dental appointment. Please give us a call to schedule an appointment. " + OfficeLoc + " " + OfficePH)
        #body= "Hello " + Fname[i] + " " + Lname[i] +", Just a friendly reminder that it has been 6 months since your last dental appointment. Please visit our site to schedule an appointment. (link))
        #Daniel just remove the comment and the old body to input the link
        mycursor.execute("DELETE FROM SixMonthReminder where PatientID = " + str(ID[i]))
        mydb.commit()
mydb.close()