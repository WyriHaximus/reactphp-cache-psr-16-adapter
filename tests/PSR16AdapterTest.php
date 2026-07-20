<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use DateInterval;
use Exception;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use React\Cache\CacheInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Cache\PSR16Adapter;

use function React\Promise\reject;
use function React\Promise\resolve;

final class PSR16AdapterTest extends AsyncTestCase
{
    #[Test]
    public function get(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('get')->with($key, null)->andReturn(resolve($value));
        self::assertSame($value, new PSR16Adapter($client)->get($key));
    }

    #[Test]
    public function getNonExistant(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('get')->with($key, null)->andReturn(resolve(null));
        self::assertNull(new PSR16Adapter($client)->get($key));
    }

    #[Test]
    public function set(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('set')->with($key, $value, null)->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->set($key, $value));
    }

    #[Test]
    public function setTtl(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->shouldReceive('set')->with($key, $value, $ttl)->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->set($key, $value, $ttl));
    }

    #[Test]
    public function setDateIntervalTtl(): void
    {
        $client          = Mockery::mock(CacheInterface::class);
        $key             = 'key';
        $value           = 'value';
        $dateIntervalTtl = new DateInterval('PT123S');
        $ttl             = 123;
        $client->shouldReceive('set')->with($key, $value, $ttl)->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->set($key, $value, $dateIntervalTtl));
    }

    #[Test]
    public function setTtlException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessageIsOrContains($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->shouldReceive('set')->with($key, $value, $ttl)->andReturn(reject($exception));
        self::assertFalse(new PSR16Adapter($client)->set($key, $value, $ttl));
    }

    #[Test]
    public function setException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessageIsOrContains($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('set')->with($key, $value, null)->andReturn(reject($exception));
        self::assertFalse(new PSR16Adapter($client)->set($key, $value));
    }

    #[Test]
    public function delete(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('delete')->with($key)->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->delete($key));
    }

    #[Test]
    public function deleteException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessageIsOrContains($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('delete')->with($key)->andReturn(reject($exception));
        new PSR16Adapter($client)->delete($key);
    }

    #[Test]
    public function has(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('has')->with($key)->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->has($key));
    }

    #[Test]
    public function deleteMultiple(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('deleteMultiple')->with([$key])->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->deleteMultiple([$key]));
    }

    #[Test]
    public function deleteMultipleException(): void
    {
        $exception = new Exception('fail!');
        self::expectException($exception::class);
        self::expectExceptionMessageIsOrContains($exception->getMessage());

        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $client->shouldReceive('deleteMultiple')->with([$key])->andReturn(reject($exception));
        new PSR16Adapter($client)->deleteMultiple([$key]);
    }

    #[Test]
    public function cLear(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $client->shouldReceive('clear')->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->clear());
    }

    #[Test]
    public function setMultiple(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $ttl    = 123;
        $client->shouldReceive('setMultiple')->with([$key => $value], $ttl)->andReturn(resolve(true));
        self::assertTrue(new PSR16Adapter($client)->setMultiple([$key => $value], $ttl));
    }

    #[Test]
    public function getMultiple(): void
    {
        $client = Mockery::mock(CacheInterface::class);
        $key    = 'key';
        $value  = 'value';
        $client->shouldReceive('getMultiple')->with([$key], null)->andReturn(resolve([$key => $value]));
        self::assertSame([$key => $value], new PSR16Adapter($client)->getMultiple([$key]));
    }
}
