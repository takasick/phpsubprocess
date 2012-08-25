<?php
define('MAX_CHILD', 2);

$child_pids = array();

// MAX_CHILDの子プロセスをforkして処理を実行する
for ($child_index = 0; $child_index < MAX_CHILD; $child_index++) {
	$pid = pcntl_fork();
	if ($pid === -1) {
		echo "fork error";
		exit(1);
	} else if ($pid) {
		// 親プロセスはここが実行される
		echo "subprocess forked!! " . PHP_EOL;
		$child_pids[] = $pid;
	} else {
		// 子プロセスはここが実行される
		sleep(1);
		$pid = getmypid();
		echo "child process!! pid: " . $pid . " index: " . $child_index . PHP_EOL;
		exit(0);
	}
}

// 子プロセスの終了を待つ
$child_pids = array_flip($child_pids);
while (true) {
	$status = NULL;
	$ret = pcntl_wait($status, WUNTRACED);
	if ($ret > 0) {
		echo 'Exit Process pid: ' . $ret . PHP_EOL;
		unset($child_pids[$ret]);
	}

	if (count($child_pids) === 0)
		break;
}

echo 'done.' . PHP_EOL;
?>