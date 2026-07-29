<?php

declare(strict_types=1);

use Everully\LaravelEnumOnSteroids\Tests\Support\AnotherStringEnum;
use Everully\LaravelEnumOnSteroids\Tests\Support\IntegerEnum;
use Everully\LaravelEnumOnSteroids\Tests\Support\StringEnum;
use Illuminate\Support\Collection;

it('determines equality with various objects and strings', function () {
    // Arrange & Act & Assert
    expect(StringEnum::A)
        ->equals(StringEnum::A)->toBeTrue()
        ->and(StringEnum::A->equals('a'))->toBeTrue()
        ->and(StringEnum::A->equals(StringEnum::B))->toBeFalse()
        ->and(StringEnum::A->equals(AnotherStringEnum::A))->toBeFalse()
        ->and(StringEnum::A->equals('b'))->toBeFalse();
});

it('returns all enum values as an array', function () {
    // Act & Assert
    expect(StringEnum::values())
        ->toBe(['a', 'b', 'c']);
});

it('returns all enum names as an array', function () {
    // Act & Assert
    expect(StringEnum::names())
        ->toBe(['A', 'B', 'C']);
});

it('creates a collection containing all enum instances', function () {
    // Act
    $collection = StringEnum::collection();

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toHaveCount(3)
        ->and($collection->contains(StringEnum::A))->toBeTrue()
        ->and($collection->contains(StringEnum::B))->toBeTrue()
        ->and($collection->contains(StringEnum::C))->toBeTrue();
});

it('collects valid enum instances from an array', function () {
    // Act
    $collection = StringEnum::collect([StringEnum::A, StringEnum::B]);

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toHaveCount(2)
        ->and($collection->contains(StringEnum::A))->toBeTrue()
        ->and($collection->contains(StringEnum::B))->toBeTrue();
});

it('excludes invalid enum instances during collection', function () {
    // Act
    $collection = StringEnum::collect([StringEnum::A, IntegerEnum::B]);

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toHaveCount(1)
        ->and($collection->contains(StringEnum::A))->toBeTrue();
});

it('returns an empty collection when no valid enums are provided', function () {
    // Act
    $collection = StringEnum::collect([IntegerEnum::B]);

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toBeEmpty();
});

it('collects valid enum instances from strings', function () {
    // Act
    $collection = StringEnum::collect(['a', 'b']);

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toHaveCount(2)
        ->and($collection->contains(StringEnum::A))->toBeTrue()
        ->and($collection->contains(StringEnum::B))->toBeTrue();
});

it('excludes invalid strings during collection', function () {
    // Act
    $collection = StringEnum::collect(['a', 'b', 'e']);

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toHaveCount(2)
        ->and($collection->contains(StringEnum::A))->toBeTrue()
        ->and($collection->contains(StringEnum::B))->toBeTrue();
});

it('returns an empty collection when no valid strings are provided', function () {
    // Act
    $collection = StringEnum::collect(['invalid']);

    // Assert
    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->and($collection)->toBeEmpty();
});

it('verifies if enum contains a specific string or object', function () {
    // Act & Assert
    expect(StringEnum::has('a'))->toBeTrue()
        ->and(StringEnum::has(StringEnum::A))->toBeTrue()
        ->and(StringEnum::has('invalid'))->toBeFalse()
        ->and(StringEnum::has(AnotherStringEnum::A))->toBeFalse();
});

it('verifies if enum contains any of the provided strings or objects', function () {
    // Act & Assert
    expect(StringEnum::hasAny(['a', 'invalid']))->toBeTrue()
        ->and(StringEnum::hasAny([StringEnum::A, AnotherStringEnum::A]))->toBeTrue()
        ->and(StringEnum::hasAny(['invalid', 'invalid2']))->toBeFalse()
        ->and(StringEnum::hasAny([AnotherStringEnum::A, AnotherStringEnum::B]))->toBeFalse();
});

it('verifies if enum contains all of the provided strings or objects', function () {
    // Act & Assert
    expect(StringEnum::hasAll(['a', 'b']))->toBeTrue()
        ->and(StringEnum::hasAll([StringEnum::A, StringEnum::B]))->toBeTrue()
        ->and(StringEnum::hasAll(['a', 'invalid']))->toBeFalse()
        ->and(StringEnum::hasAll(['invalid', 'invalid2']))->toBeFalse()
        ->and(StringEnum::hasAll([StringEnum::A, AnotherStringEnum::A]))->toBeFalse()
        ->and(StringEnum::hasAll([AnotherStringEnum::A, AnotherStringEnum::B]))->toBeFalse();
});

it('checks if an enum instance is among the provided values', function () {
    // Act & Assert
    expect(StringEnum::A)
        ->oneOf(StringEnum::A)->toBeTrue()
        ->oneOf([StringEnum::A, StringEnum::B])->toBeTrue()
        ->oneOf('a')->toBeTrue()
        ->oneOf(['a', 'b'])->toBeTrue()
        ->oneOf(StringEnum::B)->toBeFalse()
        ->oneOf([StringEnum::B, StringEnum::C])->toBeFalse()
        ->oneOf('b')->toBeFalse()
        ->oneOf(['b', 'c'])->toBeFalse();
});

it('retrieves an enum instance or defaults when invalid', function () {
    // Act & Assert
    expect(StringEnum::fromOrDefault('a', StringEnum::B))
        ->toBe(StringEnum::A);

    expect(StringEnum::fromOrDefault('some', StringEnum::B))
        ->toBe(StringEnum::B);

    expect(StringEnum::fromOrDefault('some'))->toBeNull();

    expect(StringEnum::fromOrDefault('some', null))->toBeNull();
});

// todo StringEnum::random()
// todo StringEnum::randomArray(maxItems: int)
// todo StringEnum::randomCollection(maxItems: int)
