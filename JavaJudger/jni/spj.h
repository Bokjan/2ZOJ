#ifndef OJ_SPJ_H_
#define OJ_SPJ_H_
#include <stdio.h>
#ifdef __cplusplus
extern "C" {
#endif

#define OJ_CORRECT_ANSWER 1
#define OJ_WRONG_ANSWER 0
#define OJ_SPJ_ERROR -1
#define OJ_SPJ_FUNCTION_NAME "spj"

int compare(FILE *in, FILE *out, FILE *ans, int score);
int spj(const char *in, const char *out, const char *ans, int score);

#ifdef __cplusplus
}
#endif
#endif
