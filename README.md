# energy_ua
Class to calculate the cost of electricity in Ukraine

```
// Using example:
$energy = new EnergyUA();
$energy->setVillage();
$energy->setInitialIndications('00200');
$energy->setFinalIndications('00600');
$energy->calculateCost();
$report = $energy->getFullReport();
print_r($report);

// Output:
Array ( 
  [initial_indicators]  => 200
  [final_indicators]    => 600
  [is_city]             => 
  [is_village]          => 1
  [is_special_status]   => 
  [first_limit]         => 150
  [second_limit]        => 500
  [cost]                => 212.4
)
```
