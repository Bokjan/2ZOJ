package JavaJudger.Compiler;

import JavaJudger.Compiler.SourceValidator.CppValidator;
import JavaJudger.Compiler.SourceValidator.SourceValidator;
import JavaJudger.Config;

public class CCompiler extends Compiler {
	public CCompiler(String sourceFile, String outputPath) {
		super(sourceFile, outputPath);
	}

	@Override
	void setArguments() {
		cmdArgs.add(Config.ccAbs());
		cmdArgs.add("-std=c11");
		cmdArgs.add("-O2");
		cmdArgs.add("-DOJ");
		cmdArgs.add("-w");
		cmdArgs.add("-fmax-errors=3");
		cmdArgs.add("-o");
		cmdArgs.add(outputPath + Config.executableFilename());
		cmdArgs.add(symbolizedSource); // source file
		cmdArgs.add("-lm");
	}

	@Override
	void setSymbolizedSource() {
		this.symbolizedSource = Config.tempPath() + "src.c";
	}

	@Override
	boolean validateSource() {
		SourceValidator sv = new CppValidator();
		return sv.validate(this.symbolizedSource);
	}
}
