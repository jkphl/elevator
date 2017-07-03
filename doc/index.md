# jkphl/elevator

[![Build Status][travis-image]][travis-url] [![Coverage Status][coveralls-image]][coveralls-url] [![Scrutinizer Code Quality][scrutinizer-image]][scrutinizer-url] [![Code Climate][codeclimate-image]][codeclimate-url] [![Documentation Status][readthedocs-image]][readthedocs-url] [![Clear architecture][clear-architecture-image]][clear-architecture-url]

> Elevator pattern — type casting of user defined objects in PHP

PHP offers no built-in way to cast a user-defined object to another class — probably for good reasons. There are some dirty hacks to achieve similar effects, but in general these are considered harmful.

While building applications following the [Clear Architecture](https://jkphl.is/articles/clear-architecture-php/) I occasionally send value objects with simple methods across layer boundaries. For example, think of a `PersonalName` object holding a `firstname` and a `surname` and returning the full name via its `__toString()` method. In order to not violate the [dependency rule](https://github.com/jkphl/clear-architecture#the-dependency-rule), the classes of such objects need to be declared on the innermost layer where they are being used, e.g. on the domain layer. They could even be useful as public API input parameters or return values, but handing out domain objects to the outer world would violate a couple of principles. This is where the need to "upgrade" these objects arises. For public usage, they should be transformed to classes that are declared in the ports sector of the client layer which is defining the public API.

There are several approaches towards this challenge. You might instantly think of the [Decorator Pattern](https://en.wikipedia.org/wiki/Decorator_pattern), but for some reasons I dislike the use of [magic methods / overloading](http://php.net/manual/en/language.oop5.overloading.php) to forward method calls to the wrapped objects. For example, decorators don't have the same method signatures as their wrapped objects so your IDE won't give you code completion by default. Also, decorators might not have access to all aspects of their wrapped objects (think of protected properties and methods).

## The Elevator Pattern

Fortunately, PHP's [Reflection API](http://php.net/manual/en/book.reflection.php) offers a way to get around the lack of support for true object casting in this particular situation. I call this technique the "**Elevator Pattern**".

![The Elevator Pattern](https://rawgit.com/jkphl/elevator/master/doc/clear-architecture-elevator-pattern.svg)
 
The library proposed here lets you "elevate" an object to an arbitrary subclass of its original class:

```php
use Jkphl\Elevator\Ports\Elevator;

class MyClass
{
    /* ... */
}

class MySubclass extends MyClass
{
    /* ... */
}

$object = new MyClass();

/** @var MySubclass $elevatedObject */
$elevatedObject = Elevator::elevate($object, MySubclass::class);
```

The only requirement for this to work is that `MySubclass` extends `MyClass`. The elevated object will extend the original object (and all its parents) and inherit its properties including protected and private ones (only the `parent` layers will have access to the latter ones obviously).
  
Using the elevator pattern, you can "upgrade" a domain or application layer object on the fly to a port layer variant whenever it passes the outer boundary of the client layer (e.g. as an API return value). Regarding the inwards direction, the ports layer variant is a perfectly valid API input parameter as it's a true descendant of the underlying domain or application layer object.

### Considerations

* Elevated objects are created from scratch and are not identical to their source object. References to the source object are not updated automatically.
* Elevated objects are instantiated without calling their constructor as this could have unwanted side effects. If you want to perform particular actions during elevation, consider use the `__elevate()` magic method as an alternative described below.

## The `__elevate()` magic method

If you want to simulate a constructor during elevation, you can implement the `ElevatorAwareInterface` on your elevation class. The `Elevator` will then call the `__elevate()` magic method during instantiation:

```php
use Jkphl\Elevator\Ports\Elevator;
use Jkphl\Elevator\Ports\ElevatorAwareInterface;

class MyClass
{
    /* ... */
}

class MySubclass extends MyClass implements ElevatorAwareInterface
{
    /**
     * Custom elevation pseudo constructor
     * 
     * @param array ...$args Elevation arguments
     */
    public function __elevate(...$args) {
        /* Do some fancy stuff here after instantiation */    
    }
}

$object = new MyClass();

/** @var MySubclass $elevatedObject */
$elevatedObject = Elevator::elevate($object, MySubclass::class, 'some', 'values');
```

## Installation

This library requires PHP >=5.6 or later. I recommend using the latest available version of PHP as a matter of principle. It has no userland dependencies.

## Dependencies

![Composer dependency graph](https://rawgit.com/jkphl/elevator/master/doc/dependencies.svg)

## Quality

To run the unit tests at the command line, issue `composer install` and then `phpunit` at the package root. This requires [Composer](http://getcomposer.org/) to be available as `composer`, and [PHPUnit](http://phpunit.de/manual/) to be available as `phpunit`.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

## Contributing

Found a bug or have a feature request? [Please have a look at the known issues](https://github.com/jkphl/elevator/issues) first and open a new issue if necessary. Please see [contributing](../CONTRIBUTING.md) and [conduct](../CONDUCT.md) for details.

## Security

If you discover any security related issues, please email joschi@kuphal.net instead of using the issue tracker.

## Credits

- [Joschi Kuphal][author-url]
- [All Contributors](../../contributors)

## License

Copyright © 2017 [Joschi Kuphal][author-url] / joschi@kuphal.net. Licensed under the terms of the [MIT license](../LICENSE).


[travis-image]: https://secure.travis-ci.org/jkphl/elevator.svg
[travis-url]: https://travis-ci.org/jkphl/elevator
[coveralls-image]: https://coveralls.io/repos/jkphl/elevator/badge.svg?branch=master&service=github
[coveralls-url]: https://coveralls.io/github/jkphl/elevator?branch=master
[scrutinizer-image]: https://scrutinizer-ci.com/g/jkphl/elevator/badges/quality-score.png?b=master
[scrutinizer-url]: https://scrutinizer-ci.com/g/jkphl/elevator/?branch=master
[codeclimate-image]: https://lima.codeclimate.com/github/jkphl/elevator/badges/gpa.svg
[codeclimate-url]: https://lima.codeclimate.com/github/jkphl/elevator
[readthedocs-image]: https://readthedocs.org/projects/jkphl-elevator/badge/?version=latest
[readthedocs-url]: http://jkphl-elevator.readthedocs.io/en/latest/?badge=latest
[clear-architecture-image]: https://img.shields.io/badge/Clear%20Architecture-%E2%9C%94-brightgreen.svg
[clear-architecture-url]: https://github.com/jkphl/clear-architecture
[author-url]: https://jkphl.is
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
