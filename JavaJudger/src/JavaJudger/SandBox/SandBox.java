package JavaJudger.SandBox;

import JavaJudger.Config;
import JavaJudger.Model.Problem;
import JavaJudger.Model.Task;

import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;

import com.google.gson.Gson;

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
//		Jni.get().updateIntegerConfs(conf);
//		Jni.get().updateStringConfs(conf);
	}

	public void setInput(String input) {
		conf.setInputFile(input);
//		Jni.get().updateInputFile(input);
	}

	private String buildCmd() {
		ArrayList<String> l = new ArrayList<>();
		l.add(Config.judgerLibrary());
		l.add("--max_cpu_time=" + conf.getMaxCpuTime());
		l.add("--max_real_time=" + conf.getMaxRealTime());
		l.add("--max_memory=" + conf.getMaxMemory());
		l.add("--max_stack=" + conf.getMaxStack());
		l.add("--exe_path=" + conf.getExePath());
		l.add("--input_path=" + conf.getInputFile());
		l.add("--output_path=" + conf.getOutputFile());
		l.add("--error_path=" + conf.getErrorFile());
		l.add("--seccomp_rule_name=c_cpp");
		l.add("--uid=" + Config.sandboxUid());
		l.add("--gid=" + Config.sandboxGid());
		StringBuffer sb = new StringBuffer();
		for (String i : l) {
			sb.append(i);
			sb.append(' ');
		}
		return sb.toString();
	}

	public Result run() {
		final int bufferSize = 1024;
		String cmd = buildCmd();
		int jsonLen = 0;
		Process process;
		char[] stdout = new char[bufferSize];
		try {
			process = Runtime.getRuntime().exec(cmd);
			InputStreamReader isr = new InputStreamReader(process.getInputStream());
			jsonLen = isr.read(stdout, 0, bufferSize);
			isr.close();
		} catch (IOException ex) {
			System.out.println(ex.getMessage());
			System.exit(-1);
		}
		String jsonString = String.valueOf(stdout, 0, jsonLen);
		Result ret = new Gson().fromJson(jsonString, Result.class);
		ret.setError(ret.getErrorNum());
		ret.setState(ret.getResultNum());
		return ret;
	}
}
