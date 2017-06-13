<?php

namespace Softbox\Support\Tests;

use PHPUnit\Framework\TestCase;
use Softbox\Support\Transformer;

class TransformerTest extends TestCase
{
    /**
     * @test
     */
    public function testSimpleTransform()
    {
        $data = array('name' => 'william');
        $transformer = new Transformer(array('name' => 'nome'));
        $transformedData = $transformer->transform($data);

        $this->assertEquals(array('name'), array_keys($data));
        $this->assertEquals(array('nome'), array_keys($transformedData));
        $this->assertArrayHasKey('nome', $transformedData);
        $this->assertEquals('william', $transformedData['nome']);
    }

    public function testTwoLevelsTransform()
    {
        $data = array(
            'person' => array(
                'name' => 'William'
            )
        );
        $transformer = new Transformer(array('person.name' => 'person.nome'));
        $transformedData = $transformer->transform($data);

        $this->assertEquals(array(
            'person' => array(
                'nome' => 'William'
            )
        ), $transformedData);
        $this->assertArrayHasKey('nome', $transformedData['person']);
        $this->assertEquals('William', $transformedData['person']['nome']);
    }
}
