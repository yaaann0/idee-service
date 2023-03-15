<?php

namespace App\Entity;

class ClientSearch extends DefaultSearch
{
    /**
     * @var string|null
     */
    private $fullname;

    /**
     * @var string|null
     */
    private $city;
    
    /**
     * @var int|null
     */
    private $postalCode;

    /**
     * Get the value of fullname
     *
     * @return  string|null
     */ 
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set the value of fullname
     *
     * @param  string|null  $fullname
     *
     * @return  self
     */ 
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get the value of city
     *
     * @return  string|null
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @param  string|null  $city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of postalCode
     *
     * @return  int|null
     */ 
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set the value of postalCode
     *
     * @param  int|null  $postalCode
     *
     * @return  self
     */ 
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}