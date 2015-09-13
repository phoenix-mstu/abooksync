#!/usr/bin/php
<?php

require 'autoload.php';

// original file consists of words in lower case splitted by spaces
define('ORIG_FILE', 'assets/harry.txt');

// regognized file line example: 
// was(2) 662.110000 662.290000 0.868041
define('RECOGNIZED_FILE', 'assets/harry-parsed.txt');

define('RESULT_FILE', 'result.txt');

// min size of a text fragment  
define('MIN_NEEDLE_SIZE', 5); 

// size of a search area
define('SEARCH_AREA_SIZE', MIN_NEEDLE_SIZE*200);

// size of searh step
define('STEP_SIZE', (int) floor(MIN_NEEDLE_SIZE / 2));

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// preparations

$atext = file_get_contents(ORIG_FILE);
$btext = '';

// порядковый номер символа в распознанном тексте => секунда в аудиофайле
// symbol number in regognized text => second in audiofile
$times = array();

// filling times array and btext string
$ph = fopen(RECOGNIZED_FILE, 'r');
while ($s = fgets($ph)) {
  if ($s[0] == '<') continue;
  //was(2) 662.110000 662.290000 0.868041
  $s = strtolower($s);
  if (!preg_match('#^([\w\']+).+?([0-9]+\.[0-9]+)#', $s, $pocket)) continue;
  $pocket[1] = str_replace('\'', '', $pocket[1]);
  $times[strlen($btext)] = $pocket[2];
  $btext .= $pocket[1] . ' ';
}
fclose($ph);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Main algorithm (see the README)

$a0 = 0; $b0 = 0;
$cells = array();
while (true) {
  $search_area = mb_substr($atext, $a0, SEARCH_AREA_SIZE);
  for($i = $b0; $i < $b0 + mb_strlen($search_area) && $i < strlen($btext); $i += STEP_SIZE) {
    // searching for matching text pices with minimum length in current text fragment
    $offset = 0;
    while (false !== $pos = mb_strpos($search_area, mb_substr($btext, $i, STEP_SIZE), $offset)) {
      // we found the fragment, now we try to grow it
      $cell = new Cell($atext, $btext, $a0 + $pos, $i, STEP_SIZE);
      $cell->expand();
      if ($cell->getSize() >= MIN_NEEDLE_SIZE && !isset($cells[$cell->getId()])) {
        $cells[$cell->getId()] = $cell;
      }
      $offset = $pos + 1;
    }
  }

  $graph = new Graph($cells);
  $cells = array();
  foreach ($graph->getLongestPaths() as $path) {
    $cells[] = $chain = new Chain($path); 
  }

  if ($a0 + SEARCH_AREA_SIZE >= mb_strlen($atext)) {
    // BEST CHAIN
    $chain = array_shift($cells);
    break;
  } elseif ($a0 == $chain->a2 || !$chain){
    $a0 = $a0 + (int) floor(SEARCH_AREA_SIZE / 2);
    $b0 = $b0 + (int) floor(SEARCH_AREA_SIZE / 2);
    continue;
  }

  $a0 = $chain->a2 - (int) floor(SEARCH_AREA_SIZE / 4);
  $b0 = $chain->b2 - (int) floor(SEARCH_AREA_SIZE / 4);
  echo floor($a0 / strlen($atext) * 1000) / 10 . "%\n";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CONGRATULATIONS! Here we have the result.
// $chain contains best path
// now we're prepearing result in desired format:
// [times: [<array of times in seconds>], offsets: [<array of offsets in symbols from the beginning of the text>]]
  
$times_offsets = array(); 
$found_len = 0; 
foreach ($chain->getCells() as $cell) {
  $val = $cell->getText();
  $found_len += mb_strlen($val);
  
  $offset = 0;
  while (false !== $pos = mb_strpos($val, ' ', $offset)) {
    for ($i = $cell->b1 + $pos; $i < $cell->b1 + $pos + 20; $i++) {
      if (!isset($times[$i])) continue;
      $time = round($times[$i]*10);
      $word_offset = $cell->a1 + $i - $cell->b1;
      if (!isset($times_offsets[$word_offset])) $times_offsets[$word_offset] = $time;
      //$result[] = $time;
      break;
    }
    $offset = $pos + 1;
  }
}

$start = 0;
$result = array('times' => array(), 'text' => array());
foreach ($times_offsets as $offset => $time) {
  $text = substr($atext, $start, $offset - $start);
  $start = $offset;
  $result['times'][] = $time;
  $result['text'][] = $text;
}

echo "writing result file: " . RESULT_FILE . "\n";
echo "\n" . $found_len / mb_strlen($atext)  * 100 . "% match\n";

file_put_contents(RESULT_FILE, json_encode($result));

