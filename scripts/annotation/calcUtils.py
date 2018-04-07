#-*- coding: utf-8 -*-
# Python 3
from __future__ import division
import math
from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *

def calculateTF(term, docTerms):
    """
    Calculate TF (term frequency) of a term in a document.
    Requires: term, the term to calculate the frequency;
              docTerms, list of tuples (DOID, term) with all terms found in the document.
    Ensures: returns the calculation for the TF of the given terms in
    the given document.
    """
    countTermOccurrences = 0
    totalNumberTerms = len(docTerms)
    for doid, t in docTerms:
        if t == term:
            countTermOccurrences += 1

    #return TF calculation
    return countTermOccurrences / totalNumberTerms

def calculateIDF(term, docCollection):
    """
    Calculate IDF (inverse document frequency) of a term in all documents.
    Requires: term, the term to calculate the frequency;
              docCollection, list with list with 2 dictionaries (one with
              the terms for the articles and another with the terms for the tweets).
    Ensures: returns the calculation for the IDF of the given term in
    the given collection of terms (of all documents).
    """
    countDocsWithTerm = 0
    #total number of docs = number of articles + number of tweets
    totalNumberDocs = len(docCollection[0]) + len(docCollection[1])

    #iterate through articles
    for key, value in docCollection[0].items():
        #get list with term values only
        term_list = [tup[1] for tup in value]
        if term in term_list:
            countDocsWithTerm += 1

    #iterate through tweets
    for key, value in docCollection[1].items():
        #get list with term values only
        term_list = [tup[1] for tup in value]
        if term in term_list:
            countDocsWithTerm += 1

    #return IDF calculation
    return math.log10(totalNumberDocs / countDocsWithTerm)

def calculateTFIDF():
    """
    Calculates TF-IDF for all terms in all documents (articles and tweets).
    Requires: no args.
    Ensures: TF-IDF calculation for all terms in all documents and database
    insertion of the values for posterior use in relevance calculations.
    """
    #get entity terms from docs
    dictsList = entityAnnotation()
    #just some pointers for name clarification
    articleTermsDict = dictsList[0]
    tweetTermsDict = dictsList[1]

    #set of unique terms to calculate IDF easier
    uniqueTerms = set()

    #calculate TF for article terms
    #articleTfValues: key is term, value is list of tuples (article['id'], tf_value)
    articleTfValues = auxTFCalculation(articleTermsDict, uniqueTerms)

    #calculate TF for tweet terms
    #articleTfValues: key is term, value is list of tuples (tweet['id'], tf_value)
    tweetTfValues = auxTFCalculation(tweetTermsDict, uniqueTerms)

    #calculate IDF for all found unique terms
    idfValues = {}
    #iterate by all terms
    for term in uniqueTerms:
        idf_value = calculateIDF(term, dictsList)
        idfValues[term] = idf_value

    # print("articleTfValues:")
    # print(articleTfValues)
    # print("tweetTfValues:")
    # print(tweetTfValues)
    # print("uniqueTerms:")
    # print(uniqueTerms)
    # print("idfValues:")
    # print(idfValues)

    #calculate TF-IDF for Articles
    auxTFIDFCalculation(articleTfValues, idfValues, Table_Tf_Idf_Articles)

    #calculate TF-IDF for Tweets
    auxTFIDFCalculation(tweetTfValues, idfValues, Table_Tf_Idf_Tweets)

    #pass already calculated values to next method
    return dictsList


def auxTFCalculation(docDictTerms, uniqueTerms):
    """
    Calculates TF-IDF for all terms in all documents (articles or tweets).
    Requires: docDictTerms, one of the dicts result of entityAnnotation method;
              uniqueTerms, set where unique terms for IDF are stored.
    Ensures: TF-IDF calculation for all terms in all given documents and return
    of a dictionary with said values.
    """
    #docTfValues: key is term, value is list of tuples (doc['id'], tf_value)
    docTfValues = {}
    #iterate by all docs
    for key, value in docDictTerms.items():
        #key is tuple (doc['did'], doc['id'])
        #value is tuple (DOID, term)
        term_list = [tup[1] for tup in value]
        uniqueTermsInDoc = set(term_list)
        for term in uniqueTermsInDoc:
            #add to unique terms set
            uniqueTerms.add(term)
            #calculate TF value
            tf_value = calculateTF(term, value)
            #save TF value for this document
            if term in docTfValues:
                docTfValues[term] += [(key[1], tf_value)]
            else:
                docTfValues[term] = [(key[1], tf_value)]
    #return result
    return docTfValues

