# energy_ua
Class to calculate the cost of electricity in Ukraine

```
// Using example:
$energy = new EnergyUA();
$energy->setCity();
$energy->setInitialIndications('00200');
$energy->setFinalIndications('00600');
$energy->calculateCost();
$report = $energy->getFullReport();
print_r($report);

// Output:
Array ( 
  [initial_indicators]  => 200
  [final_indicators]    => 600
  [is_city]             => 1
  [is_village]          => 
  [first_limit]         => 150
  [second_limit]        => 500
  [cost]                => 212.4
)
```
