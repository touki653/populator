# PHP Populator (Array-to-Object)
[![Build Status](https://travis-ci.org/touki653/populator.png?branch=master)](https://travis-ci.org/touki653/populator)


PHP Populator is a simple Array-To-Object library which transforms arrays into a given object with a few additions with Annotations.  
This is **not** a deserializer. If you're looking for a serializer/deserializer, I advise you to use [**jms/serializer**](https://github.com/schmittjoh/serializer).

The simple goal of this library is to use a lightweight (still object oriented) solution to hydrate an object from a given array without the overkill of a full serializer.  
Plus, behaviour can be easily modified (decoupled), and it's unit tested!

# Documentation Summary

 - [Installation](#installation)
   - [Installation with composer](#installation-with-composer)
 - [Setup](#setup)
   - [Basic Setup](#basic-setup)
   - [Advanced Setup](#advanced-setup)
 - [Usage](#usage)
   - [Simple Usage](#simple-usage)
   - [Annotations](#annotations)
     - [@Populator\Ignore](#populatorignore)
     - [@Populator\Setter](#populatorsetter)
     - [@Populator\Alias](#populatoralias)
     - [@Populator\Aliases](#populatoraliases)
     - [@Populator\Deep](#populatordeep)
 
# Installation

## Installation with composer

The easiest way to use this library is to use [Composer]
Just add the following lines into your `composer.json`

```json
{
    "require": {
        "touki/populator": "~1.0.0"
    }
}
```

And run

```sh
composer update
```

# Setup

## Basic setup

This library uses the [PSR-0] autoloading mechanism, if you're using composer, there is nothing to do, the class should be autolaoded already.

In order to start using the populator, you just need to create an instance of it.

```php
<?php

use Touki\Populator\Populator;

$populator = new Populator;

?>
```

And, that's it! You can already already start using the library. See [Usage](#usage) for more informations

## Advanced setup

The populator is magically creating two instances in its [constructor][populator-constructor] if none are given.  
It needs an instance of [`HydratorInterface`][HydratorInterface] (Will hydrate the object on a given context) and an instance of [`HydratorContextFactoryInterface`][HydratorContextFactoryInterface] (Will create a context based on reflection of a given object)

To reproduce the default behaviour you can do something like this

```php
<?php

use Touki\Populator\Populator;
use Touki\Populator\Hydrator;
use Touki\Populator\HydratorContextFactory;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * You can instanciate your own as long as it implements HydratorInterface
 */
$hydrator = new Hydrator;

/**
 * You can instanciate your own as long as it implements HydratorContextFactoryInterface
 * The default one accepts Any Doctrine's Annotation Reader (Like FileCacheReader)
 *
 * @see https://github.com/doctrine/annotations/tree/master/lib/Doctrine/Common/Annotations
 */
$factory = new HydratorContextFactory(new AnnotationReader);

$populator = new Populator($hydrator, $factory);

?>
```

# Usage

## Simple usage

Say with have a `Foo` class

```php
<?php

namespace Acme\Model\Foo;

class Foo
{
    protected $bar;
    public $public;
    public $publicWithSetter;

    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    public function getBar()
    {
        return $this->bar;
    }

    public function setPublicWithSetter($var)
    {
        $this->publicWithSetter = $var;
    }
}

$data = array(
    'bar' => 'Foobaz!',
    'public' => 'Public!'
    'publicWithSetter' => 'BySetter'
);

/**
 * You can give either classname or an instance
 */
$foo = new Acme\Model\Foo;
$foo = 'Acme\Model\Foo';

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getBar();         // Foobaz!
echo $newFoo->public;           // Public!
echo $newFoo->publicWithSetter; // BySetter

?>
```

## Annotations

Along examples, we assume each protected property has its setter and its getter

### @Populator\Ignore

This annotation skips the setting of the property

```php
<?php

use Touki\Populator\Annotations as Populator;

class Foo
{
    /**
     * @Populator\Ignore
     */
    protected $bar;
}

$data = array(
    'bar' => 'Foobaz!'
);

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getBar(); // NULL

?>
```

### @Populator\Setter

This annotation allows property to define its own class' setter 

```php
<?php

use Touki\Populator\Annotations as Populator;

class Foo
{
    /**
     * @Populator\Setter("mySetter")
     */
    protected $bar;

    public function mySetter($value)
    {
        $this->bar = $value;
    }
}

$data = array(
    'bar' => 'Foobaz!'
);

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getBar(); // Foobaz!

?>
```

### @Populator\Alias

This annotation adds an alias to match on a property

```php
<?php

use Touki\Populator\Annotations as Populator;

class Foo
{
    /**
     * @Populator\Alias("bar")
     * @Populator\Alias("another")
     */
    protected $foo;

    public function setFoo($value)
    {
        $this->foo = $value;
    }
}

$data = array(
    'bar' => 'Foobaz!'
);

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getFoo(); // Foobaz!

$data = array(
    'another' => 'Foobaz!'
);

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getFoo(); // Foobaz!

?>
```

### @Populator\Aliases

This annotation sets and replaces aliases

```php
<?php

use Touki\Populator\Annotations as Populator;

class Foo
{
    /**
     * @Populator\Aliases({"bar", "another"})
     */
    protected $foo;

    public function setFoo($value)
    {
        $this->foo = $value;
    }
}

$data = array(
    'bar' => 'Foobaz!'
);

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getFoo(); // Foobaz!

?>
```

### @Populator\Deep

This annotations lets you have a deeper object

```php
<?php

use Touki\Populator\Annotation as Populator;

class Foo
{
    /**
     * @Populator\Deep("Bar")
     */
    protected $bar;

    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }
}

class Bar
{
    protected $baz;

    public function setBaz($bar)
    {
        $this->baz = $baz;
    }
}

$data = array(
    'bar' => array(
        'baz' => 'DeepBaz!'
    )
);

$newFoo = $populator->populate($data, $foo);

echo $newFoo->getBar()->getBaz(); // DeepBaz!

?>
```

 [Composer]: http://getcomposer.org
 [PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 [populator-constructor]: https://github.com/touki653/populator/blob/master/lib/Touki/Populator/Populator.php#L32
 [HydratorInterface]: https://github.com/touki653/populator/blob/master/lib/Touki/Populator/HydratorInterface.php
 [HydratorContextFactoryInterface]: https://github.com/touki653/populator/blob/master/lib/Touki/Populator/HydratorContextFactoryInterface.php

