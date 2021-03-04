<?php

/**
 * Given the 2D coordinates and dimensions of multiple objects,
 * determine if 2 objects occupy the same space.
 * 
 * @author Nicholas Davies
*/

class Point { 
  public int $x;
  public int $y;
  public function __construct(int $x, int $y) {
    $this->x = $x;
    $this->y = $y;
  }

  public function __toString() {
    return "[$this->x, $this->y]";
  }
}

class Shape {
  public $points = [];
  public $num_points = 0;
  public $minx = 0;
  public $miny = 0;
  public $maxx = 0;
  public $maxy = 0;

  /**
   * @input array A multi-dimensional array of x, y coords
   * @example [[0,0], [5, 5]] for a 5x5 square
   * @example [[0,0], [1, 3]] for a 1x3 rectangle
   */
  public function __construct(array $coords) {
    if (!$coords || !in_array(count($coords), [2, 4])) {
      throw new Exception('Shapes with ' . count($coords) . ' coordinates are not currently supported.');
    }
    foreach ($coords as $xy) {
      if (!empty($xy) && count($xy) == 2) {
        $this->points[] = new Point($xy[0], $xy[1]);
        if ($this->minx > $xy[0]) $this->minx = $xy[0];
        if ($this->miny > $xy[1]) $this->miny = $xy[1];
        if ($this->maxx < $xy[0]) $this->maxx = $xy[0];
        if ($this->maxy < $xy[1]) $this->maxy = $xy[1];
      }
    }
    $this->num_points = count($this->points);
  }

  /**
   * @description Determine if 2 Shape objects occupy the same space.
   * @input Shape The Shape objects to test against.
   * @return bool
   */
  public function occupies_same_space(Shape $shape) {
    if ($this->num_points && $this->num_points == $shape->num_points) {
      // Current implementation for this solution:
      // 2D Squares and Rectangles
      if ($this->minx === $shape->minx
        && $this->miny === $shape->miny
        && $this->maxx === $shape->maxx
        && $this->maxy === $shape->maxy) {        
        return true;
      }
    }
    return false;
  }

}

$started = microtime(1);

#############
# Initiate Shape Objects to test
#############

$shapes = [];
$shapes[] = new Shape([[0,0],[1,1]]); // 1x1 pixel 
//$shapes[] = new Shape([[0,0],[0,1],[1,0],[1,1]]); // 1x1 pixel 
//$shapes[] = new Shape([[4,10],[5,10],[4,10]]); // exception
$shapes[] = new Shape([[2,1],[3,2]]);
$shapes[] = new Shape([[0,0],[5,5]]);
$shapes[] = new Shape([[2,2],[4,5]]);
$shapes[] = new Shape([[2,1],[3,2]]);
$shapes[] = new Shape([[0,0],[5,5]]);
//$shapes[] = new Shape([[0,0],[0,1],[1,0],[1,1]]); // 1x1 pixel 

$shapes[] = new Shape([[0,0],[5,5]]);

#############
# Process Start
#############

$num_shapes = count($shapes);
$found_match = false;
for ($i = 0; $i < $num_shapes; $i++) {
  for ($j = $i + 1; $j < $num_shapes; $j++) {
    if ($shapes[$i]->occupies_same_space($shapes[$j])) {
      $found_match = true;
      
      echo sprintf("[FOUND_MATCH]\n"
            . "Shape Index %d:\t[%s]\n"
            . "Shape Index %d:\t[%s]\n",
          $i, implode(', ', $shapes[$i]->points),
          $j, implode(', ', $shapes[$j]->points));

      // We have a match, so now have two options:
      // 1) Continue and match all possible matches for all Shape objects
      // 2) Break from both loops here if we only wanted the first match
      //    by uncommenting the line below.
      break 2;
    }
  }
}
if (!$found_match) {
  echo "[NO_MATCH]\n";
}
echo sprintf("Processed %d Shapes of %d available in %f(ms)\n", $i, $num_shapes, (microtime(1) - $started) * 1000);
