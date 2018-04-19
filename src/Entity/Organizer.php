<?php

namespace Jcanda\Gotomeeting\Entity;

class Organizer extends EntityAbstract
{
    /* Model Schema
      {
      "organizerEmail": "string",
      "firstName": "string",
      "lastName": "string",
      "productType": "G2M"
      }
     */
    //required
    public $organizerEmail;
    public $firstName;
    public $lastName;
    public $productType = 'G2M';

    public function __construct($parameterArray = null)
    {
        if (isset($parameterArray) && is_array($parameterArray)) {

            $this->organizerEmail = $parameterArray['organizerEmail']; //required
            $this->firstName = $parameterArray['firstName']; //required
            $this->lastName = $parameterArray['lastName']; //required
            $this->productType = (isset($parameterArray['productType']) ? $parameterArray['productType'] : $this->productType );
        }
    }
}