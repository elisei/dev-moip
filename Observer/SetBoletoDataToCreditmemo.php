<?php
/**
 * Copyright © Moip by PagSeguro. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Observer;

use Magento\Framework\Event\ObserverInterface;
use Moip\Magento2\Block\Adminhtml\Sales\Creditmemo as CreditmemoBlock;
use Moip\Magento2\Model\Ui\ConfigProviderBoleto;

/**
 * Class SetBoletoDataToCreditmemo - Set refund data of boleto.
 */
class SetBoletoDataToCreditmemo implements ObserverInterface
{
    /**
     * Set boleto data to creditmemo before register.
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $input = $observer->getEvent()->getInput();
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() === ConfigProviderBoleto::CODE) {
            $bankNumber = !empty($input[CreditmemoBlock::BANK_NUMBER]) ?
                                    $input[CreditmemoBlock::BANK_NUMBER] : null;
            $creditmemo->setData(CreditmemoBlock::BANK_NUMBER, $bankNumber);

            $agencyNumber = !empty($input[CreditmemoBlock::AGENCY_NUMBER]) ?
                                        $input[CreditmemoBlock::AGENCY_NUMBER] : null;
            $creditmemo->setData(CreditmemoBlock::AGENCY_NUMBER, $agencyNumber);

            $agencyCheckNumber = !empty($input[CreditmemoBlock::AGENCY_CHECK_NUMBER]) ?
                                            $input[CreditmemoBlock::AGENCY_CHECK_NUMBER] : null;
            $creditmemo->setData(CreditmemoBlock::AGENCY_CHECK_NUMBER, $agencyCheckNumber);

            $accountNumber = !empty($input[CreditmemoBlock::ACCOUNT_NUMBER]) ?
                                    $input[CreditmemoBlock::ACCOUNT_NUMBER] : null;
            $creditmemo->setData(CreditmemoBlock::ACCOUNT_NUMBER, $accountNumber);

            $accountCheckNumber = !empty($input[CreditmemoBlock::ACCOUNT_CHECK_NUMBER]) ?
                                            $input[CreditmemoBlock::ACCOUNT_CHECK_NUMBER] : null;
            $creditmemo->setData(CreditmemoBlock::ACCOUNT_CHECK_NUMBER, $accountCheckNumber);

            $holderFullname = !empty($input[CreditmemoBlock::HOLDER_FULLNAME]) ?
                                        $input[CreditmemoBlock::HOLDER_FULLNAME] : null;
            $creditmemo->setData(CreditmemoBlock::HOLDER_FULLNAME, $holderFullname);

            $holderDocumment = !empty($input[CreditmemoBlock::HOLDER_DOCUMENT_NUMBER]) ?
                                        $input[CreditmemoBlock::HOLDER_DOCUMENT_NUMBER] : null;
            $creditmemo->setData(CreditmemoBlock::HOLDER_DOCUMENT_NUMBER, $holderDocumment);
        }

        return $this;
    }
}
