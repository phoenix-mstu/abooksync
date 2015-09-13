<?php

abstract class TextFragment implements GraphElement {
  protected $a1, $a2, $b1, $b2;
  protected $atext, $btext;

  public function getId() {
    return $this->a1 . '-' . $this->a2 . '-' . $this->b1 . '-' . $this->b2;
  }

  public function followedBy(GraphElement $follower) {
    return $follower instanceof TextFragment && $this->a2 < $follower->a1 && $this->b2 < $follower->b1;
  }

  public function getSize() {
    return $this->a2 - $this->a1;
  }

  public function __get($name) {
    if (isset($this->$name)) return $this->$name;
  }
  abstract public function getWeight();
}

