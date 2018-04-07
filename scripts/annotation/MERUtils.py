#-*- coding: utf-8 -*-
# Python 3
import subprocess
from constants import *
from dbUtils import *

def callGetEntitiesMER(text):
    """
    Calls MER getEntities.sh script and retrieves the console result.
    Requires: text, the text to be analyzed by MER with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (positions to the entities and their names).
    """
    #call get_entities script
    command1 = ["./get_entities.sh", text, "doid-simple"]
    process1 = subprocess.Popen(command1, cwd=MER_path, stdout=subprocess.PIPE)
    #get result from stdout and stderr
    (out1,err1) = process1.communicate()
    return out1.decode('utf-8')

def callGetLinkEntitiesMER(text):
    """
    Calls MER getEntities.sh and linkEntities.sh scripts and retrieves the console result.
    Requires: text, the text to be analyzed by MER with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (links to the entities and their names).
    NOTE: could be improved (to avoid having the callGetEntitiesMER method to get the intermediate result)
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
    (out4,err4) = process4.communicate()
    return out4.decode('utf-8')

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

def saveEntitiesMER(table, id, resultText):
    """
    Saves the output of callGetLinkEntitiesMER to the database.
    Requires: table, where to save the information (please use Table_MER_Terms_Articles
              or Table_MER_Terms_Tweets constants);
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              resultText, the output text from callGetLinkEntitiesMER.
    Ensures: saves the ocurrences of terms in docs (including positions) in the database.
    """
    result = []
    lines = resultText.split('\n')
    for line in lines:
        #example of line: 348	354   asthma
        lineParts = line.split('\t')
        if len(lineParts) > 1:
            print(lineParts)
            saveMERTermsInformation(table, lineParts[2].lower(), id, int(lineParts[0]), \
                                    int(lineParts[1]))

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
        #result text from MER
        entityPositions = callGetEntitiesMER(article['title'] + article['abstract'])
        #save MER terms
        saveEntitiesMER(Table_MER_Terms_Articles, article['id'], entityPositions)
        #result text from MER
        resultText = callGetLinkEntitiesMER(article['title'] + article['abstract'])
        #get list of of tuples (DOID, term) from MER
        listTerms = processEntitiesMER(resultText)
        #keep did and article id in the dict key as a tuple
        termsPerArticle[(article['did'], article['id'])] = listTerms

    #dictionary with list of terms per tweet (html)
    termsPerTweet = {}
    #get tweet info
    tweetInfo = getAllTweetInformation()
    #process information from tweets
    for tweet in tweetInfo:
        print("Processing tweet ", tweet['id'])
        #result text from MER
        entityPositions = callGetEntitiesMER(tweet['html'])
        #save MER terms
        saveEntitiesMER(Table_MER_Terms_Tweets, tweet['id'], entityPositions)
        #result text from MER
        resultText = callGetLinkEntitiesMER(tweet['html'])
        #get list of of tuples (DOID, term) from MER
        listTerms = processEntitiesMER(resultText)
        #keep did and tweet id in the dict key as a tuple
        termsPerTweet[(tweet['did'], tweet['id'])] = listTerms

    return [termsPerArticle, termsPerTweet]
