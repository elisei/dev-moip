<?php
/**
 * Copyright © Wirecard Brasil. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Moip\Magento2\Block\Adminhtml\System\Config;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class Environment - Defines environment types.
 */
class Environment implements ArrayInterface
{
    /**
     * Returns Options.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            'production' => __('Production'),
            'sandbox'    => __('Sandbox - Environment for tests'),
        ];
    }
}
