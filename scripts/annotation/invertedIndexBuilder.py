#-*- coding: utf-8 -*-
# Python 3
from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *

def buildInvertedIndex():
    print("-> Cleaning tables")
    cleanTables()
    print("-> Calculating TF-IDF")
    dictsList = calculateTFIDF()
    print("-> Calculating Similarity")
    calculateSimilarity(dictsList)

#program entry point
buildInvertedIndex()
