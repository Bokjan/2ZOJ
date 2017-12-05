package JavaJudger.SandBox;

import JavaJudger.Config;
import JavaJudger.Model.Problem;
import JavaJudger.Model.Task;

public class SandBox {
	private Task task;
	private Conf conf = new Conf();

	public SandBox(Task task) {
		this.task = task;
	}

	public void setConf() {
		Problem p = task.getProblem();
		conf.setMaxCpuTime(p.getTimeLimit());
		conf.setMaxRealTime(2 * p.getTimeLimit());
		conf.setMaxMemory(p.getMemoryLimit() * 1024 * 1024);
		conf.setMaxStack(Config.stackSize() * 1024 * 1024);
		Jni.get().updateIntegerConfs(conf);
		Jni.get().updateStringConfs(conf);
	}

	public void setInput(String input) {
		conf.setInputFile(input);
		Jni.get().updateInputFile(input);
	}

	public Result run() {
		Jni.get().run();
		Result ret = new Result();
		ret.setCpuTime(Jni.get().getInfo(Jni.Info.CPU_TIME));
		ret.setRealTime(Jni.get().getInfo(Jni.Info.REAL_TIME));
		// Todo: verify
		ret.setMemory(Jni.get().getMemory()/* - 32 * 1024 * 1024*/);
		ret.setSignal(Jni.get().getInfo(Jni.Info.SIGNAL));
		ret.setExitCode(Jni.get().getInfo(Jni.Info.EXIT_CODE));
		ret.setState(Jni.get().getInfo(Jni.Info.STATE));
		ret.setError(Jni.get().getInfo(Jni.Info.ERROR));
		return ret;
	}
}
