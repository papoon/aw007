#-*- coding: utf-8 -*-
# Python 3
from __future__ import division
import pymysql.cursors
import subprocess
import math
from constants import *
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

def cleanTables():
    """
    Deletes all records from TF-IDF and Similarity tables.
    Requires: no args.
    Ensures: clean slate for TF-IDF and Similarity tables.
    """
    listTables = [Table_Tf_Idf_Articles, Table_Tf_Idf_Tweets, Table_Sim_Articles, Table_Sim_Tweets]
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            for table in listTables:
                # create delete query
                sql = "DELETE FROM  " + table + ";"
                #execute insert query
                cursor.execute(sql)
                #commit explicitly (autocommit is off by default)
                connection.commit()
    finally:
        connection.close()

def getAllDiseaseInformation():
    """
    Gets all information needed for the DiShIn script to compare with terms.
    Requires: no args.
    Ensures: queries the database and retrieves disease information for
    all diseases in a list of dictionaries format (one line, one dictionary).
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Read all article records
            sql = "SELECT id, name FROM " + Table_Disease
            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

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
            sql = "SELECT id, did, title, abstract FROM " + Table_Article + " LIMIT 7"
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
            sql = "SELECT id, did, html FROM " + Table_Tweets + " LIMIT 7"
            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

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

def calculateTF(term, docTerms):
    """
    Calculate TF (term frequency) of a term in a document.
    Requires: term, the term to calculate the frequency;
              docTerms, list of tuples (DOID, term) with all terms found in the document.
    Ensures: returns the calculation for the TF of the given terms in
    the given document.
    """
    countTermOccurrences = 0
    totalNumberTerms = len(docTerms)
    for doid, t in docTerms:
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
        #get list with term values only
        term_list = [tup[1] for tup in value]
        if term in term_list:
            countDocsWithTerm += 1

    #iterate through tweets
    for key, value in docCollection[1].items():
        #get list with term values only
        term_list = [tup[1] for tup in value]
        if term in term_list:
            countDocsWithTerm += 1

    #return IDF calculation
    return math.log10(totalNumberDocs / countDocsWithTerm)

def saveTfIdfInformation(table, term, id, tf_idf_value):
    """
    Save TF-IDF values in the database.
    Requires: table, where to save the information (please use Table_Tf_Idf_Articles
              or Table_Tf_Idf_Tweets constants);
              term, the term associated to the TF-IDF value;
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              tf_idf_value, the TF-IDF value to save for the given term and the given document.
    Ensures: saves the TD-IDF value for a given term and a given document in the
    respective table.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # create insert query
            if table == Table_Tf_Idf_Articles:
                sql = "INSERT INTO " + Table_Tf_Idf_Articles + \
                      " (term, article_id, tf_idf_value) VALUES ('" + \
                      term + "', "  + str(id) + ', ' + str(tf_idf_value) + ");"
            elif table == Table_Tf_Idf_Tweets:
                sql = "INSERT INTO " + Table_Tf_Idf_Tweets + \
                      " (term, tweet_id, tf_idf_value) VALUES ('" + \
                      term + "', "+ str(id) + ', ' + str(tf_idf_value) + ");"
            else:
                raise ValueError('Table name: valid values are Tf_Idf_Articles and Tf_Idf_Tweets (see constants).')

            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()

def callDishin(term1, term2):
    """
    Calls DiShIn python script and retrieves the console result.
    Requires: term1 and term2, the terms to be analyzed by DiShIn with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (semantic similarity between term1 and term2 according to HDO).
    """
    result = subprocess.run(["python3", DISHIN_py_path, DISHIN_DB_path, term1, term2], \
                            cwd=DISHIN_path, stdout=subprocess.PIPE)
    return result.stdout.decode('utf-8')

def processDishinOutput(resultText):
    """
    Processes the output of callDishin.
    Requires: resultText, the output text from callDishin.
    Ensures: returns the Resnik DiShIn semantic similarity result.
    """
    lines = resultText.split('\n')
    for line in lines:
        if (Dishin_name in line) and (Resnik_name in line):
            lineParts = line.split('\t')
            #example ['Resnik ', ' DiShIn ', ' intrinsic ', '4.027']
            return float(lineParts[3])

#NOT YET TESTED
def saveSimilarityInformation(table, disease_id, id, resnik_value):
    """
    Save similarity Resnik values from DiShIn in the database.
    Requires: table, where to save the information (please use Table_Sim_Articles
              or Table_Sim_Tweets constants);
              disease_id, the disease id of the disease name associated to the Resnik value;
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              resnik_value, the TF-IDF value to save for the given disease and the given document.
    Ensures: saves the Resnik value for a given disease and a given document in the
    respective table.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # create insert query
            if table == Table_Sim_Articles:
                sql = "INSERT INTO " + Table_Sim_Articles + \
                      " (did, article_id, resnik_value) VALUES (" + \
                      str(did) + ", "  + str(id) + ', ' + str(resnik_value) + ");"
            elif table == Table_Sim_Tweets:
                sql = "INSERT INTO " + Table_Sim_Tweets + \
                      " (did, tweet_id, resnik_value) VALUES (" + \
                      str(did) + ", "  + str(id) + ', ' + str(resnik_value) + ");"
            else:
                raise ValueError('Table name: valid values are Similarity_Articles and Similarity_Tweets (see constants).')

            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()
