<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class ChapterRepository extends Repository
{
    public function initializeObject(): void
    {
        $qs = $this->createQuery()->getQuerySettings();
        $qs->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($qs);
    }

    public function findByCategory(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid)
        );
        return $query->execute();
    }

    public function findBySearchTerm(string $term): QueryResultInterface
    {
        $query = $this->createQuery();

        $query->matching(
            $query->logicalOr(
                $query->like('title', '%' . $term . '%'),
                $query->like('titleAr', '%' . $term . '%')
            )
        );

        return $query->execute();
    }

}