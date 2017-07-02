# jkphl/elevator

> Elevator pattern implementation for PHP projects

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
