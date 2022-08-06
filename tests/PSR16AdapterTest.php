<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use DateInterval;
use Exception;
use React\Cache\CacheInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Cache\PSR16Adapter;

use function React\Promise\reject;
use function React\Promise\resolve;

final class PSR16AdapterTest extends AsyncTestCase
{
    public function testGet(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->get($key, null)->shouldBeCalled()->willReturn(resolve($value));
        self::assertSame($value, (new PSR16Adapter($client->reveal()))->get($key));
    }

    public function testGetNonExistant(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $client->get($key, null)->shouldBeCalled()->willReturn(resolve(null));
        self::assertNull((new PSR16Adapter($client->reveal()))->get($key));
    }

    public function testSet(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->set($key, $value, null)->shouldBeCalled()->willReturn(resolve(true));
        (new PSR16Adapter($client->reveal()))->set($key, $value);
    }

    public function testSetTtl(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->set($key, $value, $ttl)->shouldBeCalled()->willReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client->reveal()))->set($key, $value, $ttl));
    }

    public function testSetDateIntervalTtl(): void
    {
        $client          = $this->prophesize(CacheInterface::class);
        $key             = 'key';
        $value           = 'value';
        $dateIntervalTtl = new DateInterval('PT123S');
        $ttl             = 123;
        $client->set($key, $value, $ttl)->shouldBeCalled()->willReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client->reveal()))->set($key, $value, $dateIntervalTtl));
    }

    public function testSetTtlException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->set($key, $value, $ttl)->shouldBeCalled()->willReturn(reject($exception));
        self::assertFalse((new PSR16Adapter($client->reveal()))->set($key, $value, $ttl));
    }

    public function testSetException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->set($key, $value, null)->shouldBeCalled()->willReturn(reject($exception));
        self::assertFalse((new PSR16Adapter($client->reveal()))->set($key, $value));
    }

    public function testDelete(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $client->delete($key)->shouldBeCalled()->willReturn(resolve(true));
        (new PSR16Adapter($client->reveal()))->delete($key);
    }

    public function testDeleteException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $client->delete($key)->shouldBeCalled()->willReturn(reject($exception));
        (new PSR16Adapter($client->reveal()))->delete($key);
    }

    public function testHas(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $client->has($key)->shouldBeCalled()->willReturn(resolve(true));
        (new PSR16Adapter($client->reveal()))->has($key);
    }

    public function testDeleteMultiple(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $client->deleteMultiple([$key])->shouldBeCalled()->willReturn(resolve(true));
        (new PSR16Adapter($client->reveal()))->deleteMultiple([$key]);
    }

    public function testDeleteMultipleException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $client->deleteMultiple([$key])->shouldBeCalled()->willReturn(reject($exception));
        (new PSR16Adapter($client->reveal()))->deleteMultiple([$key]);
    }

    public function testCLear(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $client->clear()->shouldBeCalled()->willReturn(resolve(true));
        (new PSR16Adapter($client->reveal()))->clear();
    }

    public function testSetMultiple(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->setMultiple([$key => $value], $ttl)->shouldBeCalled()->willReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client->reveal()))->setMultiple([$key => $value], $ttl));
    }

    public function testGetMultiple(): void
    {
        $client = $this->prophesize(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->getMultiple([$key], null)->shouldBeCalled()->willReturn(resolve([$key => $value]));
        self::assertSame([$key => $value], (new PSR16Adapter($client->reveal()))->getMultiple([$key]));
    }
}
