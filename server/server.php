<?php
require "AddressRepo.php";
require "Address.php";

$GLOBALS['env'] = parse_ini_file('../.env');
 
$streetAddress = $_REQUEST['streetAddress'];
$state = $_REQUEST['state'];
$ZIPCode = $_REQUEST['ZIPCode'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($streetAddress) && isset($state) && isset($ZIPCode)) {
        echo validateAddress($streetAddress, $state, $ZIPCode);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
    $data = [];

    if (empty($streetAddress)) {
        $errors['streetAddress'] = 'Street Address is required.';
    }

    if (empty($state)) {
        $errors['state'] = 'State is required.';
    }
    if (empty($ZIPCode)) {
        $errors['ZIPCode'] = 'Zip Code is required.';
    }
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        $address = new Address(
            $_POST["streetAddress"],
            $_POST["streetAddressAbbreviation"],
            $_POST["secondaryAddress"],
            $_POST["cityAbbreviation"],
            $_POST["city"],
            $_POST["state"],
            $_POST["ZIPCode"],
            $_POST["urbanization"],
            $_POST["postalCode"],
            $_POST["province"],
            $_POST["country"],
            $_POST["countryISOCode"]
        );
        $addressRepo = new AddressRepo();
        $addressRepo->save($address);
        $data['success'] = true;
        $data['message'] = 'Success!';
    }
    
    echo json_encode($data);
  
}

function validateAddress($streetAddress, $state, $ZIPCode)
{
    $token = getValidateAddressToken();
    $url = $GLOBALS['env']['USPS_BASE_URL'].'addresses/v3/address?streetAddress=' . urlencode($streetAddress). '&state=' . $state . '&ZIPCode=' . $ZIPCode;
    $ch = curl_init($url);
    $headers = [
        'Authorization: Bearer ' . $token
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function getValidateAddressToken()
{
    $env = $GLOBALS['env'];
    $url = $env["USPS_BASE_URL"].'oauth2/v3/token';
    $ch = curl_init();
    $payload = json_encode([
        "grant_type" => "client_credentials",
        "client_id" => $env["USPS_CLIENT_ID"],
        "client_secret" => $env["USPS_CLIENT_SECRET"],
        "scope" => "addresses",
        "state" => ""
    ]);
    $headers = [
        'Content-Type: application/json '
    ];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $response = json_decode($response,true);
    curl_close($ch);
    return $response['access_token'];
}
