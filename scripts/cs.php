<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

const BASEPATH_COMMAND = 'git rev-parse --show-toplevel';
const COMMITS_COMMAND = 'git log master..HEAD --pretty=tformat:"%H"';
const FILES_COMMAND = 'git diff -M --name-only master^ HEAD -- *.php';
const LINES_COMMAND = "git blame -p %s | awk '/%s/ {print $3,$4}'";
const CS_COMMAND = '%s/vendor/bin/phpcs --standard=PSR2 %s --report=json';

$runCommand = function (string $command): array {
    $process = new Process($command);
    $process->run();

    return explode("\n", trim($process->getOutput()));
};

$basePath = current($runCommand(BASEPATH_COMMAND));
$files = $runCommand(FILES_COMMAND);
$commits = implode('|', $runCommand(COMMITS_COMMAND));

foreach ($files as $file) {
    $filePath = sprintf('%s/%s', $basePath, $file);

    $linesChanged = $runCommand(sprintf(LINES_COMMAND, $filePath, $commits));
    $report = json_decode(current($runCommand(sprintf(CS_COMMAND, $basePath, $file))));

    echo "<pre>"; print_r ($linesChanged); die("</pre>");
}

exit(1);
