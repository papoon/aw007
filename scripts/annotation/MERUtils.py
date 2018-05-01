#-*- coding: utf-8 -*-
# Python 3
import subprocess
from constants import *
from dbUtils import *


#TODO FIX ERROR ON TWEETS - ARTICLES ARE ALREADY SAVING CORRECTLY IN DB WITH NEW FIELDS 

def entityAnnotation():
    """
    Get entities from Articles and Tweets.
    Requires: no args.
    Ensures: saves MER terms on database and returns a list with 2 dictionaries
    (one with the terms for the articles and another with the terms for the tweets).
    """
    #dictionary with list of terms per article (title + abstract)
    termsPerArticle = {}

    #get article info
    articleInfo = getAllArticleInformation()

    # raw disease info
    diseaseInfo = getAllDiseaseInformation()

    # processed disease info - easier to access in next steps
    diseaseInfo = process_diseasesInfo(diseaseInfo)


    #process information from articles
    for article in articleInfo:


        print("Processing article ", article['id'])

        #result text from MER
        entityPositions = callGetEntitiesMER(article['title'] + article['abstract'])


        #result text from MER
        resultText = callGetLinkEntitiesMER(article['title'] + article['abstract'])

        # get list of dics from MER
        listTerms = processEntitiesMER(resultText)

        #only continues if finds entities otherwise jumps to tweet's process
        if entityPositions != '':


            #processed entityPosition (from str to list) - faster access
            entity_list = process_callGetEntitiesMER(entityPositions)

            for elem in entity_list:

                doid = ''
                disease_id = "NULL"


                #compares terms in entityPositions and listTerms to get do_id of term
                if elem['term'].lower() in listTerms:
                    doid = listTerms[elem['term'].lower()]

                    if doid in diseaseInfo:
                        disease_id = diseaseInfo[doid]


                # sets doid to the current term
                elem['doid'] = doid
                elem['disease_id'] = disease_id



            #save MER terms
            saveEntitiesMER(Table_MER_Terms_Articles,  article['id'], entity_list)

            #keep did and article id in the dict key as a tuple
            termsPerArticle[(article['did'], article['id'])] = listTerms

    #dictionary with list of terms per tweet (html)
    termsPerTweet = {}
    #get tweet info
    tweetInfo = getAllTweetInformation()

    # only iterates tweet info if is not an empty list
    if tweetInfo:

        for tweet in tweetInfo:

            print("Processing tweet ", tweet['id'])
            #result text from MER
            entityPositions = callGetEntitiesMER(tweet['html'])

            # result text from MER
            resultText = callGetLinkEntitiesMER(tweet['html'])

            # get list of of tuples (DOID, term) from MER
            listTerms = processEntitiesMER(resultText)

            if entityPositions != '':

                entity_list = process_callGetEntitiesMER(entityPositions)

                for elem in entity_list:

                    doid = ''
                    disease_id = "NULL"

                    # compares terms in entityPositions and listTerms to get do_id of term
                    if elem['term'].lower() in listTerms:
                        doid = listTerms[elem['term'].lower()]

                        if doid in diseaseInfo:
                            disease_id = diseaseInfo[doid]

                    # sets doid to the current term

                    elem['doid'] = doid
                    elem['disease_id'] = disease_id

                    # save MER terms
                    saveEntitiesMER(Table_MER_Terms_Tweets, tweet['id'], entity_list)

                    # result text from MER
                    resultText = callGetLinkEntitiesMER(tweet['html'])

                    # get list of of tuples (DOID, term) from MER
                    listTerms = processEntitiesMER(resultText)

                #keep did and tweet id in the dict key as a tuple
                termsPerTweet[(tweet['did'], tweet['id'])] = listTerms


    return [termsPerArticle, termsPerTweet]


