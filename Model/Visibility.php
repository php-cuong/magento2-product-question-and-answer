<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-05 03:30:52
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-16 12:58:08
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model;

use Magento\Framework\Data\OptionSourceInterface;

class Visibility implements OptionSourceInterface
{
    /**
     * Not visible code
     */
    const VISIBILITY_NOT_VISIBLE = '1';

    /**
     * Visible code
     */
    const VISIBILITY_VISIBLE = '2';

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * Get visibility type values array
     *
     * @return array
     */
    public function getOptionArray()
    {
        return [
            self::VISIBILITY_VISIBLE => __('Visible'),
            self::VISIBILITY_NOT_VISIBLE => __('Not Visible')
        ];
    }

    /**
     * Get visibility type values array with empty value
     *
     * @return array
     */
    public function getOptionValues()
    {
        $options = [];
        $options[''] = __('--Select--');
        foreach ($this->getOptionArray() as $key => $value) {
            $options[$key] = $value;
        }
        return $options;
    }

    /**
     * Get only user types
     *
     * @return array
     */
    public function getArrayKeys()
    {
        $arrayKeys = [];
        foreach ($this->getOptionArray() as $key => $value) {
            $arrayKeys[] = $key;
        }
        return $arrayKeys;
    }
}
