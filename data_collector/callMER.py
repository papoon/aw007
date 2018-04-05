from sys import argv
import sys
sys.path.insert(0, '../scripts/annotation/')

from utils import processEntitiesMER, callGetLinkEntitiesMER
from constants import *

def getDoidForDisease(diseaseName):
    """
    To call MER's methods from PHP.
    Requires: diseaseName, the name of the disease.
    Ensures: returns string with the DOID and the name of the most complete
    entity found for the disease name.
    """
    listEntities = processEntitiesMER(callGetLinkEntitiesMER(diseaseName))

    if len(listEntities) == 0:
        print("No DOID found")
    elif len(listEntities) > 1:
        # return last entity DOID (the most complete)
        print(listEntities[len(listEntities) - 1][0])
    else:
        # return the only entity DOID (the most complete)
        print(listEntities[0][0])

# program entry point
scriptName, diseaseName = argv
getDoidForDisease(diseaseName)
