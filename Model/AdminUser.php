<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-16 07:44:47
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-17 06:17:42
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model;

class AdminUser
{
    /**
     * Backend Auth session model
     *
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @param \Magento\Backend\Model\Auth\Session $authSession
     */
    public function __construct(
        \Magento\Backend\Model\Auth\Session $authSession
    ) {
        $this->authSession = $authSession;
    }

    /**
     * Retrieve the email of admin user
     *
     * @param string $default
     * @return string
     */
    public function getEmail($default = 'phpcuong@example.com')
    {
        if ($this->authSession->isLoggedIn()) {
            return $this->authSession->getUser()->getEmail();
        }

        return $default;
    }

    /**
     * Retrieve the name of admin user
     *
     * @param string $default
     * @return string
     */
    public function getName($default = 'phpcuong')
    {
        if ($this->authSession->isLoggedIn()) {
            return $this->authSession->getUser()->getFirstname();
        }

        return 'phpcuong';
    }

    /**
     * Retrieve the ID of admin user
     *
     * @return int|null
     */
    public function getID()
    {
        if ($this->authSession->isLoggedIn()) {
            return $this->authSession->getUser()->getUserId();
        }
        return null;
    }
}
