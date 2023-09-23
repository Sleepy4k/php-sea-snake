<?php

namespace Snake\Core\View;

use Snake\Interface\View\ISea;

final class Sea implements ISea {
  /**
   * Echo the rendered view.
   *
   * @param string $view
   * @param array $data
   * @param string $ext
   *
   * @return void
   */
  public static function view(string $view, array $data = [], string $ext = 'sea.php'): void {
    $block = new Block(basepath() . '/view/', $ext);

    if (!$block->has($view)) {
      $block->setDirectory(__DIR__ . '/../../View');
    }

    echo $block->render($view, $data);
  }
}
