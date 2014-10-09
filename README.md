# yii-apostle

[Apostle PHP](https://github.com/apostle/apostle-php) wrapper for Yii 1.1. It's an CApplicationComponent with one public method - `send($template, $to, $data = array())`.

# Installing

After installing it [through composer](https://packagist.org/packages/urmaul/yii-apostle) add this component into main.php.

```php
'apostle' => [
	'class' => 'yii\apostle\Component',
	'domainKey' => 'ApostleDomainKey',
	'from' => 'robot@test.com',
],
```
