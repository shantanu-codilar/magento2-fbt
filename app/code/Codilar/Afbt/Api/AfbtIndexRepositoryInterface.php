<?php

/**
 * @package     M2FBT
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */
namespace Codilar\Afbt\Api;

use Codilar\Afbt\Api\Data\AfbtIndexInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface AfbtIndexRepositoryInterface 
{
    /**
     * @param AfbtIndexInterface $page
     * @return AfbtIndexInterface
     */
    public function save(AfbtIndexInterface $page);

    /**
     * @param $id
     * @return AfbtIndexInterface
     */
    public function getById($id);

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
}
