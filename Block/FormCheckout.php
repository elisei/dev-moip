<?php
/**
 * Copyright © Wirecard Brasil. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Block;

use Magento\Framework\View\Element\Template\Context;
use Moip\Magento2\Gateway\Config\ConfigCheckout;

/**
 * Class FormCheckout - Form for payment by moip checkout.
 */
class FormCheckout extends \Magento\Payment\Block\Form
{
    /**
     * Moip Checkout template.
     *
     * @var string
     */
    protected $_template = 'Moip_Magento2::form/checkout.phtml';

    /**
     * @var configCheckout
     */
    protected $configCheckout;

    /**
     * @param Context
     * @param ConfigCheckout
     */
    public function __construct(
        Context $context,
        ConfigCheckout $configCheckout
    ) {
        parent::__construct($context);
        $this->configCheckout = $configCheckout;
    }

    /**
     * Title - Moip Checkout.
     *
     * @var string
     */
    public function getTitle(): string
    {
        return $this->configCheckout->getTitle();
    }

    /**
     * Instruction - Moip Checkout.
     *
     * @var string
     */
    public function getInstruction(): string
    {
        return $this->configCheckout->getInstructionCheckout();
    }

    /**
     * Use tax document capture - Moip Checkout.
     *
     * @var bool
     */
    public function getTaxDocumentCapture(): ?bool
    {
        return $this->configCheckout->getUseTaxDocumentCapture();
    }

    /**
     * Use Holder Name capture - Moip Checkout.
     *
     * @var bool
     */
    public function getNameCapture(): ?bool
    {
        return $this->configCheckout->getUseNameCapture();
    }

    /**
     * Use Enable Installments - Moip Checkout.
     *
     * @var bool
     */
    public function getEnableInstallments(): ?bool
    {
        return $this->configCheckout->getUseInstallments();
    }
}
