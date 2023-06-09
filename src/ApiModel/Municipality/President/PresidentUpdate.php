<?php 
namespace App\ApiModel\Municipality\President;
use DateTime;
use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;


class PresidentUpdate extends CommonParameterBag{

/**
 * @Assert\NotBlank
 */
public $first_name;
/**
 * @Assert\NotBlank
 */
public $last_name;
/**
 * @Assert\NotBlank
 */
public $email;
/**
 * @Assert\NotBlank
 * @var DateTime
 */
public $date_begin;
/**
 * @Assert\NotBlank
 */
public $date_end;
/**
 * 
 * @var bool
 */
public $is_activated;
/**
 * @Assert\NotBlank
 */
public $cin;
}