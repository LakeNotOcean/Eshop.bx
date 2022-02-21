from mysql.connector import connect, Error


def completeQuery(query):
    try:
        connection = connect(
            host="localhost",
            user="phpgang",
            password="ilovephp",
            database="eshop"
        )
        connection.set_charset_collation('utf8')
        with connection.cursor() as cursor:
            cursor.execute(query)
            result = cursor.fetchall()
            connection.commit()
            return result
    except Error as e:
        print(e, ". Query: ", query)
