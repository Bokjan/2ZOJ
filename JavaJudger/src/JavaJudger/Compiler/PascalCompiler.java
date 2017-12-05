package JavaJudger.Compiler;

import JavaJudger.Compiler.SourceValidator.CppValidator;
import JavaJudger.Compiler.SourceValidator.PascalValidator;
import JavaJudger.Compiler.SourceValidator.SourceValidator;
import JavaJudger.Config;

public class PascalCompiler extends Compiler {
	public PascalCompiler(String sourceFile, String outputPath) {
		super(sourceFile, outputPath);
	}

	@Override
	void setArguments() {
		cmdArgs.add(Config.pasAbs());
		cmdArgs.add("-Mtp");
		cmdArgs.add("-v0");
		cmdArgs.add("-dOJ");
		cmdArgs.add("-Sgic");
		cmdArgs.add("-Tlinux");
		cmdArgs.add("-o" + outputPath + Config.executableFilename());
		cmdArgs.add(symbolizedSource); // source file
	}

	@Override
	void setSymbolizedSource() {
		this.symbolizedSource = Config.tempPath() + "src.pas";
	}

	@Override
	boolean validateSource() {
		SourceValidator sv = new PascalValidator();
		return sv.validate(this.symbolizedSource);
	}
}
