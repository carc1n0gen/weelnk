#! /usr/bin/env php
<?php

$COMPOSER_BIN = getenv('WEELNK_COMPOSER_BIN');
$MIGRATE_BIN = getenv('WEELNK_MIGRATE_BIN');

if ($argc < 2) {
  echo "Usage: {$argv[0]} [version-number]\n";
  exit(1);
}

$git_response = shell_exec('git describe --tags --abbrev=0');
$current_version = substr($git_response, 0, strlen($git_response) - 1);
$new_version = $argv[1];

if ($current_version === $new_version) {
  echo "Already up-to-date.\n";
  exit(0);
}

if (!version_compare($current_version, $new_version, '<')) {
  echo "Cannot upgrade to a past version.\n";
  exit(1);
}

if (!$COMPOSER_BIN) {
  $COMPOSER_BIN = 'composer';
}

if (!$MIGRATE_BIN) {
  $MIGRATE_BIN = './vendor/bin/doctrine-migrations';
}

echo "Fetching...\n";
shell_exec('git fetch --tags');
echo "Checking out {$argv[1]}...\n";
shell_exec("git checkout {$argv[1]}");
echo "Getting dependencies...\n";
shell_exec("$COMPOSER_BIN install");
echo "Running migrations...\n";
shell_exec("$MIGRATE_BIN migrations:migrate");
