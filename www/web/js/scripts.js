let callbacksResults = document.getElementById('callbacks');
VK.init(function() {
    VK.addCallback('onOrderSuccess', function(order_id) {
        callbacksResults.innerHTML += '<br />onOrderSuccess '+order_id;
    });
    VK.addCallback('onOrderFail', function() {
        callbacksResults.innerHTML += '<br />onOrderFail';
    });
    VK.addCallback('onOrderCancel', function() {
        callbacksResults.innerHTML += '<br />onOrderCancel';
    });
    VK.callMethod('showOrderBox', {
        type: 'item',
        item: 'inue2019'
    });
}, function() {
    callbacksResults.innerHTML += '<br />Error';
}, '5.92');