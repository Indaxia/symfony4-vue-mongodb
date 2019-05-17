<?php

namespace App\Access\Document;

use App\Common\Document\Traits\HasCreatedAt;
use App\Common\Document\Traits\HasUpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as FOSUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="access_users", repositoryClass="App\Access\Repository\UserRepository")
 * @ODM\HasLifecycleCallbacks
 */
class User extends FOSUser
{
    use HasCreatedAt, HasUpdatedAt;

    /**
     * @var string|null
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $fullName;

    /**
     * @var bool
     * @ODM\Field(type="bool")
     */
    protected $emailConfirmed;

    /**
     * @var \App\Access\Document\UserSettings
     * @ODM\EmbedOne(targetDocument="UserSettings")
     */
    protected $settings;

    /**
     * @var string ISO country code
     * @ODM\Field(type="string", nullable=true)
     */
    protected $country;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $banReason;

    /**
     * User constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->addRole('ROLE_USER');
        $this->settings = new UserSettings();
        $this->emailConfirmed = false;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName ?? '';
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;
        if($enabled) {
            $this->banReason = '';
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBanReason(): string
    {
        return $this->banReason ?? '';
    }

    /**
     * @param string $banReason
     * @return User
     */
    public function setBanReason(string $banReason): User
    {
        $this->banReason = $banReason;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEmailConfirmed(): bool
    {
        return $this->emailConfirmed ?? true;
    }

    /**
     * @param bool $emailConfirmed
     * @return User
     */
    public function setEmailConfirmed(bool $emailConfirmed): User
    {
        $this->emailConfirmed = $emailConfirmed;

        return $this;
    }

    /**
     * Determines whether the given user's id equals to current user's id
     * @param User|null $anotherUser
     * @return bool
     */
    public function is(User $anotherUser = null)
    {
        return $anotherUser ? $this->getId() === $anotherUser->getId() : false;
    }

    /**
     * Determines whether the user has any of the given roles.
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        return count(array_intersect($roles, $this->getRoles())) > 0;
    }

    /**
     * Determines whether the user has all of the given roles.
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles)
    {
        return array_intersect($roles, $this->getRoles()) == $roles;
    }

    /**
     * @return \App\Access\Document\UserSettings
     */
    public function getSettings(): UserSettings
    {
        return $this->settings;
    }

    /**
     * @param \App\Access\Document\UserSettings $settings
     * @return User
     */
    public function setSettings(UserSettings $settings): User
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return string|null ISO country code
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $value ISO country code
     * @return User
     */
    public function setCountry(?string $value)
    {
        $this->country = $value;

        return $this;
    }
}