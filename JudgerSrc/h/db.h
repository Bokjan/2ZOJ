#include "mysql.h"
#include <string>
using std::string;
namespace Util{
	class Db{
	private:
		MYSQL *conn;
		MYSQL_RES *res;
		MYSQL_ROW row;
	public:
		Db(){
			const char host[] = "";
			const char user[] = "";
			const char pwd[] = "";
			const char dbname[] = "oj";
			unsigned int port = 3306;
			conn = mysql_init(0);
			mysql_real_connect(conn, host, user, pwd, dbname, 0, NULL, 0);
		}
		~Db(){
    		mysql_close(conn);
    		mysql_library_end();
    		mysql_server_end();
		}
		MYSQL_ROW Find(char *query);
		void Release(void);
		int SelectDb(char *db);
		void Execute(char *query);
	};
}
