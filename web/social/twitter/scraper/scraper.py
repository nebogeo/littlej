# littlej tweet scanner, looks for a hashtag, sends them to ushahidi

# todo: 
# * record time of last tweet persistently
# * upload photographs

import urllib
import simplejson
import datetime
import dateutil.parser as parser
import time

def load_latest():
    ret=parser.parse("2013-01-01 12:00:00 +0000");
    f=open("scraper_latest.txt","r")
    if f:
        ret=parser.parse(f.readline())
    f.close()
    return ret

def save_latest(time):
    f=open("scraper_latest.txt","w")
    if f:
        f.write(str(time))
    f.close()

def search_tweets(last,query):
    search = urllib.urlopen("http://search.twitter.com/search.json?q="+query)
    dict = simplejson.loads(search.read())
    latest=last
    for tweet in dict["results"]: # result is a list of dictionaries
        time = parser.parse(tweet["created_at"])
        if time>last:
            send(tweet,time)
            latest=time
    return latest


def send(tweet,time):
    ## only if geotagged with a point
    if tweet["geo"]!=None and tweet["geo"]["type"]=='Point' and tweet["geo"]["coordinates"]!=None:
        print tweet["text"]
        ampm="pm"
        if time.hour<12: ampm="am"
        latlon=[0,0]
        if tweet["geo"]!=None and tweet["geo"]["type"]=='Point':
            latlon=tweet["geo"]["coordinates"]
            date="%02d"%time.month+"/"+"%02d"%time.day+"/"+str(time.year)
            url="http://littlej.borrowed-scenery.com/api"
            data=urllib.urlencode({"task":"report",
                                   "incident_title": tweet["text"].encode("ascii","ignore"),
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
            #    print url
            #    print data
            sock = urllib.urlopen(url,data)
            print sock.read()
            sock.close()


def main():
#    last=parser.parse("2013-01-01 12:00:00 +0000");
    last=load_latest();
    while 1:
        last=search_tweets(last,"Port Talbot")
        save_latest(last)
#        print last
        time.sleep(20)
    

main()
