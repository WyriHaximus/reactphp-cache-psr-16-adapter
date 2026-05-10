<?php

declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use DateInterval;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use React\Cache\CacheInterface;
use Safe\DateTimeImmutable;

use function BenTools\IterableFunctions\iterable_to_array;
use function React\Async\await;

/** @api */
final readonly class PSR16Adapter implements SimpleCacheInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage,ergebnis.noParameterWithNullDefaultValue
     */
    public function get(string $key, mixed $default = null)
    {
        return await($this->cache->get($key, $default));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage,ergebnis.noParameterWithNullableTypeDeclaration,ergebnis.noParameterWithNullDefaultValue
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null)
    {
        return await($this->cache->set($key, $value, $this->convertToSeconds($ttl)));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage
     */
    public function delete(string $key)
    {
        return await($this->cache->delete($key));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage
     */
    public function clear()
    {
        return await($this->cache->clear());
    }

    /**
     * @inheritDoc
     * @phpstan-ignore ergebnis.noParameterWithNullDefaultValue
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        /** @phpstan-ignore argument.templateType,argument.type,return.type */
        return await($this->cache->getMultiple(iterable_to_array($keys), $default));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage,ergebnis.noParameterWithNullableTypeDeclaration,missingType.iterableValue,ergebnis.noParameterWithNullDefaultValue
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null)
    {
        /** @phpstan-ignore argument.templateType,argument.type */
        return await($this->cache->setMultiple(iterable_to_array($values), $this->convertToSeconds($ttl)));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage
     */
    public function deleteMultiple(iterable $keys)
    {
        /** @phpstan-ignore argument.templateType,argument.type */
        return await($this->cache->deleteMultiple(iterable_to_array($keys)));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore shipmonk.missingNativeReturnTypehint,typeCoverage.returnTypeCoverage
     */
    public function has(string $key)
    {
        return await($this->cache->has($key));
    }

    /** @phpstan-ignore ergebnis.noParameterWithNullableTypeDeclaration */
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
