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
Oneday = Today + timedelta(days=1)
Twoday = Today + timedelta(days=2)
Threeday = Today + timedelta(days=3)
Nextweek = Today + timedelta(days=7)
Twoweeks = Today + timedelta(days=14)
OfficePH = ""

#Data Format
dateformat = "%m-%d-%Y"

#connects the database
#reminder User
mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)

mycursor = mydb.cursor()
mycursor.execute("SELECT * FROM PATIENTS")
myresult = mycursor.fetchall()
ListMax = mycursor.rowcount

#Storing data
Fname = []
Lname = []
date = []
time = []
location = []
PhoneNumber = []

#Input data from database into array
for patients in myresult:
    Fname.append(patients[constant.FNAME])
    Lname.append(patients[constant.LNAME])
    date.append(dt.strptime(patients[constant.APPOINTMENT_DATE], '%m-%d-%Y').date())
    time.append(patients[constant.APPOINTMENT_TIME])
    location.append(patients[constant.LOCATION])
    PhoneNumber.append(patients[constant.PATIENTPN])


#Loop to send a message
for i in range(ListMax):
    if(PhoneNumber[i] is not None or PhoneNumber != 'Null'):
        #Phone Number for Office
        if(location[i] == "Portsmouth"):
            OfficePH = "(757)485-2222"
        elif(location[i] == "Buffalo"):
            OfficePH = "(716)893-2211"

        #1 week notice
        if(date[i] == Nextweek):
            client.api.account.messages.create(
            to=PhoneNumber[i],
            from_=Office_PN,
            body=Fname[i] + " " + Lname[i] + ",\nWe have confirmed your appointment on our schedule! Your appointment on " + date[i].strftime('%m/%d/%Y') + " at " + time[i] +  " at the " + location[i] + ". If you have any questions about your upcoming visit, please call us at " + OfficePH + " and please allot 1 hour for the appointment.")

        #3 days notice
        elif(date[i] == Threeday): 
            client.api.account.messages.create(
            to=PhoneNumber[i],
            from_=Office_PN,
            body=Fname[i] + " " + Lname[i] + ",\nFriendly Reminder! Your appointment is on " + date[i].strftime('%m/%d/%Y') + " at " + time[i] +  " at the " + location[i] + ". If you have any questions about your upcoming visit, please call us at " + OfficePH + " and please allot 1 hour for the appointment.")

        #2 days notice
        elif(date[i] == Twoday):
            client.api.account.messages.create(
            to=PhoneNumber[i],
            from_=Office_PN,
            body=Fname[i] + " " + Lname[i] + ",\nFriendly Reminder! Your appointment is on " + date[i].strftime('%m/%d/%Y') + " at " + time[i] +  " at the " + location[i] + ". If you have any questions about your upcoming visit, please call us at " + OfficePH + " and please allot 1 hour for the appointment.")

        #1 day notice
        elif(date[i] == Oneday):
            client.api.account.messages.create(
            to=PhoneNumber[i],
            from_=Office_PN,
            body=Fname[i] + " " + Lname[i] + ",\nFriendly Reminder! Your appointment is on " + date[i].strftime('%m/%d/%Y') + " at " + time[i] +  " at the " + location[i] + ". If you have any questions about your upcoming visit, please call us at " + OfficePH + " and please allot 1 hour for the appointment.")