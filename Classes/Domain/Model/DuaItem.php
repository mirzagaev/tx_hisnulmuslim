<?php
namespace Vendor\Hisnulmuslim\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class DuaItem extends AbstractEntity
{
    protected string $type = ''; // ar, ar_translation, dua, dua_umschrift, dua_translation, hinweis
    protected string $content = '';

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
