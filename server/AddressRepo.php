<?php
require 'DB.php';

class AddressRepo
{
  private $conn;

  public function __construct()
  {
    $db = DB::getInstance();
    $this->conn = $db->getConnection();
    $this->setup();
  }
  private function setup()
  {
    try {
      $sql = "CREATE TABLE IF NOT EXISTS addresses (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        streetAddress VARCHAR(50) NOT NULL,
        streetAddressAbbreviation VARCHAR(50),
        secondaryAddress VARCHAR(50),
        cityAbbreviation VARCHAR(50),
        city VARCHAR(28) NOT NULL,
        state VARCHAR(2) NOT NULL,
        ZIPCode VARCHAR(10) NOT NULL,
        urbanization VARCHAR(96) NULL,
        postalCode VARCHAR(5),
        province VARCHAR(50),
        country VARCHAR(50),
        countryISOCode VARCHAR(3),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      echo "Something went wrong " . $e->getMessage();
    }
  }

  function save(Address $address)
  {
    try {
      $sql = "INSERT INTO addresses (streetAddress, streetAddressAbbreviation,secondaryAddress,
      cityAbbreviation,city,state,ZIPCode,urbanization,postalCode,province,country,countryISOCode)
      VALUES ('".$address->streetAddress."','" 
      .$address->streetAddressAbbreviation."','"  
      .$address->secondaryAddress."','"  
      .$address->cityAbbreviation."','"  
      .$address->city."','"  
      .$address->state."','"  
      .$address->ZIPCode."','"  
      .$address->urbanization."','"  
      .$address->postalCode."','"  
      .$address->province."','"  
      .$address->country."','" 
      .$address->countryISOCode."')";
      $this->conn->exec($sql);
      return true;
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
      return false;
    }
  }
}
