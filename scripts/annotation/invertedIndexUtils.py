#-*- coding: utf-8 -*-
# Python 3
from dbUtils import *
from operator import itemgetter
from relevance import *

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
    #get explicit feedback (all star ratings for articles of this disease)
    starRatingsArticles = getStarRatingsArticles(disease_id)
    #list to contain final values (for posterior ranking)
    finalValues = []

    #calculate rank for the Articles
    for article in articlesCalcInfo:
        if article['article_id'] in starRatingsArticles:
            avgStarRating = starRatingsArticles[article['article_id']]
        else:
            avgStarRating = 0

        relevanceValue = getRelevanceValue(True, articlesNormInfo, article['tf_idf_value'], \
                         article['resnik_value'], article['clicks'], avgStarRating, \
                         article['published_at'])
        finalValues += [(article, relevanceValue)]
    #sort list by relevanceValue
    finalValues = sorted(finalValues, key=itemgetter(1), reverse=True)

    #save information in the database
    for i in range(1, len(finalValues)):
        article = finalValues[i][0]

        if article['article_id'] in starRatingsArticles:
            avgStarRating = starRatingsArticles[article['article_id']]
        else:
            avgStarRating = 0

        saveInvertedIndexInformation(Table_Index_Articles, disease_id, article['article_id'], i, \
                                     article['tf_idf_value'], article['resnik_value'], \
                                     article['clicks'], avgStarRating, \
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
