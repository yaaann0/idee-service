<?php

namespace App\Entity;

class DefaultSearch 
{
    /**
     * @var string|null
     */
    private $orderBy;

    /**
     * @var string|null
     */
    private $order;

    /**
     * Get the value of orderBy
     *
     * @return  string|null
     */ 
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set the value of orderBy
     *
     * @param  string|null  $orderBy
     *
     * @return  self
     */ 
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get the value of order
     *
     * @return  string|null
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @param  string|null  $order
     *
     * @return  self
     */ 
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}