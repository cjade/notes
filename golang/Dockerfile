FROM golang:latest

WORKDIR $GOPATH/src/bolg
ADD . $GOPATH/src/bolg
RUN go build main.go
EXPOSE 8080
ENTRYPOINT ["./main"]