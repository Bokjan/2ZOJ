#include "h/db.h"
MYSQL_ROW  Util::Db::Find(char *query){
	mysql_query(conn, query);
	res=mysql_store_result(conn);
	return mysql_fetch_row(res);
}
void Util::Db::Release(void){
	mysql_free_result(res);
}
int Util::Db::SelectDb(char *db){
	return mysql_select_db(conn, db);
}
void Util::Db::Execute(char *query){
	mysql_query(conn, query);
}