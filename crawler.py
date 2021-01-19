import requests
from bs4 import BeautifulSoup
import normalize.preprocess as preprocess
import normalize.stopword as stopword
from hazm import Stemmer
from hazm import Lemmatizer
from db_mysql import DB_MYSQL

# all_news_body_tokens = []
main_page = 'http://news.urmia.ac.ir'
db = DB_MYSQL()

def not_null(obj):
    return obj != None

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

def remove_stopwords(tokens):
    sword = stopword.StopWord()
    return sword.remove_stopwords(tokens)

def stemming_and_lemmatization(token):
    stemmer = Stemmer()
    lemmatizer = Lemmatizer()

    stemmed = stemmer.stem(token)
    lemmatized = lemmatizer.lemmatize(stemmed)
    return lemmatized

def get_news_link(n):
    link = n.find('a')
    news_link = main_page + link.get('href')
    return news_link

def get_news_page(n):
    news_link = get_news_link(n)
    news_page = requests.get(news_link)
    news_page_parser = BeautifulSoup(news_page.content, 'html.parser')
    return news_page_parser

def get_paragraph_tokens(paragraph):
    normalized_paragraph = normalize(paragraph.text)
    paragraphs_tokens = tokenize(normalized_paragraph)
    return paragraphs_tokens

def find_paragraphs(news_page_parser):
    news_body = news_page_parser.find('div', {'class': 'field-item even'})
    paragraphs = news_body.find_all('p')
    return paragraphs

def parse_news_body(news_page_parser):
    body_tokens = []
    paragraphs = find_paragraphs(news_page_parser)
    for paragraph in paragraphs:
        paragraphs_tokens = get_paragraph_tokens(paragraph)
        for ptokens in paragraphs_tokens:
            body_tokens.append(ptokens)
    return body_tokens
    # all_news_body_tokens.append(body_tokens)

def parse_news_title(news_page_parser):
    h1_title = news_page_parser.find('h1', {'class': 'art-postheader'})
    normalized_title = normalize(h1_title.text)
    title_tokens = tokenize(normalized_title)
    return title_tokens

def get_news_title_text(n):
    link = n.find('a')
    return link.text

def get_news_description(n):
    description = n.find('span', {'class': 'field-content news-body'})
    return description.text

def get_news_date(n):
    date_div = n.find('div', {'class': 'views-field views-field-created'})
    date_span = date_div.find('span', {'class': 'field-content'})
    return date_span.text

def get_next_page_link(soup):
    pager = soup.find('div', {'class': 'art-pager'})
    all_pages_link = pager.find_all('a')
    for page_link in all_pages_link:
        if page_link.text == 'بعدی›':
            return main_page + page_link.get('href')
    return None

def crawl(page_link):
    r = requests.get(page_link)
    soup = BeautifulSoup(r.content, 'html.parser')
    body = soup.find('div', {'class': 'art-layout-cell art-content'})
    news = body.find_all('div', {'class': 'panel-flexible panels-flexible-panellaypou1 clearfix'})
    for n in news:
        news_page_parser = get_news_page(n)
        title_tokens = parse_news_title(news_page_parser)
        body_tokens = parse_news_body(news_page_parser)
        url = get_news_link(n)
        title_text = get_news_title_text(n)
        description = get_news_description(n)
        date = get_news_date(n)
        db.insert_news(title_tokens, body_tokens, title_text, description, url, date)
    
    next_page_link = get_next_page_link(soup)
    return next_page_link

if __name__ == "__main__":
    next_page_link = main_page

    while next_page_link != None:
        print("Crawling " + next_page_link)
        next_page_link = crawl(next_page_link)

    print("Done!")