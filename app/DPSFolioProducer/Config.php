<?php
/**
 * Adobe DPS API client library.
 *
 * @category  AdobeDPS
 * @package   DPSFolioProducer
 * @author    Jonathan Knapp <jon@coffeeandcode.com>
 * @copyright 2013 Jonathan Knapp
 * @license   MIT https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE
 * @version   1.0.0
 * @link      https://github.com/CoffeeAndCode/folio-producer-api
 */
namespace DPSFolioProducer;

/**
 * Config Configuration object for the application.
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @license  MIT https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class Config
{
    /**
     * Holds the configuration data in an associative array.
     * @var array
     */
    protected $data;

    /**
     * Constructor for the object.
     *
     * @param array $config Associateive array to use as a base config.
     */
    public function __construct($config=array())
    {
        $this->data = $config;
    }

    /**
     * Magic method to allow retriving properties directly.
     *
     * @param string $name The property name to look up.
     *
     * @throws Exception if the requested property has not already been set.
     *
     * @return mixed The property that was previously stored.
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * Magic method to check if a config property is already set.
     *
     * @param string $name The property name to check for.
     *
     * @return boolean Returns TRUE if the property is set, FALSE otherwise.
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Magic method to set a configuration property directly.
     *
     * @param string $name  The property to set.
     * @param mixed  $value The value to store under the specified property.
     *
     * @return null __set methods should not return any values.
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
