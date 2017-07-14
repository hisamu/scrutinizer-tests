<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

const BASEPATH_COMMAND = 'git rev-parse --show-toplevel';
const COMMITS_COMMAND = 'git log %s..HEAD --pretty=tformat:"%%H"';
const FILES_COMMAND = 'git diff -M --name-only %s^ HEAD -- *.php';
const LINES_COMMAND = "git blame -p %s | awk '/^(%s)/ {print $3}'";
const SCRUTINIZER_BRANCH_COMMAND = "git show HEAD --pretty=%P | awk '{print $1}'";
const CS_COMMAND = '%s/vendor/bin/phpcs --standard=PSR2 %s --report=json';

$output = new ConsoleOutput();
$reports = [];
$hasError = false;

$runCommand = function (string $command): array {
    $process = new Process($command);
    $process->run();

    return explode("\n", trim($process->getOutput())) ?: [];
};

$base = (bool) getenv('SCRUTINIZER') === false ? 'master' : current($runCommand(SCRUTINIZER_BRANCH_COMMAND));
$basePath = current($runCommand(BASEPATH_COMMAND));
$files = $runCommand(sprintf(FILES_COMMAND, $base));
$commits = implode('|', $runCommand(sprintf(COMMITS_COMMAND, $base)));

foreach ($files as $file) {
    $filePath = sprintf('%s/%s', $basePath, $file);
    $reports[$file] = $reports[$file] ?? [];

    $linesChanged = $runCommand(sprintf(LINES_COMMAND, $filePath, $commits));
    $report = json_decode(current($runCommand(sprintf(CS_COMMAND, $basePath, $file))), true);

    if (!isset($report['files'])) {
        continue;
    }

    $report = current($report['files']);
    $messages = $report['messages'];

    foreach ($messages as $message) {
        if (!in_array($message['line'], $linesChanged)) {
            continue;
        }

        $reports[$file][] = [
            'line' => $message['line'],
            'message' => $message['message'],
            'type' => $message['type'],
        ];

        if ($message['type'] === 'ERROR') {
            $hasError = true;
        }
    }
}


if ($hasError) {
    $table = new Table($output);
    $table->setHeaders([
        [new TableCell('<fg=red>Some styling errors were detected:</fg=red>', ['colspan' => 2])],
        ['File', 'Message'],
    ]);

    foreach ($reports as $file => $report) {
        if (!$report) {
            continue;
        }

        array_walk($report, function (array $data) use ($file, $table) {
            $table->addRow([sprintf('%s:%s', $file, $data['line']), $data['message']]);
        });
    }

    $table->render();

    exit(1);
}

$output->writeln('<info>No errors found! Congrats :)</info>');
