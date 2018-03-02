#coding:ISO-8859-15
from urllib import request
user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
encoding = "ISO-8859-15"
headers={"User-Agent":user_agent,'Accept-Encoding':'ISO-8859-15'}
url = "http://static.sse.com.cn/disclosure/listedinfo/announcement/c/2018-03-03/600610_20180303_3.pdf"

with request.urlopen(url) as web:
    with open("announce.pdf", 'wb') as outfile:
        outfile.write(web.read())