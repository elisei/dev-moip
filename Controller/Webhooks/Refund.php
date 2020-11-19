<?php
/**
 * Copyright © Wirecard Brasil. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Controller\Webhooks;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Payment\Model\Method\Logger;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\CreditmemoService;
use Magento\Store\Model\StoreManagerInterface;
use Moip\Magento2\Gateway\Config\Config;

/**
 * Class Refund - Receives communication for refunded payment.
 */
class Refund extends Action implements CsrfAwareActionInterface
{
    /**
     * createCsrfValidationException.
     *
     * @param RequestInterface $request
     *
     * @return null
     */
    public function createCsrfValidationException(RequestInterface $request): InvalidRequestException
    {
        if ($request) {
            return null;
        }
    }

    /**
     * validateForCsrf.
     *
     * @param RequestInterface $request
     *
     * @return bool true
     */
    public function validateForCsrf(RequestInterface $request): bool
    {
        if ($request) {
            return true;
        }
    }

    /**
     * @var logger
     */
    protected $logger;

    /**
     * @var orderFactory
     */
    protected $orderFactory;

    /**
     * @var resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var storeManager
     */
    protected $storeManager;

    /**
     * @param Context               $context
     * @param logger                $logger
     * @param Config                $config
     * @param OrderInterfaceFactory $orderFactory
     * @param JsonFactory           $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Config $config,
        Logger $logger,
        OrderInterfaceFactory $orderFactory,
        CreditmemoFactory $creditmemoFactory,
        CreditmemoService $creditmemoService,
        Invoice $invoice,
        StoreManagerInterface $storeManager,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->logger = $logger;
        $this->orderFactory = $orderFactory;
        $this->creditmemoFactory = $creditmemoFactory;
        $this->creditmemoService = $creditmemoService;
        $this->invoice = $invoice;
        $this->storeManager = $storeManager;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Command Accept.
     *
     * @return json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $response = $this->getRequest()->getContent();
        $originalNotification = json_decode($response, true);
        $authorization = $this->getRequest()->getHeader('Authorization');
        $storeId = $this->storeManager->getStore()->getId();
        $storeCaptureToken = $this->config->getMerchantGatewayRefundToken($storeId);
        if ($storeCaptureToken === $authorization) {
            $order = $this->orderFactory->create()->load($originalNotification['id'], 'ext_order_id');
            $this->logger->debug([
                'webhook'            => 'refund',
                'ext_order_id'       => $originalNotification['id'],
                'increment_order_id' => $order->getIncrementId(),
            ]);
            $invoices = $order->getInvoiceCollection();
            if ($invoices) {
                foreach ($invoices as $invoiceLoad) {
                    $invoiceincrementid = $invoiceLoad->getIncrementId();
                }
                $invoiceobj = $this->invoice->loadByIncrementId($invoiceincrementid);
                $creditmemo = $this->creditmemoFactory->createByOrder($order);
                $creditmemo->setInvoice($invoiceobj);

                try {
                    $this->creditmemoService->refund($creditmemo);
                } catch (\Exception $e) {
                    return $resultJson->setData([
                        'success' => 0,
                        'error'   => $e,
                    ]);
                }

                return $resultJson->setData([
                    'success' => 1,
                    'status'  => $order->getStatus(),
                    'state'   => $order->getState(),
                ]);
            }

            return $resultJson->setData([
                'success' => 0,
                'error'   => 'The transaction could not be refund',
            ]);
        }

        return $resultJson->setData(['success' => 0, 'fdp' => '']);
    }
}
