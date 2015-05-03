<?php
/**
 * @file
 * Class EnergyUA file definition.
 *
 * @author Valentine Matsveiko <mve@drupalway.net>
 */

/**
 * Class EnergyUA.
 */
class EnergyUA {
  /**
   * Initial indications.
   *
   * @var int
   */
  private $initialIndications;
  /**
   * Final indications.
   *
   * @var int
   */
  private $finalIndications;
  /**
   * Is City flag.
   *
   * @var bool
   */
  private $city = FALSE;
  /**
   * Is Village flag.
   *
   * @var bool
   */
  private $village = FALSE;
  /**
   * Is Special Family status flag.
   *
   * @var bool
   */
  private $specialFamiliesStatus = FALSE;
  /**
   * Indications differences.
   *
   * @var int
   */
  private $diff;
  /**
   * Price rates static array.
   *
   * @var array
   */
  static protected $rates = array(0.366, 0.63, 1.407);
  /**
   * Final cost param.
   *
   * @var int
   */
  private $finalCost = 0;

  /**
   * Set initial indications value.
   *
   * @param string $var
   *   Initial indications string.
   */
  public function setInitialIndications($var) {
    if (is_string($var)) {
      $this->initialIndications = self::removeLeadingZeros($var);
    }
    else {
      die("Wrong param type.");
    }
  }

  /**
   * Set final indications value.
   *
   * @param string $var
   *   Final indications string.
   */
  public function setFinalIndications($var) {
    if (is_string($var)) {
      $this->finalIndications = self::removeLeadingZeros($var);
    }
    else {
      die("Wrong param type.");
    }
  }

  /**
   * Helper method for removing leading zeros from the string number.
   *
   * @param string $str
   *   Input string number.
   *
   * @return int
   *   Filtered integer value.
   */
  protected static function removeLeadingZeros($str) {
    return (int) preg_replace('/^0*/', '', $str);
  }

  /**
   * Set City.
   */
  public function setCity() {
    $this->city = TRUE;
  }

  /**
   * Set Village.
   */
  public function setVillage() {
    $this->village = TRUE;
  }

  /**
   * Set indications differences.
   */
  private function setDiff() {
    $this->diff = $this->finalIndications - $this->initialIndications;
  }

  /**
   * Calculate cost.
   *
   * @return int
   *   Cost value.
   */
  public function calculateCost() {
    $this->setDiff();
    $cost = 0;
    $cost += $this->calculateUpToLimit(self::$rates[0]);
    if ($this->diff > 0) {
      $cost += $this->calculateAboveFirstLimit(self::$rates[1]);
      if ($this->diff > 0) {
        $cost += $this->calculateAboveSecondLimit(self::$rates[2]);
      }
    }
    $this->finalCost = $cost;
    return $cost;
  }

  /**
   * Calculate cost for a "Special Families status"
   *
   * @return int
   *   Cost value.
   */
  public function calculateForSpecialFamiliesStatus() {
    $this->specialFamiliesStatus = TRUE;
    $this->setDiff();
    $this->finalCost = self::$rates[0] * $this->diff;
    return $this->finalCost;
  }

  /**
   * Get full report.
   *
   * @return array
   *   Report array.
   */
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

  /**
   * Calculate price before the first limit.
   *
   * @param float $rate
   *   Rate value.
   *
   * @return mixed
   *   Cost value.
   */
  private function calculateUpToLimit($rate) {
    $temp = 100;
    if ($this->village) {
      $temp += 50;
    }
    if ($this->diff < $temp) {
      $temp = $this->diff;
    }
    $this->diff -= $temp;
    return $rate * $temp;
  }

  /**
   * Calculate price above the first limit.
   *
   * @param float $rate
   *   Rate value.
   *
   * @return mixed
   *   Cost value.
   */
  private function calculateAboveFirstLimit($rate) {
    $temp = 500;
    if ($this->village) {
      $temp -= 50;
    }
    if ($this->diff < $temp) {
      $temp = $this->diff;
      $this->diff = 0;
    }
    else {
      $this->diff -= $temp;
    }
    return $rate * $temp;
  }

  /**
   * Calculate price above the second limit.
   *
   * @param float $rate
   *   Rate value.
   *
   * @return mixed
   *   Cost value.
   */
  private function calculateAboveSecondLimit($rate) {
    return $rate * $this->diff;
  }

}
