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
            sql = "SELECT id, did, title, abstract FROM " + Table_Article + " LIMIT 2"
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
            sql = "SELECT id, did, html FROM " + Table_Tweets + " LIMIT 2"
            cursor.execute(sql)
            result = cursor.fetchall()
            return result
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
                      str(disease_id) + ", "  + str(id) + ', ' + str(resnik_value) + ");"
            elif table == Table_Sim_Tweets:
                sql = "INSERT INTO " + Table_Sim_Tweets + \
                      " (did, tweet_id, resnik_value) VALUES (" + \
                      str(disease_id) + ", "  + str(id) + ', ' + str(resnik_value) + ");"
            else:
                raise ValueError('Table name: valid values are Similarity_Articles and Similarity_Tweets (see constants).')

            #execute insert query
            cursor.execute(sql)
            #commit explicitly (autocommit is off by default)
            connection.commit()
    finally:
        connection.close()
