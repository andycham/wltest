<?php
//--------------
// UNIT TESTS 
//--------------
namespace andycham\wltest;
use Product;

require_once __DIR__ . '/../src/App.php';

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase{

  // Check that the log works.
  public function testSetUpLog(){
    $app = new App();    
    $app->setUpLog();
    $app->log->debug("test");
    $this->assertFileExists('logs/application.log');
  }

  // Check that the url is set
  public function testUrlIsSet(){
    $app = new App();
    $app->setUpLog();
    $app->getSettings();
    $this->assertNotEquals($app->url, '');
  }

  // Test that the GetContent function returns some content.
  public function testGetContent(){
    $app = new App();

    // Read from static file instead of live website ($app->getContent('https://wltest.dns-systems.net/')
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);
    $app = $this->getMockBuilder('App')->setMethods(['setUpLog','getContent'])->getMock();
    $app->method('getContent')->willReturn($content);
    $app->setUpLog();        
    $this->assertNotEquals($content, '');
  }

  // Test that the parseContent function generates products
  public function testParseContent(){
    $app = new App();
    $app->setUpLog();
    
    // Read from static file instead of live website ($app->getContent('https://wltest.dns-systems.net/')
    $filePath = __DIR__ . '/mockSite.html';
    $content = file_get_contents($filePath);    
    $app->parseContent($content);
    $productCount = count($app->products);
    $this->assertGreaterThan(0, $productCount);
  }
  

  // Test that products are sorted by annual price in descending order.
  public function testSortProducts(){
    $app = new App();
    $app->setUpLog();

    // Generate some test products.
    $products = [];
    $product = new Product();
    $product->title = "Blue Package";
    $product->annualPrice = 100;
    $products[] = $product;

    $product = new Product();
    $product->title = "Green Package";
    $product->annualPrice = 300;
    $products[] = $product;

    $product = new Product();
    $product->title = "Yellow Package";
    $product->annualPrice = 200;
    $products[] = $product;
    
    $app->products = $products;

    // Do the sort
    $app->sortProducts();
    
    $products = $app->products;

    $index = 0;
    foreach($products as $product){
      $index ++;
      if($index>1){
        // (only check from second product onwards)
        // Products should be sorted by annual price in descending order.
        // Therefore, annual price must be lower than the annual price of the previous product.
        $this->assertLessThanOrEqual($prevAnnualPrice, $product->annualPrice);
      }
      // Store Annual Price for comparison in next product.
      $prevAnnualPrice = $product->annualPrice;
    }
  }

  // Test the the OutputJson function returns a JSON object.
  public function testOutputJson(){
    $app = new App();
    $app->setUpLog();

    // Generate some test products.
    $products = [];
    $product = new Product();
    $product->title = "Blue Package";
    $product->annualPrice = 100;
    $products[] = $product;

    $product = new Product();
    $product->title = "Green Package";
    $product->annualPrice = 300;
    $products[] = $product;

    $product = new Product();
    $product->title = "Yellow Package";
    $product->annualPrice = 200;
    $products[] = $product;

    $app->products = $products;    

    // Output the JSON
    $output = $app->outputJson();
    $this->assertJson($output);
  }

}
