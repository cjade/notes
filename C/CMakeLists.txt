cmake_minimum_required(VERSION 3.13)
project(C)

set(CMAKE_C_STANDARD 11)
link_directories(/usr/local/Cellar/mysql-connector-c/6.1.11/lib/)
add_executable(C main.c lib/utils.c lib/utils.h demo/mysqldb.c demo/mysqldb.h)

target_link_libraries(C mysqlclient)
