<?php

namespace Snake\Core\View;

final class Sea extends Block {
  /**
   * Echo the rendered view.
   *
   * @param string $dir
   * @param string $view
   * @param array $data
   * @param string $ext
   *
   * @return void
   */
  public static function view(string $dir = '', string $view = '', array $data = [], string $ext = 'sea.php'): void {
    $block = new Block($dir, $ext);
    echo $block->render($view, $data);
  }
}
