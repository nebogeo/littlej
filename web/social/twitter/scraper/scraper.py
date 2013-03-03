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
    return latest


def param(a,b):
    return "&"+a+"=\""+b+"\"";

def send(tweet,time):
    ampm="pm"
    if time.hour<12: ampm="am"
    latlon=[0,0]
    if tweet["geo"]!=None and tweet["geo"]["type"]=='Point':
        latlon=tweet["geo"]["coordinates"]

    url="http://littlej.borrowed-scenery.com/api/?task=report"+\
        param("incident_title", tweet["text"])+\
        param("incident_description", "From Twitter")+\
        param("incident_date", str(time.month)+"/"+str(time.day)+"/"+str(time.year))+\
        param("incident_hour", str(time.hour))+\
        param("incident_minute", str(time.minute))+\
        param("incident_ampm", ampm)+\
        param("incident_category", "1")+\
        param("latitude", str(latlon[0]))+\
        param("longitude", str(latlon[1]))+\
        param("location_name", "No location")+\
        param("person_first", tweet["from_user_name"])+\
        param("person_last", "");

    print url

    sock = urllib.urlopen(url,"POST")
    print sock.read()
    sock.close()


def main():
    last=parser.parse("2013-01-01 12:00:00 +0000");
    while 1:
        last=search_tweets(last,"#littlejporttalbot")
        time.sleep(2)
    

main()
