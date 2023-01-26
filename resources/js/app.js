import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

$(document).ready(function(){
    let currency = Intl.NumberFormat("en-US");

    $('a.btnprn').click(function(){
        window.print();
        return false;
    });

    var grandTotal = 0;
    $('.total-amount').simpleMoneyFormat();
    $(".total-amount").each(function () {
        var productVal = parseFloat($(this).html().replace(/,/g, ""));
        grandTotal += isNaN(productVal) ? 0 : productVal;
        currency.format($(this).html());
    });
    $('.total').html(currency.format(grandTotal.toFixed(2)));
});
