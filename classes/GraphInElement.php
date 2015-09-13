<?php

class GraphInElement implements GraphElement {
  public function getId() {
    return 'in';
  }
  public function getWeight() {
    return 0;
  }
  public function followedBy(GraphElement $element) {
    return true;
  }
}

