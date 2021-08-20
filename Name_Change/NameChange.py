import discord
from discord.ext.commands import Bot
from discord.ext import commands
import asyncio
import time
import re
import os
import random

client = discord.Client()

@client.event
async def on_ready():
    
    print("I am online")
    
@client.event
async def on_member_update(before, after):
    #217149575302086657 vit
    Name1 = 
    Name2 = 
    Name3 = 
    n = after.nick
    b = after.id #checks the id
    newnick = Name()
    if namecheck(n):
        b = after.id
        if Name1 == b:
            await after.edit(nick=Name())
        elif Name2 == b:
            await after.edit(nick=Name())
    
def namecheck(newname):
    filename = "NameList.txt"
    found = True
    try:
        with open(filename,'rt') as f:
            content = f.readlines()
            found = True
    except IOError:
        print("The file doesn’t exist!")
    for i in range(len(content)):
        if newname.lower() in content[i].lower():
            found = False
    f.close()
    return found

def Name():
    filename = "NameList.txt"
    found = False
    try:
        with open(filename,'rt') as f:
            content = f.readlines()
            found = True
    except IOError:
        print("The file doesn’t exist!")
    f.close()
    return content[random.randint(0,32)]
        
client.run("Insert Token")
