
#-*- coding: utf-8 -*-
# Python 3
from dbUtils import *
from invertedIndexUtils import *
from constants import *


def createInvertedIndex():
    #get disease information
    diseaseInfo = getAllDiseaseInformation()
    #iterate by all diseases
    for disease in diseaseInfo:
        createInvertedIndexForDisease(disease['id'], disease['name'])

def buildInvertedIndex():
    print("-> Cleaning index tables")
    cleanIndexTables()
    print("-> Building inverted index for all diseases")
    createInvertedIndex()

#program entry point
buildInvertedIndex()
