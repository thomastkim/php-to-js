<?php

namespace spec\Kim\JavaScript;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Illuminate\Session\Store;

class JavaScriptSessionSpec extends ObjectBehavior
{
    function let(Store $session)
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kim\JavaScript\JavaScriptSession');
    }

    function it_gets_the_javascript_data_and_compiles_it_into_a_javascript_usable_string(Store $session)
    {
    	$placeholderSessionData = ['Just', 'A', 'Bunch', 'Of', 'Random', 'String'];

    	$session->get('javascript.data')->willReturn($placeholderSessionData);

    	$this->getCompiledData()->shouldReturn("var window = window || [];JustABunchOfRandomString");
    }
}