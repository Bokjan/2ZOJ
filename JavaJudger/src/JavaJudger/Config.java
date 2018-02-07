package JavaJudger;

import com.google.gson.Gson;
import com.google.gson.annotations.SerializedName;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;

public class Config {
	public static String jniLibrary() {
		return inner.jniLibrary;
	}

	public static String judgerLibrary() { return inner.judgerLibrary; }

	public static int mysqlPort() {
		return inner.mysqlPort;
	}

	public static String mysqlUser() {
		return inner.mysqlUser;
	}

	public static String mysqlPass() {
		return inner.mysqlPass;
	}

	public static String mysqlHost() {
		return inner.mysqlHost;
	}

	public static String mysqlDatabase() {
		return inner.mysqlDatabase;
	}

	public static int pollWaitMillis() {
		return inner.pollWaitMillis;
	}

	public static String tempPath() {
		return inner.tempPath;
	}

	public static String sourcePath() {
		return inner.sourcePath;
	}

	public static String problemPath() {
		return inner.problemPath;
	}

	public static String executableFilename() {
		return inner.executableFilename;
	}

	public static int stackSize() {
		return inner.stackSize;
	}

	public static String ccAbs() {
		return inner.ccAbs;
	}

	public static String cxxAbs() {
		return inner.cxxAbs;
	}

	public static String pasAbs() {
		return inner.pasAbs;
	}

	public static int sandboxUid() {
		return inner.sandboxUid;
	}

	public static int sandboxGid() {
		return inner.sandboxGid;
	}

	private static InnerClass inner;

	public static void initialize(String jsonFile) {
		String json = readFile(jsonFile);
		Gson gson = new Gson();
		inner = gson.fromJson(json, InnerClass.class);
	}

	public static String readFile(String filename) {
		String encoding = "UTF-8";
		File file = new File(filename);
		Long length = file.length();
		byte[] content = new byte[length.intValue()];
		try {
			FileInputStream fis = new FileInputStream(file);
			fis.read(content);
			fis.close();
			return new String(content, encoding);
		} catch (IOException ex) {
			System.err.println("Fatal: Cannot load " + filename);
			System.exit(-1);
		}
		return null;
	}

	class InnerClass {
		@SerializedName("jni_library")
		String jniLibrary;
		@SerializedName("judger_library")
		String judgerLibrary;

		@SerializedName("mysql_port")
		int mysqlPort;
		@SerializedName("mysql_user")
		String mysqlUser;
		@SerializedName("mysql_pass")
		String mysqlPass;
		@SerializedName("mysql_host")
		String mysqlHost;
		@SerializedName("mysql_database")
		String mysqlDatabase;

		@SerializedName("poll_wait_ms")
		int pollWaitMillis;

		@SerializedName("temp_path")
		String tempPath;
		@SerializedName("source_path")
		String sourcePath;
		@SerializedName("problem_path")
		String problemPath;
		@SerializedName("exe_file_name")
		String executableFilename;

		@SerializedName("stack_size")
		int stackSize;

		@SerializedName("c_compiler_absolute")
		String ccAbs;
		@SerializedName("cpp_compiler_absolute")
		String cxxAbs;
		@SerializedName("pascal_compiler_absolute")
		String pasAbs;

		@SerializedName("sandbox_uid")
		int sandboxUid;
		@SerializedName("sandbox_gid")
		int sandboxGid;
	}
}

