<?php
/**
 * Copyright © Moip by PagSeguro. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Cron;

use Magento\Payment\Model\Method\Logger;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Moip\Magento2\Gateway\Config\ConfigCheckout;

/*
 * Class StatusUpdateOrderCheckout - Cron fetch order
 */
class StatusUpdateOrderCheckout
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ConfigCheckout
     */
    protected $configCheckout;

    /*
     * @param Order
     * @param Logger
     * @param ConfigCheckout
     * @param collectionFactory
     */
    public function __construct(
        Order $order,
        Logger $logger,
        ConfigCheckout $configCheckout,
        CollectionFactory $collectionFactory
    ) {
        $this->order = $order;
        $this->logger = $logger;
        $this->configCheckout = $configCheckout;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $orders = $this->collectionFactory->create()
        ->addFieldToFilter('status', [
            'in' => [
                Order::STATE_PAYMENT_REVIEW,
            ],
        ]);

        $orders->getSelect()
                ->join(
                    ['sop' => 'sales_order_payment'],
                    'main_table.entity_id = sop.parent_id',
                    ['method']
                )
                ->where('sop.method = ?', ConfigCheckout::METHOD);

        foreach ($orders as $order) {
            if (!$order->getEntityId()) {
                continue;
            }
            $loadedOrder = $this->order->load($order->getEntityId());

            if ($loadedOrder->canFetchPaymentReviewUpdate()) {
                $payment = $loadedOrder->getPayment()->save();

                try {
                    $payment->update(true);
                    $loadedOrder->save();
                    $this->logger->debug([
                        'cron'      => 'checkout',
                        'type'      => 'updateStatus',
                        'order'     => $loadedOrder->getIncrementId(),
                        'new_state' => $loadedOrder->getStatus(),
                    ]);
                } catch (\Exception $exc) {
                    $this->logger->debug([
                        'cron'  => 'checkout',
                        'type'  => 'updateStatus',
                        'order' => $loadedOrder->getIncrementId(),
                        'error' => $exc->getMessage(),
                    ]);
                }
            }
        }
    }
}
