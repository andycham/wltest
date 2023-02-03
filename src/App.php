<?php
require_once(__DIR__ .'/../vendor/autoload.php');
require_once(__DIR__ .'/Product.php');

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;

Class App 
{
  public $log;
  public string $url = '';
  public string $content = '';
  public $products=[];

  // -------------------------------------------------------
  // Main Entry Point of App
  // -------------------------------------------------------
  public function main(){
    // Set up log
    $this->setUpLog();
    
    // Get environmental variable settings from .env file.
    $this->getSettings();
    
    $this->log->info('Starting App');
    $this->log->info('Source URL is ' . $this->url);

    // Get raw content from website
    $content = $this->getContent($this->url);

    // Parse content
    $this->parseContent($content);
    
    // Sort products by annual price descending
    $this->sortProducts();

    // Output JSON
    echo($this->outputJson());

    $this->log->info('Exiting App');
  }


  // Load settings from .env file.
  public function getSettings(){
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
    if(isset($_ENV['SOURCE_URL'])){
      $this->url = $_ENV['SOURCE_URL'];
    }    
  }

  public function setUpLog(){
    // Set up logging
    $this->log = new Logger('main');

    // Log to file
    $this->log->pushHandler(new StreamHandler('logs/application.log', Level::Debug));

    // Log to screen
    $this->log->pushHandler(new StreamHandler('php://stdout', Level::Info));
  }

  // Return content from website url
  public function getContent($url){
    $content = file_get_contents($url);
    return $content;
  }

  // Parse website content to get products.
  public function parseContent($content){
    //Suppress any warnings
    libxml_use_internal_errors(true);
    
    $this->log->info("Parsing content");
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $productElements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');
    
    foreach($productElements as $productElement){
      // Now parse each product DOMElement to get the attributes of the product
      $title = $xpath->query('.//h3', $productElement)[0]->textContent;
      $description = $xpath->query('.//div[@class="package-description"]', $productElement)[0]->textContent;
      $price = $xpath->query('.//span[@class="price-big"]', $productElement)[0]->textContent;

      // Clean up price
      $price = str_replace('£', '', $price);

      // Get Discount
      if($xpath->query('.//div[@class="package-price"]/p', $productElement)[0]){
        $discount = $xpath->query('.//div[@class="package-price"]/p', $productElement)[0]->textContent;
        // Clean up the surrounding text to get the discount amount.
        $discount = str_replace('Save £', '', $discount);
        $discount = str_replace(' on the monthly price', '', $discount);
      } else {
        $discount = 0;
      }      

      // Determine whether price is for month or year and set monthlyPrice and annualPrice accordingly
      $price_raw = $xpath->query('.//div[@class="package-price"]', $productElement)[0]->textContent;
      if(strpos($price_raw, 'Month')){
        $monthlyPrice = $price;
        $annualPrice = $price * 12;
      } else {
        $monthlyPrice = $price / 12;
        $annualPrice = $price;
      }

      // Log some debugging info
      $this->log->debug("title = " . $title);
      $this->log->debug("description = " . $description);
      $this->log->debug("price = " . $price);
      $this->log->debug("discount = " . $discount);

      // Create new product
      $product = new Product();
      $product->title = $title;
      $product->description = $description;
      $product->price = $price;
      $product->monthlyPrice = $monthlyPrice;
      $product->annualPrice = $annualPrice;
      $product->price = $price;
      $product->discount = $discount;

      // Add product to products array
      $this->products[] = $product; 
    }
  }

  // Sort Products by Annual Price with the most expensive first.
  function sortProducts(){
    $this->log->info('Sorting Products');    
    $products = $this->products;

    function cmp($a, $b) {
      if($a->annualPrice < $b->annualPrice){
        return true;
      } else {
        return false;
      }        
    }

    $tempProducts = $products;
    usort($tempProducts, "cmp");

    $this->products = $tempProducts;    
  }

  // Output JSON
  function outputJson(){
    $this->log->info('Outputting JSON'); 
    $products = $this->products;
    return json_encode($products);
  }
}