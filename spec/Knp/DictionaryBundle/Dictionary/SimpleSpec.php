<?php

declare(strict_types=1);

namespace spec\Knp\DictionaryBundle\Dictionary;

use Assert\Assert;
use Knp\DictionaryBundle\Dictionary;
use PhpSpec\ObjectBehavior;

class SimpleSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', [
            'foo' => 0,
            'bar' => 1,
            'baz' => 2,
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Dictionary\Simple::class);
    }

    function it_is_a_dictionary()
    {
        $this->shouldImplement(Dictionary::class);
    }

    function it_access_to_value_like_an_array()
    {
        Assert::that($this['foo']->getWrappedObject())->eq(0);
        Assert::that($this['bar']->getWrappedObject())->eq(1);
        Assert::that($this['baz']->getWrappedObject())->eq(2);
    }

    function its_getvalues_should_return_dictionary_values()
    {
        $this->getValues()->shouldReturn([
            'foo' => 0,
            'bar' => 1,
            'baz' => 2,
        ]);
    }

    function its_getname_should_return_dictionary_name()
    {
        $this->getName()->shouldReturn('foo');
    }
}
