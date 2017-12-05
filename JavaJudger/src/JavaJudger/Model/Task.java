package JavaJudger.Model;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class Task {
	private Connection conn;
	private Map<Result, String> resultStringMap;
	private Map<Language, String> languageStringMap;
	private int taskId;
	private int userId;
	private int problemId;
	private Problem problem;
	private Language language;
	private Result result;
	private int score = 0;
	private boolean isAccepted = false;
	private boolean wasAccepted = false;
	private int timeUsage = 0;
	private long memoryUsage = 0;
	private String compilerMessage = "";
	public ArrayList<Subtask> subtasks = new ArrayList<>();

	public enum Language {
		C,
		Cpp,
		Pascal
	}

	public enum Result {
		WAITING,
		RUNNING,
		ACCEPTED,
		WRONG_ANSWER,
		TIME_LIMIT_EXCEEDED,
		MEMORY_LIMIT_EXCEEDED,
		RUNTIME_ERROR,
		COMPILE_ERROR,
		JUDGER_ERROR
	}

	Task(Connection conn) {
		this.conn = conn;
		this.initializeMaps();
	}

	private void initializeMaps() {
		resultStringMap = new HashMap<>();
		resultStringMap.put(Result.ACCEPTED, "AC");
		resultStringMap.put(Result.RUNNING, "r");
		resultStringMap.put(Result.WAITING, "u");
		resultStringMap.put(Result.WRONG_ANSWER, "WA");
		resultStringMap.put(Result.TIME_LIMIT_EXCEEDED, "TLE");
		resultStringMap.put(Result.MEMORY_LIMIT_EXCEEDED, "MLE");
		resultStringMap.put(Result.RUNTIME_ERROR, "RE");
		resultStringMap.put(Result.COMPILE_ERROR, "CE");
		resultStringMap.put(Result.JUDGER_ERROR, "JE");
		languageStringMap = new HashMap<>();
		languageStringMap.put(Language.C, "c");
		languageStringMap.put(Language.Cpp, "cpp");
		languageStringMap.put(Language.Pascal, "pas");
	}

	public String getResultString(Result result) {
		return resultStringMap.get(result);
	}

	public String getLanguageString(Language language) {
		return languageStringMap.get(language);
	}

	public int getTaskId() {
		return taskId;
	}

	public void setTaskId(int taskId) {
		this.taskId = taskId;
	}

	public int getUserId() {
		return userId;
	}

	public void setUserId(int userId) {
		this.userId = userId;
	}

	public int getProblemId() {
		return problemId;
	}

	public void setProblemId(int problemId) {
		this.problemId = problemId;
	}

	public Language getLanguage() {
		return language;
	}

	public void setLanguage(String str) {
		switch (str) {
			case "c":
				this.language = Language.C;
				break;
			case "cpp":
				this.language = Language.Cpp;
				break;
			case "pas":
				this.language = Language.Pascal;
				break;
			default:
				;
		}
	}

	public Result getResult() {
		return result;
	}

	public void setResult(String str) {
		switch (str) {
			case "u":
				this.result = Result.WAITING;
				break;
			case "r":
				this.result = Result.RUNNING;
				break;
			case "AC":
				this.result = Result.ACCEPTED;
				break;
			case "RE":
				this.result = Result.RUNTIME_ERROR;
				break;
			case "TLE":
				this.result = Result.TIME_LIMIT_EXCEEDED;
				break;
			case "MLE":
				this.result = Result.MEMORY_LIMIT_EXCEEDED;
				break;
			case "JE":
				this.result = Result.JUDGER_ERROR;
				break;
			case "CE":
				this.result = Result.COMPILE_ERROR;
				break;
			case "WA":
				this.result = Result.WRONG_ANSWER;
				break;
		}
	}

	public void setResult(Result result) {
		this.result = result;
	}

	public int getScore() {
		return score;
	}

	public void setScore(int score) {
		this.score = score;
	}

	public boolean isAccepted() {
		return isAccepted;
	}

	public void setAccepted(boolean accepted) {
		isAccepted = accepted;
	}

	public int getTimeUsage() {
		return timeUsage;
	}

	public void setTimeUsage(int timeUsage) {
		this.timeUsage = timeUsage;
	}

	public long getMemoryUsage() {
		return memoryUsage;
	}

	public void setMemoryUsage(long memoryUsage) {
		this.memoryUsage = memoryUsage;
	}

	public String getCompilerMessage() {
		return compilerMessage;
	}

	public void setCompilerMessage(String compilerMessage) {
		this.compilerMessage = compilerMessage;
	}

	public Problem getProblem() {
		return problem;
	}

	void setProblem() {
		problem = new Problem(this.conn, this.problemId);
		if (!problem.fetchInformation()) {
			problem = null;
		}
	}

	public boolean wasAccepted() {
		return wasAccepted;
	}

	public void setWasAccepted(boolean wasAccepted) {
		this.wasAccepted = wasAccepted;
	}

	@Override
	public String toString() {
		return "Task{" +
				"taskId=" + taskId +
				", userId=" + userId +
				", problemId=" + problemId +
				", language=" + language +
				", result=" + result +
				", score=" + score +
				", isAccepted=" + isAccepted +
				", timeUsage=" + timeUsage +
				", memoryUsage=" + memoryUsage +
				'}';
	}

	public void setRunning() {
		final String query = "UPDATE oj_submit SET result='r' WHERE id=? LIMIT 1;";
		try {
			PreparedStatement ps = this.conn.prepareStatement(query);
			ps.setInt(1, this.taskId);
			ps.execute();
		} catch (SQLException ex) {
			Db.exHandler(ex);
		}
	}

	// For didn't-run submissions
	public void syncResult(Result result) {
		this.updateUserAndProblemInfo();
		// construct subtasks
		subtasks.clear();
		if (result != Result.JUDGER_ERROR) {
			for (int i = 0; i < this.problem.getSubtaskCount(); ++i) {
				Subtask s = new Subtask();
				s.setTimeUsage(0);
				s.setMemoryUsage(0);
				s.setExitCode(0);
				s.setResult(result);
				s.setScore(0);
				subtasks.add(s);
			}
		}
		this.result = result;
		syncResult();
	}

	public void syncResult() {
		this.updateUserAndProblemInfo();
		final String query = "UPDATE oj_submit SET result=?, timeused=?, memused=?, resdata=?, score=?, accepted=?, compmsg=? WHERE id=? LIMIT 1;";
		try {
			PreparedStatement ps = conn.prepareStatement(query);
			ps.setString(1, getResultString(this.result));
			ps.setInt(2, this.timeUsage);
			ps.setLong(3, this.memoryUsage);
			ps.setString(4, this.subtasksToString());
			ps.setInt(5, this.score);
			ps.setBoolean(6, this.isAccepted);
			ps.setString(7, this.compilerMessage);
			ps.setInt(8, this.taskId);
			ps.execute();
		} catch (SQLException ex) {
			Db.exHandler(ex);
		}
	}

	private String subtasksToString() {
		StringBuffer s = new StringBuffer();
		for (Subtask st : subtasks) {
			s.append(String.format("%d %d %d %s %d\n",
					st.getTimeUsage(), st.getMemoryUsage() / 1024, // in KiB
					st.getExitCode(), this.getResultString(st.getResult()),
					st.getScore()));
		}
		return s.toString();
	}

	public void updateUserAndProblemInfo() {
		String query1 = "", query2;
		boolean other = this.userHasOtherAc();
		if (this.isAccepted && !this.wasAccepted) {
			if (!other) {
				query1 = "UPDATE oj_user SET accept=accept+1, points=points+? WHERE id=? LIMIT 1;";
			}
			query2 = "UPDATE oj_problem SET accept=accept+1 WHERE id=? LIMIT 1;";
		} else if (!this.isAccepted && this.wasAccepted) {
			if (!other) {
				query1 = "UPDATE oj_user SET accept=accept-1, points=points-? WHERE id=? LIMIT 1;";
			}
			query2 = "UPDATE oj_problem SET accept=accept-1 WHERE id=? LIMIT 1;";
		} else {
			return;
		}
		try {
			PreparedStatement ps;
			if (!query1.isEmpty()) {
				ps = conn.prepareStatement(query1);
				ps.setInt(1, this.problem.getDifficulty());
				ps.setInt(2, this.userId);
				ps.execute();
			}
			if (!query2.isEmpty()) {
				ps = conn.prepareStatement(query2);
				ps.setInt(1, this.problemId);
				ps.execute();
			}
		} catch (SQLException ex) {
			Db.exHandler(ex);
		}
	}

	private boolean userHasOtherAc() {
		final String query = "SELECT count(*) FROM oj_submit WHERE pid=? AND uid=? AND result='AC';"; // 必须是 result='AC'
		try {
			PreparedStatement ps = conn.prepareStatement(query);
			ps.setInt(1, this.problemId);
			ps.setInt(2, this.userId);
			ResultSet rs = ps.executeQuery();
			rs.next();
			return rs.getInt(1) > 0;
		} catch (SQLException ex) {
			Db.exHandler(ex);
		}
		return false;
	}
}
