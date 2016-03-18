CC	= gcc
CXX	= g++
CFLAGS	= -O2 -g -lm
CPPFLAGS = 
CXXFLAGS = -O2 -L/usr/local/mysql/lib -I/usr/local/mysql/include -g
DEFS	=   
LIBS	= -O2 -g -L/usr/local/mysql/lib -lmysqlclient -lpthread -lz -lm -lrt -lssl -lcrypto -ldl
OBJS	= main.o db.o judge.o

all: ./j clean

./j : $(OBJS)
	$(CXX) $(CFLAGS) $(OBJS) ${LIBS} -o ./j

./judge_client : gcc -O2 -o judge_client judge_client.c
	
.cpp.o :
	$(CXX) $(CXXFLAGS) ${CPPFLAGS} ${DEFS} -c $<

.c.o:
	$(CC) $(CFLAGS) ${CPPFLAGS} ${DEFS} -c $<

clean :
	rm -f ${OBJS}
