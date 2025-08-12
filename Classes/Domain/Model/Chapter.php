<?php
namespace Webzadev\Hisnulmuslim\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Chapter extends AbstractEntity
{
    protected string $title = '';
    protected string $titleAr = '';

    /**
     * @var ObjectStorage<Dua>
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $duas;
    protected $categories;

    public function __construct()
    {
        $this->duas = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function addCategory(Category $category): void
    {
        $this->categories->attach($category);
    }

    public function removeCategory(Category $category): void
    {
        $this->categories->detach($category);
    }

    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
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
