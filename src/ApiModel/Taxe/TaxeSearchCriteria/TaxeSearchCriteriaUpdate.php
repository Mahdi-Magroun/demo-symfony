<?php 
namespace App\ApiModel\Taxe\TaxeSearchCriteria;
use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class TaxeSearchCriteriaUpdate extends CommonParameterBag
{
    /**
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @Assert\NotBlank
     */
    public $value;
    /**
     * @Assert\NotBlank
     */
    public $date_begin;

    public $date_end;
    public $is_activated;


  
}