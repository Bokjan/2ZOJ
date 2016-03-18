#include "h/global.h"
#include "h/db.h"
#include <unistd.h>
#include <cstdio>
Util::Db Db;
extern void Judge(MYSQL_ROW rq);
int main(void)
{
	const char sql[] = "SELECT id,pid,uid,lang FROM oj_submit WHERE result='u' LIMIT 1";
	bool isJudged = false;
	for(;;){
		if(!isJudged)
			sleep(PollWaitInS);
		MYSQL_ROW res = Db.Find((char *)sql);
		if(NULL != res){
			Judge(res);
			isJudged = true;
		}
		else
			isJudged = false;
		Db.Release();
	}
	return 0;
}