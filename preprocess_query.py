import normalize.preprocess as preprocess
import normalize.stopword as stopword
import sys
import json

def not_null(obj):
    return obj != None

def remove_stopwords(tokens):
    sword = stopword.StopWord()
    return sword.remove_stopwords(tokens)

def normalize(text):
    preprocessed = preprocess.PreProcess(text)
    preprocessed.normalize_letter()
    preprocessed.remove_punctuation()
    return preprocessed.get_text()

def tokenize(normalized):
    if not_null(normalized):
        tokens = normalized.split()
        return remove_stopwords(tokens)
    else: return normalized

query_list = sys.argv[1:]
q = ''
for word in query_list:
    q = q + word + ' '

normalized_query = normalize(q)
query_tokens = tokenize(normalized_query)
print(json.dumps(query_tokens))