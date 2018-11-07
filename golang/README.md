1. mkdir bolg
2. cd mkdir && touch Dockerfile
3. Dockerfile写入
```dockerfile
FROM golang:latest

WORKDIR $GOPATH/src/bolg
ADD . $GOPATH/src/bolg
RUN go build main.go
EXPOSE 8080
ENTRYPOINT ["./main"]

```

- 参数解释：

```dockerfile
FROM -> 母镜像 


WORKDIR -> 工作目录 

ADD -> 将文件复制到镜像中 

RUN -> 执行操作（就跟在终端执行语句一样） 

EXPOSE -> 暴露端口 

ENTRYPOINT -> 程序入口
```

4. 编写main.go 文件 放在myDocker下
5. 构建镜像
```dockerfile
docker build -t bolg .
```
6. docker images 查看创建的镜像
7.  docker run -p 8080:8080 -d bolg
  
  -p   本机端口:镜像端口 
  
  -d    后台运行
  
8.    本机访问 localhost:8080/zc 返回Hello Docker Form Golang!则成功
  

