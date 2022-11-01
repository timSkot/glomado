(function ($) {
    $('.stm_lms_mixed_button__list [data-learners]').on('click', function (e) {
        e.preventDefault();
        var price = $(this).attr("data-learners");
        var label = $(this).attr("data-learners-label");
        var purchased = $(this).attr("data-purchased");
        var course_id = $(this).attr("data-course");
        document.cookie = `bookly_learns=${price}`;
        document.cookie = `bookly_learns_label=${label}`;
        document.cookie = `course_id=${course_id}`;
        document.cookie = `bookly_purchased=${purchased}`;

        $('body').addClass('closed');
        $('#bookly-popup').show();
    });
    $('#bookly-popup').on('click', function (e) {
        if($(e.target).closest('.bookly-form').length === 0 && e.target.id !== 'removePerson') {
            $('body').removeClass('closed');
            $('#bookly-popup').hide();
        }
    });
    $(document).keyup(function(e) {
        if (e.key === 'Escape') {
            $('body').removeClass('closed');
            $('#bookly-popup').hide();
        }
    });
    var existSelect = setInterval(function() {
        if ($('.bookly-service-step .bookly-form-group select').length) {
            clearInterval(existSelect);
            $('.bookly-service-step .bookly-form-group select').attr('disabled', 'disabled');
        }
    }, 100);
    $('.gamipress_tabs a').click(function (e) {
        e.preventDefault()
        var tabid = $(this).attr('href')
        $('.gamipress_tabs a, .gamipress_content').removeClass('active')

        $(tabid).addClass('active')
        $(this).addClass('active')
    })
    $('#bookly-popup').on('click', '#addNewLearner', function (e) {
        e.preventDefault();
        var persons_length = $('.bookly-details-person-list .bookly-details-person').length;
        persons_length++;
        var htmlNewLearner = '';
        htmlNewLearner += '<div class="bookly-details-person bookly-details-person-additional">';
            htmlNewLearner += '<div class="bookly-details-person-top">';
                htmlNewLearner += `<div class="count">#${persons_length}</div>`;
                htmlNewLearner +='<div id="removePerson" class="btn btn-danger">Remove <i class="fa fa-trash"></i></div>';
            htmlNewLearner += '</div>';
            htmlNewLearner += '<div class="bookly-box bookly-table">';
                htmlNewLearner += '<div class="bookly-form-group">';
                    htmlNewLearner += '<label>First Name</label>';
                    htmlNewLearner += '<div><input class="bookly-first-name" name="additionalName" type="text" value=""/></div>';
                htmlNewLearner += '</div>';
                htmlNewLearner += '<div class="bookly-form-group">';
                    htmlNewLearner += '<label>Last Name</label>';
                    htmlNewLearner += '<div><input class="bookly-last-name" name="additionalLastName" type="text" value=""/></div>';
                htmlNewLearner += '</div>';
            htmlNewLearner += '</div>';
            htmlNewLearner += '<div class="bookly-box bookly-table">';
                htmlNewLearner += '<div class="bookly-form-group">';
                    htmlNewLearner += '<label>Email</label>';
                    htmlNewLearner += '<div><input class="bookly-email" name="additionalEmail" type="text" value=""/></div>';
                htmlNewLearner += '</div>';
                htmlNewLearner += '<div class="bookly-form-group">';
                    htmlNewLearner += '<label>Phone</label>';
                    htmlNewLearner += '<div><input class="bookly-phone bookly-js-user-phone" name="additionalPhone" type="text" value=""/></div>';
                htmlNewLearner += '</div>';
                // htmlNewLearner += '<div class="bookly-form-group">';
                //     htmlNewLearner += '<label>Address</label>';
                //     htmlNewLearner += '<div><input id="address-autocomplete" class="bookly-address" name="additionalAddress" type="text" value=""/></div>';
                // htmlNewLearner += '</div>';
                htmlNewLearner += '</div>';
                htmlNewLearner += '</div>';
        htmlNewLearner += '</div>';

        $('.bookly-details-person-list').append(htmlNewLearner);

        $('.bookly-address').each(function () {
            new google.maps.places.Autocomplete(this);
        });

        $('.bookly-js-user-phone').intlTelInput();
    });
    $('#bookly-popup').on('click', '#removePerson', function (e) {
        e.preventDefault();
        $(this).parents('.bookly-details-person').remove();
    });
})(jQuery);