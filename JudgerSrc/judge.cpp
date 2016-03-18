#include "h/global.h"
#include "h/db.h"
#include <ctime>
#include <cstdio>
#include <cstring>
#include <cstdlib>
#include <string>
#include <fstream>
#include <cmath>
#define Log printf
extern Util::Db Db;
struct RowOfTableSubmit{
	char Result[ResultMax], CompMsg[CompMsgMax], Resdata[ResdataMax];
	int TimeUsed, MemUsed, Score, Time;
	bool Accepted;
	RowOfTableSubmit(void)
	{
		TimeUsed = MemUsed = Score = 0;
		Accepted = false;
		memset(CompMsg, 0, sizeof(char) * CompMsgMax);
		memset(Resdata, 0, sizeof(char) * ResdataMax);
	}
};
inline int TimeStamp(void)
{
	return (int)time(NULL);
}
bool FileExists(char *file)
{
	FILE *fp = fopen(file, "rb");
	if(NULL == fp)
		return false;
	else{
		fclose(fp);
		return true;
	}
}
bool Compare_Cpp(char* Ans)
{
	char Out[TinyBuf];
	sprintf(Out,"%sfoo.out",TmpPath);
	std::ifstream std(Ans);
	std::ifstream out(Out);
	std::string s1;
	std::string s2;
	int h = 0;
	while (std && out){
		bool flag = getline(std, s1);
		flag = getline(out, s2) && flag;
		if (!flag)
			break;
		h++;
		int k = s1.length();
		while (k && (s1[k - 1] == ' ' || s1[k - 1] == '\r' || s1[k - 1] == '\n')) k--;
		s1 = s1.substr(0, k);
		
		k = s2.length();
		while (k && (s2[k - 1] == ' ' || s2[k - 1] == '\r' || s2[k - 1] == '\n')) k--;
		s2 = s2.substr(0, k);
		
		if (s1 != s2){
			string::size_type k = 0;
			while (k < s1.length() && k < s2.length() && s1[k] == s2[k]) k++;
			if (k == s1.length()){
				return false;
			}
			if (k == s2.length()){
				return false;
			}
			return false;
		}
	}
	return true;
}
bool Compare(char *Ans)
{
	char Out[TinyBuf];
	sprintf(Out,"%sfoo.out",TmpPath);
	FILE *i = fopen(Ans, "r"), *o = fopen(Out, "r");
	char a, b;
	while(fscanf(i, "%c", &a) != EOF){
		if(a == ' ' || a == '\r' || a == '\n')
			continue;
		while(fscanf(o, "%c", &b) != EOF){
			if(b == ' ' || b == '\r' || b == '\n')
				continue;
			else
				break;
		}
		if(a != b)
			return false;
	}
	while(fscanf(o, "%c", &b) != EOF)
	{
		if(b == ' ' || b == '\r' || b == '\n')
			continue;
		else
			return false;
	}
	fclose(i);
	fclose(o);
	return true;
}
void Done(char* sid,RowOfTableSubmit* data){
	//WHAT THE FUCK!!!
	////result,timeused,memused,resdata,score,accepted,time,compmsg,pid
	//UPDATE oj_submit SET result='%s',timeused=%d,memused=%d,resdata='%s',score=%d,accepted=%d,compmsg='%s',time=%d WHERE id=%s
	char t[16];
	std::string sql = "UPDATE oj_submit SET result='";
	sql += data->Result; sql += "',timeused=";
	sprintf(t,"%d",data->TimeUsed);
	sql += t; sql += ",memused=";
	sprintf(t,"%d",data->MemUsed);
	sql += t; sql += ",resdata='"; sql += data->Resdata;
	sql += "',score=";
	sprintf(t,"%d",data->Score);
	sql += t; sql += ",accepted=";
	sprintf(t,"%d",data->Accepted);
	sql += t; sql += ",compmsg='";
	for(int i = 0; i != strlen(data->CompMsg); ++i)
		if('\'' == data->CompMsg[i])
			data->CompMsg[i] = '`';
	sql += data->CompMsg;
	sql += "',time=";
	sprintf(t,"%d",data->Time);
	sql += t;
	sql += " WHERE id="; sql += sid;
	Db.Execute((char *)sql.c_str());
	//Log("%s\n", (char *)sql.c_str());
	Log("Task finished at %d, result: %s.\n\n", TimeStamp(), data->Result);
}
void CompileError(RowOfTableSubmit *task, int PtNum)
{
	strcpy(task->Result, "CE");
	while(PtNum--)
		strcat(task->Resdata, "0 0 0 CE 0\n");
	task->Time = TimeStamp();
}
void JudgerError(RowOfTableSubmit *task, int PtNum)
{
	strcpy(task->Result, "JE");
	while(PtNum--)
		strcat(task->Resdata, "0 0 0 JE 0\n");
	task->Time = TimeStamp();
}
void Judge(MYSQL_ROW rq)
{
	Log("Task %s started at %d, language: %s.\n", rq[0], TimeStamp(), rq[3]);
	RowOfTableSubmit task;
	char Exe[TinyBuf], InputFileBase[TinyBuf], OutputFileBase[TinyBuf], Cmd[TinyBuf];
	int PtNum;
	Util::Db Db2;
	sprintf(Cmd, "SELECT dataset,mlim,tlim FROM oj_problem WHERE id=%s", rq[1]);
	MYSQL_ROW prob = Db2.Find(Cmd);
	if(NULL == prob){
		Db2.Release();
		Log("Invalid pid #%s, task terminated.\n\n", rq[1]);
		JudgerError(&task, 1);
		Done(rq[0], &task);
		return;
	}
	PtNum = atoi(prob[0]);
	int MemLim = atoi(prob[1]), TimeLim = atoi(prob[2]), PtScore = ceil(100.0 / PtNum);
	//LOAD CONFIGURATIONS
	sprintf(Exe, "%s%s/conf.ini", ProblemPath, rq[1]);
	FILE *fp = fopen(Exe, "r");
	if(NULL == fp){
		JudgerError(&task, PtNum);
		Done(rq[0], &task);
		Log("Cannot load config file #%s, task terminated.\n\n", rq[1]);
		return;
	}
	fscanf(fp, "%s%s", InputFileBase, OutputFileBase);
	fclose(fp);
	//COMPILE PROGRAM
	sprintf(Exe, "%sfoo", TmpPath);
	if(FileExists(Exe))	//DELETE THE LAST EXECUTABLE
		remove(Exe);	//LAST JUDGING MAY DO NOT BUILD A FILE
	if(!strcmp(rq[3], "c"))
		sprintf(Cmd, GccCmd, Exe, SrcPath, rq[0], TmpPath);
	if(!strcmp(rq[3], "cpp"))
		sprintf(Cmd, GppCmd, Exe, SrcPath, rq[0], TmpPath);
	if(!strcmp(rq[3], "pas"))
		sprintf(Cmd, FpcCmd, Exe, SrcPath, rq[0], TmpPath);
	system(Cmd);		//COMPILE
	sprintf(Cmd, "%scmsg.t", TmpPath);
	fp=fopen(Cmd, "r");
	//fpc compatiable
	if(!strcmp(rq[3], "pas"))
		fseek(fp, 114, SEEK_SET);
	//
	fread(task.CompMsg, sizeof(char), CompMsgMax - 1, fp);
	fclose(fp);
	remove(Cmd);		//DELETE COMPILER OUTPUT FILE
	if(!FileExists(Exe)){//COMPILE FAILED
		CompileError(&task, PtNum);
		Done(rq[0], &task);
		return;
	}
	//INITIALIZE THE OUTPUT FILE
	//GENERATE THE __result's PATH
	sprintf(Cmd, "%s__result.txt", JudgerPath);
	bool SpecFlag = false;
	//RUN JUDGE_CLIENT
	for(int i = 0; i < PtNum; i++){
		char JcCmd[SmallBuf], AnsPath[SmallBuf];
		sprintf(JcCmd, "%sjudge_client %sfoo foo %d %d %s/%s/%s%d.in %sfoo.out", JudgerPath, TmpPath, TimeLim, MemLim, ProblemPath, rq[1], InputFileBase, i, TmpPath);
		system(JcCmd);
		char __result[32], __result2[32 + 16];
		fp = fopen(Cmd, "r");
		if(NULL == fp){
			Log("Judger config error, task terminated.\n\n");
		}
		fgets(__result, 32 - 1, fp);
		fclose(fp);
		__result[strlen(__result) - 1] = 0;
		int _a[3];
		char _b[4];
		bool ThisPtSpec = false;
		sscanf(__result, "%d%d%d%s", _a, _a + 1, _a + 2, _b);
		if(!strcmp("TLE", _b) || !strcmp("MLE", _b) || !strcmp("RE", _b)){
			SpecFlag = true;
			strcpy(task.Result, _b);
			sprintf(__result2, "%s %d\n", __result, 0);
			ThisPtSpec = true;
		}
		sprintf(AnsPath, "%s%s/%s%d.ans", ProblemPath, rq[1], OutputFileBase, i);
		if(!FileExists(AnsPath))
			sprintf(AnsPath, "%s%s/%s%d.out", ProblemPath, rq[1], OutputFileBase, i);
		if(!FileExists(AnsPath))
			break;
		if(!ThisPtSpec && Compare(AnsPath)){
			sprintf(__result2, "%s %d\n", __result, PtScore);
			task.Score += PtScore;
		}
		else if(!ThisPtSpec){
			int _a, _b, _c;
			sscanf(__result, "%d%d%d", &_a, &_b, &_c);
			sprintf(__result2, "%d %d %d %s %d\n", _a, _b, _c, "WA", 0);
		}
		strcat(task.Resdata, __result2);
		task.TimeUsed += *(_a);
		task.MemUsed += *(_a + 1);
	}
	if(task.Score >= 100 && !SpecFlag){
		task.Accepted = true;
		task.Score = 100;
		strcpy(task.Result, "AC");
		Db2.Release();
		char sql[SmallBuf];
		sprintf(sql, "SELECT id FROM oj_submit WHERE uid=%s AND pid=%s AND accepted=1 LIMIT 1", rq[2], rq[1]);
		if(NULL == Db2.Find(sql)){
			sprintf(sql, "UPDATE oj_user SET accept=accept+1 WHERE id=%s LIMIT 1", rq[2]);
			Db2.Execute(sql);
		}
		sprintf(sql, "UPDATE oj_problem SET accept=accept+1 WHERE id=%s LIMIT 1", rq[1]);
		Db2.Execute(sql);
	}
	else if(!SpecFlag){
		sprintf(task.Result, "WA");
	}
	/*else if(SpecFlag)
		task.Score = 0;*/
	Db2.Release();
	task.Time = TimeStamp();
	Done(rq[0], &task);
}
