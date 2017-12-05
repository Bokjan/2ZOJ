package JavaJudger.Model;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class Problem {
	private Connection conn;
	private int problemId;
	private int difficulty;
	private int subtaskCount;
	private int timeLimit;
	private int memoryLimit; // in MiB
	private boolean isSpecialJudge;

	public Problem(Connection conn, int problemId) {
		this.conn = conn;
		this.problemId = problemId;
	}

	public boolean fetchInformation() {
		final String query = "SELECT difficulty, dataset, tlim, mlim, spj FROM oj_problem WHERE id = ? LIMIT 1;";
		try {
			PreparedStatement ps = conn.prepareStatement(query);
			ps.setInt(1, this.problemId);
			ResultSet rs = ps.executeQuery();
			if (!rs.next()) {
				return false;
			}
			this.difficulty = rs.getInt(1);
			this.subtaskCount = rs.getInt(2);
			this.timeLimit = rs.getInt(3);
			this.memoryLimit = rs.getInt(4) / 1000;
			this.isSpecialJudge = rs.getBoolean(5);
		} catch (SQLException ex) {
			Db.exHandler(ex);
		}
		return true;
	}

	public int getProblemId() {
		return problemId;
	}

	public int getDifficulty() {
		return difficulty;
	}

	public int getSubtaskCount() {
		return subtaskCount;
	}

	public int getTimeLimit() {
		return timeLimit;
	}

	public int getMemoryLimit() {
		return memoryLimit;
	}

	public boolean isSpecialJudge() {
		return isSpecialJudge;
	}

	public void increaseAcceptance() {
		final String query = "UPDATE oj_problem SET accept=accept+1 WHERE id=? LIMIT 1;";
		try {
			PreparedStatement ps = conn.prepareStatement(query);
			ps.setInt(1, this.problemId);
			ps.execute();
		} catch (SQLException ex) {
			Db.exHandler(ex);
		}
	}
}
