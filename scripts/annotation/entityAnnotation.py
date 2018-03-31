from utils import *
from constants import *

def startEntityAnnotation():
    #get article info
    articleInfo = getAllArticleInformation()
    #MER test
    for article in articleInfo:
        print(callGetEntitiesMER(article['abstract']))
        print("*")

#program entry point
startEntityAnnotation()
