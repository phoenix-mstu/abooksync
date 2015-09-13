<?php

class Cell extends TextFragment {
  public function __construct(&$atext, &$btext, $a1, $b1, $size) {
    $this->a1 = $a1; $this->a2 = $a1 + $size;
    $this->b1 = $b1; $this->b2 = $b1 + $size;
    $this->atext = $atext;
    $this->btext = $btext;
  } 
  public function getWeight() {
    return ($this->a2 - $this->a1) * ($this->a2 - $this->a1);
  }
  
  public function expand() {
    //var_dump($this->a1,$this->b1 );
    while ($this->a1 > 0 && $this->b1 > 0 && 
          $this->atext[$this->a1 - 1] == $this->btext[$this->b1 - 1]) {$this->a1--; $this->b1--;}
    while ($this->a2 + 1 < mb_strlen($this->text) && $this->b2 + 1 < mb_strlen($ptext) && 
          $text->atext[$this->a2 + 1] == $this->btext[$this->b2 + 1]) {$this->a2++; $this->b2++;}
  }

  public function getText() {
    return mb_substr($this->atext, $this->a1, $this->a2 - $this->a1);
  }

}


