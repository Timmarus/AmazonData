from sqlalchemy import create_engine
import pyodbc

user = ""
passw = ""
host = ""
db = "AmazonCustomers"
cnxn = pyodbc.connect('DRIVER=mssql;SERVER='+host+';DATABASE='+db+';UID='+user+';PWD='+passw)
engine = create_engine("mssql://" + user + ":" + passw + "@" + host + "/" + db)
for row in engine.execute("select user_id, user_name from users"):
    print(row.user_id, row.user_name)
