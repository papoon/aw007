from utils import *
from constants import *
from callMER import *

#program entry point
diseaseInfo = getAllDiseaseInformation()
#process information from diseases
for disease in diseaseInfo:
    print("Processing disease ", disease['name'])
    #get list of of tuples (DOID, term) from MER
    print(getDoidForDisease(disease['name']))
