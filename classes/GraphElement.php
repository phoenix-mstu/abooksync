<?php

interface GraphElement {
  public function getId();
  public function getWeight();
  public function followedBy(GraphElement $element);
}

