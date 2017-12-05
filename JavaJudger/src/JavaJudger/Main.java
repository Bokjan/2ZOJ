package JavaJudger;

import JavaJudger.Judger.Judger;
import JavaJudger.Model.Db;
import JavaJudger.Model.Task;
import JavaJudger.SandBox.Jni;

import java.text.SimpleDateFormat;
import java.util.Date;

public class Main {
	public static void main(String[] args) {
		if (args.length < 1) {
			System.err.println("Usage: sudo java -jar JavaJudger.jar <config.json>");
			System.exit(-1);
		}
		Config.initialize(args[0]);
		run();
	}

	private static void run() {
		Db db = new Db();
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss ");
		for (; ; ) {
			Task task;
			while ((task = db.getTask()) != null) {
				System.out.print(sdf.format(new Date()));
				System.out.println("Running Task #" + task.getTaskId());
				new Judger(task).run();
				System.out.print(sdf.format(new Date()));
				System.out.println(task);
			}
			Main.waitForAWhile();
		}
	}

	private static void waitForAWhile() {
		try {
			Thread.sleep(Config.pollWaitMillis());
		} catch (InterruptedException ex) {
			ex.printStackTrace(System.err);
		}
	}
}
