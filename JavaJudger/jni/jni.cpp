#include "inc.h"
#include "spj.h"
#include "runner.h"
#include <cstdio>
#include <cstring>
#include <cstdlib>
#include <dlfcn.h>
#include "JavaJudger_SandBox_Jni.h"
static result res;
static config conf;
static char b[4][BUFFER];

JNIEXPORT void JNICALL Java_JavaJudger_SandBox_Jni_jniUpdateInputFile
  (JNIEnv *env, jobject obj, jstring s)
{
	const char *chars;
	conf.input_path = b[1];
	chars = env->GetStringUTFChars(s, NULL);
	strcpy(conf.input_path, chars);
	env->ReleaseStringUTFChars(s, chars);
}
JNIEXPORT void JNICALL Java_JavaJudger_SandBox_Jni_jniUpdateOutputFile
  (JNIEnv *env, jobject obj, jstring s)
{
	const char *chars;
	conf.output_path = b[2];
	chars = env->GetStringUTFChars(s, NULL);
	strcpy(conf.output_path, chars);
	env->ReleaseStringUTFChars(s, chars);
}
JNIEXPORT void JNICALL Java_JavaJudger_SandBox_Jni_jniUpdateErrorFile
  (JNIEnv *env, jobject obj, jstring s)
{
	const char *chars;
	conf.error_path = b[3];
	chars = env->GetStringUTFChars(s, NULL);
	strcpy(conf.error_path, chars);
	env->ReleaseStringUTFChars(s, chars);
}
JNIEXPORT void JNICALL Java_JavaJudger_SandBox_Jni_jniUpdateExecuteableFile
  (JNIEnv *env, jobject obj, jstring s)
{
	const char *chars;
	conf.exe_path = b[0];
	chars = env->GetStringUTFChars(s, NULL);
	strcpy(conf.exe_path, chars);
	env->ReleaseStringUTFChars(s, chars);
}
JNIEXPORT void JNICALL Java_JavaJudger_SandBox_Jni_jniUpdateIntegerConfs
  (JNIEnv *env, jobject obj, jintArray arr)
{
	jint *ints = env->GetIntArrayElements(arr, NULL);
	conf.max_cpu_time = ints[0];
	conf.max_real_time = ints[1];
	conf.max_memory = ints[2];
	conf.max_stack = ints[3];
	conf.max_process_number = ints[4];
	conf.max_output_size = ints[5];
	conf.uid = ints[6];
	conf.gid = ints[7];	
}
JNIEXPORT void JNICALL Java_JavaJudger_SandBox_Jni_jniRun
  (JNIEnv *env, jobject obj)
{
	conf.log_path = LOG_PATH;
	conf.seccomp_rule_name = RULE_NAME;
	run(&conf, &res);
}
JNIEXPORT jint JNICALL Java_JavaJudger_SandBox_Jni_jniGetResultInfo
  (JNIEnv *env, jobject obj, jint type)
{
	switch(type)
	{
		case 1:
			return res.cpu_time;
		case 2:
			return res.real_time;
		case 3:
			return static_cast<int>(res.memory);
		case 4:
			return res.signal;
		case 5:
			return res.exit_code;
		case 6:
			return res.error;
		case 7:
			return res.result;
	}
	return res.exit_code;
}

JNIEXPORT jlong JNICALL Java_JavaJudger_SandBox_Jni_jniGetMemory
  (JNIEnv *, jobject)
{
	return res.memory;
}
JNIEXPORT jboolean JNICALL Java_JavaJudger_SandBox_Jni_jniIgnoringCompare
  (JNIEnv *env, jobject obj, jstring js1, jstring js2)
{
	bool ret = true;
	const char *f1 = env->GetStringUTFChars(js1, NULL);
	const char *f2 = env->GetStringUTFChars(js2, NULL);
	FILE *x = fopen(f1, "rb");
	FILE *y = fopen(f2, "rb");
	int a, b;
	while((a = fgetc(x)) != EOF)
	{
		if(a == ' ' || a == '\r' || a == '\n')
			continue;
		while((b = fgetc(y)) != EOF)
			if(b == ' ' || b == '\r' || b == '\n')
				continue;
			else
				break;
		if(a != b)
		{
			ret = false;
			goto RETURN;
		}
	}
	while((b = fgetc(y)) != EOF)
		if(b == ' ' || b == '\r' || b == '\n')
			continue;
		else
		{
			ret = false;
			goto RETURN;
		}
RETURN:
	fclose(x);
	fclose(y);
	env->ReleaseStringUTFChars(js1, f1);
	env->ReleaseStringUTFChars(js2, f2);
	return ret;
}
JNIEXPORT jint JNICALL Java_JavaJudger_SandBox_Jni_jniRunCommand
  (JNIEnv *env, jobject obj, jstring s)
{
	const char *cmd;
	cmd = env->GetStringUTFChars(s, NULL);
	int ret = system(cmd);
	env->ReleaseStringUTFChars(s, cmd);
	return ret;
}
JNIEXPORT jint JNICALL Java_JavaJudger_SandBox_Jni_jniSpecialJudge
  (JNIEnv *env, jobject obj, jstring in, jstring out, jstring ans, jint score, jstring lib)
{
	int ret = OJ_CORRECT_ANSWER;
	const char *i, *o, *a, *l;
	i = env->GetStringUTFChars(in, NULL);
	o = env->GetStringUTFChars(out, NULL);
	a = env->GetStringUTFChars(ans, NULL);
	l = env->GetStringUTFChars(lib, NULL);

	int (*spj_function)(const char *, const char *, const char *, int);
	void *dlpointer = dlopen(l, RTLD_LAZY);
	if(dlpointer == NULL)
	{
		fprintf(stderr, "Special Judger: in JNICALL, cannot load %s\n", l);
		ret = OJ_SPJ_ERROR;
		goto RETURN;
	}
	spj_function = (int(*)(const char *, const char *, const char *, int))dlsym(dlpointer, OJ_SPJ_FUNCTION_NAME);
	if(spj_function == NULL)
	{
		fprintf(stderr, "Special Judger: in JNICALL, cannot load %s symbol from %s\n", OJ_SPJ_FUNCTION_NAME, l);
		ret = OJ_SPJ_ERROR;
		goto RETURN;
	}
	ret = spj_function(i, o, a, score);
	dlclose(dlpointer);

RETURN:
	env->ReleaseStringUTFChars(in, i);
	env->ReleaseStringUTFChars(out, o);
	env->ReleaseStringUTFChars(ans, a);
	env->ReleaseStringUTFChars(lib, l);
	return ret;
}
