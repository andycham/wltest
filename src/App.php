<?php

namespace andycham\wltest;

require_once(__DIR__ .'/../vendor/autoload.php');
require_once(__DIR__ .'/ProductFactory.php');
require_once(__DIR__ .'/Product.php');

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;
use andycham\wltest\ProductFactory;
use DOMDocument;
use DOMElement;
use DOMXPath;

Class App 
{
  public $log;
  public string $url = '';
  public int $logToScreen = 0;
  public string $content = '';
  public $products=[];

  // -------------------------------------------------------
  // Main Entry Point of App
  // -------------------------------------------------------
  public function main() : void {
    // Get environmental variable settings from .env file.
    $this->getSettings();
    
    // Set up log
    $this->setUpLog();
        
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
  public function getSettings() : void {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
    if(isset($_ENV['SOURCE_URL'])){
      $this->url = $_ENV['SOURCE_URL'];
    }
    if(isset($_ENV['LOG_TO_SCREEN'])){
      if($_ENV['LOG_TO_SCREEN']=='1'){
        $this->logToScreen = 1;
      } else {
        $this->logToScreen = 0;
      }     
    }
  }


  public function setUpLog() : void {
    // Set up logging
    $this->log = new Logger('main');

    // Log to file
    $this->log->pushHandler(new StreamHandler('logs/application.log', Level::Debug));

    // Log to screen
    if($this->logToScreen==1){
      $this->log->pushHandler(new StreamHandler('php://stdout', Level::Info));
    }
  }


  // Return content from website url
  public function getContent($url) : string {
    $content = file_get_contents($url);
    return $content;
  }


  // Parse website content to get products.
  public function parseContent($content) : void {
    //Suppress any warnings
    libxml_use_internal_errors(true);
    
    $this->log->info("Parsing content");
    
    $doc = new DOMDocument();
    $doc->loadHTML($content);
    $xpath = new DOMXPath($doc);

    // Get DOMElements for all products
    $elements = $xpath->evaluate('//div[@class="row-subscriptions"]/div');
    
    $productFactory = new ProductFactory();

    foreach($elements as $element){
      // Now parse each product DOMElement to get the attributes of the product 
      $this->log->debug("element = " . print_r($element, true)); 
      $product = $productFactory->create($element, $doc);
      $this->products[] = $product;

      // Log some debugging info
      $this->log->debug("title = " . $product->title); 
    }
  }


  // Comparison function for sorting
  public function cmp($a, $b) : int{
    if($a->annualPrice < $b->annualPrice){
      return 1;
    } else {
      return 0;
    }        
  }
  
  // Sort Products by Annual Price with the most expensive first.
  public function sortProducts() : void{
    $this->log->info('Sorting Products');    
    $products = $this->products;

    $tempProducts = $products;
    usort($tempProducts, array($this, "cmp"));

    $this->products = $tempProducts;    
  }

  // Output JSON
  public function outputJson() : string{
    $this->log->info('Outputting JSON'); 
    $products = $this->products;
    return json_encode($products);
  }
}