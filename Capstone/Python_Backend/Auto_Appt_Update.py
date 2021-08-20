from datetime import datetime as dt, date, timedelta
from twilio.rest import Client
import mysql.connector
import constant
import Decrypt
'''
This file will automatic delete old patient data if 7 days is passed. For example
If today's date was 1-25-2021 and there was an appointment 1-17-2021. The whole information
will be deleted. Also will send a text notification about a survey once.
'''


#Data is fake purpose of showing project work
account_sid = Decrypt.TW_SID
auth_token = Decrypt.TW_TOKEN
Office_PN = "+5555555555"
client = Client(account_sid, auth_token)

#Deletion from tomorrow's date
Today = dt.now().date()
Sevendays = Today + timedelta(days=-7)
#Data Format
dateformat = "%m-%d-%Y"
#Use Reminder account
mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)

#This is for appointments
mycursor = mydb.cursor()
mycursor.execute("SELECT * FROM SixMonthReminder")
myresult = mycursor.fetchall()
ListMax = mycursor.rowcount


#Once the appointment is done
for patients in myresult:
    if((dt.strptime(str(patients[constant.APPOINTMENT_DATE]), dateformat).date()) < Today):
        #0 is the message has not been sent yet
        if (patients[constant.MSGSENT] ==  0):
            #note for me. Get the FULL Dental office's Name do not use the location name
            if(patients[constant.LOCATION] == "Portsmouth"):
                LocName = "Portsmouth Family Dental"
            elif(patients[constant.LOCATION] == "Buffalo"):
                LocName = "Lesinski Family Dental"
            client.api.account.messages.create(
            to=patients[constant.PATIENTPN],
            from_=Office_PN,
            body= "Thank you for visiting " + LocName + "! Our team would love to hear how we can improve your experience, please use the following link to take a short survey (30 seconds long).\
            \nhttps://forms.gle/1aGpgdh7J5zgkU3a8")
            mycursor.execute("UPDATE SixMonthReminder set MSGSENT = '1' WHERE Patientid=\'" + str(patients[constant.ID]) + "\'")
            mydb.commit()

#this automatically delete the driving days
mycursor.execute("SELECT * FROM DocLoc")
myresult2 = mycursor.fetchall()
ListMax2 = mycursor.rowcount
for driving in myresult2:
    if((dt.strptime(str(driving[2]), dateformat).date()) < Today):
        mycursor.execute("DELETE FROM DocLoc where DateUntil = \'" + str(driving[2]) + "\'")
        mydb.commit()


mydb.close()