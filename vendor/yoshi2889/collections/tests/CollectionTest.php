<?php
/**
 * Copyright 2017 NanoSector
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

use Yoshi2889\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
	public function getStringValidatorClosure(): \Closure
	{
		return function ($value)
		{
			return is_string($value);
		};
	}

	public function testAppend()
	{
		$collection = new Collection($this->getStringValidatorClosure());

		$string = 'Test string';
		$collection->append($string);

		self::assertEquals(1, $collection->count());
		self::assertCount(1, $collection->values());
		self::assertEquals($string, $collection[0]);
		self::assertEquals($string, $collection->offsetGet(0));
	}

	public function testAppendWithKey()
	{
		$collection = new Collection($this->getStringValidatorClosure());

		$string = 'Test string';
		$key = 'test';

		$collection->offsetSet($key, $string);

		self::assertEquals(1, $collection->count());
		self::assertCount(1, $collection->values());
		self::assertEquals($string, $collection[$key]);
		self::assertEquals($string, $collection->offsetGet($key));
		self::assertTrue($collection->offsetExists($key));
		self::assertSame([$key], $collection->keys());
	}

	public function testRemoveAll()
	{
		$collection = new Collection($this->getStringValidatorClosure());

		$string = 'Test string';
		$collection->append($string);
		$collection->append($string);
		$collection->append($string);

		self::assertEquals(3, $collection->count());
		self::assertTrue($collection->contains($string));

		$collection->removeAll($string);

		self::assertEquals(0, $collection->count());
		self::assertFalse($collection->contains($string));
	}

	public function testExchangeArray()
	{
		$array1 = ['Test', 'ing'];
		$array2 = ['foo', 'bar'];
		$array3 = ['foo', 10];

		$collection = new Collection($this->getStringValidatorClosure(), $array1);
		$collection->exchangeArray($array2);

		self::assertEquals($array2, $collection->getArrayCopy());

		self::expectException(\InvalidArgumentException::class);
		$collection->exchangeArray($array3);
	}

	public function testInvalidType()
	{
		$collection = new Collection($this->getStringValidatorClosure());

		self::assertFalse($collection->validateType(10));
		self::assertTrue($collection->validateType('Test'));

		$this->expectException(InvalidArgumentException::class);
		$collection->append(10);

		$this->expectException(InvalidArgumentException::class);
		$collection->offsetSet('test', 10);
	}

	public function testValidateArray()
	{
		$collection = new Collection($this->getStringValidatorClosure());

		self::assertTrue($collection->validateArray(['foo', 'bar']));
		self::assertFalse($collection->validateArray(['foo', 10]));
	}

	public function testFilter()
	{
		$initialValues = ['Test', 'ing', 'something', 'else'];
		$collection = new Collection($this->getStringValidatorClosure(), $initialValues);

		$filteredCollection = $collection->filter(function (string $value)
		{
			return $value != 'else';
		});

		self::assertEquals(['Test', 'ing', 'something'], $filteredCollection->values());
		self::assertEquals(3, $filteredCollection->count());
	}
}
