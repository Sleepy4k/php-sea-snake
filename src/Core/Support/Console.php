<?php

namespace Snake\Core\Support;

include_once __DIR__ . '/../Const/Menu.php';

class Console {
  private $command;
  private $options;

  public function __construct() {
    $argv = $_SERVER['argv'];
    array_shift($argv);
    $this->command = $argv[0] ?? null;
    $this->options = $argv[1] ?? null;
  }

  public function __destruct() {
    print(PHP_EOL);
  }

  private function list_menu() {
    foreach (MENU_LIST as $key => $value) {
      printf("%s\t%s\n", $value['command'], $value['description']);
    }
  }

  public function run() {
    switch ($this->command) {
      case 'run':
        $location = ($this->options) ? $this->options : 'localhost:8000';
        $location = explode(':', $location);
        $host = $location[0];
        $port = $location[1] ?? 8000;
        $host = ($host == 'localhost') ? 'localhost' : '0.0.0.0';
        $port = (int) $port;
        shell_exec("php -S {$host}:{$port} -t public");
        break;
      default:
        $this->list_menu();
        break;
    }

    return 0;
  }
}