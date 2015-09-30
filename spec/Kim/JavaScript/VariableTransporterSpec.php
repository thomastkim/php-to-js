<?php

namespace spec\Kim\JavaScript;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Illuminate\Events\Dispatcher;
use Kim\JavaScript\JavaScriptSession;
use Kim\JavaScript\VariableConverter;

class VariableTransporterSpec extends ObjectBehavior
{
    function let(Dispatcher $events, JavaScriptSession $session, VariableConverter $converter)
    {
        $this->beConstructedWith($events, $session, $converter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kim\JavaScript\VariableTransporter');
    }

    function it_flashes_converts_the_data_and_then_flashes_it_to_the_session(VariableConverter $converter, JavaScriptSession $session)
    {
        $startingData = [];
        $key = 'key';
        $value = 'value';

        $session->getData()->willReturn($startingData);
        $converter->convertToJavascript($key, $value)->willReturn("window['$key']='$value';");
        $session->flash(["window['$key']='$value';"])->shouldBeCalled();

        $this->add($key, $value);
    }
}