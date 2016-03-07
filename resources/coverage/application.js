/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$(function() {
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            var offset = parseInt($('body').css('margin-top')) + $('.ui.sticky.stats').outerHeight();

            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - offset
                }, 1000);
                return false;
            }
        }
    });

    $(".repoStats").each(function(){
        var stats = $(this).data('stats');
        var colors = $(this).data('colors');

        var result = {
            data: [],
            colors: []
        };

        stats.forEach(function(item, i){
            if (item.value > 0) {
                result.data.push(item);
                result.colors.push(colors[i]);
            }
        })

        Morris.Donut({
            element: $(this),
            colors: result.colors,
            data: result.data,
            formatter: function(y, data) {
                return y + "%";
            }
        });
    });
});