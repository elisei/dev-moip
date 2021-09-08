<?php
/**
 * Copyright © Wirecard Brasil. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Gateway\Request;

use Magento\Framework\Url;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Moip\Magento2\Gateway\Config\Config;
use Moip\Magento2\Gateway\Config\ConfigCheckout;
use Moip\Magento2\Gateway\Data\Order\OrderAdapterFactory;
use Moip\Magento2\Gateway\SubjectReader;

/**
 * Class CheckoutPreferencesRequest - Checkout Preferences Request structure.
 */
class CheckoutPreferencesRequest implements BuilderInterface
{
    /**
     * Checkout Preferences block name.
     */
    const CHECKOUT_PREFERENCE = 'checkoutPreferences';

    /**
     * Redirect Urls block name.
     */
    const REDIRECT_URLS = 'redirectUrls';

    /**
     * Url for Callback Success.
     */
    const REDIRECT_URL_SUCCESS = 'urlSuccess';

    /**
     * Url for Callback Failure.
     */
    const REDIRECT_URL_FAILURE = 'urlFailure';

    /**
     * Installments Block name.
     */
    const INSTALLMENTS = 'installments';

    /**
     * Installments Qty Block name.
     */
    const INSTALLMENTS_QTY = 'quantity';

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var OrderAdapterFactory
     */
    private $orderAdapterFactory;

    /**
     * @var Config
     */
    private $configCheckout;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Url
     */
    private $urlHelper;

    /**
     * @param SubjectReader       $subjectReader
     * @param OrderAdapterFactory $orderAdapterFactory
     * @param Config              $config
     */
    public function __construct(
        SubjectReader $subjectReader,
        OrderAdapterFactory $orderAdapterFactory,
        Config $config,
        ConfigCheckout $configCheckout,
        Url $urlHelper
    ) {
        $this->subjectReader = $subjectReader;
        $this->orderAdapterFactory = $orderAdapterFactory;
        $this->config = $config;
        $this->configCheckout = $configCheckout;
        $this->urlHelper = $urlHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        $payment = $paymentDO->getPayment();

        $result = [];

        $orderAdapter = $this->orderAdapterFactory->create(
            ['order' => $payment->getOrder()]
        );
        $order = $paymentDO->getOrder();
        $storeId = $order->getStoreId();

        $orderId = $orderAdapter->getId();
        //tanto order adapter como o order do pay retorna null para getOrderId(), getId() ou getEntityId()... o.O
        // $urlViewOrder = $this->urlHelper->getUrl('sales/order/view/', [ '_scope' => $storeId, 'order_id' => $orderId, '_nosid' => true ]);
        $urlViewOrder = $this->urlHelper->getUrl('sales/order/history/', ['_scope' => $storeId, '_nosid' => true]);

        $result[self::CHECKOUT_PREFERENCE][self::REDIRECT_URLS] = [
            self::REDIRECT_URL_SUCCESS => $urlViewOrder,
            self::REDIRECT_URL_FAILURE => $urlViewOrder,
        ];
        if ($payment->getAdditionalInformation('checkout_enable_installments')) {
            $result[self::CHECKOUT_PREFERENCE][self::INSTALLMENTS][] = [
                self::INSTALLMENTS_QTY => [
                    1,
                    $this->configCheckout->getMaxInstallments(),
                ],
            ];
        } else {
            $result[self::CHECKOUT_PREFERENCE][self::INSTALLMENTS][] = [
                self::INSTALLMENTS_QTY => [
                    1,
                ],
            ];
        }

        return $result;
    }
}