package JavaJudger.Compiler;

import JavaJudger.Config;
import JavaJudger.SandBox.Conf;
import JavaJudger.SandBox.Jni;

import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;
import java.nio.file.FileSystems;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.ArrayList;

public abstract class Compiler {
	protected String sourceFile;
	protected String outputPath;
	protected String symbolizedSource;
	protected String outputMessage = "";
	protected ArrayList<String> cmdArgs = new ArrayList<>();
	protected final static int bufferSize = 16384;
	protected static char[] stdoutBuffer;
	protected static char[] stderrBuffer;

	public Compiler(String sourceFile, String outputPath) {
		this.sourceFile = sourceFile;
		this.outputPath = outputPath;
	}

	public String getOutputMessage() {
		return outputMessage;
	}

	abstract boolean validateSource();

	abstract void setArguments();

	protected boolean isSuccessful() {
		return new File(outputPath + Config.executableFilename()).exists();
	}

	abstract void setSymbolizedSource();

	protected boolean createSymbolizedSource() {
		this.setSymbolizedSource();
		try {
			Path ssPath = FileSystems.getDefault().getPath(this.symbolizedSource);
			Files.deleteIfExists(ssPath);
			Files.deleteIfExists(FileSystems.getDefault().getPath(outputPath + Config.executableFilename()));
			Files.createSymbolicLink(ssPath, FileSystems.getDefault().getPath(this.sourceFile));
		} catch (IOException ex) {
			System.out.println(ex.getMessage());
			return false;
		}
		return true;
	}

	public boolean compile() {
		this.createSymbolizedSource();
		if (!this.validateSource()) {
			this.outputMessage = ": permission denied\nterminate with exit code -128";
			return false;
		}
		this.setArguments();
		Process process;
		stderrBuffer = new char[bufferSize];
		stdoutBuffer = new char[bufferSize];
		try {
			String[] args = new String[cmdArgs.size()];
			process = Runtime.getRuntime().exec(cmdArgs.toArray(args));
			InputStreamReader isr = new InputStreamReader(process.getErrorStream());
			isr.read(stderrBuffer, 0, bufferSize - 1);
			isr.close();
			isr = new InputStreamReader(process.getInputStream());
			isr.read(stdoutBuffer, 0, bufferSize - 1);
			isr.close();
		} catch (IOException ex) {
			System.out.println(ex.getMessage());
			return false;
		}
		this.outputMessage = String.valueOf(stderrBuffer) + String.valueOf(stdoutBuffer);
		if (this.outputMessage.charAt(0) == 0) {
			this.outputMessage = "";
		}
		return this.isSuccessful();
	}
}
