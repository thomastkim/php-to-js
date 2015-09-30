<?php

namespace spec\Kim\JavaScript;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VariableConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kim\JavaScript\VariableConverter');
    }

    function it_gets_the_variables_type_in_title_case()
    {
        $this->getValueType([1, 2, 3])->shouldReturn('Array');
        $this->getValueType(true)->shouldReturn('Boolean');
        $this->getValueType(1.2)->shouldReturn('Double');
        $this->getValueType(1)->shouldReturn('Integer');
        $this->getValueType(NULL)->shouldReturn('Null');
        $this->getValueType(new \StdClass)->shouldReturn('Object');
        $this->getValueType('Hello')->shouldReturn('String');
    }

    function it_converts_php_array_into_its_JSON_representation()
    {
        $testKey = 'an-array';
        $testArray = [
            'key' => 'value',
            'inner-array' => [
                'inner-key' => 'inner-value'
            ]
        ];

        $this->convertToJavascript($testKey, $testArray)
            ->shouldReturn('window[\'an-array\']={"key":"value","inner-array":{"inner-key":"inner-value"}};');
    }

    function it_converts_php_boolean_into_a_javascript_usable_boolean()
    {
        $testKey = 'isValid';
        $testBoolean = true;

        $this->convertToJavascript($testKey, $testBoolean)
            ->shouldReturn("window['isValid']=true;");
    }

    function it_converts_php_double_into_a_javascript_usable_double()
    {
        $testKey = 'pi';
        $testDouble = 3.14;

        $this->convertToJavascript($testKey, $testDouble)
            ->shouldReturn("window['pi']=3.14;");
    }

    function it_converts_php_integer_into_a_javascript_usable_double()
    {
        $testKey = 'year';
        $testInteger = 2015;

        $this->convertToJavascript($testKey, $testInteger)
            ->shouldReturn("window['year']=2015;");
    }

    function it_converts_php_null_into_a_javascript_null_value()
    {
        $testKey = 'my-soul';
        $testNull = NULL;

        $this->convertToJavascript($testKey, $testNull)
            ->shouldReturn("window['my-soul']=null;");
    }

    function it_converts_php_strings_into_a_javascript_usable_strings()
    {
        $testKey = 'sentence';
        $testString = "Will this test work?";

        $this->convertToJavascript($testKey, $testString)
            ->shouldReturn("window['sentence']='Will this test work?';");
    }

    function it_converts_php_objects_that_have_the_toJson_methods()
    {
        $testKey = 'laravel-message-bag';
        $testObject = new \Illuminate\Support\MessageBag(['This test shall pass.', 'Because it is JsonSerializable']);
        $this->convertToJavascript($testKey, $testObject)
            ->shouldReturn('window[\'laravel-message-bag\']=[["This test shall pass."],["Because it is JsonSerializable"]];');
    }

    function it_throws_exception_if_the_php_object_is_not_serializable()
    {
        $testKey = 'std-class';
        $testObject = new \StdClass;
        $this->shouldThrow('\InvalidArgumentException')->during('convertToJavascript', [$testKey, $testObject]);
    }
}