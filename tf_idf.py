from numpy.lib.function_base import delete
from db_mysql import DB_MYSQL
import json 
import numpy as np
from collections import Counter

db = DB_MYSQL()
DF = {}
N = 0
IDF = {}
alpha = 0.6
val = []

def doc_freq(word):
    c = 0
    try:
        c = DF[word]
    except:
        pass
    return c

def calculate_df(all_news):
    for row in all_news:
        title_tokens = json.loads(row[1])
        body_tokens = json.loads(row[2])
        news_tokens = title_tokens + body_tokens

        for token in news_tokens:
            try:
                DF[token].add(row[0])
            except:
                DF[token] = {row[0]}

def calculate_idf():
    for token in DF:
        DF[token] = len(DF[token])
        df = doc_freq(token)
        IDF[token] = np.log((N+1)/(df+1))
    

def calculate_tf_idf(all_news):
    for row in all_news:
        title_tokens = json.loads(row[1])
        body_tokens = json.loads(row[2])
        news_tokens = title_tokens + body_tokens
        tokens_counter = Counter(news_tokens)
        num_of_tokens = len(news_tokens)

        for token in np.unique(news_tokens):
            idf = IDF[token]
            tf = tokens_counter[token]/num_of_tokens

            if token in title_tokens and token in body_tokens:
                tf_idf = tf * idf
            elif token in title_tokens:
                tf_idf = alpha * (tf * idf)
            elif token in body_tokens:
                tf_idf = (1 - alpha) * (tf * idf)
            
            val.append((str(token), row[0], str(tf_idf)))
        



if __name__ == "__main__":
    print("Selecting all news from DB...")
    all_news = db.select_all_news()
    N = len(all_news)
    print("Calculating DF of all tokens...")
    calculate_df(all_news)
    print("Calculating IDF of all (news, token) pair...")
    calculate_idf()
    print("Calculating TF-IDF of all (news, token) pairs...")
    calculate_tf_idf(all_news)
    print("Inserting TF-IDF into table...")
    db.insert_many_tf_idf(val)
    # db.insert_many_idf(IDF)


