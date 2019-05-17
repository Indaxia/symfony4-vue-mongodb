<?php
/**
 * Created by PhpStorm.
 * User: Scorp
 * Date: 09.01.2018
 * Time: 17:20
 */

namespace App\Common\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait HasUpdatedAtIndexed
{
    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     * @ODM\Index(order="asc")
     */
    protected $updatedAt;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt ?? new \DateTime();
    }

    /**
     * @return bool
     */
    public function hasUpdatedAt(): bool
    {
        return (bool)$this->updatedAt;
    }

    /**
     * @ODM\PrePersist
     * @ODM\PreUpdate
     * @return static
     */
    public function touchUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }
}