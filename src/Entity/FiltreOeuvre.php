<?php


namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class FiltreOeuvre
{


    /**
     * @var string
     */
    public $q = '';

    /**
     * @var string
     */
    public $domaine;

    /**
     * @var null|integer
     */
    public $max ;

    /**
     * @var null|integer

     */
    public $min ;

}