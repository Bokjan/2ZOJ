#include "spj.h"
int spj(const char *i, const char *o, const char *a, int score)
{
	FILE *in, *out, *ans;
	int ret = OJ_WRONG_ANSWER;
	// 如果有不需要使用的文件（或文件不存在），应删除对应代码
	///////////// 文件打开
	in  = fopen(i, "r");
	if(in == NULL)
		return OJ_SPJ_ERROR;
	out = fopen(o, "r");
	if(out == NULL)
	{
		fclose(in);
		return OJ_SPJ_ERROR;
	}
	ans = fopen(a, "r");
	if(ans == NULL)
	{
		fclose(in);
		fclose(out);
		return OJ_SPJ_ERROR;
	}
	///////////// 文件打开结束
	// 评测
	ret = compare(in, out, ans, score);
	///////////// 文件关闭
	fclose(in);
	fclose(out);
	fclose(ans);
	///////////// 文件结束
	return ret;
}
