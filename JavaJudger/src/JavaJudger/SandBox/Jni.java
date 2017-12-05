package JavaJudger.SandBox;

import JavaJudger.Config;

public class Jni {
	private static Jni ourInstance = new Jni();

	public static Jni get() {
		return ourInstance;
	}

	static {
		System.load(Config.jniLibrary());
	}

	private Jni() {

	}

	private native void jniUpdateIntegerConfs(int[] array);

	private native void jniUpdateInputFile(String s);

	private native void jniUpdateOutputFile(String s);

	private native void jniUpdateErrorFile(String s);

	private native void jniUpdateExecuteableFile(String s);

	private native void jniRun();

	private native long jniGetMemory();

	private native int jniGetResultInfo(int type);

	public native boolean jniIgnoringCompare(String x, String y);

	public native int jniRunCommand(String cmd);

	public native int jniSpecialJudge(String in, String out, String ans, int score, String library);

	public void updateIntegerConfs(Conf conf) {
		int[] arr = {conf.getMaxCpuTime(), conf.getMaxRealTime(), conf.getMaxMemory(),
				conf.getMaxStack(), conf.getMaxProcessNumber(), conf.getMaxOutputSize(),
				conf.getUid(), conf.getGid()};
		this.jniUpdateIntegerConfs(arr);
	}

	public void updateStringConfs(Conf conf) {
		this.jniUpdateInputFile(conf.getInputFile());
		this.jniUpdateOutputFile(conf.getOutputFile());
		this.jniUpdateErrorFile(conf.getErrorFile());
		this.jniUpdateExecuteableFile(conf.getExePath());

	}

	public void updateInputFile(String s) {
		this.jniUpdateInputFile(s);
	}

	public void run() {
		this.jniRun();
	}

	enum Info {
		CPU_TIME,
		REAL_TIME,
		MEMORY,
		SIGNAL,
		EXIT_CODE,
		ERROR,
		STATE
	}

	public int getInfo(Info info) {
		int type = 5;
		switch (info) {
			case CPU_TIME:
				type = 1;
				break;
			case REAL_TIME:
				type = 2;
				break;
			case MEMORY:
				type = 3;
				break;
			case SIGNAL:
				type = 4;
				break;
			case EXIT_CODE:
				type = 5;
				break;
			case ERROR:
				type = 6;
				break;
			case STATE:
				type = 7;
				break;
		}
		return this.jniGetResultInfo(type);
	}

	public long getMemory() {
		return jniGetMemory();
	}
}