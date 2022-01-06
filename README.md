# Time Array Helper

The time array helper can handle times in array's. All times are represented as integers. Start of the day is 0 end of the day is 24*60=1440.

Example time blok
```php
[[600,660],[720,800]]
```
This represent 10:00-11:00 and 12:00-13:20

If times blocks overlap each other, the blocks will be compacted to one block

Example
```php
[[600,700],[700,800]] => [[600,800]]
```

### Requirements

- PHP 8.0

### Installation

```
composer require mmerlijn/helper-time-array
```

### Usage

#####Start time array
```php
$t = new TimeArray();

//or with initial value

$t = new TimeArray([[500,600],[700,800]]); //multiple intervals
$t = new TimeArray([600,720]); //one interval
```
Or with the create method
```php
$t = new TimeArray();
$t->create([600,700]);
```

#####Add times (accepts only one time interval)
```php
$t = $t->add([600,700]); //add 10:00-11:40 
```

#####Substract times  (accepts only one time interval)
```php
$t = $t->substract([650,700]); //reduce the time Array
```

#####Get
Returning the array
```php
$t->get(); //
 ```

#####Chaning
```php
(new TimeArray([500,600]))->add([700,800])->substract([750,800])->get();
```

### Testing

See phpunit tests