
#include <stdio.h>
#include <zconf.h>
#include <unistd.h>
#include <stdlib.h>
#include <errno.h>
#include <pwd.h>
#include <time.h>
#include <sys/time.h>
#include <locale.h>
#include <string.h>
#include <sys/times.h>


char *date(char *format){
    struct tm *tm;
    time_t t;
    t = time(NULL);
    tm = localtime(&t);
    char  buffer[97];
    static char  str[97];
    size_t format_len = strlen(format);
    for (int i = 0; i < format_len; i++) {
        switch (format[i]){
            /*年*/
            case 'Y': sprintf(buffer,"%d",tm->tm_year + 1900);  strcat(str,buffer);break;
            /*月*/
            case 'm': sprintf(buffer,"%02d",tm->tm_mon + 1); strcat(str,buffer);break;
            /*日*/
            case 'd': sprintf(buffer,"%02d",tm->tm_mday); strcat(str,buffer);break;
            /*时*/
            case 'H': sprintf(buffer,"%02d",tm->tm_hour); strcat(str,buffer);break;
            /*分*/
            case 'i': sprintf(buffer,"%02d",tm->tm_min); strcat(str,buffer);break;
            /*秒*/
            case 's': sprintf(buffer,"%02d",tm->tm_sec); strcat(str,buffer);break;
            /*其他字符*/
            default: sprintf(buffer,"%c",format[i]); strcat(str,buffer);
        }
    }
    return str;
}

static void  displayProcessTimes(const char *msg){
    struct tms t;
    clock_t clockTime;
    static long clockTicks = 0;

    printf("%s", msg);

    if(clockTicks == 0){
        clockTicks = sysconf(_SC_CLK_TCK);
        if(clockTicks == -1) exit(EXIT_FAILURE);
    }

    clockTime = clock();

    if(clockTime == -1) exit(EXIT_FAILURE);

    printf(" clock() returns: %ld clocks-per-sec (%.2f secs)\n",

           (long) clockTime, (double) clockTime / CLOCKS_PER_SEC);

    if (times(&t) == -1)
        exit(EXIT_FAILURE);

    printf(" times() yields: user CPU=%.2f; system CPU: %.2f\n",

           (double) t.tms_utime / clockTicks,

           (double) t.tms_stime / clockTicks);

}

int main(int argc, char *argv[])
{
    int i = 0;
    int arr[3] = {0};
    for (; i <= 3 ; i++) {
        arr[i] = 0;
        printf("%p hello world\n",&arr[i]);
    }
    return 0;
}
