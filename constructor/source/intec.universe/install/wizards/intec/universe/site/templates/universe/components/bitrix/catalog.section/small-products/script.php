<?php

/**
 * @var string $componentHash
 */

?>
<script>
    $(document).ready(function(){
        var root = $('#<?= $componentHash ?>');
        var slider = root.find('.owl-carousel');

        slider.owlCarousel({
            loop: false,
            margin: 10,
            nav: false,
            navText: ['<i class="fa fa-arrow-left intec-cl-text-hover"></i>', '<i class="fa fa-arrow-right intec-cl-text-hover"></i>'],
            autoplay: false,
            autoplayTimeout: 5000,
            autoplayHoverPause: false,
            responsive:{
                0: { items:1 },
                500: { items:2 },
                900: { items:2 },
                1000: { items:4 }
            },
            dots: true,
            dotsData: false
        });
    });
</script>