<?php
namespace Vendor\Hisnulmuslim\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Chapter extends AbstractEntity
{
    protected string $title = '';
    protected string $titleAr = '';

    /**
     * @var ObjectStorage<Dua>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $duas;

    public function __construct()
    {
        $this->duas = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitleAr(): string
    {
        return $this->titleAr;
    }

    public function setTitleAr(string $titleAr): void
    {
        $this->titleAr = $titleAr;
    }

    public function addDua(Dua $dua): void
    {
        $this->duas->attach($dua);
    }

    public function removeDua(Dua $dua): void
    {
        $this->duas->detach($dua);
    }

    /**
     * @return ObjectStorage<Dua>
     */
    public function getDuas(): ObjectStorage
    {
        return $this->duas;
    }
}