def auxTFIDFCalculation(tfDict, idfValues, tableToSave):
    """
    Auxiliary function to calculate similarity between all diseases and all terms
    in all documents in a dictionary (articles or tweets).
    Requires: tfDict, result of auxTFCalculation method for articles or tweets;
              idfValues, dict of IDF values for all terms;
              tableToSave, variable Table_Tf_Idf_Articles or Table_Tf_Idf_Tweets.
    Ensures: TF-IDF calculation for all terms in all given documents and database
    insertion of the values for posterior use in relevance calculations.
    """
    for key, value in tfDict.items():
        #get idf value for term
        idf_value = idfValues[key]
        #key is term, value is list of tuples (tweet['id'], tf_value)
        for element in value:
            #TF-IDF = TF * IDF (rounded to 4 decimal cases)
            tf_idf_value = round(element[1] * idf_value, 4)
            #save TF-IDF in Tf_Idf_Tweets table
            saveTfIdfInformation(tableToSave, key, element[0], tf_idf_value)

def calculateSimilarity(dictsList):
    """
    Calculates similarity between all diseases and all terms in all documents
    (articles and tweets).
    Requires: no args.
    Ensures: Semantic similarity calculation for all terms in all documents and
    database insertion of the values for posterior use in relevance calculations.
    """
    #just some pointers for name clarification
    articleTermsDict = dictsList[0]
    tweetTermsDict = dictsList[1]
    #get disease information
    diseaseInfo = getAllDiseaseInformation()

    #calculate similarity for article terms and save them
    print(' Calculating similarity between Article terms and Disease names')
    #call auxiliary method for Articles
    auxSimilarity(diseaseInfo, articleTermsDict, Table_Sim_Articles)

    #calculate similarity for tweet terms and save them
    print(' Calculating similarity between Tweet terms and Disease names')
    #call auxiliary method for Tweets
    auxSimilarity(diseaseInfo, tweetTermsDict, Table_Sim_Tweets)

def auxSimilarity(diseaseInfo, termsDict, tableToSave):
    """
    Auxiliary function to calculate similarity between all diseases and all terms
    in all documents in a dictionary (articles or tweets).
    Requires: diseaseInfo, result of getAllDiseaseInformation method;
              termsDict, dict of article terms or tweet terms;
              tableToSave, variable Table_Sim_Articles or Table_Sim_Tweets.
    Ensures: Semantic similarity calculation for all terms in all given documents
    and database insertion of the values for posterior use in relevance calculations.
    """
    #iterate by all diseases
    for disease in diseaseInfo:
        #iterate by all docs in dict
        for key, value in termsDict.items():
            #termsDict: key is tuple (doc['did'], doc['id'])
            #                  value is list of tuples (DOID, term)
            #list to keep all Resnik values for a given document
            resnikValuesInDoc = []
            #get unique DOIDs
            #value is tuple (DOID, term)
            doid_list = [tup[0] for tup in value]
            uniqueTermsInDoc = set(doid_list)
            #calculate Resnik value between disease do_id and term do_id and add to list
            for doid in uniqueTermsInDoc:
                print('  Calculating similarity between ', disease['do_id'], ' and ' , doid)
                try:
                    similarity = processDishinOutput(callDishin(disease['do_id'], doid))
                    if similarity is not None:
                        resnikValuesInDoc += [similarity]
                except TypeError:
                    print('  Not possible (None as a result), skipping...') #doesnt work
                    #catch the error and do nothing
                    pass
            #get minimum Resnik value and round it (rounded to 4 decimal cases)
            if len(resnikValuesInDoc) > 0:
                resnik_value = round(min(resnikValuesInDoc), 4)
            else:
                resnik_value = 0.0000
            #save minimum Resnik value for this document
            saveSimilarityInformation(tableToSave, disease['id'], key[1], resnik_value)

def createInvertedIndexForDisease(disease_id, disease_name):
    
