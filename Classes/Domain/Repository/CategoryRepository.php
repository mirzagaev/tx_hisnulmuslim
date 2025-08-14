<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class CategoryRepository extends Repository
{
    // protected array $defaultOrderings = ['title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];

    public function initializeObject(): void
    {
        $qs = $this->createQuery()->getQuerySettings();
        $qs->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($qs);
    }

    public function findByParent(int $parentUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('parent', $parentUid)
        );
        return $query->execute();
    }

    /**
     * Find only top-level categories (no parent set)
     */
    public function findTopLevel(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                $query->equals('parent', null),
                $query->equals('parent', 0)
            )
        );
        return $query->execute();
    }
}