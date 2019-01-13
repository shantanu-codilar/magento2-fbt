<?php

/**
 * @package     M2FBT
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Afbt\Helper;

use Codilar\Afbt\Model\AfbtIndex;
use Codilar\Afbt\Model\AfbtIndexFactory;
use Codilar\Afbt\Model\ResourceModel\AfbtIndex as AfbtIndexResource;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory as QuoteItemCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Helper\ImageFactory;


class Data extends AbstractHelper
{
    /**
     * @var QuoteItemCollectionFactory
     */
    private $quoteItemCollectionFactory;
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var AfbtIndexFactory
     */
    private $afbtIndexFactory;
    /**
     * @var AfbtIndexResource
     */
    private $afbtIndexResource;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var ProductResource
     */
    private $productResource;
    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param QuoteItemCollectionFactory $quoteItemCollectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param AfbtIndexFactory $afbtIndexFactory
     * @param AfbtIndexResource $afbtIndexResource
     * @param LoggerInterface $logger
     * @param ProductFactory $productFactory
     * @param ProductResource $productResource
     * @param ImageFactory $imageFactory
     */
    public function __construct(
        Context $context,
        QuoteItemCollectionFactory $quoteItemCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        AfbtIndexFactory $afbtIndexFactory,
        AfbtIndexResource $afbtIndexResource,
        LoggerInterface $logger,
        ProductFactory $productFactory,
        ProductResource $productResource,
        ImageFactory $imageFactory
    )
    {
        parent::__construct($context);
        $this->quoteItemCollectionFactory = $quoteItemCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->afbtIndexFactory = $afbtIndexFactory;
        $this->afbtIndexResource = $afbtIndexResource;
        $this->logger = $logger;
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        $this->imageFactory = $imageFactory;
    }

    /**
     * @return \Magento\Quote\Model\ResourceModel\Quote\Item\Collection
     */
    public function getQuoteItemCollectionFactory()
    {
        return $this->quoteItemCollectionFactory->create();
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        return $this->productCollectionFactory->create();
    }

    /**
     * @param $unsortedArray
     * @return array
     */
    public function getWeightSortedArray($unsortedArray)
    {
        $array = array_count_values($unsortedArray); //get all occurrences of each values
        arsort($array);
        $sortedArray = [];

        foreach($array as $key=>$val){ // iterate over occurrences array
            for($i=0;$i<$val;$i++){ //apply loop based on occurrences number
                $sortedArray[] = $key; // assign same name to the final array
            }
        }
        return array_unique($sortedArray);
    }

    /**
     * @param $productId
     * @param $associatedProducts
     * @return bool|int
     */
    public function createOrUpdateIndexRow($productId, $associatedProducts)
    {
        /** @var AfbtIndex $afbtIndex */
        $afbtIndex = $this->afbtIndexFactory->create();
        $this->afbtIndexResource->load($afbtIndex, $productId, "pp_id");
        $afbtIndex->setPpId($productId);
        $afbtIndex->setAspIds($associatedProducts);
        try {
            $this->afbtIndexResource->save($afbtIndex);
            return $afbtIndex->getId();
        } catch (AlreadyExistsException $e) {
            $this->logger->error("AFBT ERROR: ". $e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->logger->error("AFBT ERROR: ". $e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct($id)
    {
        $product = $this->productFactory->create();
        $this->productResource->load($product, $id);
        return $product;
    }

    /**
     * @param Product $product
     * @param string $imageId
     * @return string
     */
    public function getProductImage($product, $imageId = "category_page_list")
    {
        $image = $this->imageFactory->create()->init($product, $imageId)
            ->setImageFile($product->getFile());
        $imageUrl = $image->getUrl();
        return (string)$imageUrl;
    }


}