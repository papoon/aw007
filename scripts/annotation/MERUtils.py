#-*- coding: utf-8 -*-
# Python 3
import subprocess
from constants import *
from dbUtils import *

def callGetLinkEntitiesMER(text):
    """
    Calls MER getEntities.sh and getEntities.sh scripts and retrieves the console result.
    Requires: text, the text to be analyzed by MER with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (links to the entities and their names).
    """
    #call get_entities script
    command1 = ["./get_entities.sh", text, "doid-simple"]
    process1 = subprocess.Popen(command1, cwd=MER_path, stdout=subprocess.PIPE)
    #call link_entities script
    command2 = ["./link_entities.sh", MER_DB_path]
    process2 = subprocess.Popen(command2, cwd=MER_path, stdin=process1.stdout, stdout=subprocess.PIPE)
    #call sort
    command3 = ["sort"]
    process3 = subprocess.Popen(command3, cwd=MER_path, stdin=process2.stdout, stdout=subprocess.PIPE)
    #call uniq
    command4 = ["uniq"]
    process4 = subprocess.Popen(command4, cwd=MER_path, stdin=process3.stdout, stdout=subprocess.PIPE)
    #get result from stdout and stderr
    (out,err) = process4.communicate()
    return out.decode('utf-8')

def processEntitiesMER(resultText):
    """
    Processes the output of callGetLinkEntitiesMER.
    Requires: resultText, the output text from callGetLinkEntitiesMER.
    Ensures: returns a list of tuples (DOID, term) for all found terms.
    """
    result = []
    lines = resultText.split('\n')
    for line in lines:
        lineParts = line.split('\t')
        if len(lineParts) > 1:
            #remove link part
            doid = lineParts[0].replace(DOID_link, '')
            result.append((doid, lineParts[1].lower()))
    return result

def entityAnnotation():
    """
    Get entities from Articles and Tweets.
    Requires: no args.
    Ensures: returns a list with 2 dictionaries (one with the terms for the articles
    and another with the terms for the tweets).
    """
    #dictionary with list of terms per article (title + abstract)
    termsPerArticle = {}
    #get article info
    articleInfo = getAllArticleInformation()
    #process information from articles
    for article in articleInfo:
        print("Processing article ", article['id'])
        #get list of of tuples (DOID, term) from MER
        listTerms = processEntitiesMER(callGetLinkEntitiesMER(article['title'] + article['abstract']))
        #keep did and article id in the dict key as a tuple
        termsPerArticle[(article['did'], article['id'])] = listTerms

    #dictionary with list of terms per tweet (html)
    termsPerTweet = {}
    #get tweet info
    tweetInfo = getAllTweetInformation()
    #process information from tweets
    for tweet in tweetInfo:
        print("Processing tweet ", tweet['id'])
        #get list of of tuples (DOID, term) from MER
        listTerms = processEntitiesMER(callGetLinkEntitiesMER(tweet['html']))
        #keep did and tweet id in the dict key as a tuple
        termsPerTweet[(tweet['did'], tweet['id'])] = listTerms

    return [termsPerArticle, termsPerTweet]
