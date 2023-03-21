<?php
namespace App\ApiModel\Municipality; 
use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;
class MunicipalityUpdate extends CommonParameterBag {
 /**
     * @Assert\NotBlank
     */
    public  $frensh_name;
    /**
     * @Assert\NotBlank
     */
    public  $arabic_name;

    
/**
     * @Assert\NotBlank
     */
    public  $zip_code;
    /**
     * @Assert\NotBlank
     */
    public  $building_number;
    /**
     * @Assert\NotBlank
     */
    public  $street;


     /**
     * @Assert\NotBlank
     */
    public  $national_id;

     /**
     * @Assert\NotBlank
     */
    public  $governorate;

    

    // aux 
    public  $population_count;
    public  $population_year_count;
    public  $phone_number;
    public  $web_site;
    public   $email;


    
}