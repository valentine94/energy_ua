<?php
/**
 * @file
 * Class EnergyUA file definition.
 */

/**
 * Class EnergyUA.
 */
class EnergyUA {
  private $initialIndications;
  private $finalIndications;
  private $city;
  private $village;
  private $limit;
  private $diff;
  static protected $rates = array(0.366, 0.63, 1.407);

  public function setInitialIndications($var) {
    $this->initialIndications = $var;
  }

  public function setFinalIndications($var) {
    $this->finalIndications = $var;
  }

  public function setCity() {
    $this->village  = FALSE;
    $this->city     = TRUE;
    $this->limit    = 100;
  }

  public function setVillage() {
    $this->city     = FALSE;
    $this->village  = TRUE;
    $this->limit    = 150;
  }

  private function setDiff() {
    $this->diff = $this->finalIndications - $this->initialIndications;
  }

  public function calculateCost() {
    $this->setDiff();
    $cost = 0;
    $cost += $this->calculateUpToLimit();
    if ($this->diff > 0) {
      $cost += $this->calculateAboveFirstLimit();
      if ($this->diff > 0) {
        $cost += $this->calculateAboveSecondLimit();
      }
    }
    return $cost;
  }

  private function calculateUpToLimit() {
    $temp = 100;
    if ($this->diff < 100) {
      $temp = $this->diff;
    }
    $this->diff -= 100;
    return self::$rates[0] * $temp;
  }

  private function calculateAboveFirstLimit() {
    $temp = 500;
    if ($this->diff < 500) {
      $temp = $this->diff;
      $this->diff = 0;
    }
    else {
      $this->diff -= 500;
    }
    return self::$rates[1] * $temp;
  }

  private function calculateAboveSecondLimit() {
    return self::$rates[2] * $this->diff;
  }

}
