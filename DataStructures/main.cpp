#include <iostream>

using namespace std;

class Node{
public:
    int data;
    Node *next;
    Node(int _data = 0, Node *p = NULL){
        data = _data;
        next = p;
    }
};


class List{
private:
    Node *head; //头节点
    Node *tail; //尾节点
    int length; //链表长度
public:
    List(){
        head = tail = NULL;
        length = 0;
    }
    ~List(){
        delete head;
        delete tail;
    };
    void print();

    /**
     * 插入数据
     * @param da
     */
    void Insert(int da = 0);

    /**
     * 获取链表长度
     * @return
     */
    int getLength();

    /**
     * 删除节点
     * @param da
     */
    void Delete(int da = 0);

    /**
     * 通过索引删除节点
     * @param index
     */
    void DeleteByIndex(int index);

    /**
     * 搜索节点
     * @param da
     */
    void Search(int da = 0);

    /**
     * 通过索引获取节点
     * @param index
     * @return
     */
    int getValueAtIndex(int index);

    /**
     * 通过索引修改节点
     * @param index
     * @param da
     * @return
     */
    int setValueAtIndex(int index, int da);
};

int List::getValueAtIndex(int index) {
    Node *p = head;
    if(length == 0){
        cout << "The List is Empty!" << endl;
    } else{
        int posi = 0;
        while (p != NULL && posi != index){
            posi++;
            p = p->next;
        }
    }

    return p->data;
}

void List::Insert(int da) {
    if(head == NULL){
        head = tail  = new Node(da);
        head->next = NULL;
        tail->next = NULL;
    } else{
        Node *p = new Node(da);
        tail->next = p;
        tail = p;
        tail->next = NULL;
    }
    length++;
}

int List::getLength() {
    return length;
}

int List::setValueAtIndex(int index, int da) {
    Node *p = head;
    if(length < index){
        cout << "The List is Empty!" << endl;
    } else{
        int i = 0;
        while (p != NULL && i != index){
            p = p->next;
            i++;
        }
        p->data = da;
    }
    return 1;
}

void List::Delete(int da) {
    Node *p = head, *q = NULL;
    if(length == 0){
        cout << "Sorry, The List is Empty!" << endl;
    }

    while (p != NULL && p->data != da){
        q = p;
        p = p->next;

    }
    q->next = p->next;
    length--;
    cout << "The Deletion Operation had been finished!" << endl;
}

void List::DeleteByIndex(int index) {
    Node *p = head, *q = tail, *t = NULL;

    if(length < index){
        cout << "Sorry, Index out of length！" << endl;
    } else{
        if(index == 1){
            head = head->next;
            p = p->next;
        } else {
            int i = 0;
            while (p != NULL && i != index){
                i++;
                if (index == i){
                    q = p;
                    q->next = NULL;
                    break;
                }
                t = p;
                p = p->next;

            }
            t->next = p->next;

        }

    }
    length--;
}



void List::print() {
    Node *p = head;
    while (p != NULL){
        cout << p->data << " \a";
        p = p->next;
    }
    cout << endl;
}




int main() {
//    List l1;
//    l1.Insert(1);
//    l1.Insert(2);
//    l1.Insert(3);
//    l1.Insert(3);
//    l1.Insert(4);
//    l1.Delete(3);
//    l1.Insert(5);
//    l1.Insert(5);
//    l1.setValueAtIndex(6,6);
//    l1.DeleteByIndex(6);
//    l1.Insert(7);
//    l1.print();

    unsigned i = 10;
   i = 3.14;

    cout << "Hello, World! \n"  << i << endl;
    return 0;
}