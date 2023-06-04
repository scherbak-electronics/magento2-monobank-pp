define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'mono',
                component: 'Shch_Mono/js/view/payment/method-renderer/mono-method'
            }
        );
        return Component.extend({});
    }
);