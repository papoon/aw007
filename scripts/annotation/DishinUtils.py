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
