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
        $this->vegetarian = ($foo->name == "Kasvislounas" OR $foo->name == "Kasviskeitto");
        $this->components = $foo->Components;
    }

}
