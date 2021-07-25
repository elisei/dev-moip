/**
 * Copyright © Wirecard Brasil. All rights reserved.
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */
define(
    [
        "jquery",
        "Magento_Checkout/js/model/quote",
        "Magento_Checkout/js/model/url-builder",
        "Magento_Checkout/js/model/error-processor",
        "mage/url",
        "Magento_Checkout/js/action/get-totals"
    ],
    function ($, quote, urlBuilder, errorProcessor, urlFormatter, getTotalsAction) {
    "use strict";
    return {
        /**
         * Save SimpleNote ibn the quote
         *
         * @param installment
         */
        save(installment) {
            var quoteId = quote.getQuoteId();
            var url = urlBuilder.createUrl("/carts/mine/set-installment-for-moip-interest", {});
            var payload = {
                cartId: quoteId,
                installment: {
                    installment_for_interest: installment
                }
            };
            
            var result = true;
            $.ajax({
                url: urlFormatter.build(url),
                data: JSON.stringify(payload),
                global: false,
                contentType: "application/json",
                type: "PUT",
                async: false
            }).done(
                function (response) {
                    var deferred = $.Deferred();
                    getTotalsAction([], deferred); 
                }
            ).fail(
                function (response) {
                    result = false;
                    errorProcessor.process(response);
                }
            );
            return result;
        }
    };
});