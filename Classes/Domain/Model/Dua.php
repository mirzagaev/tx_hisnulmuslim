<?php
namespace Webzadev\Hisnulmuslim\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use \Webzadev\Hisnulmuslim\Domain\Model\DuaItem;

class Dua extends AbstractEntity
{
    protected int $duaId = 0;

    /**
     * @var ObjectStorage<DuaItem>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $items;

    /**
     * @var Chapter|null
     */
    protected $chapter = null;

    public function __construct()
    {
        $this->items = new ObjectStorage();
    }

    public function getDuaId(): int
    {
        return $this->duaId;
    }

    public function setDuaId(int $duaId): void
    {
        $this->duaId = $duaId;
    }

    public function addItem(DuaItem $item): void
    {
        $this->items->attach($item);
    }

    public function removeItem(DuaItem $item): void
    {
        $this->items->detach($item);
    }

    /**
     * @return ObjectStorage<DuaItem>
     */
    public function getItems(): ObjectStorage
    {
        return $this->items;
    }
    
    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): void
    {
        $this->chapter = $chapter;
    }
}
