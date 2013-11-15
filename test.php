<?php

$loader = require __DIR__.'/vendor/autoload.php';

use Touki\Populator\Populator;
use Touki\Populator\Annotation;
use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$foo = new Foo;
$data = array(
    'bar' => array(
        'baz' => array(
            'foobaz' => 'erf'
        )
    ),
    'ignored' => 'ignore!',
    'unknown' => 'unknown',
    'pub' => 'Public!'
);
$populator = new Populator;

$foo = $populator->populate($data, $foo);

var_dump($foo);

class Foo
{
    public $pub;

    /**
     * @Annotation\Setter("setFoo")
     * @Annotation\Aliases({"foo", "ignored"})
     */
    protected $foo;

    /**
     * @Annotation\Ignore
     */
    protected $ignored;

    /**
     * @Annotation\Deep("Bar")
     */
    protected $bar;

    public function setFoo($val)
    {
        $this->foo = $val;
    }

    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }
}

class Bar
{
    /**
     * @Annotation\Deep("Baz")
     */
    protected $baz;

    public function setBaz(Baz $baz)
    {
        $this->baz = $baz;
    }
}

class Baz
{
    /**
     * @Annotation\Ignore
     */
    protected $foobaz;

    public function setFoobaz($foobaz)
    {
        $this->foobaz = $foobaz;
    }
}
