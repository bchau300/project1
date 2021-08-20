import tweepy
import credentials
import sys
import json
import os
import codecs
'''
Bryan Chau
Henry Fisher
'''

#give auth the API code
auth = tweepy.OAuthHandler(credentials.APP_KEY, credentials.APP_SECRET)
auth.set_access_token(credentials.OAUTH, credentials.OAUTH_SECRET)
ACCESS_TOKEN = credentials.ACCESS_TOKEN
api = tweepy.API(auth)


sys.stdout = codecs.getwriter('utf8')(sys.stdout)

#listens to the tweets occuring
class MyStreamListener(tweepy.StreamListener):
    
    
    def on_status(self, status):
        print(status.text.encode('utf-8'))

    #checks the tweet
    def on_data(self, data):
        #loads up the data into a json
        tweet = json.loads(data)
        #grabs the json file and load the text(tweet)
        text = tweet["text"]

        saveFile = open('twitter.txt', 'a')
        saveFile.write(str(text.encode('utf-8')))
        saveFile.write('\n')
        
        

    #checks for errors
    def on_error(self, status_code):
        print >> 'Encourtered error: ', status_code
        return True #dont kill the stream
    #checks if its grabbing too much data
    def on_ontime(self):
        print >> sys.stderr, 'Timeout...'
        return True #dont kill the stream

#use credentials to load up the API
myStreamListener = MyStreamListener()
myStream = tweepy.Stream(auth = api.auth, listener=myStreamListener)
#checks for a certain word
myStream.filter(track=['ethereum'])

