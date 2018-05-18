#-*- coding: utf-8 -*-
# Python 3
from constants import *
from datetime import datetime
from decimal import *

def getRelevanceValue(flagArticles, norm_info, tf_idf_value, resnik_value, imp_feedback, exp_feedback, published_at):
    """
    Calculates rankable value for inverted index for articles and tweets.
    Requires: flagArticles, true for Articles, false for Tweets;
              norm_info, dictionary with normalization variables (MIN(TFIDF), MAX(TFIDF), MIN(Resnik), MAX(Resnik),
              MIN(ImpFeedback), MAX(ImpFeedback), MIN(ExpFeedback), MAX(ExpFeedback));
              tf_idf_value, the TF-IDF value for the disease name in the given document;
              resnik_value, the minimum similarity Resnik value for the disease name in the given document;
              imp_feedback, the value for implicit feedback (clicks for Articles, nr_likes for Tweets);
              exp_feedback, the value for explicit feedback (relevance for Articles and Tweets);
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
    Rescales a given datetime value to the [0, 1] range.
    Requires: value, the datetime value to rescale;
              min, the minimum datetime value for the scale;
              max, the maximum datetime value for the scale;
    Ensures: returns a rescaled version of the datetime value in the [0, 1] range.
    """
    if (max - min).days == 0 or type(value) is str:
        return 0
    else:
        return Decimal((value - min).days / (max - min).days)
