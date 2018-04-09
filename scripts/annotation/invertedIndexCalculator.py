#-*- coding: utf-8 -*-
# Python 3
from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *

def calculateInvertedIndex():
    print("-> Cleaning calculation tables")
    cleanCalculationTables()
    print("-> Calculating TF-IDF")
    dictsList = calculateTFIDF()
    print("-> Calculating Similarity")
    calculateSimilarity(dictsList)

#program entry point
calculateInvertedIndex()