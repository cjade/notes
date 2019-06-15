// 线性表顺序存储
// Created by haibao on 2019-04-08.
//

#include "stdio.h"

#define OK 1
#define ERROR 0
#define TRUE 1
#define FALSE 0

#define MAXSIZE 20

typedef int Status;
typedef int ElemType;

Status visit(ElemType c)
{
    printf("%d", c);
    return OK;
}

typedef struct
{
    ElemType data[MAXSIZE] //数组，存储数据元素
    int length;            //线性表当前长度
}SqList;

/**
 * 初始化
 * @param L
 * @return
 */
Status InitList(SqList *L){
    L->length = 0;
    return OK;
}

/**
 * 若线性表为空，返回true，否则返回false
 * @param L
 * @return
 */
Status ListEmpty(SqList L){
    if(L.length == 0)
        return TRUE;
    else
        return FALSE;
}

/**
 * 将线性表清空
 * @param L
 * @return
 */
Status ClearList(SqList *L){
    L->length = 0;
    return OK;
}

/**
 * 将线性表L中的第i个元素返回给e
 * @param L
 * @param i
 * @param e
 * @return
 */
Status GetElem(SqList L, int i, ElemType *e){
    if(L.length == 0 || i < 1 || 1 >L.length)
        return ERROR;
    *e = L.data[i-1];
    return OK;
}

/**
 * 返回L中第1个与e满足关系的数据元素的位序。
 * @param L
 * @param e
 * @return
 */
Status LocateElem(SqList L, ElemType e){
    int i;
    if(L.length == 0)
        return ERROR;


    for (i = 0; i < L.length; ++i) {
        if(L.data[i] == e)
            break;
    }

    if(i >= L.length)
        return ERROR;

    return i+1;

}

int main()
{
    SqList L;

    InitList(&L);
    printf("dsd");
}