<?php

class Chain extends TextFragment {
  protected $cells = array();
  protected $weight = 0;

  protected function addFragments($fragments) {
    foreach ($fragments as $fragment) {
      if ($fragment instanceof Cell) {
        if (isset($this->cells[$fragment->a1])) die('error');
        $this->cells[$fragment->a1] = $fragment;
        $this->weight += $fragment->getWeight();
        if (!$this->a1 || $this->a1 > $fragment->a1) $this->a1 = $fragment->a1;
        if (!$this->b1 || $this->b1 > $fragment->b1) $this->b1 = $fragment->b1;
        if (!$this->a2 || $this->a2 < $fragment->a2) $this->a2 = $fragment->a2;
        if (!$this->b2 || $this->b2 < $fragment->b2) $this->b2 = $fragment->b2;
      } else {
        $this->addFragments($fragment->getCells());
      }
    }
  }
  public function __construct($fragments) {
    $this->addFragments($fragments);
    //var_dump($this->cells);die();
    ksort($this->cells);
  }
  public function getCells() {
    return $this->cells;
  }
  public function  getWeight() {
    return $this->weight;
  }
}


