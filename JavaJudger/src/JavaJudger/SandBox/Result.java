package JavaJudger.SandBox;

import JavaJudger.Model.Task;

public class Result {
	private int cpuTime;
	private int realTime;
	private long memory;
	private int signal;
	private int exitCode;
	private State state;
	private Error error;

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
		return state;
	}

	public void setState(int state) {
		switch (state) {
			case 0:
				this.state = State.SUCCESS;
				break;
			case 1:
				this.state = State.CPU_TIME_LIMIT_EXCEEDED;
				break;
			case 2:
				this.state = State.REAL_TIME_LIMIT_EXCEEDED;
				break;
			case 3:
				this.state = State.MEMORY_LIMIT_EXCEEDED;
				break;
			case 4:
				this.state = State.RUNTIME_ERROR;
				break;
			case 5:
				this.state = State.SYSTEM_ERROR;
				break;
		}
	}

	public Error getError() {
		return error;
	}

	public void setError(int error) {
		switch (error) {
			case 0:
				this.error = Error.SUCCESS;
				break;
			case -1:
				this.error = Error.INVALID_CONFIG;
				break;
			case -2:
				this.error = Error.FORK_FAILED;
				break;
			case -3:
				this.error = Error.PTHREAD_FAILED;
				break;
			case -4:
				this.error = Error.WAIT_FAILED;
				break;
			case -5:
				this.error = Error.ROOT_REQUIRED;
				break;
			case -6:
				this.error = Error.LOAD_SECCOMP_FAILED;
				break;
			case -7:
				this.error = Error.SETRLIMIT_FAILED;
				break;
			case -8:
				this.error = Error.DUP2_FAILED;
				break;
			case -9:
				this.error = Error.SETUID_FAILED;
				break;
			case -10:
				this.error = Error.EXECVE_FAILED;
				break;
		}
	}
}
