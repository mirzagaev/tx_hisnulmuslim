<?php
namespace Webzadev\Hisnulmuslim\Domain\Model;

use \Webzadev\Hisnulmuslim\Domain\Model\Dua;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Chapter extends AbstractEntity
{
    protected int $chapterId = 0;
    protected string $title = '';
    protected string $titleAr = '';
    protected string $slug = '';

    /**
     * @var ObjectStorage<Dua>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected ObjectStorage $dua;

    /**
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->dua = new ObjectStorage();
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

    public function getChapterId(): int
    {
        return $this->chapterId;
    }

    public function setChapterId(int $chapterId): void
    {
        $this->chapterId = $chapterId;
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

    /** @return ObjectStorage<Dua> */
    public function getDua(): ObjectStorage
    {
        return $this->dua;
    }

    /** @param ObjectStorage<Dua> $dua */
    public function setDua(ObjectStorage $dua): void
    {
        $this->dua = $dua;
    }

    public function addDua(Dua $dua): void
    {
        $this->dua->attach($dua);
    }

    public function removeDua(Dua $dua): void
    {
        $this->dua->detach($dua);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
