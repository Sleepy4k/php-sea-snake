<?php

namespace Snake\Core\View;

use Snake\Core\View\Block;

class Sea {
  protected $block;

  /*
  * Constructor
  *
  * @param string $view_dir
  * @param string $view_ext
  *
  * @return void
  */
  public function __construct(string $view_dir = '', string $view_ext = '.sea.php') {
    $this->block = new Block($view_dir, $view_ext);
  }

  /*
  * Render a view
  *
  * @param string $view
  * @param array $data
  *
  * @return void
  */
  public function render(string $view = '', array $data = []) {
    $this->block->render($view, $data);
  }
}