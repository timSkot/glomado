(function ($) {
    $(document).ready(function (){
        var $form = $('#bundle_filter_form');
        $form.on('submit', function (e) {
            e.preventDefault();
            var $this = $(this);
            var suburl = '?' + $this.serialize();

            $.ajax({
                url: stm_lms_ajaxurl + suburl,
                dataType: 'json',
                context: this,
                data: {
                    offset: 0,
                    action: 'stm_lms_load_bundle',
                    nonce: bundle_nonces['load_bundle']
                },
                beforeSend: function beforeSend() {
                    $('.stm_lms_bundles_with_filter_archive .stm_lms_my_course_bundles__list').addClass('loading');
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $('.stm_lms_bundles_with_filter_archive').offset().top - 130
                    }, 1000);
                },
                complete: function complete(data) {
                    data = data['responseJSON'];
                    $('.stm_lms_bundles_with_filter_archive .stm_lms_my_course_bundles__list').empty();
                    for (var prop in data) {
                        if( data.hasOwnProperty( prop ) ) {
                            var courses = data[prop].courses;
                            var percent = data[prop].bundle.rating.percent / data[prop].bundle.rating.count;
                            var htmlBundle = '';
                                htmlBundle += '<div class="stm_lms_single_bundle_card">';
                                    htmlBundle += '<div class="stm_lms_single_bundle_card__inner">';
                                        htmlBundle += '<div class="stm_lms_single_bundle_card__top heading_font">';
                                            htmlBundle += `<a href="${ data[prop].bundle.guid }" class="stm_lms_single_bundle_card__title">${ data[prop].bundle.post_title }</a>`;
                                            htmlBundle += `<div class="stm_lms_single_bundle_card__top_subplace"><span>${Object.keys(data[prop].courses).length} Courses</span></div>`;
                                        htmlBundle += '</div>';
                                        htmlBundle += '<div class="stm_lms_single_bundle_card__center_bottom">';
                                            htmlBundle += '<div class="stm_lms_single_bundle_card__center_bottom_inner">';
                                                htmlBundle += '<div class="stm_lms_single_bundle_card__center">';
                                                    htmlBundle += '<div class="stm_lms_single_bundle_card__courses">';
                                                        for (var course in courses) {
                                                            if( courses.hasOwnProperty( course ) ) {
                                                                htmlBundle += `<a class="stm_lms_single_bundle_card__course" href="${courses[course].link}">`;
                                                                    htmlBundle += '<div class="stm_lms_single_bundle_card__course_image">';
                                                                        htmlBundle += `<img src="${courses[course].image}">`;
                                                                    htmlBundle += '</div>';
                                                                    htmlBundle += '<div class="stm_lms_single_bundle_card__course_data heading_font">';
                                                                        htmlBundle += `<div class="stm_lms_single_bundle_card__course_title">${courses[course].title}</div>`;
                                                                        htmlBundle += `<div class="stm_lms_single_bundle_card__course_price">${courses[course].price}</div>`;
                                                                    htmlBundle += '</div>';
                                                                htmlBundle += '</a>';
                                                            }
                                                        }
                                                    htmlBundle += '</div>';
                                                htmlBundle += '</div>';
                                                htmlBundle += '<div class="stm_lms_single_bundle_card__bottom">';
                                                    htmlBundle += '<div class="stm_lms_single_bundle_card__rating heading_font">';
                                                        htmlBundle += '<div class="average-rating-stars__top">';
                                                            htmlBundle += '<div class="star-rating">';
                                                                htmlBundle += `<span style="width: ${ percent }%">${ data[prop].bundle.rating.average }</span>`;
                                                            htmlBundle += '</div>';
                                                            htmlBundle += `<div class="average-rating-stars__av heading_font">${ data[prop].bundle.rating.average } (${ data[prop].bundle.rating.count })</div>`;
                                                        htmlBundle += '</div>';
                                                    htmlBundle += '</div>';
                                                    htmlBundle += '<div class="stm_lms_single_bundle_card__price heading_font">';
                                                        htmlBundle += `<span class="bundle_price">${ data[prop].bundle.price }</span>`;
                                                    htmlBundle += '</div>';
                                                htmlBundle += '</div>';
                                            htmlBundle += '</div>';
                                        htmlBundle += '</div>';
                                    htmlBundle += '</div>';
                                htmlBundle += '</div>';

                            $('.stm_lms_my_course_bundles__list').append(htmlBundle);
                        }
                    }

                    $('.stm_lms_bundles_with_filter_archive .stm_lms_my_course_bundles__list').removeClass('loading');
                }
            });
        });
    });
})(jQuery);