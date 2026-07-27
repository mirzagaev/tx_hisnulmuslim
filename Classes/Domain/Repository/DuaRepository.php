<?php

declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class DuaRepository extends Repository
{
    public function initializeObject(): void
    {
        $qs = $this->createQuery()->getQuerySettings();
        $qs->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($qs);
    }

    public function findByChapter(int $chapterUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('chapter', $chapterUid)
        );
        return $query->execute();
    }
}
