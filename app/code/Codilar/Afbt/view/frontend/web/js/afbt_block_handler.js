define(['jquery', 'underscore'], function ($, _) {
    return function (config) {
        var Afbt = {
            fetchUrl: config.afbtFetchUrl,
            parentProductId: config.product_id,
            init: function () {
                var self = this;
                self.getAssociatedProducts();
            },
            getAssociatedProducts: function () {
                var self = this;
                $.ajax({
                    url: self.fetchUrl,
                    method: "POST",
                    data: {product_id: self.parentProductId},
                    success: function (response) {
                        if (response.status) {
                            require([
                                "text!"+require.toUrl("Codilar_Afbt/template/afbt_cards.html")
                            ], function (template) {
                                template = _.template(template);
                                $(".afbt-container").html(template({
                                    productsData: response
                                }));
                            });
                        } else {
                            $(".afbt-container").html("");
                        }
                    }
                });
            }
        };
        /** initialize the afbt widget */
        Afbt.init();
    };
});