from dbUtils import *

#program entry point
l = getStarRatingsArticles(1);

print(l);

# d = {}
#
# for row in l:
#     d[row["article_id"]] = row['avg(AR.rating)']
#
# print(d)
