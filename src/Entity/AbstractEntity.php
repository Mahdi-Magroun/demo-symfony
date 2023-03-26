<?php

namespace App\Entity;

use SSH\MyJwtBundle\Utils\MyTools;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\DBAL\Types\Type;

Type::overrideType('datetime', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('datetimetz', 'Doctrine\DBAL\Types\VarDateTimeType');

abstract class AbstractEntity
{

    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    const MAPPING = [];
    const CONSTANTS = [];
    const IGNORED_ATTRIBUTES = [
        'id',
        'lazyPropertiesNames',
        'lazyPropertiesDefaults',
        '__initializer__',
        '__cloner__',
        '__isInitialized__'
    ];

    public function __construct($data = [])
    {
        if (!empty($data) && MyTools::isArrayAssoc($data)) {

            $this->fromArray($data);
        }

        if (method_exists($this, 'setCreatedAt')) {
            $this->setCreatedAt(new \DateTime());
        }
        return $this;
    }

    public function __toString()
    {
        if (method_exists($this, 'getCode')) {
            return $this->getCode();
        }
        return null;
    }

    /**
     * override this methods to select only some parameters key.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [];
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $attributes[] = $property->getName();
        }

        return $attributes;
    }

    /**
     * Format object to array
     * @return type
     */
    public function toArray($toSnake = false, $eager = true, $prefix = '')
    {
        $return = [];

        $attributes = $this->getAttributes();

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($attributes as $newAttribute) {
            if ($newAttribute && !in_array($newAttribute, self::IGNORED_ATTRIBUTES)) {

                $key = ($toSnake ? MyTools::toSnake($newAttribute) : $newAttribute );

                if (!empty($prefix)) {
                    $key = $prefix . ($toSnake ? '_' . $key : ucfirst($key));
                }

                $return[$key] = $accessor->getValue($this, $newAttribute);

                if ($return[$key] instanceof \DateTime) {

                    $return[$key] = $return[$key]->format(static::DATE_TIME_FORMAT);

                } 
                elseif ($return[$key] instanceof \Doctrine\Common\Collections\Collection) {
                    # code...
                   // unset($return[$key]);
                }
                else if (is_object($return[$key])) {

                    $return[$key] = $return[$key]->__toString();
                    if (!$eager) {
                        unset($return[$key]);
                    }
                }
            }
        }

        if (method_exists($this, 'getLabel')) {
            $return ['label'] = $this->getLabel();
        }

        return $return;
    }

    public function serialize($mapping = null, $prefix = [])
    {
        return json_encode([$this->toArray($mapping, $prefix)]);
    }

    /**
     * Set object attributes from array
     *
     * @param type $data
     */
    public function fromArray($data)
    {
        foreach ($data as $property => $value) {
            $method = 'set' . MyTools::camelize($property, false);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    public function createDateTime($dateTime = null, $format = null)
    {
        date_default_timezone_set('Europe/Paris');

        if (!$dateTime) {
            return null;
        }

        if ($dateTime instanceof \DateTime) {
            return $dateTime;
        }
        if ($format) {
            return \DateTime::createFromFormat($format, trim($dateTime));
        }

        return new \DateTime(trim($dateTime));
    }

    public function getFields($mapping)
    {
        return array_keys(static::MAPPING[$mapping]);
    }

    /* Verification du format de date */

    public function getFormatDate($date)
    {
        $format = 'd/m/Y H:i:s';
        if (strpos($date, '-') && is_numeric(substr($date, 0, 4))) {
            $format = 'Y-m-d H:i:s';
            return $format;
        }

        if (strpos($date, '-') && !is_numeric(substr($date, 0, 4))) {
            $format = 'd-m-Y H:i:s';
            return $format;
        }

        if (strpos($date, '/') && is_numeric(substr($date, 0, 4))) {
            $format = 'Y/m/d H:i:s';
            return $format;
        }

        return $format;
    }

}
