<?php
namespace andycham\wltest;

use Product;
use DOMXPath;

class ProductFactory {
  public $element;
  public Product $product ;
  public DOMXPath $xpath;

  // Create a product from element and document
  public function create($element, $doc) : Product {            
    $this->element = $element;
    $this->xpath = new DOMXPath($doc);
    $this->product = new Product();
    $this->product->title = $this->getTitle();
    $this->product->description = $this->getDescription();
    $this->product->price = $this->getPrice();
    $this->product->discount = $this->getDiscount();

    if($this->isMonthlyPackage()){
      $this->product->monthlyPrice = $this->product->price;
      $this->product->annualPrice = $this->product->price * 12;
    } else {
      $this->product->monthlyPrice = $this->product->price / 12;
      $this->product->annualPrice = $this->product->price;
    }
    return $this->product;
  }

  public function getTitle() : string {
    return $this->xpathValue('.//h3');
  }

  public function getDescription() : string {
    return $this->xpathValue('.//div[@class="package-description"]');
  }

  public function getPrice() : float {
    $price = $this->xpathValue('.//span[@class="price-big"]');
    $price = str_replace('£', '', $price);
    return $price;    
  }

  public function getDiscount() : float {
    $discount = $this->xpathValue('.//div[@class="package-price"]/p');
    if($discount!=''){
      // Clean up the surrounding text to get the discount amount.
      $discount = str_replace('Save £', '', $discount);
      $discount = str_replace(' on the monthly price', '', $discount);
    } else {
      $discount = 0;
    }
    return $discount;
  }

  public function isMonthlyPackage() : bool {
    $price_raw = $this->xpathValue('.//div[@class="package-price"]');
    if(strpos($price_raw, 'Month')){
      return true;
    } else {
      return false;
    }
  }

  public function xpathValue($query) : string {
    if($this->xpath->query($query, $this->element)[0]){
      $value = $this->xpath->query($query, $this->element)[0]->textContent;
    } else {
      $value = '';
    }
    return $value;
  }
  
}