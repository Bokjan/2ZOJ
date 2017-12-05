package JavaJudger.Model;

import java.sql.*;

import JavaJudger.Config;

public class Db {
	private String jdbcUrl;
	private Connection conn = null;
	private Statement stmt;

	public static void exHandler(SQLException ex) {
		System.err.println(ex.getMessage());
		System.exit(-1);
	}

	public Db() {
		jdbcUrl = String.format("jdbc:mysql://%s:%d/%s?user=%s&password=%s&useUnicode=true&characterEncoding=UTF8&autoReconnect=true",
				Config.mysqlHost(), Config.mysqlPort(), Config.mysqlDatabase(), Config.mysqlUser(), Config.mysqlPass());
		try {
			new com.mysql.jdbc.Driver();
			conn = DriverManager.getConnection(jdbcUrl);
			stmt = conn.createStatement();
		} catch (SQLException ex) {
			exHandler(ex);
		}
	}

	public Task getTask() {
		ResultSet rs = null;
		try {
			final String uquery = "SELECT id, uid, pid, lang, result, score, accepted FROM oj_submit WHERE result = 'u' ORDER BY id ASC LIMIT 1;";
			rs = stmt.executeQuery(uquery);
			if (!rs.next()) {
				final String vquery = "SELECT id, uid, pid, lang, result, score, accepted FROM oj_submit WHERE result = 'v' ORDER BY id ASC LIMIT 1;";
				rs = stmt.executeQuery(vquery);
				if (!rs.next()) {
					return null;
				}
			}
		} catch (SQLException ex) {
			exHandler(ex);
		}
		Task task = new Task(conn);
		try {
			task.setTaskId(rs.getInt(1));
			task.setUserId(rs.getInt(2));
			task.setProblemId(rs.getInt(3));
			task.setLanguage(rs.getString(4));
			task.setResult(rs.getString(5));
//			task.setScore(rs.getInt(6));
			task.setAccepted(rs.getBoolean(7));
			task.setWasAccepted(task.isAccepted());
		} catch (SQLException ex) {
			exHandler(ex);
		}
		task.setProblem();
		return task;
	}
}
