#-*- coding: utf-8 -*-
# Python 3
import pymysql.cursors
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

def cleanCalculationTables():
    """
    Deletes all records from MER terms, TF-IDF and Similarity tables.
    Requires: no args.
    Ensures: clean slate for MER terms, TF-IDF and Similarity tables.
    """
    listTables = [Table_Tf_Idf_Articles, Table_Tf_Idf_Tweets, Table_Sim_Articles, \
                  Table_Sim_Tweets, Table_MER_Terms_Articles, Table_MER_Terms_Tweets]
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

def cleanIndexTables():
    """
    Deletes all records from Inverted Index tables.
    Requires: no args.
    Ensures: clean slate for Inverted Index tables.
    """
    listTables = [Table_Index_Articles, Table_Index_Tweets]
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
            sql = "SELECT id, name, do_id FROM " + Table_Disease
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
            sql = "SELECT id, did, title, abstract FROM " + Table_Article
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
            sql = "SELECT id, did, html FROM " + Table_Tweets
            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

def saveMERTermsInformation(table, term, id, pos_start, pos_end, doid, disease_id):
    """
    Save MER terms and respective positions in documents in the database.
    Requires: table, where to save the information (please use Table_MER_Terms_Articles
              or Table_MER_Terms_Tweets constants);
              term, the term associated to the positions and document;
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              pos_start, starting position of the term in the given document;
              pos_end, end position of the term in the given document.
    Ensures: saves the positions for given term in a given document in the
    respective table.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # create insert query
            if table == Table_MER_Terms_Articles:
                sql = "INSERT INTO " + Table_MER_Terms_Articles + \
                      " (term, article_id, pos_start, pos_end,disease_id,do_id) VALUES ('"
            elif table == Table_MER_Terms_Tweets:
                sql = "INSERT INTO " + Table_MER_Terms_Tweets + \
                      " (term, tweet_id, pos_start, pos_end, disease_id, do_id) VALUES ('"
            else:
                raise ValueError('Table name: valid values are Table_MER_Terms_Articles and Table_MER_Terms_Tweets (see constants).')

            sql += term + "', "  + str(id) + ', ' + str(pos_start) + ', ' + str(pos_end) + ', ' +                       str(disease_id) + ", '" + str(doid)+ "' );"


            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()

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
                      " (term, article_id, tf_idf_value) VALUES ('"

            elif table == Table_Tf_Idf_Tweets:
                sql = "INSERT INTO " + Table_Tf_Idf_Tweets + \
                      " (term, tweet_id, tf_idf_value) VALUES ('"
            else:
                raise ValueError('Table name: valid values are Tf_Idf_Articles and Tf_Idf_Tweets (see constants).')

            sql += term + "', "  + str(id) + ', ' + str(tf_idf_value) + ");"

            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()

def saveSimilarityInformation(table, disease_id, id, resnik_value):
    """
    Save similarity Resnik values from DiShIn in the database.
    Requires: table, where to save the information (please use Table_Sim_Articles
              or Table_Sim_Tweets constants);
              disease_id, the disease id of the disease name associated to the Resnik value;
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              resnik_value, the similarity Resnik value to save for the given disease and the
              given document.
    Ensures: saves the Resnik value for a given disease and a given document in the
    respective table.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # create insert query
            if table == Table_Sim_Articles:
                sql = "INSERT INTO " + Table_Sim_Articles + \
                      " (did, article_id, resnik_value) VALUES ("
            elif table == Table_Sim_Tweets:
                sql = "INSERT INTO " + Table_Sim_Tweets + \
                      " (did, tweet_id, resnik_value) VALUES ("
            else:
                raise ValueError('Table name: valid values are Similarity_Articles and Similarity_Tweets (see constants).')

            sql += str(disease_id) + ", "  + str(id) + ', ' + str(resnik_value) + ");"

            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()

