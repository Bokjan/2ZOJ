package JavaJudger.Judger;

import JavaJudger.Config;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class ConfigReader {
	private int problemId;
	private String inPrefix;
	private String outPrefix;

	public ConfigReader(int problemId) throws IOException {
		this.problemId = problemId;
		String confFile = String.format("%s%d/conf.ini", Config.problemPath(), this.problemId);
		FileReader fr = new FileReader(confFile);
		BufferedReader br = new BufferedReader(fr);
		inPrefix = br.readLine();
		outPrefix = br.readLine();
		br.close();
		fr.close();
	}

	public String getInPrefix() {
		return inPrefix;
	}

	public String getOutPrefix() {
		return outPrefix;
	}
}
