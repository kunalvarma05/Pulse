<?php
namespace Pulse\Services\Identity\Account;

interface AccountInterface
{

    /**
     * Set Account ID
     * @param string
     */
    public function setId($id);

    /**
     * Set Account Name
     * @param string
     */
    public function setName($name);

    /**
     * Set Account Email
     * @param string
     */
    public function setEmail($email);

    /**
     * Set Account Image
     * @param string Account Image URL
     */
    public function setImage($image);

    /**
     * Get Account ID
     * @return string
     */
    public function getId();

    /**
     * Get Account Name
     * @return string
     */
    public function getName();

    /**
     * Get Account Email
     * @return string
     */
    public function getEmail();

    /**
     * Get Account Image
     * @return string Account Image URL
     */
    public function getImage();
}
