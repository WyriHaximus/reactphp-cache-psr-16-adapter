<?php

declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use DateInterval;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use React\Cache\CacheInterface;
use Safe\DateTimeImmutable;

use function BenTools\IterableFunctions\iterable_to_array;
use function React\Async\await;

final class PSR16Adapter implements SimpleCacheInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function get(string $key, mixed $default = null)
    {
        return await($this->cache->get($key, $default));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null)
    {
        return await($this->cache->set($key, $value, $this->convertToSeconds($ttl)));
    }

    /** @inheritDoc */
    public function delete(string $key)
    {
        return await($this->cache->delete($key));
    }

    /** @inheritDoc */
    public function clear()
    {
        return await($this->cache->clear());
    }

    /**
     * @inheritDoc
     * @psalm-suppress InvalidReturnType
     * @phpstan-ignore-next-line
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        /**
         * @psalm-suppress InvalidReturnStatement
         * @psalm-suppress MixedArgumentTypeCoercion
         * @phpstan-ignore-next-line
         */
        return await($this->cache->getMultiple(iterable_to_array($keys), $default));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null)
    {
        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         * @phpstan-ignore-next-line
         */
        return await($this->cache->setMultiple(iterable_to_array($values), $this->convertToSeconds($ttl)));
    }

    /** @inheritDoc */
    public function deleteMultiple(iterable $keys)
    {
        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         * @phpstan-ignore-next-line
         */
        return await($this->cache->deleteMultiple(iterable_to_array($keys)));
    }

    /** @inheritDoc */
    public function has(string $key)
    {
        return await($this->cache->has($key));
    }

    /** @phpstan-ignore-next-line */
    private function convertToSeconds(DateInterval|int|null $ttl): int|null
    {
        if ($ttl instanceof DateInterval) {
            $reference = new DateTimeImmutable();
            $endTime   = $reference->add($ttl);

            return $endTime->getTimestamp() - $reference->getTimestamp();
        }

        return $ttl;
    }
}
