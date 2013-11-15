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
     * The top-level PHP session key to store data under.
     */
    const SESSION_KEY = 'folio-producer-api';

    /**
     * Holds the configuration data in an associative array.
     * @var array
     */
    protected $data;

    /**
     * An array of property names that are allowed to be set.
     * @var array
     */
    private $properties;

    /**
     * An array of property names that will be set in PHP Session
     * if one exists when the property is altered.
     * @var array
     */
    private $syncedProperties;

    /**
     * Constructor for the object.
     *
     * @param array $config Associateive array to use as a base config.
     */
    public function __construct($config=array())
    {
        $this->data = array();

        $this->properties = array(
            'api_server',
            'company',
            'consumer_key',
            'consumer_secret',
            'download_server',
            'download_ticket',
            'email',
            'password',
            'request_server',
            'session_props',
            'ticket'
        );

        $this->syncedProperties = array(
            'download_server',
            'download_ticket',
            'request_server',
            'ticket'
        );

        foreach (array_keys($config) as $key) {
            if (in_array($key, $this->properties)) {
                $this->$key = $config[$key];
            }
        }

        $this->syncFromSession();
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
        if (in_array($name, $this->properties)) {
            $this->data[$name] = $value;
            $this->syncSessionProperty($name, $value);
        }
    }

    /**
     * Clear out properties and session data.
     *
     * @return null This method does not return anything.
     */
    public function reset() {
        $this->data = array();

        if (session_id()) {
            unset($_SESSION[self::SESSION_KEY]);
        }
    }

    /**
     * Check PHP session and sync configuration properties if found.
     *
     * @return null This method does not return anything.
     */
    private function syncFromSession() {
        if (session_id() &&
            isset($this->company) &&
            isset($this->email) &&
            isset($_SESSION[self::SESSION_KEY]) &&
            isset($_SESSION[self::SESSION_KEY][$this->company]) &&
            isset($_SESSION[self::SESSION_KEY][$this->company][$this->email])
            ) {

            $data = $_SESSION[self::SESSION_KEY][$this->company][$this->email];
            foreach ($this->syncedProperties as $property) {
                if (isset($data[$property])) {
                    $this->$property = $data[$property];
                }
            }
        }
    }

    /**
     * If whitelisted, set the property in the PHP session.
     *
     * @return null This method does not return anything.
     */
    private function syncSessionProperty($name, $value) {
        if (session_id() &&
            in_array($name, $this->syncedProperties) &&
            isset($this->company) &&
            isset($this->email)) {

            if (!isset($_SESSION[self::SESSION_KEY]) ||
                !isset($_SESSION[self::SESSION_KEY][$this->company])) {
                $_SESSION[self::SESSION_KEY] = array(
                    $this->company => array($this->email => array())
                );
            } elseif (!isset($_SESSION[self::SESSION_KEY][$this->company][$this->email])) {
                $_SESSION[self::SESSION_KEY][$this->company][$this->email] = array();
            }

            $_SESSION[self::SESSION_KEY][$this->company][$this->email][$name] = $value;
        }
    }
}
