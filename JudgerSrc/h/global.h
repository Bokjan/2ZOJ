const int BigBuf = 10240;
const int SmallBuf = 10240;
const int TinyBuf = 10240;
const int PollWaitInS = 1;
const int CompMsgMax = 1000;
const int ResdataMax = 500;
const int ResultMax = 4;
const char ProblemPath[] = "/home/OJ/p/";
const char JudgerPath[] = "/home/OJ/Judger/";
const char TmpPath[] = "/home/OJ/tmp/";
const char SrcPath[] = "/home/OJ/src/";
const char UpdateSql[] = "UPDATE oj_submit SET result='%s',timeused=%d,memused=%d,resdata='%s',score=%d,accepted=%d,compmsg='%s',time=%d WHERE id=%s";
const char GccCmd[] = "gcc -Wall -std=c99 -DOJ -o %s %s%s.c -lm >& %scmsg.t";//exe_file,src_path,id,tmp_path
const char GppCmd[] = "g++ -Wall -std=c++0x -DOJ -o %s %s%s.cpp -lm >& %scmsg.t";
const char FpcCmd[] = "fpc -Mtp -v0 -dOJ -Sgic -Tlinux -o%s %s%s.pas -lm >& %scmsg.t";
