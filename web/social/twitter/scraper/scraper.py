# littlej tweet scanner, looks for a hashtag, sends them to ushahidi

import urllib
import simplejson
import datetime
import dateutil.parser as parser
import time

def search_tweets(last,query):
    search = urllib.urlopen("http://search.twitter.com/search.json?q="+query)
    dict = simplejson.loads(search.read())
    latest=last
    for tweet in dict["results"]: # result is a list of dictionaries
        time = parser.parse(tweet["created_at"])
        if time>last:
            #if tweet["geo"]!=None and tweet["geo"]["type"]=='Point':
            send(tweet,time)
            latest=time
    update_latest(latest)
    return latest


def param(a,b):
    return "&"+a+"=\""+b+"\"";

def send(tweet,time):
    ampm="pm"
    if time.hour<12: ampm="am"
    latlon=[0,0]
    if tweet["geo"]!=None and tweet["geo"]["type"]=='Point':
        latlon=tweet["geo"]["coordinates"]
    date="%02d"%time.month+"/"+"%02d"%time.day+"/"+str(time.year)
    url="http://littlej.borrowed-scenery.com/api"
    data=urllib.urlencode({"task":"report",
                           "incident_title": tweet["text"],
                           "incident_description": "From Twitter",
                           "incident_date": date,
                           "incident_hour": time.hour%12,
                           "incident_minute": time.minute,
                           "incident_ampm": ampm,
                           "incident_category": "1",
                           "latitude": str(latlon[0]),
                           "longitude": str(latlon[1]),
                           "location_name": "No location",
                           "person_first": tweet["from_user_name"],
                           "person_last": ""});
    print url
    print data
    sock = urllib.urlopen(url,data)
    print sock.read()
    sock.close()


def main():
    last=parser.parse("2013-01-01 12:00:00 +0000");
    while 1:
        last=search_tweets(last,"#littlejporttalbot")
        time.sleep(2)
    

main()
