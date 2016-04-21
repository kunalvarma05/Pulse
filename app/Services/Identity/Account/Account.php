<?php
namespace Pulse\Services\Identity\Account;

class Account implements AccountInterface
{
    /**
     * Account id
     * @var string
     */
    protected $id;

    /**
     * Account name
     * @var string
     */
    protected $name;

    /**
     * Account email
     * @var string
     */
    protected $email;

    /**
     * Account image
     * @var string
     */
    protected $image;

    /**
     * Constructor
     * @param array $accountInfo ['id', 'name', 'email', 'image']
     */
    public function __construct(array $accountInfo = array())
    {
        if (!empty($accountInfo)) {
            if (!isset($accountInfo['id']) || !isset($accountInfo['name']) || !isset($accountInfo['email']) || !isset($accountInfo['image'])) {
                throw new \Exception("id, name, email and image are required!");
            }

            $this->id = $accountInfo['id'];
            $this->name = $accountInfo['name'];
            $this->email = $accountInfo['email'];
            $this->image = $accountInfo['image'];
        }
    }

    /**
     * Set Account ID
     * @param string
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set Account Name
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set Account Email
     * @param string
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set Account Image
     * @param string Account Image URL
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get Account ID
     * @return string
     */
    public function getId()
    {
        return (string) $this->id;
    }

    /**
     * Get Account Name
     * @return string
     */
    public function getName()
    {
        return (string) $this->name;
    }

    /**
     * Get Account Email
     * @return string
     */
    public function getEmail()
    {
        return (string) $this->email;
    }

    /**
     * Get Account Image
     * @return string Account Image URL
     */
    public function getImage()
    {
        return (string) $this->image;
    }
}
