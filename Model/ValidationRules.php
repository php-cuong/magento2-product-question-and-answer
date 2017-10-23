<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-14 07:35:12
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-16 10:07:28
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model;

use Magento\Framework\Exception\LocalizedException;

class ValidationRules
{
    /**
     * Visibility
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\Visibility
     */
    protected $visibility;

    /**
     * UserType
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\UserType
     */
    protected $userType;

    /**
     * Status
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\Status
     */
    protected $status;

    /**
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\Visibility $visibility
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\UserType $userType
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\Status $status
     */
    public function __construct(
        \PHPCuong\ProductQuestionAndAnswer\Model\Visibility $visibility,
        \PHPCuong\ProductQuestionAndAnswer\Model\UserType $userType,
        \PHPCuong\ProductQuestionAndAnswer\Model\Status $status
    ) {
        $this->visibility = $visibility;
        $this->userType = $userType;
        $this->status = $status;
    }

    /**
     * Validate Email
     *
     * @param string $value
     * @param string $label
     * @return void
     */
    public function validateEmail($value, $label)
    {
        $validator = new \Zend_Validate_EmailAddress();
        $this->validateEmptyValue($value, $label);
        if (!$validator->isValid($value)) {
            throw new LocalizedException(__('"%1" is not a valid email address.', $value));
        }
    }

    /**
     * Validate value in the case value is empty
     *
     * @param string $value
     * @param string $label
     * @return void
     */
    public function validateEmptyValue($value, $label)
    {
        if (empty($value)) {
            throw new LocalizedException(
                __('"%1" is required.', $label)
            );
        }
    }

    /**
     * Validate unsigned integer number
     *
     * @param unsinged integer $value
     * @param string $label
     * @return void
     */
    public function validateIntegerNumber($value, $label)
    {
        if (!empty($value) && !ctype_digit($value)) {
            throw new LocalizedException(
                __('"%1" is not a valid number.', $label)
            );
        }
    }

    /**
     * Validate Visibility
     *
     * @param int $value
     * @return void
     */
    public function validateVisibility($value)
    {
        $this->validateOptionArray($value, 'Visibility code', $this->visibility->getOptionArray());
    }

    /**
     * Validate status
     *
     * @param int $value
     * @return void
     */
    public function validateStatus($value)
    {
        $this->validateOptionArray($value, 'Status code', $this->status->getOptionArray());
    }

    /**
     * Validate User Type
     *
     * @param int $value
     * @param string $type
     * @return void
     */
    public function validateUserType($value, $type = 'answer')
    {
        switch ($type) {
            case 'question':
                $this->validateOptionArray($value, 'User type code', $this->userType->getOptionArray(), true);
                break;

            case 'answer':
                $this->validateOptionArray($value, 'User type code', $this->userType->getOptionArray());
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Validate option array
     *
     * @param string $value
     * @param string $label
     * @param array $optionArray
     * @param boolean $isQuestion
     * @return void
     */
    public function validateOptionArray($value, $label, $optionArray, $isQuestion = false)
    {
        $valueKeys = [];

        if ($isQuestion) {
            unset($optionArray[3]);
        }

        foreach ($optionArray as $key => $valueKey) {
            $valueKeys[] = $key;
        }

        $this->validateEmptyValue($value, $label);

        if (!in_array($value, $valueKeys)) {
            throw new LocalizedException(
                __('%1 is not a valid %2.', $label, $label)
            );
        }
    }
}
