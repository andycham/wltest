<?php
//--------------
// UNIT TESTS 
//--------------
namespace andycham\wltest;
require_once __DIR__ . '/../src/ProductFactory.php';

use DOMDocument;
use DOMXPath;

use PHPUnit\Framework\TestCase;

class ProductFactoryTest extends TestCase{

  // Test GetTitle function.
  public function testGetTitle(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get First Element
    $element = $elements[0];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $title = $productFactory->getTitle();    
    $this->assertEquals('Basic: 500MB Data - 12 Months', $title);
  }

  
  // Test GetDescription function.
  public function testGetDescription(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get First Element
    $element = $elements[0];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $description = $productFactory->getDescription();    
    $this->assertEquals('Up to 500MB of data per monthincluding 20 SMS(5p / MB data and 4p / SMS thereafter)', $description);
  }


  // Test GetPrice function.
  public function testGetPrice(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get First Element
    $element = $elements[0];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $price = $productFactory->GetPrice();    
    $this->assertEquals(5.99, $price);
  }



  // Test GetDiscount function.
  public function testGetDiscount(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get Fifth Element
    $element = $elements[4];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $discount = $productFactory->getDiscount();    
    $this->assertEquals(11.9, $discount);
  }


  // Test isMonthlyPackage function.
  public function testIsMonthlyPackage(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get First Element
    $element = $elements[0];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $isMonthlyPackage = $productFactory->isMonthlyPackage();    
    $this->assertEquals(true, $isMonthlyPackage);
  }

  
  // Test xpathValue function.
  public function testXpathValue(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);
    
    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get First Element
    $element = $elements[0];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $xpathValue = $productFactory->xpathValue('.//div[@class="package-price"]');    
    $this->assertEquals('£5.99(inc. VAT)Per Month', $xpathValue);
  }


  // Test create function returns a product
  public function testCreate(){
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);
    
    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');

    // Get First Element
    $element = $elements[0];    

    $productFactory = new ProductFactory();
    $productFactory->element = $element;
    $productFactory->xpath = new DOMXPath($doc);

    $product = $productFactory->create($element, $doc);    
    $this->assertIsObject($product);
  }

}
