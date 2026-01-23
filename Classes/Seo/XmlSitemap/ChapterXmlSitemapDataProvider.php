<?php

declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Seo\XmlSitemap;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider;

class ChapterXmlSitemapDataProvider extends RecordsXmlSitemapDataProvider
{
    /**
     * @return array
     */
    protected function findAll(): array
    {
        $table = $this->config['table'];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

        $queryBuilder
            ->select($table . '.*', 'sys_category.uid AS category_uid')
            ->from($table)
            ->join(
                $table,
                'tx_hisnulmuslim_domain_model_chapter_category_mm',
                'mm',
                $queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->quoteIdentifier($table) . '.uid')
            )
            ->join(
                'mm',
                'sys_category',
                'sys_category',
                $queryBuilder->expr()->eq('sys_category.uid', 'mm.uid_foreign')
            );

        // Filter sys_category (versteckte/gelöschte ignorieren)
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('sys_category.deleted', 0),
            $queryBuilder->expr()->eq('sys_category.hidden', 0)
        );

        if (isset($this->config['pid'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq($table . '.pid', (int)$this->config['pid'])
            );
        }

        if (isset($this->config['sortField'])) {
            $sortDirection = $this->config['sortDir'] ?? 'ASC';
            $queryBuilder->orderBy($table . '.' . $this->config['sortField'], $sortDirection);
        }

        // Sprache berücksichtigen (falls konfiguriert)
        if (isset($GLOBALS['TCA'][$table]['ctrl']['languageField'])) {
            $languageField = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
            $queryBuilder->andWhere(
                $queryBuilder->expr()->in(
                    $table . '.' . $languageField,
                    [-1, $this->language]
                )
            );
        }

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
