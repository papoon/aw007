#-*- coding: utf-8 -*-
# Python 3
from __future__ import division
import pymysql.cursors
import subprocess
import math
from constants import *

import sys
sys.path.insert(0, '../../private/')
from pythonPrivate import *

def getDatabaseConnection():
    """
    Returns connection to the database.
    Requires: no args.
    Ensures: returns a connection to the database.
    """
    # Connect to the database
    connection = pymysql.connect(host=DB_HOST,
                                 user=DB_USER,
                                 password=DB_PASS,
                                 db=DB_NAME,
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)
    return connection

def getAllArticleInformation():
    """
    Gets all information needed for the MER script to get the entities.
    Requires: no args.
    Ensures: queries the database and retrieves article information for
    all diseases in a list of dictionaries format (one line, one dictionary).
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Read all article records
            sql = "SELECT id, did, title, abstract FROM Article"
            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

def getAllTweetInformation():
    """
    Gets all information needed for the MER script to get the entities.
    Requires: no args.
    Ensures: queries the database and retrieves tweet information for
    all diseases in a list of dictionaries format (one line, one dictionary).
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Read all tweet records
            sql = "SELECT id, did, html FROM Tweets"
            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

def callGetEntitiesMER(text):
    """
    Calls MER getEntities.sh script and retrieves the console result.
    Requires: text, the text to be analyzed by MER with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (found diseases in text and respective positions).
    """
    result = subprocess.run(["./get_entities.sh", text, "doid-simple"], cwd=MER_path, stdout=subprocess.PIPE)
    return result.stdout.decode('utf-8')

def processEntitiesMER(resultText):
    """
    Processes the output of callGetEntitiesMER.
    Requires: resultText, the output text from callGetEntitiesMER.
    Ensures: returns a list of all found terms in lowercase.
    """
    result = []
    lines = resultText.split('\n')
    for line in lines:
        lineParts = line.split('\t')
        if len(lineParts) > 1:
            #get only the entity term in lowercase
            result.append(lineParts[2].lower())
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
        #get list of terms from MER
        listTerms = processEntitiesMER(callGetEntitiesMER(article['title'] + article['abstract']))
        #keep did and article id in the dict key as a tuple
        termsPerArticle[(article['did'], article['id'])] = listTerms

    #dictionary with list of terms per tweet (html)
    termsPerTweet = {}
    #get tweet info
    tweetInfo = getAllTweetInformation()
    #process information from tweets
    for tweet in tweetInfo:
        #get list of terms from MER
        listTerms = processEntitiesMER(callGetEntitiesMER(tweet['html']))
        #keep did and tweet id in the dict key as a tuple
        termsPerTweet[(tweet['did'], tweet['id'])] = listTerms

    return [termsPerArticle, termsPerTweet]

def calculateTF(term, docTerms):
    """
    Calculate TF (term frequency) of a term in a document.
    Requires: term, the term to calculate the frequency;
              docTerms, list with all terms found in the document.
    Ensures: returns the calculation for the TF of the given terms in
    the given document.
    """
    countTermOccurrences = 0
    totalNumberTerms = len(docTerms)
    for t in docTerms:
        if t == term:
            countTermOccurrences += 1

    #return TF calculation
    return countTermOccurrences / totalNumberTerms

def calculateIDF(term, docCollection):
    """
    Calculate IDF (inverse document frequency) of a term in all documents.
    Requires: term, the term to calculate the frequency;
              docCollection, list with list with 2 dictionaries (one with
              the terms for the articles and another with the terms for the tweets).
    Ensures: returns the calculation for the IDF of the given term in
    the given collection of terms (of all documents).
    """
    countDocsWithTerm = 0
    #total number of docs = number of articles + number of tweets
    totalNumberDocs = len(docCollection[0]) + len(docCollection[1])

    #iterate through articles
    for key, value in docCollection[0].items():
        if term in value:
            countDocsWithTerm += 1

    #iterate through tweets
    for key, value in docCollection[1].items():
        if term in value:
            countDocsWithTerm += 1

    #return IDF calculation
    return math.log10(totalNumberDocs / countDocsWithTerm)
