#-*- coding: utf-8 -*-
# Python 3
import pymysql.cursors
import subprocess
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
            # Read all disease records
            sql = "SELECT id, did, title, abstract FROM Article"
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
