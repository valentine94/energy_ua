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
  private $finalCost = 0;
  private $specialFamiliesStatus = FALSE;

  public function setInitialIndications($var) {
    if (gettype($var) == 'string') {
      $this->initialIndications = (int) preg_replace('/^0*/', '', $var);
    }
    else {
      die("Wrong param type.");
    }
  }

  public function setFinalIndications($var) {
    if (gettype($var) == 'string') {
      $this->finalIndications = (int) preg_replace('/^0*/', '', $var);
    }
    else {
      die("Wrong param type.");
    }
  }

  public function setCity() {
    $this->village  = FALSE;
    $this->city     = TRUE;
  }

  public function setVillage() {
    $this->city     = FALSE;
    $this->village  = TRUE;
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
    $this->finalCost = $cost;
    return $cost;
  }

  public function calculateForSpecialFamiliesStatus() {
    $this->specialFamiliesStatus = TRUE;
    $this->setDiff();
    $this->finalCost = self::$rates[0] * $this->diff;
    return $this->finalCost;
  }

  public function getFullReport() {
    return array(
      'initial_indicators'  => $this->initialIndications,
      'final_indicators'    => $this->finalIndications,
      'is_city'             => $this->city,
      'is_village'          => $this->village,
      'is_special_status'   => $this->specialFamiliesStatus,
      'first_limit'         => !$this->specialFamiliesStatus ? (100 + ($this->village ? 50 : 0)) : 0,
      'second_limit'        => !$this->specialFamiliesStatus ? 500 : 0,
      'cost'                => $this->finalCost,
    );
  }

  private function calculateUpToLimit() {
    $temp = 100 + ($this->village ? 50 : 0);
    if ($this->diff < $temp) {
      $temp = $this->diff;
    }
    $this->diff -= $temp;
    return self::$rates[0] * $temp;
  }

  private function calculateAboveFirstLimit() {
    $temp = 500 - ($this->village ? 50 : 0);
    if ($this->diff < $temp) {
      $temp = $this->diff;
      $this->diff = 0;
    }
    else {
      $this->diff -= $temp;
    }
    return self::$rates[1] * $temp;
  }

  private function calculateAboveSecondLimit() {
    return self::$rates[2] * $this->diff;
  }

}
