<?php

/**
 * @package     M2FBT
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */
namespace Codilar\Afbt\Api\Data;

interface AfbtIndexInterface
{

    /**
     * @return integer
     */
    public function getPpId();

    /**
     * @return string
     */
    public function getAspIds();

    /**
     * @return array|null
     */
    public function getAspIdsArray();

    /**
     * @param int $ppId
     * @return $this
     */
    public function setPpId($ppId);

    /**
     * @param int $aspIds
     * @return $this
     */
    public function setAspIds($aspIds);

    /**
     * @return array|string|int
     */
    public function getData();
}