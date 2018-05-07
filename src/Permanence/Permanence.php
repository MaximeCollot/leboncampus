<?php

namespace Permanence;


class Permanence  
{

    private $id;
    private $date;
    private $slot;
    private $name;
    
    public function __construct($date,
                                $slot,
                                $name)
    {
        $this->date = $date;
        $this->slot = $slot;
        $this->name = $name;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getSlot(){
        return $this->slot;
    }

    public function setSlot($slot) {
        $this->slot = $slot;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}
