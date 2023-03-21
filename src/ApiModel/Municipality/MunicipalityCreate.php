<?php 
namespace App\ApiModel\Municipality;
use ZipStream\Bigint;
use DateTimeImmutable;
use App\ApiModel\Common\Address;
use SSH\MyJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;
use App\ApiModel\Municipality\President\PresidentCreate;

class MunicipalityCreate extends CommonParameterBag{
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

    

    // president 
    /**
     * @Assert\NotBlank
     */
    public  $president_first_name;
    /**
     * @Assert\NotBlank
     */
    public  $president_last_name;

    /**
     * @Assert\NotBlank
     */
    public  $president_email;

    /**
     * @Assert\NotBlank
     */
    public  $president_cin;

    /**
     *  @Assert\NotBlank
     * 
     */
    public  $president_date_begin; 

     /**
     *  @Assert\NotBlank
     * 
     */
    public  $president_date_end;


    // aux 
    public  $population_count;
    public  $population_year_count;
    public  $phone_number;
    public  $web_site;
    public   $email;


    
    
}