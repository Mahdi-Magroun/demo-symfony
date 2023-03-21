<?php 
namespace App\ApiModel\Taxe; 

use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;
class TaxeUpdate extends CommonParameterBag{
/**
 * @Assert\NotBlank
 */
public $taxe_abbreviation; 
/**
 * @Assert\NotBlank
 */
public $taxe_name;
/**
 * @Assert\NotBlank
 */
public $date_begin; 
public $date_end; 
public $is_activated;
}