from utils import *
from constants import *

#program entry point
dictsList = entityAnnotation()
print("Article information terms:")
print(dictsList[0])
print("Tweet information terms:")
print(dictsList[1])
