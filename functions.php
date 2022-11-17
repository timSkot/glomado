<?php

use Bookly\Lib\Base\Installer;
use Bookly\Lib\Entities\Customer;
use Bookly\Lib\Utils\Common;

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    $ajaxurl = admin_url( 'admin-ajax.php' );
    global $wp_locale;
    // Theme main stylesheet
    wp_enqueue_style( 'theme-style', get_stylesheet_uri(), null, 1.7, 'all' );
    wp_enqueue_style( 'select2-gld', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', null, STM_THEME_VERSION, 'all' );

    $path_bookly = WP_PLUGIN_URL . '/bookly-responsive-appointment-booking-tool';

    wp_enqueue_style( 'bookly-ladda.min.css', $path_bookly . '/frontend/resources/css/ladda.min.css', null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'bookly-picker.classic.css', $path_bookly . '/frontend/resources/css/picker.classic.css', null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'bookly-picker.classic.date.css', $path_bookly . '/frontend/resources/css/picker.classic.date.css', null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'bookly-intlTelInput.css', $path_bookly . '/frontend/resources/css/intlTelInput.css', null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'bookly-bookly-main.css', $path_bookly . '/frontend/resources/css/bookly-main.css', null, STM_THEME_VERSION, 'all' );

    wp_enqueue_script('bookly-spin.min.js', $path_bookly . '/frontend/resources/js/spin.min.js', null, STM_THEME_VERSION, true);
    wp_localize_script( 'bookly-spin.min.js', 'BooklyL10nGlobal', array(
        'csrf_token' => Common::getCsrfToken(),
    ) );

    wp_enqueue_script('bookly-ladda.min.js', $path_bookly . '/frontend/resources/js/ladda.min.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-moment.min.js', $path_bookly . '/backend/resources/js/moment.min.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-hammer.min.js', $path_bookly . '/frontend/resources/js/hammer.min.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-jquery.hammer.min.js', $path_bookly . '/frontend/resources/js/jquery.hammer.min.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-picker.js', $path_bookly . '/frontend/resources/js/picker.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-picker.date.js', $path_bookly . '/frontend/resources/js/picker.date.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-bookly.min.js', $path_bookly . '/frontend/resources/js/bookly.min.js', null, 2.6, true);
    wp_localize_script( 'bookly-bookly.min.js', 'BooklyL10n', array(
        'ajaxurl' => $ajaxurl,
        'csrf_token' => Common::getCsrfToken(),
        'today' => __( 'Today', 'bookly' ),
        'months' => array_values( $wp_locale->month ),
        'days' => array_values( $wp_locale->weekday ),
        'daysShort' => array_values( $wp_locale->weekday_abbrev ),
        'monthsShort' => array_values( $wp_locale->month_abbrev ),
        'nextMonth' => __( 'Next month', 'bookly' ),
        'prevMonth' => __( 'Previous month', 'bookly' ),
        'show_more' => __( 'Show more', 'bookly' ),
    ) );
    wp_enqueue_script('bookly-intlTelInput.min.js', $path_bookly . '/frontend/resources/js/intlTelInput.min.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('bookly-filter', get_stylesheet_directory_uri() . '/assets/js/bundle_filter.js', null, STM_THEME_VERSION, true);
    wp_enqueue_script('google-map-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDSKy96bazMCE6yEM5HzMV-oIeNZu9W4_M&libraries=places', null, STM_THEME_VERSION, true);
    wp_enqueue_script('glomado.js', get_stylesheet_directory_uri() . '/assets/js/glomado.js', null, '1.2', true);

}

add_action('admin_head', 'admin_gm_styles');
function admin_gm_styles() {
    wp_enqueue_style( 'admin-gm', get_stylesheet_directory_uri() . '/assets/admin/css/admin-bar.css', null, 2, 'all' );
}

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields ($fields)
{
    $fields['billing']['billing_first_name']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_first_name', true);
    $fields['billing']['billing_last_name']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_last_name', true);
    $fields['billing']['billing_email']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_email', true);
    $fields['billing']['billing_city']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_city', true);
    $fields['billing']['billing_postcode']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_postcode', true);
    $fields['billing']['billing_state']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_state', true);
    $fields['billing']['billing_address_1']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_address_1', true);

    add_filter( 'default_checkout_billing_country', 'njengah_change_default_checkout_country' );

    function njengah_change_default_checkout_country () {
        $country_name = get_user_meta($customer_id = get_current_user_id(), 'billing_country', true);
        $countryList = array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "BQ" => "British Antarctic Territory",
            "IO" => "British Indian Ocean Territory",
            "VG" => "British Virgin Islands",
            "BN" => "Brunei",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CT" => "Canton and Enderbury Islands",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos [Keeling] Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo - Brazzaville",
            "CD" => "Congo - Kinshasa",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "CI" => "Côte d’Ivoire",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "NQ" => "Dronning Maud Land",
            "DD" => "East Germany",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "FQ" => "French Southern and Antarctic Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "HN" => "Honduras",
            "HK" => "Hong Kong SAR China",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JT" => "Johnston Island",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macau SAR China",
            "MK" => "Macedonia",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "FX" => "Metropolitan France",
            "MX" => "Mexico",
            "FM" => "Micronesia",
            "MI" => "Midway Islands",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar [Burma]",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NT" => "Neutral Zone",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "KP" => "North Korea",
            "VD" => "North Vietnam",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PC" => "Pacific Islands Trust Territory",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territories",
            "PA" => "Panama",
            "PZ" => "Panama Canal Zone",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "YD" => "People's Democratic Republic of Yemen",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn Islands",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RO" => "Romania",
            "RU" => "Russia",
            "RW" => "Rwanda",
            "RE" => "Réunion",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "KR" => "South Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "ST" => "São Tomé and Príncipe",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UM" => "U.S. Minor Outlying Islands",
            "PU" => "U.S. Miscellaneous Pacific Islands",
            "VI" => "U.S. Virgin Islands",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "SU" => "Union of Soviet Socialist Republics",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "ZZ" => "Unknown or Invalid Region",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VA" => "Vatican City",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "WK" => "Wake Island",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
            "AX" => "Åland Islands",
        );
        $sim = '';
        foreach($countryList as $key => $val) {
            if(preg_match("/{$country_name}/i", $val)) {
                $sim = $key;
            }
        }
        return $sim;

    }

    $fields['billing']['billing_postcode']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_postcode', true);
    $fields['billing']['billing_state']['default'] = get_user_meta($customer_id = get_current_user_id(), 'billing_state', true);
    return $fields;
}

add_action('woocommerce_cancelled_order','glomado_cancelled_order');
function glomado_cancelled_order() {
  wp_redirect(wc_get_checkout_url());
}

$inc_path = get_stylesheet_directory() . '/inc';
require_once $inc_path .'/glokit_metabox.php';
require_once $inc_path .'/manage_course_glokit.php';
require_once $inc_path .'/glokit_filters.php';
require_once $inc_path .'/bookly_sync_product.php';
require_once $inc_path .'/bookly_service.php';
require_once $inc_path .'/bundle-helper.php';
require_once $inc_path .'/bookly_woo_int.php';