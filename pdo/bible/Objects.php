<?php

// listing 03.04

// Working with inheritance
class ShopProduct implements Chargeable, IdentityObject {

    use PriceUtilities;
    use IdentityTrait;
    use TaxTools {
        TaxTools::calculateTax insteadof PriceUtilities;
        PriceUtilities::calculateTax as basicTax;
    }
    public const AVAILABLE = 0;
    public const OUT_OF_STOCK = 1;
    private int $id = 0;
    private int|float $discount = 0;

    public function __construct(
        private string $title,
        private string $producerFirstName,
        private string $producerMainName,
        protected int|float $price
    ) {
    }

    public function getTaxRate(): float {
        return 20;
    }
    public function setID(int $id): void {
        $this -> id = $id;
    }

    public static function getInstance(int $id, \PDO $pdo): ?ShopProduct {
        $stmt = $pdo -> prepare("select * from products where id=?");
        $result = $stmt -> execute([$id]);
        $row = $stmt -> fetch();

        if (empty($row)) {
            return null;
        }

        if ($row['type'] == "book") {
            $product = new BookProduct(
                $row['title'],
                $row['firstname'],
                $row['mainname'],
                (float) $row['price'],
                (int) $row['numpages']
            );
        } elseif ($row['type'] == "cd") {
            $product = new CdProduct(
                $row['title'],
                $row['firstname'],
                $row['mainname'],
                (float) $row['price'],
                (int) $row['playlength']
            );
        } else {
            $firstname = (is_null($row['firstname'])) ? "" : $row['firstname'];
            $product = new ShopProduct(
                $row['title'],
                $firstname,
                $row['mainname'],
                (float) $row['price']
            );
        }
        $product -> setID((int) $row['id']);
        $product -> setDiscount((int) $row['discount']);
        return $product;
    }

    public function getProducerFirstName(): string {
        return $this -> producerFirstName;
    }

    public function getProducerMainName(): string {
        return $this -> producerMainName;
    }

    public function setDiscount(int|float $num): void {
        $this -> discount = $num;
    }

    public function getDiscount(): int|float {
        return $this -> discount;
    }

    public function getTitle(): string {
        return $this -> title;
    }

    public function getPrice(): int|float {
        return ($this -> price - $this -> discount);
    }

    public function getProducer(): string {
        return $this -> producerFirstName . " "
            . $this -> producerMainName;
    }

    public function getSummaryLine(): string {
        $base = "{$this -> title} ( {$this -> producerMainName}, ";
        $base .= "{$this -> producerFirstName} )";
        return $base;
    }
}

class CdProduct extends ShopProduct {
    public function __construct(
        string $title,
        string $firstName,
        string $mainName,
        int|float $price,
        private int $playLength
    )
    {
        parent::__construct(
            $title,
            $firstName,
            $mainName,
            $price
        );
    }

    public function getPlayLength(): int {
        return $this -> playLength;
    }
    public function getSummaryLine(): string {
        $base = parent::getSummaryLine();
        $base .= ": playing time - {$this -> playLength}";
        return $base;
    }
}

class BookProduct extends ShopProduct {
    public function __construct(
        string $title,
        string $firstName,
        string $mainName,
        float $price,
        private int $numPages
    ) {
        parent:: __construct(
            $title,
            $firstName,
            $mainName,
            $price
        );
    }

    public function getNumberOfPages(): int {
        return $this -> numPages;
    }
    public function getSummaryLine(): string {
        $base = parent::getSummaryLine();
        $base .= ": page count - {$this -> numPages}";
        return $base;
    }

}
/*
//$product1 = new ShopProduct();
//print $product1 -> title;
//print "<br>";

// I can assign new values, changing the default ones
//$product2 = new ShopProduct();
//$product3 = new ShopProduct();

//$product2 -> title = "My Antonia";
//$product3 -> title = "Catch 22";

// I can add more properties than the ones specified in the class
//$product2 -> arbitraryAddition = "treehouse";

//$product1 = new ShopProduct();

//$product1 -> title = "My Antonia";
//$product1 -> producerMainName = "Cather";
//$product1 -> producerFirstName = "Willa";
//$product1 -> price = 5.99;

// Accessing data

//print "author: {$product1 -> producerFirstName}"
//    . "{$product1 -> producerMainName}<br>";

// Assigning data like this is not best practice
// Even if I mispelled some attribute names, the code would be still fine

//print "author: {$product1 -> getProducer()}<br>";

// Calling the constructor
*/

abstract class Service {
    // TODO
}
trait PriceUtilities {

    private static $taxrate = 20;
    public static function calculateTax(float $price): float {
        return ((self::$taxrate / 100) * $price);
    }

    abstract public function getTaxRate(): float;
    // TODO other utilities
}

trait IdentityTrait {
    public function generateId(): string {
        return uniqid();
    }
}

trait TaxTools {
    public function calculateTax(float $price): float {
        return 222;
    }
}

interface IdentityObject {
    public function generateId(): string;
}

class UtilityService extends Service {

    use PriceUtilities {
        PriceUtilities::calculateTax as private;
    }

    public function __construct(private float $price) {
        // TODO
    }

    public function getTaxRate(): float {
        return 2434;
    }

    public function getFinalPrice(): float {
        return ($this -> price + $this -> calculateTax($this -> price));
    }
}

$product1 = new ShopProduct(
    "My Antonia",
    "Willa",
    "Cather", 5.99
);

