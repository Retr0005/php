<?php
/*
 * In this chapter
 * - Static methods and properties
 * - Abstract classes and interfaces
 * - Traits
 * - Error Handling
 * - Final classes and methods
 * - Interceptor methods
 * - Destructor methods
 * - Cloning methods
 * - Resolving objects to strings
 * - Callbacks & Closures
 */

class StaticExample {
    public static int $aNum = 0;
    public static function sayHello(): void {
        print "hello";
    }
}

class StaticExample2 {
    public static int $aNum = 0;
    public static function sayHello(): void {
        self::$aNum++;
        print "hello (" . self::$aNum . ")\n";
    }
}

abstract class DomainObject {

    private string $group;

    public function __construct() {
        $this -> group = static::getGroup();
    }
    public static function create(): DomainObject {
        return new static();
    }

    public static function getGroup(): string {
        return "default";
    }
    // TODO
}

class User extends DomainObject {
    // TODO
}

class Document extends DomainObject {
    public static function getGroup(): string
    {
        return "document";
    }
    // TODO
}

class SpreadSheet extends Document {
    // TODO
}

// Error Handling

class Conf {
    private \SimpleXMLElement $xml;
    private \SimpleXMLElement $lastmatch;

    public function __construct(private string $file) {
        if (! file_exists($file)) {
            throw new FileException("file '$file' does not exist");
        }
        $this -> xml = simplexml_load_file($file, null, LIBXML_NOERROR);
        if (! is_object($this -> xml)) {
            throw new XmlException(libxml_get_last_error());
        }
        $matches = $this -> xml -> xpath("/conf");
        if (! count($matches)) {
            throw new ConfException("could not find root element: conf");
        }
    }

    public function write(): void {
        if (! is_writable($this -> file)) {
            throw new FileException("file '{$this -> file}' is not writeable");
        }
        file_put_contents($this -> file, $this -> xml -> asXML());
    }

    public function get(string $str): ?string {
        $matches = $this -> xml -> xpath("/conf/item[@name=\"$str\"]");
        if (count($matches)) {
            $this -> lastmatch = $matches[0];
            return (string)$matches[0];
        }
        return null;
    }

    public function set(string $key, string $value): void {
        if (! is_null($this -> get($key))) {
            $this -> lastmatch[0] = $value;
            return;
        }
        $conf = $this -> xml -> conf;
        $this -> xml -> addChild('item', $value) -> addAttribute('name', $key);
    }
}

// Subclassing Exception

class XmlException extends \Exception {
    public function __construct(private \LibXMLError $error) {
        $shortfile = basename($error -> file);
        $msg = "[{$shortfile}, line {$error -> line}, col {$error -> column}] {$error -> message}";
        $this -> error = $error;
        parent::__construct($msg, $error -> code);
    }
}

class FileException extends \Exception {
    // TODO
}

class ConfException extends \Exception {
    // TODO
}

class Runner {
    public static function init() {
        try {
            $fh = fopen("/tmp/log.txt", "a"); fputs($fh, "start\n");
            $conf = new Conf(dirname( __DIR__ ) . "/conf.broken.xml");
            print "user: " . $conf -> get('user') . "<br>";
            print "host: " . $conf -> get('host') . "<br>";
            $conf -> set("pass", "newpass");
            $conf -> write();
        } catch (FileException $e) {
            // permissions issue or non-existent file throw $e;
            fputs($fh, "file exception\n");
            throw $e;
        } catch (XmlException $e) {
            // broken xml
            fputs($fh, "xml exception\n");
        } catch (ConfException $e) {
            // wrong kind of xml file
            fputs($fh, "conf exception\n");
        } catch (\Exception $e) {
            // backstop: should not be called
            fputs($fh, "general exception\n");
        } finally {
            fputs($fh, "end\n");
            fclose($fh);
        }
    }
}

// Final keyword => a final class cannot be subclassed, a final method cannot be overwritten

