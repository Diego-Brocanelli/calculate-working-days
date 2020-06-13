# Calculate Working Days

Calculate number of working days in a date range

<a href="https://codeclimate.com/github/Diego-Brocanelli/calculate-working-days/maintainability"><img src="https://api.codeclimate.com/v1/badges/eb590106ff8f25a3580f/maintainability" /></a>

## Requirements

- PHP >= 7.4
- Composer

## Instalation

```bash
composer install
```

## Tests

```bash
composer tests
```

## Examples


#### A simple interval

```php
$days = (new WorkingDays('2019-06-06', '2019-06-11'))->calculate();

$days->getNumber(); //output: 04
$days->getDayList(); //output: ['2019-06-06', '2019-06-07', '2019-06-10', '2019-06-11']
```


#### A simple interval

```php
$days = (new WorkingDays('2019-06-06', '2019-06-11'))->calculate();

$days->getNumber(); //output: 04
$days->getDayList(); //output: ['2019-06-06', '2019-06-07', '2019-06-10', '2019-06-11']
```

#### with holidays list

```php

$holidays = ['2019-06-06'];

$days = (new WorkingDays('2019-06-05', '2019-06-11', $holidays))->calculate();

$days->getNumber(); //output: 04
$days->getDayList(); //output: ['2019-06-05', '2019-06-07', '2019-06-10', '2019-06-11']
```

## Author

[Diego Brocanelli Francisco](http://www.diegobrocanelli.com.br/)

## License

[MIT](https://github.com/Diego-Brocanelli/calculate-working-days/blob/master/LICENSE)
