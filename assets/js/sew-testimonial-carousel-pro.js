(function ($) {
    'use strict';

    var initSwiper = function ($scope) {
        var $carousel = $scope.find('.sew-testimonial-carousel-container');
        if (!$carousel.length) {
            return;
        }

        var settings = $carousel.data('settings') || {};

        var swiperOptions = {
            direction: 'horizontal',
            loop: settings.loop !== undefined ? settings.loop : true,
            autoplay: settings.autoplay ? {
                delay: settings.autoplaySpeed || 3000,
                disableOnInteraction: false,
            } : false,
            slidesPerView: (settings.slidesPerView && settings.slidesPerView.mobile) || 1,
            spaceBetween: (settings.spaceBetween && settings.spaceBetween.mobile) || 10,
            breakpoints: {
                768: {
                    slidesPerView: (settings.slidesPerView && settings.slidesPerView.tablet) || 2,
                    spaceBetween: (settings.spaceBetween && settings.spaceBetween.tablet) || 15
                },
                1024: {
                    slidesPerView: (settings.slidesPerView && settings.slidesPerView.desktop) || 3,
                    spaceBetween: (settings.spaceBetween && settings.spaceBetween.desktop) || 20
                }
            },
            navigation: {
                nextEl: $scope.find('.sew-swiper-button-next')[0],
                prevEl: $scope.find('.sew-swiper-button-prev')[0],
            },
            pagination: {
                el: $scope.find('.swiper-pagination')[0],
                clickable: true,
            },
        };

        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.utils && elementorFrontend.utils.swiper) {
            new elementorFrontend.utils.swiper($carousel[0], swiperOptions).then(function (swiperInstance) {
                // Swiper Ready
            });
        } else if (typeof Swiper !== 'undefined') {
            new Swiper($carousel[0], swiperOptions);
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/sew-testimonial-carousel-pro.default', function ($scope) {
            initSwiper($scope);
        });
    });

})(jQuery);