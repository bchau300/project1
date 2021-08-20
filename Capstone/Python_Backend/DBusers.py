import mysql.connector
#Patient for all 3
#SixmonthReminder
#Password user select
mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)

mycursor = mydb.cursor()

#Creating user for the database
mycursor.execute("CREATE USER 'PatientAcct'@'localhost' IDENTIFIED BY 'password'")
mycursor.execute("GRANT SELECT ON APPOINTMENTS.PATIENTS TO 'PatientAcct'@'localhost'")
mycursor.execute("GRANT INSERT ON APPOINTMENTS.PATIENTS TO 'PatientAcct'@'localhost'")
mycursor.execute("GRANT UPDATE ON APPOINTMENTS.PATIENTS TO 'PatientAcct'@'localhost'")
mycursor.execute("GRANT DELETE ON APPOINTMENTS.PATIENTS TO 'PatientAcct'@'localhost'")

mycursor.execute("CREATE USER 'Reminders'@'localhost' IDENTIFIED BY 'password'")
mycursor.execute("GRANT SELECT ON APPOINTMENTS.PATIENTS TO 'Reminders'@'localhost'")
mycursor.execute("GRANT UPDATE ON APPOINTMENTS.PATIENTS TO 'Reminders'@'localhost'")
mycursor.execute("GRANT DELETE ON APPOINTMENTS.PATIENTS TO 'Reminders'@'localhost'")

mycursor.execute("CREATE USER 'PassCheck'@'localhost' IDENTIFIED BY 'password'")
mycursor.execute("GRANT SELECT ON APPOINTMENTS.PATIENTS TO 'PassCheck'@'localhost'")

mycursor.execute("CREATE USER 'Reset'@'localhost' IDENTIFIED BY 'password'")
mycursor.execute("GRANT UPDATE ON APPOINTMENTS.PATIENTS TO 'Reset'@'localhost'")

mydb.commit()

mydb.close()