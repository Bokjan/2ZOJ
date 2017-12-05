package JavaJudger.Judger;

import JavaJudger.Compiler.*;
import JavaJudger.Compiler.Compiler;
import JavaJudger.Config;
import JavaJudger.Judger.Checker.Checker;
import JavaJudger.Judger.Checker.SpecialJudgeChecker;
import JavaJudger.Judger.Checker.VisibleOnlyChecker;
import JavaJudger.Model.Subtask;
import JavaJudger.Model.Task;
import JavaJudger.SandBox.Result;
import JavaJudger.SandBox.SandBox;

import java.io.IOException;

public class Judger {
	private Task task;

	public Judger(Task task) {
		this.task = task;
	}

	public void run() {
		task.setAccepted(false);
		// problem doesn't exist
		if (task.getProblem() == null) {
			task.syncResult(Task.Result.JUDGER_ERROR);
			return;
		}
		task.setRunning();
		if (!this.compile()) {
			task.syncResult(Task.Result.COMPILE_ERROR);
			return;
		}
		this.execute();
	}

	private boolean compile() {
		Compiler compiler = null;
		String source = String.format("%s%d", Config.sourcePath(), task.getTaskId()); // without suffix
		String outpath = Config.tempPath();
		switch (task.getLanguage()) {
			case C:
				compiler = new CCompiler(source + ".c", outpath);
				break;
			case Cpp:
				compiler = new CppCompiler(source + ".cpp", outpath);
				break;
			case Pascal:
				compiler = new PascalCompiler(source + ".pas", outpath);
				break;
		}
		boolean isSuccessful = compiler.compile();
		task.setCompilerMessage(compiler.getOutputMessage());
		return isSuccessful;
	}

	private void execute() {
		SandBox sb = new SandBox(task);
		sb.setConf();
		ConfigReader cr;
		try {
			cr = new ConfigReader(task.getProblemId());
		} catch (IOException ex) {
			System.out.println(ex.getMessage());
			task.syncResult(Task.Result.JUDGER_ERROR);
			return;
		}
		this.runAll(sb, cr);
		task.syncResult();
	}

	private Subtask runOnce(SandBox sb) {
		Result r = sb.run();
		Subtask st = new Subtask();
		st.setTimeUsage(r.getRealTime());
		st.setMemoryUsage(r.getMemory());
		st.setExitCode(r.getExitCode());
		st.setResult(JavaJudger.SandBox.Result.stateToResult(r.getState()));
		return st;
	}

	private void runAll(SandBox sb, ConfigReader cr) {
		task.setScore(0);
		task.setResult(Task.Result.ACCEPTED);
		// determine checker type
		Checker checker;
		if (task.getProblem().isSpecialJudge()) {
			checker = new SpecialJudgeChecker();
		} else {
			checker = new VisibleOnlyChecker();
		}
		checker.setProblem(task.getProblem());
		// for each subtask
		for (int i = 0; i < task.getProblem().getSubtaskCount(); ++i) {
			sb.setInput(String.format("%s%d/%s%d.in", Config.problemPath(),
					task.getProblemId(), cr.getInPrefix(), i));
			Subtask st = this.runOnce(sb);
			if (st.getResult() != Task.Result.ACCEPTED) { // don't check the answer
				st.setScore(0);
				task.subtasks.add(st);
				task.setResult(st.getResult()); // set the result
				continue;
			}
			// accumulate mem and time usage
			task.setTimeUsage(task.getTimeUsage() + st.getTimeUsage());
			task.setMemoryUsage(task.getMemoryUsage() + st.getMemoryUsage() / 1024); // in KiB

			// check the answer
			int score = checker.getScore(i);
			st.setScore(score);
			if (score == -1) { // ERROR
				st.setScore(0);
				st.setResult(Task.Result.JUDGER_ERROR);
				task.setResult(Task.Result.JUDGER_ERROR);
			} else if (score == checker.getSubMarks()) { // ACCEPTED
				st.setResult(Task.Result.ACCEPTED);
			} else { // WA
				st.setResult(Task.Result.WRONG_ANSWER);
				task.setResult(Task.Result.WRONG_ANSWER);
			}
			task.setScore(task.getScore() + st.getScore());
			task.subtasks.add(st);
		}
		task.setAccepted(task.getResult() == Task.Result.ACCEPTED);
		if (task.isAccepted()) {
			task.setScore(100);
		}
	}
}
