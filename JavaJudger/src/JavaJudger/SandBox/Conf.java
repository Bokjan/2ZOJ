package JavaJudger.SandBox;

import JavaJudger.Config;

// http://docs.onlinejudge.me/Judger/English/
public class Conf {
	private int maxCpuTime; // ms
	private int maxRealTime; // ms
	private int maxMemory; // byte, address space
	private int maxStack; // byte, stack size
	private int maxProcessNumber = -1; // process can be invoked, -1 for unlimited
	private int maxOutputSize = -1; // byte, -1 for unlimited
	private String exePath; // file to run
	private String inputFile = ""; // stdin
	private String errorFile; // stderr
	private String outputFile; // stdout
	private int uid;
	private int gid;
	public Conf() {
		exePath = Config.tempPath() + Config.executableFilename();
		errorFile = Config.tempPath() + "stderr.out";
		outputFile = Config.tempPath() + "stdout.out";
		uid = Config.sandboxUid();
		gid = Config.sandboxGid();
	}

	public int getMaxCpuTime() {
		return maxCpuTime;
	}

	public int getMaxRealTime() {
		return maxRealTime;
	}

	public int getMaxMemory() {
		return maxMemory;
	}

	public int getMaxStack() {
		return maxStack;
	}

	public int getMaxProcessNumber() {
		return maxProcessNumber;
	}

	public int getMaxOutputSize() {
		return maxOutputSize;
	}

	public String getExePath() {
		return exePath;
	}

	public String getInputFile() {
		return inputFile;
	}

	public String getOutputFile() {
		return outputFile;
	}

	public String getErrorFile() {
		return errorFile;
	}

	public int getUid() {
		return uid;
	}

	public int getGid() {
		return gid;
	}

	public void setMaxCpuTime(int maxCpuTime) {
		this.maxCpuTime = maxCpuTime;
	}

	public void setMaxRealTime(int maxRealTime) {
		this.maxRealTime = maxRealTime;
	}

	public void setMaxMemory(int maxMemory) {
		this.maxMemory = maxMemory;
	}

	public void setMaxStack(int maxStack) {
		this.maxStack = maxStack;
	}

	public void setInputFile(String inputFile) {
		this.inputFile = inputFile;
	}
}
