'''
Bryan Chau
Henry Fisher
'''

#Filters out the spams
import re
import os
#FileName
filename = "twitter.txt"
found = False
try:
    with open(filename,'rt') as f:
        inputfile = f.readlines()
        found = True
except IOError:
    print("The file doesn’t exist!")

#varible names
inputs = inputfile
counting = 0
List1 = []


#filters out the file and put a list
if(found):
    for i in range(len(inputs)):
        urlfind = r'\b([Giveaway?|giveaway?|RT])\w+'
        if not(re.findall(urlfind, inputs[i])):
            List1.append(inputs[i])
            counting += 1
print(List1, counting)
