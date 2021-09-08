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
 * Class TypeInterest - Define Type Interest.
 */
class TypeInterest implements ArrayInterface
{
    /**
     * Returns Options.
     *
     * @return array attributesArrays
     */
    public function toOptionArray()
    {
        return [
            'simple'   => __('Juros Simples'),
            'compound' => __('Juros Composto'),
        ];
    }
}
