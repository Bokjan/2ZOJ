package JavaJudger.Judger.Checker;

import JavaJudger.Model.Problem;

abstract public class Checker {
	int subMarks;
	Problem problem;

	protected static final int ERROR = -1;

	public void setProblem(Problem problem) {
		this.problem = problem;
		this.subMarks = 100 / this.problem.getSubtaskCount();
		if (this.subMarks * this.problem.getSubtaskCount() < 100) {
			++this.subMarks;
		}
	}

	abstract public int getScore(int subtaskId);

	public int getSubMarks() {
		return this.subMarks;
	}
}
