<?php


namespace App\Entity;


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