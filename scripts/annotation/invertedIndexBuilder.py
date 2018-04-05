from utils import *
from constants import *

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
    articleTfValues = {}
    #iterate by all articles
    for key, value in articleTermsDict.items():
        #key is tuple (article['did'], article['id'])
        #value is tuple (DOID, term)
        term_list = [tup[1] for tup in value]
        uniqueTermsInDoc = set(term_list)
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
        #value is tuple (DOID, term)
        term_list = [tup[1] for tup in value]
        uniqueTermsInDoc = set(term_list)
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

    # print("articleTfValues:")
    # print(articleTfValues)
    # print("tweetTfValues:")
    # print(tweetTfValues)
    # print("uniqueTerms:")
    # print(uniqueTerms)
    # print("idfValues:")
    # print(idfValues)

    #calculate TF-IDF for Articles
    for key, value in articleTfValues.items():
        #get idf value for term
        idf_value = idfValues[key]
        #key is term, value is list of tuples (article['id'], tf_value)
        for element in value:
            #TF-IDF = TF * IDF (rounded to 4 decimal cases)
            tf_idf_value = round(element[1] * idf_value, 4)
            #save TF-IDF in Tf_Idf_Articles table
            saveTfIdfInformation(Table_Tf_Idf_Articles, key, element[0], tf_idf_value)

    #calculate TF-IDF for Tweets
    for key, value in tweetTfValues.items():
        #get idf value for term
        idf_value = idfValues[key]
        #key is term, value is list of tuples (tweet['id'], tf_value)
        for element in value:
            #TF-IDF = TF * IDF (rounded to 4 decimal cases)
            tf_idf_value = round(element[1] * idf_value, 4)
            #save TF-IDF in Tf_Idf_Tweets table
            saveTfIdfInformation(Table_Tf_Idf_Tweets, key, element[0], tf_idf_value)

    #pass already calculated values to next method
    return dictsList

#NOT YET TESTED
def calculateSimilarity(dictsList):
    """
    Calculates similarity between all diseases and all terms in all documents
    (articles and tweets).
    Requires: no args.
    Ensures: TF-IDF calculation for all terms in all documents and database
    insertion of the values for posterior use in relevance calculations.
    TO IMPROVE: get an auxiliary method to process both dicts
    """
    #just some pointers for name clarification
    articleTermsDict = dictsList[0]
    tweetTermsDict = dictsList[1]

    #get disease information
    diseaseInfo = getAllDiseaseInformation()

    #calculate similarity for article terms and save them
    print(' Calculating similarity between Article terms and Disease names')
    #iterate by all diseases
    for disease in diseaseInfo:
        #iterate by all articles
        for key, value in articleTermsDict.items():
            #articleTermsDict: key is tuple (article['did'], article['id'])
            #                  value is list of tuples (DOID, term)
            #list to keep all Resnik values for a given document
            resnikValuesInDoc = []
            #get unique DOIDs
            #value is tuple (DOID, term)
            doid_list = [tup[0] for tup in value]
            uniqueTermsInDoc = set(doid_list)
            #calculate Resnik value between disease do_id and term do_id and add to list
            for doid in uniqueTermsInDoc:
                print('  Calculating similarity between ', disease['do_id'], ' and ' , term_doid)
                try:
                    similarity = processDishinOutput(callDishin(disease['do_id'], term_doid))
                    if similarity is not None:
                        resnikValuesInDoc += [similarity]
                except TypeError:
                    print('  Not possible (None as a result), skipping...')
                    #catch the error and do nothing
                    pass
            #get minimum Resnik value and round it (rounded to 4 decimal cases)
            if len(resnikValuesInDoc) > 0:
                resnik_value = round(min(resnikValuesInDoc), 4)
            else:
                resnik_value = 0.0000
            #save minimum Resnik value for this document
            saveSimilarityInformation(Table_Sim_Articles, disease['id'], key[1], resnik_value)

    #calculate similarity for tweet terms and save them
    print(' Calculating similarity between Tweet terms and Disease names')
    #iterate by all diseases
    for disease in diseaseInfo:
        #iterate by all tweets
        for key, value in tweetTermsDict.items():
            #tweetTermsDict: key is tuple (tweet['did'], tweet['id']), value is list of terms
            #list to keep all Resnik values for a given document
            resnikValuesInDoc = []
            #get unique DOIDs
            #value is tuple (DOID, term)
            doid_list = [tup[0] for tup in value]
            uniqueTermsInDoc = set(doid_list)
            #calculate Resnik value between disease do_id and term do_id and add to list
            for doid in uniqueTermsInDoc:
                print('  Calculating similarity between ', disease['do_id'], ' and ' , term_doid)
                try:
                    similarity = processDishinOutput(callDishin(disease['do_id'], term_doid))
                    if similarity is not None:
                        resnikValuesInDoc += [similarity]
                except TypeError:
                    print('  Not possible (None as a result), skipping...')
                    #catch the error and do nothing
                    pass
            #get minimum Resnik value and round it (rounded to 4 decimal cases)
            if len(resnikValuesInDoc) > 0:
                resnik_value = round(min(resnikValuesInDoc), 4)
            else:
                resnik_value = 0.0000
            #save minimum Resnik value for this document
            saveSimilarityInformation(Table_Sim_Tweets, disease['id'], key[1], resnik_value)

def buildInvertedIndex():
    print("-> Cleaning tables")
    cleanTables()
    print("-> Calculating TF-IDF")
    dictsList = calculateTFIDF()
    print("-> Calculating Similarity")
    calculateSimilarity(dictsList)

#program entry point
buildInvertedIndex()
