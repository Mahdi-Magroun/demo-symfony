<?php 
namespace App\ApiModel\Taxe; 

use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;
class TaxeCreate extends CommonParameterBag{
/**
 * @Assert\NotBlank
 */
public $abbreviation; 
/**
 * @Assert\NotBlank
 */
public $name;
/**
 * @Assert\NotBlank
 */
public $date_begin; 

public $is_activated;
}