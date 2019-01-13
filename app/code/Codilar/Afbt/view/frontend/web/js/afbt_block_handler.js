define(['jquery', 'underscore', 'owlCarousel'], function ($, _, owlCarousel) {
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
                                var owl = $("#fbt-carousel-blog");
                                owl.owlCarousel({
                                    loop:false,
                                    nav:true,
                                    margin:15,
                                    navText: ['<div class="left-arrow">', '<div class="right-arrow">'],
                                    responsive:{
                                        0:{
                                            items:1,
                                            margin:0
                                        },
                                        600:{
                                            items:1,
                                            margin:0
                                        },
                                        1000:{
                                            items:3
                                        }
                                    }
                                });
                                checkClasses();
                                owl.on('translated.owl.carousel', function(event) {
                                    checkClasses();
                                });

                                function checkClasses(){
                                    var total = $('.fbt-container .owl-stage .owl-item.active').length;

                                    $('.fbt-container .owl-stage .owl-item').removeClass('lastActiveItem');

                                    $('.fbt-container .owl-stage .owl-item.active').each(function(index){
                                        if (index === total - 1 && total>1) {
                                            // this is the last one
                                            $(this).addClass('lastActiveItem');
                                        }
                                    });
                                }
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