def callGetEntitiesMER(text):
    """
    Calls MER getEntities.sh script and retrieves the console result.
    Requires: text, the text to be analyzed by MER with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (positions to the entities and their names).
    """
    #call get_entities script
    command1 = ["./get_entities.sh", text, "doid-simple"]
    process1 = subprocess.Popen(command1, cwd=MER_path, stdout=subprocess.PIPE)
    #get result from stdout and stderr
    (out1,err1) = process1.communicate()

    output = out1.decode('utf-8')

    return output

def callGetLinkEntitiesMER(text):
    """
    Calls MER getEntities.sh and linkEntities.sh scripts and retrieves the console result.
    Requires: text, the text to be analyzed by MER with the HDO.
    Ensures: calls the command and retrieves the console result as
    string (links to the entities and their names).
    NOTE: could be improved (to avoid having the callGetEntitiesMER method to get the intermediate result)
    """
    #call get_entities script
    command1 = ["./get_entities.sh", text, "doid-simple"]
    process1 = subprocess.Popen(command1, cwd=MER_path, stdout=subprocess.PIPE)

    #call link_entities script
    command2 = ["./link_entities.sh", MER_DB_path]
    process2 = subprocess.Popen(command2, cwd=MER_path, stdin=process1.stdout, stdout=subprocess.PIPE)

    #call sort
    command3 = ["sort"]
    process3 = subprocess.Popen(command3, cwd=MER_path, stdin=process2.stdout, stdout=subprocess.PIPE)

    #call uniq
    command4 = ["uniq"]
    process4 = subprocess.Popen(command4, cwd=MER_path, stdin=process3.stdout, stdout=subprocess.PIPE)
    #get result from stdout and stderr
    (out4,err4) = process4.communicate()
    return out4.decode('utf-8')

def processEntitiesMER(resultText):
    """
    Processes the output of callGetLinkEntitiesMER.
    Requires: resultText, the output text from callGetLinkEntitiesMER.
    Ensures: returns a list of tuples (DOID, term) for all found terms.
    """
    result = {}
    lines = resultText.split('\n')
    for line in lines:

        lineParts = line.split('\t')
        if len(lineParts) > 1:
            #remove link part

            doid = lineParts[0].split('/')[-1]

            #doid = lineParts[0].replace(DOID_link, '')
            result[lineParts[1].lower()] = doid

    return result

def process_callGetEntitiesMER(resultText):
    """
    Processes the output of callGetEntitiesMER.
    Requires: resultText, the output text from callGetLinkEntitiesMER.
    Ensures: returns a list of dictionary of the terms found
    """
    result = []
    lines = resultText.split('\n')
    for line in lines:

        lineParts = line.split('\t')

        if len(lineParts) > 1:
            #remove link part
            tmp = {

                'start': lineParts[0],
                'stop' : lineParts[1],
                'term' : lineParts[2]

            }

            result.append(tmp)

    return result

def process_diseasesInfo(diseaseInfo):
    """
        Processes the output of getAllDiseaseInformation().
        Requires: diseaseInfo, the output text from getAllDiseaseInformation().
        Ensures: returns a list of dictionary having as keys each do_id and value as disease id (primary key)
    """

    data={}

    for disease in diseaseInfo:

        data[disease['do_id']] = disease['id']

    return data

def saveEntitiesMER(table,id, data):
    """
    Saves the output of callGetLinkEntitiesMER to the database.
    Requires: table, where to save the information (please use Table_MER_Terms_Articles
              or Table_MER_Terms_Tweets constants);
              id, document database id (Articles(id) or Tweets(id) depending on table argument);
              resultText, the output text from callGetLinkEntitiesMER.
    Ensures: saves the ocurrences of terms in docs (including positions) in the database.
    """
    print("table: ", table, "id: ",id,"result  ", data)


    for i in data:

        disease_id = i['disease_id']
        doid = i['doid']
        start = i['start']
        stop = i['stop']
        term = i['term']

        saveMERTermsInformation(table, term, id, start, stop, doid, disease_id)

