<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use DateInterval;
use Exception;
use Mockery;
use React\Cache\CacheInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Cache\PSR16Adapter;

use function React\Promise\reject;
use function React\Promise\resolve;

final class PSR16AdapterTest extends AsyncTestCase
{
    public function testGet(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('get')->with($key, null)->andReturn(resolve($value));
        self::assertSame($value, (new PSR16Adapter($client))->get($key));
    }

    public function testGetNonExistant(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('get')->with($key, null)->andReturn(resolve(null));
        self::assertNull((new PSR16Adapter($client))->get($key));
    }

    public function testSet(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('set')->with($key, $value, null)->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->set($key, $value));
    }

    public function testSetTtl(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->shouldReceive('set')->with($key, $value, $ttl)->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->set($key, $value, $ttl));
    }

    public function testSetDateIntervalTtl(): void
    {
        $client          = Mockery::mock(CacheInterface::class);
        $key             = 'key';
        $value           = 'value';
        $dateIntervalTtl = new DateInterval('PT123S');
        $ttl             = 123;
        $client->shouldReceive('set')->with($key, $value, $ttl)->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->set($key, $value, $dateIntervalTtl));
    }

    public function testSetTtlException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->shouldReceive('set')->with($key, $value, $ttl)->andReturn(reject($exception));
        self::assertFalse((new PSR16Adapter($client))->set($key, $value, $ttl));
    }

    public function testSetException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('set')->with($key, $value, null)->andReturn(reject($exception));
        self::assertFalse((new PSR16Adapter($client))->set($key, $value));
    }

    public function testDelete(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('delete')->with($key)->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->delete($key));
    }

    public function testDeleteException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('delete')->with($key)->andReturn(reject($exception));
        (new PSR16Adapter($client))->delete($key);
    }

    public function testHas(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('has')->with($key)->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->has($key));
    }

    public function testDeleteMultiple(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('deleteMultiple')->with([$key])->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->deleteMultiple([$key]));
    }

    public function testDeleteMultipleException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('deleteMultiple')->with([$key])->andReturn(reject($exception));
        (new PSR16Adapter($client))->deleteMultiple([$key]);
    }

    public function testCLear(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $client->shouldReceive('clear')->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->clear());
    }

    public function testSetMultiple(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->shouldReceive('setMultiple')->with([$key => $value], $ttl)->andReturn(resolve(true));
        self::assertTrue((new PSR16Adapter($client))->setMultiple([$key => $value], $ttl));
    }

    public function testGetMultiple(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('getMultiple')->with([$key], null)->andReturn(resolve([$key => $value]));
        self::assertSame([$key => $value], (new PSR16Adapter($client))->getMultiple([$key]));
    }
}
