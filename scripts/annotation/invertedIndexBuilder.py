#-*- coding: utf-8 -*-
# Python 3
from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *

def buildInvertedIndex():
    print("-> Building Inverted Index")
    cleanIndexTables()


#program entry point
buildInvertedIndex()