class Checkout {
    final public function totalize(): void {
        // calculate bill
    }
    // TODO
}

// Testing it out

print_r(Document::create());
print "<br>";
print_r(User::create());
print "<br>";
print_r(SpreadSheet::create());
print "<br>";

$xml = new Conf("../example.xml");
$element = $xml -> get("user");
print $element;

try {
    $conf = new Conf("../example.xml");
    print "user: " . $conf -> get('user') . "<br>";
    print "host: " . $conf -> get('host') . "<br>";
    $conf -> set("pass", "newpass");
    $conf -> write();
} catch (\Exception $e) {
    // TODO handle error
    // or
    //throw new \Exception("Conf error: " . $e -> getMessage());
    throw $e;
}

// Interceptor methods

class Person {

    private ?string $myname;
    private ?int $myage;
    private int $id;

    public function __construct(
        protected string $name,
        private int $age,
        public Account $account
    )
    {
        $this -> name = $name;
        $this -> age = $age;
    }

    public function setId(int $id): void {
        $this -> id = $id;
    }

    public function getId(): int {
        return $this -> id;
    }
    public function __clone(): void {
        $this -> id = 0;
        // If I don't want the clones to share the same Account object
        $this -> account = clone $this -> account;
    }

    public function __destruct() {
        if (! empty($this -> id)) {
            // save Person data
            print "saving person <br>";
        }
    }

    public function __call(string $method, array $args): mixed {
        if (method_exists($this -> writer, $method)) {
            return $this -> writer -> $method($this);
        }
        return null;
    }

    public function __set(string $property, mixed $value): void {
        $method = "set{$property}";
        if (method_exists($this, $method)) {
            $this -> $method($value);
        }
    }

    public function setName(?string $name): void {
        $this -> myname = $name;
        if (! is_null($name)) {
            $this -> myname = strtoupper($this -> myname);
        }
    }

    public function setAge(?int $age): void {
        $this -> myage = $age;
    }
    public function __get(string $property): mixed {
        $method = "get{$property}";
        if (method_exists($this, $method)) {
            return $this -> $method();
        }
        return null;
    }

    public function __isset(string $property): bool {
        $method = "get{$property}";
        return (method_exists($this, $method));
    }

    public function getName(): string {
        return "Bob";
    }

    public function getAge(): string {
        return 44;
    }

    public function __unset(string $property): void {
        $method = "set{$property}";
        if (method_exists($this, $method)) {
            $this -> $method(null);
        }
    }

    public function __toString(): string {
        $desc = $this -> getName() . " (age: ";
        $desc .= $this -> getAge() . ", account balance: ";
        $desc .= $this -> account -> balance . ")<br>";
        return $desc;
    }

    public static function printThing(string|\Stringable $str): void {
        print $str;
    }
}

class Account {
    public function __construct(public float $balance) {
        // TODO,
    }
}

class PersonWriter {
    public function writeName(Person $p): void {
        print $p -> getName() . "<br>";
    }

    public function writeAge(Person $p): void {
        print $p -> getAge() . "<br>";
    }
}

class Address {
    private string $number;
    private string $street;

    public function __construct(string $maybenumber, string $maybestreet = null) {
        if (is_null($maybestreet)) {
            $this -> streetaddress = $maybenumber;
        } else {
            $this -> number = $maybenumber;
            $this -> street = $maybestreet;
        }
    }

    public function __set(string $property, mixed $value): void {
        if ($property === "streetaddress") {
            if (preg_match("/^(\d+.*?)[\s,]+(.+)$/", $value, $matches)) {
                $this -> number = $matches[1];
                $this -> street = $matches[2];
            } else {
                throw new \Exception("unable to parse street address:
                '{$value}'");
            }
        }
    }

    public function __get(string $property): mixed {
        if ($property === "streetaddress") {
            return $this -> number . " " . $this -> street;
        }
        return null;
    }
}

// Copying Objects

class CopyMe {}

// eval statement
try{
    print "eval('bad code')";
   // TODO
} catch (\Error $e) {
    print get_class($e) . "<br>";
    print $e -> getMessage();
} catch (\Exception $e) {
    // TODO
}

// THE REAL DEAL
class Product {
    public function __construct(public string $name, public float $price) {
        // TODO
    }
}

class ProcessSale {
    private array $callbacks;

