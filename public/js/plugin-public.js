(function ($) {

    $(document).ready(function () {

        // selected hotel details show by selected options
        $('.hotel').on('change', function (e) {
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

        //by default add to cart button hide in pop up
        $(".hotel_details").find('.pop_up_add_to_cart_button').hide();


        $('.hotel_details').on('click', '.pop_up_add_to_cart_button', function (e) {

            var total_person = $('.hotel_details').find('.total_person').val();

            if (parseInt(total_person) == 0) {

                $('.total_person').addClass("total_person_error");

                $('.hotel_details').find('.total_person_show_error').append('<p class="error_text">Please add at least 1 traveller</p>');

                // $("form").submit(function (e) {
                //     e.preventDefault();
                //     $(this).unbind('.total_person');
                // });

                return false;
            }

        });


        $('.hotel_details').on('change', '.total_person', function () {
            var inputs = jQuery(this).val() || 0;
            var input = parseInt(inputs);

            if (input > 0) {
                jQuery('.total_person').removeClass("total_person_error");
                jQuery('.hotel_details').find('.total_person_show_error').find('p').hide();
            }else{
                jQuery('.total_person').addClass("total_person_error");
                jQuery('.hotel_details').find('.total_person_show_error').find('p').show();
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

                if (total_room_price > 0) {
                    $('.pop_up_add_to_cart_button').show();
                } else {
                    $('.pop_up_add_to_cart_button').hide();
                }

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

        //jquery dialog form
        $(function () {
            var dialog, form,

                dialog = $("#dialog-form").dialog({
                    autoOpen: false,
                    height: 400,
                    width: 350,
                    modal: true,
                    buttons: {
                        //"Buy": addUser,
                        Cancel: function () {
                            dialog.dialog("close");
                        }
                    },
                    close: function () {
                        //form[0].reset();
                        //allFields.removeClass("ui-state-error");
                    }
                });


            $("#create-user").button().on("click", function () {
                dialog.dialog("open");
            });

        });


        $("#create-user").click(function (event) {
            event.preventDefault();
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
