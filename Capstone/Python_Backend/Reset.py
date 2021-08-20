import mysql.connector

'''
This file reset the login attempts every 5 minutes
'''

#Use Reset User
mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="test123",
  database="test"
)

mycursor = mydb.cursor()
mycursor.execute("UPDATE DocInfo SET LimitPW = 0")
mydb.commit()
mydb.close()