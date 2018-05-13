from decimal import *

#path to the MER tool
MER_path = '../../tools/MER_Tool'
MER_DB_path = 'data/doid-simple.owl'

#path to the DiShIn tool
DISHIN_path = '../../tools/DiShIn-master'
DISHIN_py_path = DISHIN_path + '/dishin.py'
DISHIN_DB_path = DISHIN_path + '/data/doid.db'

#doid part to remove
DOID_link = 'http://purl.obolibrary.org/obo/'

#DISHIN_path = '/home/aw000/DiShIn'
#DISHIN_py_path = DISHIN_path + '/dishin.py'
#DISHIN_DB_path = DISHIN_path + '/doid.db'

#DiShIn variables
Dishin_name = 'DiShIn'
Resnik_name = 'Resnik'

#table names
Table_Article_Author = 'Article_Author'
Table_Article = 'Article'
Table_Photos = 'Photos'
Table_Tweets = 'Tweets'
Table_Author = 'Author'
Table_Disease = 'Disease'
Table_MER_Terms_Articles = 'MER_Terms_Articles'
Table_MER_Terms_Tweets = 'MER_Terms_Tweets'
Table_Tf_Idf_Articles = 'Tf_Idf_Articles'
Table_Tf_Idf_Tweets = 'Tf_Idf_Tweets'
Table_Sim_Articles = 'Similarity_Articles'
Table_Sim_Tweets = 'Similarity_Tweets'
Table_Sim_Diseases = 'Similarity_Diseases'
Table_Index_Articles = 'Inv_Index_Articles'
Table_Index_Tweets = 'Inv_Index_Tweets'

#misc constants
Min_Tfidf_Articles = 'MIN(TA.tf_idf_value)'
Max_Tfidf_Articles = 'MAX(TA.tf_idf_value)'
Min_Resnik_Articles = 'MIN(SA.resnik_value)'
Max_Resnik_Articles = 'MAX(SA.resnik_value)'
Min_Clicks_Articles = 'MIN(A.clicks)'
Max_Clicks_Articles = 'MAX(A.clicks)'
Min_Relev_Articles = 'MIN(A.relevance)'
Max_Relev_Articles = 'MAX(A.relevance)'

Min_Tfidf_Tweets = 'MIN(TT.tf_idf_value)'
Max_Tfidf_Tweets = 'MAX(TT.tf_idf_value)'
Min_Resnik_Tweets = 'MIN(ST.resnik_value)'
Max_Resnik_Tweets = 'MAX(ST.resnik_value)'
Min_Likes_Tweets = 'MIN(T.nr_likes)'
Max_Likes_Tweets = 'MAX(T.nr_likes)'
Min_Relev_Tweets = 'MIN(T.relevance)'
Max_Relev_Tweets = 'MAX(T.relevance)'

#importance values in the relevance formula (all 5 should sum as 1)
Coef_Tfidf = Decimal(0.2)
Coef_Similarity = Decimal(0.2)
Coef_Imp_Feedback = Decimal(0.2)
Coef_Exp_Feedback = Decimal(0.3)
Coef_Pub_date = Decimal(0.1)
