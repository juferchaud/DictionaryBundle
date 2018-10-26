<?php

declare(strict_types=1);

namespace spec\Knp\DictionaryBundle\Dictionary;

use ArrayIterator;
use Assert\Assert;
use IteratorAggregate;
use Knp\DictionaryBundle\Dictionary;
use PhpSpec\ObjectBehavior;

class CombinedSpec extends ObjectBehavior
{
    function let(Dictionary $dictionary1, Dictionary $dictionary2, Dictionary $dictionary3)
    {
        $this->beConstructedWith('combined_dictionary', [
            $dictionary1,
            $dictionary2,
            $dictionary3,
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Dictionary\Combined::class);
    }

    function it_is_a_dictionary()
    {
        $this->shouldImplement(Dictionary::class);
    }

    function it_access_to_value_like_an_array($dictionary1, $dictionary2, $dictionary3)
    {
        $dictionary1->offsetExists('foo1')->willReturn(true);
        $dictionary1->offsetGet('foo1')->willReturn('foo10');

        $dictionary1->offsetExists('bar1')->willReturn(false);
        $dictionary2->offsetExists('bar1')->willReturn(true);
        $dictionary2->offsetGet('bar1')->willReturn('bar10');

        $dictionary1->offsetExists('baz1')->willReturn(false);
        $dictionary2->offsetExists('baz1')->willReturn(false);
        $dictionary3->offsetExists('baz1')->willReturn(true);
        $dictionary3->offsetGet('baz1')->willReturn('baz10');

        Assert::that($this['foo1']->getWrappedObject())->eq('foo10');
        Assert::that($this['bar1']->getWrappedObject())->eq('bar10');
        Assert::that($this['baz1']->getWrappedObject())->eq('baz10');
    }

    function it_getvalues_should_return_dictionaries_values($dictionary1, $dictionary2, $dictionary3)
    {
        $dictionary1->getValues()->willReturn([
            'foo1' => 'foo10',
            'foo2' => 'foo20',
        ]);

        $dictionary2->getValues()->willReturn([
            'bar1' => 'bar10',
            'bar2' => 'bar20',
        ]);

        $dictionary3->getValues()->willReturn([
            'foo2' => 'baz20',
            'bar2' => 'baz20',
        ]);

        $this->getValues()->shouldReturn([
            'foo1' => 'foo10',
            'foo2' => 'baz20',
            'bar1' => 'bar10',
            'bar2' => 'baz20',
        ]);
    }

    function it_can_iterate_over_dictionaries($dictionary1, $dictionary2, $dictionary3)
    {
        $dictionary1->getIterator()->willReturn(new ArrayIterator([
            'foo1' => 'foo10',
            'foo2' => 'foo20',
        ]));

        $dictionary2->getIterator()->willReturn(new ArrayIterator([
            'bar1' => 'bar10',
            'bar2' => 'bar20',
        ]));

        $dictionary3->getIterator()->willReturn(new ArrayIterator([
            'foo2' => 'baz20',
            'bar2' => 'baz20',
        ]));

        $this->shouldIterateOver([
            'foo1' => 'foo10',
            'foo2' => 'baz20',
            'bar1' => 'bar10',
            'bar2' => 'baz20',
        ]);
    }

    function its_getname_should_return_dictionary_name()
    {
        $this->getName()->shouldReturn('combined_dictionary');
    }

    function it_sums_the_count_of_elements($dictionary1, $dictionary2, $dictionary3)
    {
        $dictionary1->count()->willReturn(1);

        $dictionary2->count()->willReturn(2);

        $dictionary3->count()->willReturn(4);

        $this->count()->shouldReturn(7);
    }

    public function getMatchers(): array
    {
        return [
            'iterateOver' => function (IteratorAggregate $iterator, array $array): bool {
                Assert::that(iterator_to_array($iterator))->eq($array);

                return true;
            },
        ];
    }
}
