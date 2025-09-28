<?php
namespace Webzadev\Hisnulmuslim\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\Category as ExtbaseCategory;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    /**
     * @var ObjectStorage<FileReference>
     */
    protected $icon;
    
    /** @var string */
    protected $color = '';

    public function __construct()
    {
        $this->icon = new ObjectStorage();
    }

    /**
     * @psalm-return ObjectStorage<FileReference>
     */
    public function getIcon(): ObjectStorage
    {
        return $this->icon;
    }

    public function setIcon(ObjectStorage $icon): void
    {
        $this->icon = $icon;
    }

    public function addImage(FileReference $image): void
    {
        $this->icon->attach($image);
    }

    public function removeImage(FileReference $image): void
    {
        $this->icon->detach($image);
    }

    public function getFirstImage(): ?FileReference
    {
        $icon = $this->getIcon();
        $icon->rewind();

        return $icon->valid() ? $icon->current() : null;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}
