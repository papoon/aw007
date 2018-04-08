#-*- coding: utf-8 -*-
# Python 3
from __future__ import division
import math
from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *
from operator import itemgetter
from datetime import datetime
from decimal import *

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
    """
    Creates inverted index for articles and tweets for the given disease.
    Requires: disease_id, id of the disease to process;
              disease_name, name of the disease for the same id.
    Ensures: Calculates the rank of associated articles and tweets and saves the
    information in the database.
    """
    #get calculation information for articles of the given disease
    articlesCalcInfo = getArticleCalcInformation(disease_id)
    #get normalization information for articles
    articlesNormInfo = getArticleNormInformation()
    #list to contain final values (for posterior ranking)
    finalValues = []
    #calculate rank for the Articles
    for article in articlesCalcInfo:
        relevanceValue = getRelevanceValue(True, articlesNormInfo, article['tf_idf_value'], \
                         article['resnik_value'], article['clicks'], article['relevance'], \
                         article['published_at'])
        finalValues += [(article, relevanceValue)]
    #sort list by relevanceValue
    finalValues = sorted(finalValues, key=itemgetter(1), reverse=True)
    #save information in the database
    for i in range(1, len(finalValues)):
        article = finalValues[i][0]
        saveInvertedIndexInformation(Table_Index_Articles, disease_id, article['article_id'], i, \
                                     article['tf_idf_value'], article['resnik_value'], \
                                     article['clicks'], article['relevance'], \
                                     article['published_at'])

    #get calculation information for tweets of the given disease
    tweetsCalcInfo = getTweetCalcInformation(disease_id)
    #get normalization information for tweets
    tweetsNormInfo = getTweetNormInformation()
    #list to contain final values (for posterior ranking)
    finalValues = []
    #calculate rank for the Tweets
    for tweet in tweetsCalcInfo:
        relevanceValue = getRelevanceValue(False, tweetsNormInfo, tweet['tf_idf_value'], \
                         tweet['resnik_value'], tweet['nr_likes'], tweet['relevance'], \
                         tweet['published_at'])
        finalValues += [(tweet, relevanceValue)]
    #sort list by relevanceValue
    finalValues = sorted(finalValues, key=itemgetter(1), reverse=True)
    #save information in the database
    for i in range(1, len(finalValues)):
        tweet = finalValues[i][0]
        saveInvertedIndexInformation(Table_Index_Tweets, disease_id, tweet['tweet_id'], i, \
                                     tweet['tf_idf_value'], tweet['resnik_value'], \
                                     tweet['nr_likes'], tweet['relevance'], \
                                     tweet['published_at'])

def getRelevanceValue(flagArticles, norm_info, tf_idf_value, resnik_value, imp_feedback, exp_feedback, published_at):
    """
    Calculates rankable value for inverted index for articles and tweets.
    Requires: flagArticles, true for Articles, false for Tweets;
              norm_info, dictionary with normalization variables (MIN(TFIDF), MAX(TFIDF), MIN(Resnik), MAX(Resnik));
              tf_idf_value, the TF-IDF value to save for the given term and the given document;
              resnik_value, the similarity Resnik value to save for the given disease and the given document;
              imp_feedback, the value for implicit feedback to save (clicks for Articles, nr_likes for Tweets);
              exp_feedback, the value for explicit feedback to save (relevance for Articles and Tweets);
              published_at, the date of publication of the document.
    Ensures: Calculates the value that will dictate the rank of a given article or tweet.
    """
    #variable setting
    if(flagArticles):
        minTfIdf = norm_info[Min_Tfidf_Articles]
        maxTfIdf = norm_info[Max_Tfidf_Articles]
        minSimilarity = norm_info[Min_Resnik_Articles]
        maxSimilarity = norm_info[Max_Resnik_Articles]
        minImpFeedback = norm_info[Min_Clicks_Articles]
        maxImpFeedback = norm_info[Max_Clicks_Articles]
        minExpFeedback = norm_info[Min_Relev_Articles]
        maxExpFeedback = norm_info[Max_Relev_Articles]
    else:
        minTfIdf = norm_info[Min_Tfidf_Tweets]
        maxTfIdf = norm_info[Max_Tfidf_Tweets]
        minSimilarity = norm_info[Min_Resnik_Tweets]
        maxSimilarity = norm_info[Max_Resnik_Tweets]
        minImpFeedback = norm_info[Min_Likes_Tweets]
        maxImpFeedback = norm_info[Max_Likes_Tweets]
        minExpFeedback = norm_info[Min_Relev_Tweets]
        maxExpFeedback = norm_info[Max_Relev_Tweets]

    #earliest dtae = 1st of January 2017
    minDate = datetime(2017, 1, 1, 0, 0, 0)
    maxDate = datetime.now()
    maxDate = maxDate.replace(hour=0, minute=0, second=0, microsecond=0)

    #rescaling numeric variables
    tdIdfRescaled = rescale(tf_idf_value, minTfIdf, maxTfIdf)
    similarityRescaled = rescale(resnik_value, minSimilarity, maxSimilarity)
    impFeedbackRescaled = rescale(imp_feedback, minImpFeedback, maxImpFeedback)
    expFeedbackRescaled = rescale(exp_feedback, minExpFeedback, maxExpFeedback)
    publishedDateRescaled = rescaleDatetime(published_at, minDate, maxDate)

    #calculate final value
    relevance = Coef_Tfidf * tdIdfRescaled + Coef_Similarity * similarityRescaled + \
                Coef_Imp_Feedback * impFeedbackRescaled + Coef_Exp_Feedback * expFeedbackRescaled + \
                Coef_Pub_date * publishedDateRescaled

    return relevance

def rescale(value, min, max):
    """
    Rescales a given value to the [0, 1] range.
    Requires: value, the value to rescale;
              min, the minimum value for the scale;
              max, the maximum value for the scale;
    Ensures: returns a rescaled version of the value in the [0, 1] range.
    """
    if (max - min) == 0:
        return 0
    else:
        return Decimal((value - min) / (max - min))

def rescaleDatetime(value, min, max):
    """
    Rescales a given value to the [0, 1] range.
    Requires: value, the value to rescale;
              min, the minimum value for the scale;
              max, the maximum value for the scale;
    Ensures: returns a rescaled version of the value in the [0, 1] range.
    """
    if (max - min).days == 0:
        return 0
    else:
        return Decimal((value - min).days / (max - min).days)
