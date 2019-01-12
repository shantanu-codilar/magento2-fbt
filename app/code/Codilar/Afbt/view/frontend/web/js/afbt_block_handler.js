define(['jquery', 'underscore'], function ($, _) {
    return function (config) {
        var Afbt = {
            fetchUrl: config.afbtFetchUrl,
            parentProductId: config.product_id,
            cartAddUrl: config.addToCartUrl,
            form_key: config.form_key,
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
                            self.processAddCart();
                        } else {
                            $(".afbt-container").html("");
                        }
                    }
                });
            },
            processAddCart: function () {
                var self = this;
                $(document).on("click", ".add-to-cart-fbt", function () {
                    var parentProductId = $(this).closest("form").find("[name='product_parent']").val();
                    var associatedProductId = $(this).closest("form").find("[name='product_associated']").val();
                    var products = [];
                    products.push(parentProductId);
                    products.push(associatedProductId);
                    if (parentProductId && associatedProductId) {
                        $.ajax({
                            url: self.cartAddUrl+"product/"+parentProductId+"/",
                            method: "POST",
                            showLoader: true,
                            data: {products: products},
                            success: function (response) {
                                console.log(response);
                            }
                        });
                    }
                });
            }
        };
        /** initialize the afbt widget */
        Afbt.init();
    };
});