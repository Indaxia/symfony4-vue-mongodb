<?php
/**
 * Created by PhpStorm.
 * User: Scorp
 * Date: 09.01.2018
 * Time: 17:20
 */

namespace App\Common\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait HasCreatedAtIndexed
{
    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     * @ODM\Index(order="asc")
     */
    protected $createdAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt ?? new \DateTime();
    }

    /**
     * @return bool
     */
    public function hasCreatedAt(): bool
    {
        return (bool)$this->createdAt;
    }

    /**
     * @ODM\PrePersist
     * @ODM\PreUpdate
     * @return static
     */
    public function touchCreatedAt()
    {
        $this->createdAt = $this->createdAt ?? new \DateTime();

        return $this;
    }
}