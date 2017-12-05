g++ -o jjjni.so jni.cpp -fPIC -shared \
	-I/usr/lib/jvm/java-1.8.0-openjdk-amd64/include \
	-I/usr/lib/jvm/java-1.8.0-openjdk-amd64/include/linux \
	-ljudger -L/usr/lib/judger \
	-O2 -g -std=c++14 -Wall
