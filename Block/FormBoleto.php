<?php
/**
 * Copyright © Moip by PagSeguro. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Block;

use Magento\Framework\View\Element\Template\Context;
use Moip\Magento2\Gateway\Config\ConfigBoleto;

/**
 * Class FormBoleto - Form for payment by boleto.
 */
class FormBoleto extends \Magento\Payment\Block\Form
{
    /**
     * Boleto template.
     *
     * @var string
     */
    protected $_template = 'Moip_Magento2::form/boleto.phtml';

    /**
     * @var ConfigBoleto
     */
    protected $configBoleto;

    /**
     * @param Context
     * @param ConfigBoleto
     */
    public function __construct(
        Context $context,
        ConfigBoleto $configBoleto
    ) {
        parent::__construct($context);
        $this->configBoleto = $configBoleto;
    }

    /**
     * Title - Boleto.
     *
     * @var string
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->configBoleto->getTitle();
    }

    /**
     * Instruction - Boleto.
     *
     * @var string
     *
     * @return string
     */
    public function getInstruction()
    {
        return $this->configBoleto->getInstructionCheckout();
    }

    /**
     * Expiration - Boleto.
     *
     * @var date
     *
     * @return string
     */
    public function getExpiration()
    {
        return $this->configBoleto->getExpirationFormat();
    }
}
