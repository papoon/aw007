from sys import argv
import sys
sys.path.insert(0, '../scripts/annotation/')

from MERUtils import processEntitiesMER, callGetLinkEntitiesMER
from constants import *

def getDoidForDisease(diseaseName):
    """
    To call MER's methods from PHP.
    Requires: diseaseName, the name of the disease.
    Ensures: returns string with the DOID and the name of the most complete
    entity found for the disease name.
    """
    listEntities, dictEntities = processEntitiesMER(callGetLinkEntitiesMER(diseaseName))

    if len(dictEntities) == 0:
        print("No DOID found")
    else:
        if diseaseName.lower() in dictEntities:
            print(dictEntities[diseaseName.lower()])
        else:
            print("No DOID found")

# program entry point
scriptName, diseaseName = argv
getDoidForDisease(diseaseName)
