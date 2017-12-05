package JavaJudger.Judger.Checker;

import JavaJudger.Config;
import JavaJudger.Judger.ConfigReader;
import JavaJudger.SandBox.Jni;

import java.io.IOException;
import java.nio.file.FileSystems;
import java.nio.file.Files;

public class VisibleOnlyChecker extends Checker {
	public int getScore(int subtaskId) {
		ConfigReader cr;
		try {
			cr = new ConfigReader(problem.getProblemId());
		} catch (IOException ex) {
			System.out.println(ex.getMessage());
			return ERROR;
		}
		String outputFile = Config.tempPath() + "stdout.out";
		String answerFile = String.format("%s%d/%s%d",
				Config.problemPath(), problem.getProblemId(), cr.getOutPrefix(), subtaskId);
		if (!Files.exists(FileSystems.getDefault().getPath(outputFile))) {
			return ERROR;
		}
		if (!Files.exists(FileSystems.getDefault().getPath(answerFile + ".ans"))) {
			answerFile += ".out";
			if (!Files.exists(FileSystems.getDefault().getPath(answerFile))) {
				return ERROR;
			}
		} else {
			answerFile += ".ans";
		}
		return Jni.get().jniIgnoringCompare(outputFile, answerFile) ? this.subMarks : 0;
	}
}
