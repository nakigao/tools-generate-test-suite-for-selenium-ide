<?php

class GenerateTestSuite
{
	public $testTitle;
	public $separator;

	public function __construct($options = array())
	{
		if (empty($options['testTitle'])) {
			$this->testTitle = 'Test Suite';
		} else {
			$this->testTitle = $options['testTitle'];
		}
		if (empty($options['separator'])) {
			$this->separator = ':';
		} else {
			$this->separator = $options['separator'];
		}
	}

	public function html($fileName = "")
	{
		if (empty($fileName)) {
			throw new Exception("No specific file name.");
		}
		$result = "";

		$result .= <<< EOM
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <title>{$this->testTitle}</title>
</head>
<body>
<table id="suiteTable" cellpadding="1" cellspacing="1" border="1" class="selenium"><tbody>
<tr><td><b>{$this->testTitle}</b></td></tr>
EOM;

		$filePointer = fopen($fileName, 'r');
		while ($line = fgetcsv($filePointer)) {
			if (mb_substr($line[0], 0, 1) == '#') {
				// 先頭１文字が「#」の場合は、コメント行なので読み飛ばす
				continue;
			}
			$result .= <<< EOM
<tr><td><a href="{$line[0]}.html">{$line[0]}{$this->separator}{$line[1]}</a></td></tr>

EOM;
		}
		fclose($filePointer);

		$result .= <<< EOM
</tbody></table>
</body>
</html>
EOM;

		return $result;
	}
}

if (empty($argv[1]) || empty($argv[2]) || empty($argv[3])) {
	throw new Exception("No Arguments");
}

$generator = new GenerateTestSuite(
	array(
		'testTitle' => $argv[2],
		'separator' => $argv[3]
	)
);

echo $generator->html($argv[1]);