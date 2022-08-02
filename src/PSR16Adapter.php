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
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function get(string $key, mixed $default = null): mixed
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return await($this->cache->get($key, $default));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        /**
         * @var bool
         * @psalm-suppress TooManyTemplateParams
         */
        return await($this->cache->set($key, $value, $this->convertToSeconds($ttl)));
    }

    public function delete(string $key): bool
    {
        /**
         * @var bool
         * @psalm-suppress TooManyTemplateParams
         */
        return await($this->cache->delete($key));
    }

    public function clear(): bool
    {
        /**
         * @var bool
         * @psalm-suppress TooManyTemplateParams
         */
        return await($this->cache->clear());
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        /**
         * @var iterable<string, mixed>
         * @phpstan-ignore-next-line
         * @psalm-suppress TooManyTemplateParams
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        return await($this->cache->getMultiple(iterable_to_array($keys), $default));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        /**
         * @var bool
         * @psalm-suppress TooManyTemplateParams
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        return await($this->cache->setMultiple(iterable_to_array($values), $this->convertToSeconds($ttl)));
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple(iterable $keys): bool
    {
        /**
         * @var bool
         * @phpstan-ignore-next-line
         * @psalm-suppress TooManyTemplateParams
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        return await($this->cache->deleteMultiple(iterable_to_array($keys)));
    }

    public function has(string $key): bool
    {
        /**
         * @var bool
         * @psalm-suppress TooManyTemplateParams
         */
        return await($this->cache->has($key));
    }

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
