import requests as rq
from decimal import Decimal as dl
import os
import re
import asyncio
import websockets as ws
import nest_asyncio
import json
import os

#声明函数
def getTask(api,token):
    headers = {"User-Agent":"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Mobile Safari/537.36","Connection":"keep-alive"}
    payload = {"tasks":"get","key":token}
    a = rq.get(api,headers = headers,params = payload)
    return a.json()

def download(url,fileName,idd):
    headers = {"User-Agent":"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Mobile Safari/537.36","Connection":"keep-alive"}
    filePath = os.getcwd()
    with rq.get(url,stream=True,headers = headers) as r:
        #print(r.headers)
        filename=r.headers["Content-Type"]
        filesize=r.headers["Content-Length"]
        fileHou_1 = re.search('(/.*)',filename)
        fileHou_2 = fileHou_1[1]
        fileHou = fileHou_2.lstrip(fileHou_2[0])
        chunk_size=512
        times=int(filesize)//chunk_size
        status=1
        show=1/times
        shown=1/times
        b=open(filePath + '/'+fileName+'.'+fileHou,'wb')
        for i in r.iter_content(chunk_size):
            if i:
                b.write(i)
                if status<times:
                    d=dl(show).quantize(dl('0.00'))
                    e=dl(d)*100
                    #allOf = str(e) + '%'
                    #print(allOf)
                    status=status+1
                    show+=shown
                    a = str(e)
                else:
                    #print('100%')
                    a = '100'
    b.close()
    return a

def readJson(filePath):
    with open(filePath,'rb') as fp:
        a = json.load(fp)
    return a

def writeJson(filepath,msg):
    with open(filepath,'w+',encoding='utf-8') as fp:
        #fp.write(json.dumps(msg, indent=4))
        json.dump(msg, fp, indent=4)

def writeFile(filePath,msg):
    fp = open(filePath,'w+')
    fp.write(str(msg))
    fp.close()

async def sendMsg(connect,msg):
    await connect.send(msg)
    a = await connect.recv()
    b = json.loads(str(a))
    return b

async def dlFun(wsa):
    while True:
        await asyncio.sleep(2)
        try:
            a = await wsa.recv()
            b = json.loads(str(a))
            #print('Ht' + a)
            if b['task'] == 'creat':
                creUrl = b['url']
                #creTitle = b['title']
                #download(creUrl,creTitle)
                print(creUrl)
        except:
            c = ''

async def firLogin(wbs,token):
    await wbs.send('{"token":"'+token+'","type":"login","devices":"pc"}')
    a = await wbs.recv()
    b = json.loads(str(a))
    if b['name'] != '':
        print('Hello ' + b['name'] + ',you have login success on Pc')

async def mainFun(url,token):
    async with ws.connect(url) as websocket:
        print('connect success')
        await firLogin(websocket,token)
        await dlFun(websocket)

if __name__ == '__main__':
    confPath = '../data/conf.json'
    msg = readJson(confPath)
    getJson = getTask(msg['hostMsg']['api'],msg['user']['token'])
    msg['dllist'] = getJson['data']
    writeJson(confPath,msg)
    #nest_asyncio.apply()
    #loop=asyncio.get_event_loop()
    #loop.run_until_complete(mainFun(msg['hostMsg']['ip'] + ':' + msg['hostMsg']['port'],msg['user']['token']))
    #writeFile('download.json','{"dldata":[{"title":"wis","url":"testyuu","dltype":1,"type":"dl","id":0}]}')