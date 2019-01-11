<?php

/**
 * @package     M2FBT
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */
namespace Codilar\Afbt\Api;

use Codilar\Afbt\Api\Data\AfbtIndexInterface;
use Codilar\Afbt\Model\ResourceModel\AfbtIndex\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface AfbtIndexRepositoryInterface 
{
    /**
     * @param AfbtIndexInterface $page
     * @return AfbtIndexInterface
     */
    public function save(AfbtIndexInterface $page);

    /**
     * @param int $id
     * @param string|null $field
     * @return AfbtIndexInterface
     * @throws NoSuchEntityException
     */
    public function getById($id, $field);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param AfbtIndexInterface $page
     * @return mixed
     */
    public function delete(AfbtIndexInterface $page);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @return Collection
     */
    public function getCollection();
}
