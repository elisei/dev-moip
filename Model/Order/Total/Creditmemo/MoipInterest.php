<?php
/**
 * Copyright © Wirecard Brasil. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Moip\Magento2\Model\Order\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

/**
 * Class MoipInterest - Model data Total Creditmemo.
 */
class MoipInterest extends AbstractTotal
{
    /**
     * @param Creditmemo $creditmemo
     *
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        $moipInterestAmountInvoiced = $order->getMoipInterestAmountInvoiced();
        $baseMoipInterestAmountInvoiced = $order->getBaseMoipInterestAmountInvoiced();

        if ((int) $moipInterestAmountInvoiced === 0) {
            return $this;
        }

        $moipInterestAmountRefunded = $order->getMoipInterestAmountRefunded();
        if ((int) $moipInterestAmountRefunded === 0) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $moipInterestAmountInvoiced);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseMoipInterestAmountInvoiced);
            $creditmemo->setMoipInterestAmount($moipInterestAmountInvoiced);
            $creditmemo->setBaseMoipInterestAmount($baseMoipInterestAmountInvoiced);
            $order->setMoipInterestAmountRefunded($moipInterestAmountInvoiced);
            $order->setBaseMoipInterestAmountRefunded($baseMoipInterestAmountInvoiced);
        }

        return $this;
    }
}
