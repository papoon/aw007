#-*- coding: utf-8 -*-
# Python 3
import subprocess
from constants import *

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

def getDishinDetailedOutput(resultText):
    """

    Processes the output of callDishin.
    Requires: resultText, the output text from callDishin.
    Ensures: returns the all values from Dishin tool.

    """
    try:

        text = resultText.replace("\n","/")
        text = text.replace("\t","/")
        text = text.split("/")

        data = {

            'resnik_dishin' : text[3],
            'resnik_mica' : text[7],
            'lin_dishin' : text[11],
            'lin_mica' : text[15],
            'jc_dishin' : text[19],
            'jc_mica' : text[23]

        }


        return data


    except:

        print("Ups, couldn't find that doid")


#getDishinDetailedOutput(callDishin("DOID_9970","DOID_9972"))
