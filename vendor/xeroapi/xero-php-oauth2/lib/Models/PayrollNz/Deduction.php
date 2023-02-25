<?php
/**
 * Deduction
 *
 * PHP version 5
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Xero Payroll NZ
 *
 * This is the Xero Payroll API for orgs in the NZ region.
 *
 * Contact: api@xero.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.4.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace XeroAPI\XeroPHP\Models\PayrollNz;

use \ArrayAccess;
use \XeroAPI\XeroPHP\PayrollNzObjectSerializer;
use \XeroAPI\XeroPHP\StringUtil;
use ReturnTypeWillChange;

/**
 * Deduction Class Doc Comment
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Deduction implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Deduction';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'deduction_id' => 'string',
        'deduction_name' => 'string',
        'deduction_category' => 'string',
        'liability_account_id' => 'string',
        'current_record' => 'bool',
        'standard_amount' => 'double'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'deduction_id' => 'uuid',
        'deduction_name' => null,
        'deduction_category' => null,
        'liability_account_id' => 'uuid',
        'current_record' => null,
        'standard_amount' => 'double'
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'deduction_id' => 'deductionId',
        'deduction_name' => 'deductionName',
        'deduction_category' => 'deductionCategory',
        'liability_account_id' => 'liabilityAccountId',
        'current_record' => 'currentRecord',
        'standard_amount' => 'standardAmount'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'deduction_id' => 'setDeductionId',
        'deduction_name' => 'setDeductionName',
        'deduction_category' => 'setDeductionCategory',
        'liability_account_id' => 'setLiabilityAccountId',
        'current_record' => 'setCurrentRecord',
        'standard_amount' => 'setStandardAmount'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'deduction_id' => 'getDeductionId',
        'deduction_name' => 'getDeductionName',
        'deduction_category' => 'getDeductionCategory',
        'liability_account_id' => 'getLiabilityAccountId',
        'current_record' => 'getCurrentRecord',
        'standard_amount' => 'getStandardAmount'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    const DEDUCTION_CATEGORY_PAYROLL_GIVING = 'PayrollGiving';
    const DEDUCTION_CATEGORY_KIWI_SAVER_VOLUNTARY_CONTRIBUTIONS = 'KiwiSaverVoluntaryContributions';
    const DEDUCTION_CATEGORY_SUPERANNUATION = 'Superannuation';
    const DEDUCTION_CATEGORY_NZ_OTHER = 'NzOther';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getDeductionCategoryAllowableValues()
    {
        return [
            self::DEDUCTION_CATEGORY_PAYROLL_GIVING,
            self::DEDUCTION_CATEGORY_KIWI_SAVER_VOLUNTARY_CONTRIBUTIONS,
            self::DEDUCTION_CATEGORY_SUPERANNUATION,
            self::DEDUCTION_CATEGORY_NZ_OTHER,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['deduction_id'] = isset($data['deduction_id']) ? $data['deduction_id'] : null;
        $this->container['deduction_name'] = isset($data['deduction_name']) ? $data['deduction_name'] : null;
        $this->container['deduction_category'] = isset($data['deduction_category']) ? $data['deduction_category'] : null;
        $this->container['liability_account_id'] = isset($data['liability_account_id']) ? $data['liability_account_id'] : null;
        $this->container['current_record'] = isset($data['current_record']) ? $data['current_record'] : null;
        $this->container['standard_amount'] = isset($data['standard_amount']) ? $data['standard_amount'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['deduction_name'] === null) {
            $invalidProperties[] = "'deduction_name' can't be null";
        }
        if ($this->container['deduction_category'] === null) {
            $invalidProperties[] = "'deduction_category' can't be null";
        }
        $allowedValues = $this->getDeductionCategoryAllowableValues();
        if (!is_null($this->container['deduction_category']) && !in_array($this->container['deduction_category'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'deduction_category', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['liability_account_id'] === null) {
            $invalidProperties[] = "'liability_account_id' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets deduction_id
     *
     * @return string|null
     */
    public function getDeductionId()
    {
        return $this->container['deduction_id'];
    }

    /**
     * Sets deduction_id
     *
     * @param string|null $deduction_id The Xero identifier for Deduction
     *
     * @return $this
     */
    public function setDeductionId($deduction_id)
    {

        $this->container['deduction_id'] = $deduction_id;

        return $this;
    }



    /**
     * Gets deduction_name
     *
     * @return string
     */
    public function getDeductionName()
    {
        return $this->container['deduction_name'];
    }

    /**
     * Sets deduction_name
     *
     * @param string $deduction_name Name of the deduction
     *
     * @return $this
     */
    public function setDeductionName($deduction_name)
    {

        $this->container['deduction_name'] = $deduction_name;

        return $this;
    }



    /**
     * Gets deduction_category
     *
     * @return string
     */
    public function getDeductionCategory()
    {
        return $this->container['deduction_category'];
    }

    /**
     * Sets deduction_category
     *
     * @param string $deduction_category Deduction Category type
     *
     * @return $this
     */
    public function setDeductionCategory($deduction_category)
    {
        $allowedValues = $this->getDeductionCategoryAllowableValues();
        if (!in_array($deduction_category, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'deduction_category', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }

        $this->container['deduction_category'] = $deduction_category;

        return $this;
    }



    /**
     * Gets liability_account_id
     *
     * @return string
     */
    public function getLiabilityAccountId()
    {
        return $this->container['liability_account_id'];
    }

    /**
     * Sets liability_account_id
     *
     * @param string $liability_account_id Xero identifier for Liability Account
     *
     * @return $this
     */
    public function setLiabilityAccountId($liability_account_id)
    {

        $this->container['liability_account_id'] = $liability_account_id;

        return $this;
    }



    /**
     * Gets current_record
     *
     * @return bool|null
     */
    public function getCurrentRecord()
    {
        return $this->container['current_record'];
    }

    /**
     * Sets current_record
     *
     * @param bool|null $current_record Identifier of a record is active or not.
     *
     * @return $this
     */
    public function setCurrentRecord($current_record)
    {

        $this->container['current_record'] = $current_record;

        return $this;
    }



    /**
     * Gets standard_amount
     *
     * @return double|null
     */
    public function getStandardAmount()
    {
        return $this->container['standard_amount'];
    }

    /**
     * Sets standard_amount
     *
     * @param double|null $standard_amount Standard amount of the deduction.
     *
     * @return $this
     */
    public function setStandardAmount($standard_amount)
    {

        $this->container['standard_amount'] = $standard_amount;

        return $this;
    }


    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            PayrollNzObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }
}