print "author: {$product1 -> getProducer()}<br>";

// Default Arguments and Named Arguments

//$product2 = new ShopProduct(
//    title: "Shop Catalogue",
//    price: 0.8
//);
// print "title: {$product2 -> title}<br>";

// Primitive Types and Checking Functions in PHP
/*
 * is_bool()
 * is_integer()
 * is_float()
 * is_string()
 * is_object()
 * is_resource()
 * is_array()
 * is_null()
 */

class AddressManager {
    private $addresses = ["209.131.24.242", "216.58.213.174"];

    /**
     * Outputs the list of addresses
     * @param $resolve boolean Resolve the address?
     */
    public function outputAddresses($resolve) {
        //if (is_string($resolve)) {
        //    $resolve = (preg_match("/^(false|no|off)$/i", $resolve)) ? false : true;
        //}
        foreach ($this -> addresses as $address) {
            print $address;
            if ($resolve) {
                print " (" . gethostbyaddr($address) . ")";
            }
            print "<br>";
        }
    }
}

$settings = simplexml_load_file(__DIR__ . "/resolve.xml");
$manager = new AddressManager();
$manager -> outputAddresses((string)$settings -> resolvedomains);

/*
 * Pseudo-type-checking Functions
 * is_countable()
 * is_iterable()
 * is_callable()
 * is_numeric()
 */

// Object Types

abstract class ShopProductWriter {
    protected array $products = [];

    public function addProduct(ShopProduct $shopProduct): void {
        $this ->products[] = $shopProduct;
    }
    abstract public function write(): void;
}

// Cannot create a child class of ShopProductWriter without implementing its abstract method/s
class ErroredWriter extends ShopProductWriter {
    public function write(): void
    {
        // TODO: Implement write() method.
    }
}

class XmlProductWriter extends ShopProductWriter {
    public function write(): void {
        $writer = new \XMLWriter();
        $writer -> openMemory();
        $writer -> startDocument('1.0', 'UTF-8');
        $writer -> startElement("products");
        foreach ($this -> products as $shopProduct) {
            $writer -> startElement("product");
            $writer -> writeAttribute("title", $shopProduct -> getTitle());
            $writer -> startElement("summary");
            $writer -> text($shopProduct -> getSummaryLine());
            $writer -> endElement(); // summary
            $writer -> endElement(); // product
        }
        $writer -> endElement(); // products
        $writer -> endDocument();
        print $writer -> flush();
    }
}

class TextProductWriter extends ShopProductWriter {
    public function write(): void {
        $str = "PRODUCTS:\n";
        foreach ($this -> products as $shopProduct) {
            $str .= $shopProduct -> getSummaryLine(). "\n";
        }
        print $str;
    }
}

// Interfaces -> pure templates, define functionality

interface Chargeable {
    public function getPrice(): int|float;
}

class Shipping implements Chargeable {
    public function __construct(private float $price) {
        // TODO
    }

    public function getPrice(): float {
        return $this -> price;
    }

}

// testing it out

//$product1 = new ShopProduct("My Antonia", "Willa", "Cather", 5.99);
//$writer = new ShopProductWriter();
//$writer -> write($product1);

class ConfReader {
    public function getValues(array $default = []) {
        $values = [];

        // do something to get values

        // merge the provided defaults (it will always be an array)
        $values = array_merge($default, $values);
        return $values;
    }
}

// Union Types

class Storage {
    private $playLength;
    private $discount;
    private $price;

    public function add(string $key, $value) {
        if (! is_bool($value) && ! is_string($value)) {
            error_log("value must be string or Boolean - givenS " .
            gettype($value));
            return false; // better off trowing an exception
        }
    }

    public function better_add(string $key, string|bool|null $value) {}

    public function setShopProduct(ShopProduct|null $product) {}

    // Nullable Types
    public function add_v3(string $key, ?string $value) {}
    
    // Return Type Declarations
    public function getPlayLength(): int
    {
        return $this -> playLength;
    }

    public function getPrice(): int|float
    {
        return ($this -> price - $this -> discount);
    }

    public function setDiscount(int|float $num): void {
        $this -> discount = $num;
    }
}
/*
//$product1 = new ShopProduct("My Antonia", "Willa", "Cather", 5.99);
//$product2 = new ShopProduct(
//    "Exile on Coldharbour Lane", "The",
//    "Alabama 3",
//    10.99
//);
//print "author: " . $product1 -> getProducer() . "<br>";
//print "artist: " . $product2 -> getProducer() . "<br>";
*/
// 03.60
$product2 = new CdProduct(
    "Exile on Coldharbour Lane",
    "The",
    "Alabama 3",
    10.99,
    0,
    60.33
);
print "artist: {$product2 -> getProducer()} <br>";

//$dsn = "sqlite:/tmp/products.sqlite3";
//$pdo = new \PDO($dsn, null, null);
//$pdo -> setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
//$obj = ShopProduct::getInstance(1, $pdo);

print ShopProduct::AVAILABLE;
print ShopProduct::OUT_OF_STOCK;
print "<br>";

$p = new ShopProduct(
    "Fuck me",
    "Patrick",
    "Bateman",
    20.10,
);
print $p -> calculatetax(100) . "<br>";
print $p -> generateId() . "<br>";

$u = new UtilityService();
//print $u -> calculatetax(100) . "<br>";

//print UtilityService::calculateTax(100) . "<br>";





