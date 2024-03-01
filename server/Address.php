<?php
 
class Address
{  
    public function __construct(
        public readonly string $streetAddress,
        public readonly string $streetAddressAbbreviation,
        public readonly string $secondaryAddress,
        public readonly string $cityAbbreviation,
        public readonly string $city,
        public readonly string $state,
        public readonly string $ZIPCode,
        public readonly string $urbanization,
        public readonly string $postalCode,
        public readonly string $province,
        public readonly string $country,
        public readonly string $countryISOCode
    ) {

    }
 
}