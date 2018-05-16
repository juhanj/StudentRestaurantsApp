<?php
class Food {

    /** @var string */
    public $name = null;
    /** @var string */
    public $prices = null;
    /** @var bool */
    public $vegetarian = null;

    /** @var string[] */
    public $components = array();

    function __construct ( $foo ) {
        $this->name = $foo->Name;
        $this->prices = $foo->Price;
        $this->vegetarian = (strcasecmp($this->name,"kasvislounas")==0 OR strcasecmp($this->name,"kasviskeitto")==0);
        $this->components = $foo->Components;
    }

}
