package JavaJudger.Model;

public class Subtask {
	private int timeUsage;
	private long memoryUsage;
	private int exitCode;
	private Task.Result result;
	private int score;

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

	public int getExitCode() {
		return exitCode;
	}

	public void setExitCode(int exitCode) {
		this.exitCode = exitCode;
	}

	public Task.Result getResult() {
		return result;
	}

	public void setResult(Task.Result result) {
		this.result = result;
	}

	public int getScore() {
		return score;
	}

	public void setScore(int score) {
		this.score = score;
	}

	@Override
	public String toString() {
		return "Subtask{" +
				"timeUsage=" + timeUsage +
				", memoryUsage=" + memoryUsage +
				", exitCode=" + exitCode +
				", result=" + result +
				", score=" + score +
				'}';
	}
}
