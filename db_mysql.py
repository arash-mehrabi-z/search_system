import mysql.connector
import json 

class DB_MYSQL:
    def __init__(self):
        mydb = mysql.connector.connect(
            host="localhost",
            user="YOUR_USER_HERE",
            password="YOUR_PASSWORD_HERE",
            database="search_system"
        )
        self.mydb = mydb
        self.mycursor = mydb.cursor()

    def insert_news(self, title_tokens, body_tokens, title_text, description, url, date):

        title_tokens = json.dumps(title_tokens)
        body_tokens = json.dumps(body_tokens)

        sql = "INSERT INTO news (title_tokens, body_tokens, title_text, description, url, date) VALUES (%s, %s, %s, %s, %s, %s)"
        val = (title_tokens, body_tokens, title_text, description, url, date)

        self.mycursor.execute(sql, val)
        self.mydb.commit()

    def select_all_news(self):
        self.mycursor.execute("SELECT * FROM news")

        myresult = self.mycursor.fetchall()

        return myresult

    def insert_many_idf(self, IDF):
        sql = "INSERT INTO idf (token, idf) VALUES (%s, %s)"
        val = []
        for token in IDF:
            val.append((token, str(IDF[token])))

        self.mycursor.executemany(sql, val)

        self.mydb.commit()
        print(self.mycursor.rowcount, "was inserted.")

    def insert_many_tf_idf(self, val):
        sql = "INSERT INTO tf_idf (token, news_id, tf_idf) VALUES (%s, %s, %s)"

        self.mycursor.executemany(sql, val)

        self.mydb.commit()
        print(self.mycursor.rowcount, "was inserted.")

    def select_all_idf(self):
        self.mycursor.execute("SELECT * FROM idf")

        myresult = self.mycursor.fetchall()

        return myresult