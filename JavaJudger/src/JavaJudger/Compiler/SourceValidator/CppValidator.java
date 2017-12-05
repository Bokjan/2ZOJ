package JavaJudger.Compiler.SourceValidator;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class CppValidator implements SourceValidator {
	@Override
	public boolean validate(String file) {
		boolean isValid = true;
		try {
			FileReader fr = new FileReader(file);
			BufferedReader br = new BufferedReader(fr);
			String line;
			while ((line = br.readLine()) != null) {
				if (!this.checkLine(line)) {
					isValid = false;
					break;
				}
			}
			br.close();
			fr.close();
		} catch (IOException ex) {
			return false;
		}
		return isValid;
	}

	private boolean checkLine(String line) {
		if (!line.contains("#") || !line.contains("include")) {
			return true;
		}
		for (int i = 1; i < line.length(); ++i) {
			if (line.charAt(i) != '/') {
				continue;
			}
			// line.charAt(i) is a slash now
			if (line.charAt(i - 1) >= 'a' && line.charAt(i - 1) <= 'z') {
				return true;
			} else {
				return false;
			}
		}
		return true;
	}
}
