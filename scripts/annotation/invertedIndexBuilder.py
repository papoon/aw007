from utils import *

def calculateTFIDF():
    #get entity terms from docs
    dictsList = entityAnnotation()
    #just some pointers for name clarification
    articleTermsDict = dictsList[0]
    tweetTermsDict = dictsList[1]

    #set of unique terms to calculate IDF easier
    uniqueTerms = set()

    #calculate TF for article terms
    #articleTfValues: key is term, value is list of tuples (article['id'], tf_value)
    articleTfValues = {}
    #iterate by all articles
    for key, value in articleTermsDict.items():
        #key is tuple (article['did'], article['id'])
        uniqueTermsInDoc = set(value)
        for term in uniqueTermsInDoc:
            #add to unique terms set
            uniqueTerms.add(term)
            #calculate TF value
            tf_value = calculateTF(term, value)
            #save TF value for this document
            if term in articleTfValues:
                articleTfValues[term] += [(key[1], tf_value)]
            else:
                articleTfValues[term] = [(key[1], tf_value)]

    #calculate TF for tweet terms
    #articleTfValues: key is term, value is list of tuples (tweet['id'], tf_value)
    tweetTfValues = {}
    #iterate by all tweets
    for key, value in tweetTermsDict.items():
        #key is tuple (tweet['did'], tweet['id'])
        uniqueTermsInDoc = set(value)
        for term in uniqueTermsInDoc:
            #add to unique terms set
            uniqueTerms.add(term)
            #calculate TF value
            tf_value = calculateTF(term, value)
            #save TF value for this document
            if term in tweetTfValues:
                tweetTfValues[term] += [(key[1], tf_value)]
            else:
                tweetTfValues[term] = [(key[1], tf_value)]

    #calculate IDF for all found unique terms
    idfValues = {}
    #iterate by all terms
    for term in uniqueTerms:
        idf_value = calculateIDF(term, dictsList)
        idfValues[term] = idf_value

    print("articleTfValues:")
    print(articleTfValues)
    print("tweetTfValues:")
    print(tweetTfValues)
    print("uniqueTerms:")
    print(uniqueTerms)
    print("idfValues:")
    print(idfValues)



def buildInvertedIndex():
    calculateTFIDF()

#program entry point
buildInvertedIndex()
