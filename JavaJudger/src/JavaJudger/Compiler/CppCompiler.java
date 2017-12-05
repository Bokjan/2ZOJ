package JavaJudger.Compiler;

import JavaJudger.Compiler.SourceValidator.CppValidator;
import JavaJudger.Compiler.SourceValidator.SourceValidator;
import JavaJudger.Config;

public class CppCompiler extends Compiler {
	public CppCompiler(String sourceFile, String outputPath) {
		super(sourceFile, outputPath);
	}

	@Override
	void setArguments() {
		//g++ -o t test.cpp -std=c++17 -O2 -DOJ -fmax-errors=3
		cmdArgs.add(Config.cxxAbs());
		cmdArgs.add(symbolizedSource); // source file
		cmdArgs.add("-std=c++11");
		cmdArgs.add("-O2");
		cmdArgs.add("-DOJ");
		cmdArgs.add("-w");
		cmdArgs.add("-fmax-errors=3");
		cmdArgs.add("-o");
		cmdArgs.add(outputPath + Config.executableFilename());
		cmdArgs.add("-lm");
	}

	@Override
	void setSymbolizedSource() {
		this.symbolizedSource = Config.tempPath() + "src.cpp";
	}

	@Override
	boolean validateSource() {
		SourceValidator sv = new CppValidator();
		return sv.validate(this.symbolizedSource);
	}
}