def saveInvertedIndexInformation(table, disease_id, id, rank, tf_idf_value, resnik_value, imp_feedback, \
                                 exp_feedback, published_at):
    """
    Save inverted index information in the database.
    Requires: table, where to save the information (please use Table_Index_Articles
              or Table_Index_Tweets constants);
              disease_id, the disease id of the disease name associated to the article being ranked;
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              tf_idf_value, the TF-IDF value to save for the given term and the given document;
              resnik_value, the similarity Resnik value to save for the given disease and the given document;
              imp_feedback, the value for implicit feedback to save (clicks for Articles, nr_likes for Tweets);
              exp_feedback, the value for explicit feedback to save (relevamce for Articles and Tweets);
              published_at, the date of publication of the document.
    Ensures: saves the Inverted index value for a given disease and a given document in the
    respective table.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # create insert query
            if table == Table_Index_Articles:
                sql = "INSERT INTO " + Table_Index_Articles + \
                      " (did, article_id, article_rank, tf_idf_value, resnik_value, clicks, relevance, published_at) VALUES ("
            elif table == Table_Index_Tweets:
                sql = "INSERT INTO " + Table_Index_Tweets + \
                      " (did, tweet_id, tweet_rank, tf_idf_value, resnik_value, nr_likes, relevance, published_at) VALUES ("
            else:
                raise ValueError('Table name: valid values are Inv_Index_Articles and Inv_Index_Tweets (see constants).')

            sql += str(disease_id) + ", "  + str(id) + ", "  + str(rank) + ", "  + str(tf_idf_value) + ', ' + \
                   str(resnik_value) + ", "  + str(imp_feedback) + ", "  + str(exp_feedback) + \
                   ", '"  + str(published_at) + "');"

            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()
def getArticleCalcInformation(disease_id):
    """
    Gets all information needed for the inverted index rank calculations for articles.
    Requires: no args.
    Ensures: queries the database and retrieves inverted index rank information for articles for
    one disease in a list of dictionaries format (one line, one dictionary).
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Get all pre-calculated information for Articles
            sql = "SELECT DISTINCT TA.article_id, TA.tf_idf_value, SA.resnik_value, A.clicks, A.relevance, A.published_at FROM " + \
                  Table_Tf_Idf_Articles + " AS TA, " + Table_Sim_Articles + " AS SA, " + Table_Article + " AS A, " + \
                  Table_Disease + " AS D " + \
                  "WHERE TA.term = D.name AND TA.article_id = A.id AND SA.did = D.id AND SA.article_id = A.id AND D.id = " + \
                  str(disease_id) + ";"

            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

def getTweetCalcInformation(disease_id):
    """
    Gets all information needed for the inverted index rank calculations for tweets.
    Requires: no args.
    Ensures: queries the database and retrieves inverted index rank information for tweets for
    one disease in a list of dictionaries format (one line, one dictionary).
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Get all pre-calculated information for Tweets
            sql = "SELECT DISTINCT TT.tweet_id, TT.tf_idf_value, ST.resnik_value, T.nr_likes, T.relevance, T.published_at FROM " + \
                  Table_Tf_Idf_Tweets + " AS TT, " + Table_Sim_Tweets + " AS ST, " + Table_Tweets + " AS T, " + \
                  Table_Disease + " AS D " + \
                  "WHERE TT.term = D.name AND TT.tweet_id = T.id AND ST.did = D.id AND ST.tweet_id = T.id AND D.id = " + \
                  str(disease_id) + ";"

            cursor.execute(sql)
            result = cursor.fetchall()
            return result
    finally:
        connection.close()

def getArticleNormInformation():
    """
    Gets normalization values needed for the inverted index rank calculations for articles.
    Requires: no args.
    Ensures: returns a dictionary with the minimum and maximum values of TFIDF, Resnik, clicks
    and relevance values for the rankable articles.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Get normalization values for Articles
            sql = "SELECT MIN(TA.tf_idf_value), MAX(TA.tf_idf_value), MIN(SA.resnik_value), MAX(SA.resnik_value), \
                  MIN(A.clicks), MAX(A.clicks), MIN(A.relevance), MAX(A.relevance) FROM " + \
                  Table_Tf_Idf_Articles + " AS TA, " + Table_Sim_Articles + " AS SA, " + Table_Article + " AS A, " + \
                  Table_Disease + " AS D " + \
                  "WHERE TA.term = D.name AND TA.article_id = A.id AND SA.did = D.id AND SA.article_id = A.id;"

            cursor.execute(sql)
            result = cursor.fetchall()
            return result[0]
    finally:
        connection.close()

def getTweetNormInformation():
    """
    Gets normalization values needed for the inverted index rank calculations for tweets.
    Requires: no args.
    Ensures: returns a dictionary with the minimum and maximum values of TFIDF, Resnik, clicks
    and relevance values for the rankable tweets.
    """
    connection = getDatabaseConnection()

    try:
        with connection.cursor() as cursor:
            # Get normalization values for Tweets
            sql = "SELECT MIN(TT.tf_idf_value), MAX(TT.tf_idf_value), MIN(ST.resnik_value), MAX(ST.resnik_value), \
                  MIN(T.nr_likes), MAX(T.nr_likes), MIN(T.relevance), MAX(T.relevance) FROM " + \
                  Table_Tf_Idf_Tweets + " AS TT, " + Table_Sim_Tweets + " AS ST, " + Table_Tweets + " AS T, " + \
                  Table_Disease + " AS D " + \
                  "WHERE TT.term = D.name AND TT.tweet_id = T.id AND ST.did = D.id AND ST.tweet_id = T.id;"

            cursor.execute(sql)
            result = cursor.fetchall()
            return result[0]
    finally:
        connection.close()