    public function registerCallback(callable $callback): void {
        $this -> callbacks[] = $callback;
    }

    public function sale(Product $product): void {
        print "{$product -> name}: processing <br>";
        foreach ($this -> callbacks as $callback) {
            call_user_func($callback, $product);
        }
    }
}
print "<br>";

//$p = new Person(new PersonWriter());
//print $p -> name . "<br>";
//
//if (isset($p -> name)) {
//    print $p -> name;
//}
//$p = new Person(new PersonWriter());
//$p -> name = "bob";
//// the $myname property becomes 'BOB'
//$person = new Person(new PersonWriter());
//$person -> writeName();
//$address = new Address("441b Bakers Street"); print_r($address);

// __destruct() is called whenever the unset() method is called or when no further refecences to the object are made

$person = new Person("bob", 44, new Account(200));
$person -> setId(434);
$person2 = clone $person;

print "person 1's id: " . $person -> id . "<br>";
print "person 2's id: " . $person2 -> id . "<br>";

$person -> account -> balance += 10;
// $person2 sees the credit too
print $person2 -> account -> balance . "<br>";
print $person;

// callbacks

//$logger = function ($product) {
//    print "logging ({$product -> name})<br>";
//};
$logger = fn($product) => print "logging ({$product -> name}) <br>";

$processor = new ProcessSale();
$processor -> registerCallback($logger);

$processor -> sale(new Product("shoes", 6));
print "<br>";
$processor -> sale(new Product("coffee", 6));

class Mailer {
    public function doMail(Product $product): void {
        print "mailing ({$product -> name}) <br>";
    }
}
$processor = new ProcessSale();
$processor -> registerCallback([new Mailer(), "doMail"]);

$processor -> sale(new Product("shoes", 6));
print "<br>";
$processor -> sale(new Product("coffee", 6));

// Method returning an anonymous function

class Totalizer {
    public static function warnAmount(): callable
    {
        return function (Product $product) {
            if ($product->price > 5) {
                print "reached high price: {$product -> price} <br>";
            }
        };
    }
}

class Totalizer2 {
    public static function warnAmount($amt): callable {
        $count = 0;
        return function ($product) use ($amt, &$count) {
            $count += $product -> price;
            print "count: $count <br>";
            if ($count > $amt) {
                print "high price reached: {$count} <br>";
            }
        };
    }
}

class Totalizer3 {
    private float $count = 0;
    private float $amt = 0;

    public function warnAmount(int $amt): callable {
        $this -> amt = $amt;
        return \Closure::fromCallable([$this, "processPrice"]);
    }

    public function processPrice(Product $product): void {
        $this -> count += $product -> price;
        print "count: {$this -> count} <br>";
        if ($this -> count > $this -> amt) {
            print "high price reached: {$this -> count} <br>";
        }
    }
}

$processor = new ProcessSale();
$processor -> registerCallback(Totalizer::warnAmount());

$processor -> sale(new Product("shoes", 6));
print "<br>";
$processor -> sale(new Product("coffee", 6));

$processor = new ProcessSale();
$processor -> registerCallback(Totalizer2::warnAmount(8));
$processor -> sale(new Product("shoes", 6));
print "<br>";
$processor -> sale(new Product("coffee", 6));

$markup = 4;
$counter = fn(Product $product) => print "($product -> name) marked up price: "
    . ($product -> price + $markup) . "<br>";
$processor = new ProcessSale();
$processor -> registerCallback($counter);

$processor -> sale(new Product("shoes", 6));
print "<br>";
$processor -> sale(new Product("coffee", 6));
