<?php

class Graph {
  protected $elements;
  public function __construct($elements) {
    $this->elements = $elements;
    $this->elements[] = new GraphInElement;
  }
  public function getLongestPaths() {
    $iteration_weights = array();
    $iteration_paths = array();
    $weights = array();
    foreach ($this->elements as $from) {
      $iteration_weights[$from->getId()] = ~PHP_INT_MAX;
      $iteration_paths[$from->getId()] = array();
      foreach ($this->elements as $to) {
        if ($from->followedBy($to)) {
          // области и в оригинальном и в распознанном тексте не должны перекрываться
          // и должны следовать в правильном порядке
          $weights[] = array($from->getId(), $to->getId(), $to->getWeight(), $to);
        }
      }
    }
    $iteration_weights['in'] = 0;
    // главный цикл алгоритма Беллмана-Форда
    for ($i = 0; $i < count($this->elements); $i++) {
      $finish = true;
      foreach ($weights as $w) {
        list($from_id, $to_id, $weight, $to) = $w;
        if ($iteration_weights[$to_id] < $iteration_weights[$from_id] + $weight) {
          $iteration_weights[$to_id] = $iteration_weights[$from_id] + $weight;
          $iteration_paths[$to_id] = $iteration_paths[$from_id];
          $iteration_paths[$to_id][] = $to;
          $finish = false;
        }
      }
      if ($finish) break;
    }
    arsort($iteration_weights); 
    $result = array();
    foreach ($iteration_weights as $id => $weight) {
      if (count($result) > 4 || !$weight) break;
      $result[] = $iteration_paths[$id];
    }
    //var_dump($result);
    return $result;
  }
}


