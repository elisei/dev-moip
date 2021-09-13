<?php
/**
 * Copyright © Moip by PagSeguro. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Gateway\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Gateway\Config\Config as PaymentConfig;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigCheckout - Returns form of payment configuration properties.
 */
class ConfigCheckout extends PaymentConfig
{
    /**
     * Method code - Boleto.
     *
     * @const string
     */
    const METHOD = 'moip_magento2_checkout';

    /**
     * Active - Boleto.
     *
     * @const boolean
     */
    const ACTIVE = 'active';

    /**
     * Title - Moip Checkout.
     *
     * @const string
     */
    const TITLE = 'title';

    /**
     * Instruction in Checkout - Moip Checkout.
     *
     * @const string
     */
    const INSTRUCTION_CHECKOUT = 'instruction_checkout';

    /**
     * Use tax document capture - Moip Checkout.
     *
     * @const boolean
     */
    const USE_GET_TAX_DOCUMENT = 'get_tax_document';

    /**
     * Use name capture - Moip Checkout.
     *
     * @const boolean
     */
    const USE_GET_NAME = 'get_name';

    /**
     * Use name capture - Moip Checkout.
     *
     * @const boolean
     */
    const USE_GET_INSTALLMENTS = 'get_enable_installments';

    /**
     * Maximum installments allowed - Moip Checkout.
     *
     * @const string
     */
    const USE_MAX_INSTALLMENTS = 'max_installments';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Date
     */
    private $date;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param METHOD               $methodCode
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $methodCode = self::METHOD
    ) {
        PaymentConfig::__construct($scopeConfig, $methodCode);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Payment configuration status.
     *
     * @return bool
     */
    public function isActive($storeId = null): bool
    {
        $pathPattern = 'payment/%s/%s';

        return (bool) $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::ACTIVE),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get title of payment.
     *
     * @return string|null
     */
    public function getTitle($storeId = null): ?string
    {
        $pathPattern = 'payment/%s/%s';

        return $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::TITLE),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Instruction - Checkoout.
     *
     * @return string|null
     */
    public function getInstructionCheckout($storeId = null): ?string
    {
        $pathPattern = 'payment/%s/%s';

        return $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::INSTRUCTION_CHECKOUT),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get if you use document capture on the form.
     *
     * @return bool
     */
    public function getUseTaxDocumentCapture($storeId = null): bool
    {
        $pathPattern = 'payment/%s/%s';

        return (bool) $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::USE_GET_TAX_DOCUMENT),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get if you use name capture on the form.
     *
     * @return bool
     */
    public function getUseNameCapture($storeId = null): bool
    {
        $pathPattern = 'payment/%s/%s';

        return (bool) $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::USE_GET_NAME),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get if you use installments.
     *
     * @return bool
     */
    public function getUseInstallments($storeId = null): bool
    {
        $pathPattern = 'payment/%s/%s';

        return (bool) $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::USE_GET_INSTALLMENTS),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Max installments.
     *
     * @return int
     */
    public function getMaxInstallments($storeId = null): int
    {
        $pathPattern = 'payment/%s/%s';

        return (int) $this->scopeConfig->getValue(
            sprintf($pathPattern, self::METHOD, self::USE_MAX_INSTALLMENTS),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
