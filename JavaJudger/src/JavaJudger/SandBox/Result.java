package JavaJudger.SandBox;

import JavaJudger.Model.Task;
import com.google.gson.annotations.SerializedName;

public class Result {
	@SerializedName("cpu_time")
	private int cpuTime;
	@SerializedName("real_time")
	private int realTime;
	@SerializedName("memory")
	private long memory;
	@SerializedName("signal")
	private int signal;
	@SerializedName("exit_code")
	private int exitCode;
	@SerializedName("error")
	private int errorNum;
	@SerializedName("result")
	private int resultNum;
	private State stateEnum;
	private Error errorEnum;

	public enum State {
		SUCCESS,
		CPU_TIME_LIMIT_EXCEEDED,
		REAL_TIME_LIMIT_EXCEEDED,
		MEMORY_LIMIT_EXCEEDED,
		RUNTIME_ERROR,
		SYSTEM_ERROR
	}

	public static Task.Result stateToResult(State state) {
		switch (state) {
			case SUCCESS:
				return Task.Result.ACCEPTED;
			case CPU_TIME_LIMIT_EXCEEDED:
			case REAL_TIME_LIMIT_EXCEEDED:
				return Task.Result.TIME_LIMIT_EXCEEDED;
			case MEMORY_LIMIT_EXCEEDED:
				return Task.Result.MEMORY_LIMIT_EXCEEDED;
			case RUNTIME_ERROR:
			case SYSTEM_ERROR:
				return Task.Result.RUNTIME_ERROR;
		}
		return Task.Result.JUDGER_ERROR;
	}

	enum Error {
		SUCCESS,
		INVALID_CONFIG,
		FORK_FAILED,
		PTHREAD_FAILED,
		WAIT_FAILED,
		ROOT_REQUIRED,
		LOAD_SECCOMP_FAILED,
		SETRLIMIT_FAILED,
		DUP2_FAILED,
		SETUID_FAILED,
		EXECVE_FAILED
	}

	public int getCpuTime() {
		return cpuTime;
	}

	public void setCpuTime(int cpuTime) {
		this.cpuTime = cpuTime;
	}

	public int getRealTime() {
		return realTime;
	}

	public void setRealTime(int realTime) {
		this.realTime = realTime;
	}

	public int getSignal() {
		return signal;
	}

	public void setSignal(int signal) {
		this.signal = signal;
	}

	public int getExitCode() {
		return exitCode;
	}

	public void setExitCode(int exitCode) {
		this.exitCode = exitCode;
	}

	public long getMemory() {
		return memory;
	}

	public void setMemory(long memory) {
		this.memory = memory;
	}

	public State getState() {
		return stateEnum;
	}

	public void setState(int state) {
		switch (state) {
			case 0:
				this.stateEnum = State.SUCCESS;
				break;
			case 1:
				this.stateEnum = State.CPU_TIME_LIMIT_EXCEEDED;
				break;
			case 2:
				this.stateEnum = State.REAL_TIME_LIMIT_EXCEEDED;
				break;
			case 3:
				this.stateEnum = State.MEMORY_LIMIT_EXCEEDED;
				break;
			case 4:
				this.stateEnum = State.RUNTIME_ERROR;
				break;
			case 5:
				this.stateEnum = State.SYSTEM_ERROR;
				break;
		}
	}

	public Error getError() {
		return errorEnum;
	}

	public void setError(int error) {
		switch (error) {
			case 0:
				this.errorEnum = Error.SUCCESS;
				break;
			case -1:
				this.errorEnum = Error.INVALID_CONFIG;
				break;
			case -2:
				this.errorEnum = Error.FORK_FAILED;
				break;
			case -3:
				this.errorEnum = Error.PTHREAD_FAILED;
				break;
			case -4:
				this.errorEnum = Error.WAIT_FAILED;
				break;
			case -5:
				this.errorEnum = Error.ROOT_REQUIRED;
				break;
			case -6:
				this.errorEnum = Error.LOAD_SECCOMP_FAILED;
				break;
			case -7:
				this.errorEnum = Error.SETRLIMIT_FAILED;
				break;
			case -8:
				this.errorEnum = Error.DUP2_FAILED;
				break;
			case -9:
				this.errorEnum = Error.SETUID_FAILED;
				break;
			case -10:
				this.errorEnum = Error.EXECVE_FAILED;
				break;
		}
	}

	public int getErrorNum() {
		return errorNum;
	}

	public int getResultNum() {
		return resultNum;
	}
}
