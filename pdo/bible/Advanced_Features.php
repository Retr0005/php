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
 * - Callbacks
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
            throw new \Exception("file '{$file}' does not exist");
        }
        $this -> xml = simplexml_load_file($file);
    }

    public function write(): void {
        if (! is_writable($this -> file)) {
            throw new \Exception("file '{$this -> file}' is not writeable");
        }
        print "{$this -> file} is apparently writeable <br>";
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