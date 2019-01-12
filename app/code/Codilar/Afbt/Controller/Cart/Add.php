<?php

/**
 * @package     M2FBT
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Afbt\Controller\Cart;

use Codilar\Afbt\Helper\Data;
use Codilar\Core\Helper\Product;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

class Add extends Action
{
    /**
     * @var Cart
     */
    private $cart;
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    private $formKey;
    /**
     * @var Product
     */
    private $productHelper;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    private $cartHelper;

    /**
     * Add constructor.
     * @param Context $context
     * @param Cart $cart
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param Product $productHelper
     * @param Data $helper
     * @param JsonFactory $jsonFactory
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     */
    public function __construct(
        Context $context,
        Cart $cart,
        \Magento\Framework\Data\Form\FormKey $formKey,
        Product $productHelper,
        Data $helper,
        JsonFactory $jsonFactory,
        \Magento\Checkout\Helper\Cart $cartHelper
    )
    {
        $this->cart = $cart;
        $this->formKey = $formKey;
        $this->productHelper = $productHelper;
        $this->helper = $helper;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
        $this->cartHelper = $cartHelper;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if ($data) {
            $productIds = $data['products'];
            try {
                foreach ($productIds as $productId) {
                    $product = $this->helper->getProduct($productId);
                    $requestInfo = [
                        "product" => $productId,
                        "qty" => 1,
                        "form_key" => $this->formKey->getFormKey(),
                        "from_afbt" => 1
                    ];
                    $this->cartHelper->getCart()->addProduct($product, $requestInfo);
                }
                $this->messageManager->addSuccessMessage(__("You added the combo in cart!"));
                return $this->jsonFactory->create()->setData(["status" => true, "message" => __("You added the combo in cart!")]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->jsonFactory->create()->setData(["status" => false, "message" => $e->getMessage()]);
            }
            catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->jsonFactory->create()->setData(["status" => false, "message" => $e->getMessage()]);
            }
        }
    }
}