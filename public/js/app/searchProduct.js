$('document').ready(function () {
    /**
     * Permet de faire une recherche instantanée
     */
    $('input[name=searchProduct]').bind('keyup', function () {
        let val = $(this).val().toLowerCase();
        let products = $('.productsList');
        products.hide();
        products.each(function () {
            let text = $(this).text().toLowerCase();
            if (text.indexOf(val) !== -1) {
                $(this).show();
            }
        })
    });
});