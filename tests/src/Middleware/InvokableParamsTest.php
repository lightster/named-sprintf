<?php

namespace Lstr\Sprintf\Middleware;

use PHPUnit\Framework\TestCase;

class InvokableParamsTest extends TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getName
     */
    public function testNamePassedToConstructorIsSameNameRetrieved()
    {
        $name = uniqid();
        $params = new InvokableParams($name, function () {}, []);

        $this->assertEquals($name, $params->getName());
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getName
     * @covers Lstr\Sprintf\Middleware\InvokableParams::setName
     */
    public function testNameCanBeChanged()
    {
        $name = uniqid();
        $new_name = "{$name}-{$name}";
        $params = new InvokableParams($name, function () {}, []);

        $this->assertEquals($name, $params->getName());
        $params->setName($new_name);
        $this->assertEquals($new_name, $params->getName());
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getValuesCallback
     */
    public function testValuesCallbackCanBeRetrievedAndUsed()
    {
        $uniqid = uniqid();
        $params = $this->getInvokableParamsForValueTest($uniqid);

        $values_callback = $params->getValuesCallback();

        $this->assertEquals("not-so-important-{$uniqid}", call_user_func($values_callback, 'not-so-important'));
        $this->assertEquals("hi-{$uniqid}", call_user_func($values_callback, 'hi'));
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getValuesCallback
     * @covers Lstr\Sprintf\Middleware\InvokableParams::setValue
     */
    public function testValuesCanBeOverridden()
    {
        $uniqid = uniqid();
        $params = $this->getInvokableParamsForValueTest($uniqid);

        $override_value = "hi-there-{$uniqid}";
        $params->setValue($override_value);

        $values_callback = $params->getValuesCallback();

        $this->assertEquals($override_value, call_user_func($values_callback, 'not-so-important'));
        $this->assertEquals("hi-{$uniqid}", call_user_func($values_callback, 'hi'));
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getValuesCallback
     * @covers Lstr\Sprintf\Middleware\InvokableParams::addValue
     */
    public function testValuesCanBeAdded()
    {
        $uniqid = uniqid();
        $params = $this->getInvokableParamsForValueTest($uniqid);

        $override_value = "hi-there-{$uniqid}";
        $params->addValue('hi', $override_value);

        $values_callback = $params->getValuesCallback();

        $this->assertEquals("not-so-important-{$uniqid}", call_user_func($values_callback, 'not-so-important'));
        $this->assertEquals($override_value, call_user_func($values_callback, 'hi'));
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getOptions
     */
    public function testOptionsPassedToConstructorAreSameOptionsRetrieved()
    {
        $options = ['a' => 1, 'b' => 23, 'c' => 456];
        $params = new InvokableParams('not-so-important', function () {}, $options);

        $this->assertEquals($options, $params->getOptions());
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getOption
     */
    public function testIndividualOptionCanBeRetrieved()
    {
        $options = ['a' => 1, 'b' => 23, 'c' => 456];
        $params = new InvokableParams('not-so-important', function () {}, $options);

        $this->assertEquals($options['a'], $params->getOption('a'));
        $this->assertEquals($options['b'], $params->getOption('b'));
        $this->assertEquals($options['c'], $params->getOption('c'));
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getOption
     */
    public function testIndividualOptionCanUseDefaultValue()
    {
        $options = ['a' => 1, 'b' => 23, 'c' => 456];
        $params = new InvokableParams('not-so-important', function () {}, $options);

        $this->assertEquals(7890, $params->getOption('d', 7890));
    }

    /**
     * @covers Lstr\Sprintf\Middleware\InvokableParams::__construct
     * @covers Lstr\Sprintf\Middleware\InvokableParams::getOption
     * @covers Lstr\Sprintf\Middleware\InvokableParams::setOption
     */
    public function testAnOptionCanBeSet()
    {
        $options = ['a' => 1, 'b' => 23, 'c' => 456];
        $params = new InvokableParams('not-so-important', function () {}, $options);

        $params->setOption('hi', 'there');

        $this->assertEquals('there', $params->getOption('hi'));
    }

    /**
     * @param string $uniqid
     * @return InvokableParams
     */
    private function getInvokableParamsForValueTest($uniqid)
    {
        $callback = function ($name) use ($uniqid) {
            return "{$name}-{$uniqid}";
        };
        return new InvokableParams('not-so-important', $callback, []);
    }
}
