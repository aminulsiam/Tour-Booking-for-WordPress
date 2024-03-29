(function ($) {

    $(document).ready(function () {

        // selected hotel details show by selected options
        $('.hotel').on('change', function () {
            var $hotel = $(this).val();
            var $tour = $(this).data('id');

            if ('other' == $hotel) {
                $('.hotel_details').addClass('no_hotel');
                $('.hotel_details').removeClass('display_hotel');
            } else {
                $('.hotel_details').addClass('display_hotel');
                $('.hotel_details').removeClass('no_hotel');

                $.ajax({
                    type: 'POST',
                    url: woo_tour.ajaxurl,
                    data: {
                        'action': 'show_hotel_by_option_selected',
                        'hotel_id': $hotel,
                        'tour_id': $tour,
                    },
                    dataType: "text",
                    success: function (data) {
                        $(".hotel_details").html(data);
                    },

                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }

                });

            }
        });//end selected options hotel room details

        //gallary pop up
        $('.gallary_image').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function (item) {
                    return item.el.attr('title') + '<small>by Mage People</small>';
                }
            }
        });


        // add to cart button disable if no of traveller is 0
        $('.hotel_details').on('change', '.total_person', function () {

            var inputs = jQuery(this).val() || 0;
            var input = parseInt(inputs);

            if (input > 0) {
                $('.pop_up_add_to_cart_button').attr('disabled', false);
                $('.error_text').hide();
            }else {
                $('.pop_up_add_to_cart_button').attr('disabled', true);
                $('.error_text').show();
            }
        });


        //total price fare and max person calculation
        $(".hotel_details").on("change", ".qty", function () {
            var total_room_price = 0;
            var total_person = 0;

            $(".qty").each(function () {
                var room_val = $(this).closest("tr").find(".price-td .room_price").text();
                var person_val = $(this).closest("tr").find(".price-td .person_capacity").text();

                total_room_price = parseInt(total_room_price + ($(this).val() * room_val || 0));
                total_person = parseInt(total_person + ($(this).val() * person_val || 0));

                $(".total").text(total_room_price);

                $(".total").text(total_room_price);

                $(".total_person").attr({"max": total_person});

            });//end total fare calculation and max person allowed

        });


        //Acordian
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }

        // fire this script when click on buy now
        $('.buy_tour_pakage_button').on('click', function (e) {

            e.preventDefault();

            $(this).hide(1000);
            $('.room_and_hotel_selection').show(1000);

        });


        // search autocomplete
        $(".search").autocomplete({
            source: woo_tour.pakages,
        });

        //prevent mouse scrolling for total person selected
        $('.hotel_details').on('mousewheel', '.total_person', function () {
            return false;
        });

    });
}(jQuery));
