<?php


namespace App\Data;


use PhpParser\Node\Scalar\String_;

class SearchData
{


    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var String
     */
    public $domaine ;
    /**
     *@var String
     */
public $langue;
    /**
     *@var String
     */
    public $niveau;
    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    public function __toString():String
    {
       return domaine;
    }


}