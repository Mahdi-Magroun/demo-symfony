<?php 
namespace App\ApiModel\Taxe; 

use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;
class TaxeCreate extends CommonParameterBag{
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

public $is_activated;
}