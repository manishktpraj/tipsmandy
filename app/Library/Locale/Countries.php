<?php
namespace App\Library\Locale;

use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

/**
* Countries Array
*/
class Countries
{

	public static function get()
	{
		return [
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic Of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		];
	}

	public static function languages()
	{
		return $languages = array
		(
			'en' => 'English',
			'ar' => 'العربية',
			'az' => 'Azerbaijani',
			'br' => 'Português',
			'cn' => '简体中文',
			'ct' => '中文繁體',
			'de' => 'Deutsch',
			'ge' => 'ქართული ენა',
			'es' => 'Español',
			'fr' => 'Français',
			'ge' => 'ქართული',
			'hu' => 'Magyar',
			'id' => 'Bahasa Indonesia',
			'it' => 'Italiano',
			'jp' => '日本語',
			'kr' => '한국어',
			'nl' => 'Nederlands',
			'ro' => 'Română',
			'ru' => 'Pусский',
			'th' => 'ภาษาไทย',
			'tr' => 'Türkçe',
			'se' => 'Svenska',
			'pl' => 'Polski',
			'fi' => 'Suomi',
			'sk' => 'Slovenský',
			'uk' => 'Українська',
			'in' => 'हिंदी',
			'ph' => 'Filipino',
			'my' => 'Malay',
			'vi' => 'Tiếng Việt',
			'cz' => 'Čeština',
		);
	}

	/**
	 * Get Country Name
	 * @param string $country_code
	 * @return string $name
	 */
	public static function country_name($country_code)
	{
		// Get Country
		$country = Country::where('sort_name', $country_code)->first();

		if ($country) {

			return $country->name;

		}else{

			return 'N/A';

		}

	}

	/**
	 * Get Country Name
	 * @param string $id
	 * @return string $code
	 */
	public static function country_code($id)
	{
		// Get Country
		$country    = Country::where('id', $id)->first();

		if ($country) {
			return $country->sortname;
		}

		return 'US';
	}

	/**
	 * Get Country Name
	 * @param string $id
	 * @return string $code
	 */
	public static function country_by_id($id)
	{
		// Get Country
		$country = Country::where('id', $id)->first();

		if ($country) {
			return $country->name;
		}

		return 'United States';
	}

	/**
	 * Get State Name
	 * @param string $state_id
	 * @return string $name
	 */
	public static function state_name($state_id)
	{
		// Get State
		$state = State::where('id', $state_id)->first();

		if ($state) {

			return $state->name;

		}else{

			return 'N/A';

		}

	}

	/**
	 * Get City Name
	 * @param string $city_id
	 * @return string $name
	 */
	public static function city_name($city_id)
	{
		// Get City
		$city = City::where('id', $city_id)->first();

		if ($city) {

			return $city->name;

		}else{

			// City not found
			return 'N/A';

		}

	}

	/**
	 * Get Locale by country code
	 * @param string $country_code
	 * @param string $lang_code
	 * @return string $local
	 */
	public static function country_code_to_locale($country_code, $language_code = '')
	{

	    $locales = array('af-ZA',
	                    'am-ET',
	                    'ar-AE',
	                    'ar-BH',
	                    'ar-DZ',
	                    'ar-EG',
	                    'ar-IQ',
	                    'ar-JO',
	                    'ar-KW',
	                    'ar-LB',
	                    'ar-LY',
	                    'ar-MA',
	                    'arn-CL',
	                    'ar-OM',
	                    'ar-QA',
	                    'ar-SA',
	                    'ar-SY',
	                    'ar-TN',
	                    'ar-YE',
	                    'as-IN',
	                    'az-Cyrl-AZ',
	                    'az-Latn-AZ',
	                    'ba-RU',
	                    'be-BY',
	                    'bg-BG',
	                    'bn-BD',
	                    'bn-IN',
	                    'bo-CN',
	                    'br-FR',
	                    'bs-Cyrl-BA',
	                    'bs-Latn-BA',
	                    'ca-ES',
	                    'co-FR',
	                    'cs-CZ',
	                    'cy-GB',
	                    'da-DK',
	                    'de-AT',
	                    'de-CH',
	                    'de-DE',
	                    'de-LI',
	                    'de-LU',
	                    'dsb-DE',
	                    'dv-MV',
	                    'el-GR',
	                    'en-029',
	                    'en-AU',
	                    'en-BZ',
	                    'en-CA',
	                    'en-GB',
	                    'en-IE',
	                    'en-IN',
	                    'en-JM',
	                    'en-MY',
	                    'en-NZ',
	                    'en-PH',
	                    'en-SG',
	                    'en-TT',
	                    'en-US',
	                    'en-ZA',
	                    'en-ZW',
	                    'es-AR',
	                    'es-BO',
	                    'es-CL',
	                    'es-CO',
	                    'es-CR',
	                    'es-DO',
	                    'es-EC',
	                    'es-ES',
	                    'es-GT',
	                    'es-HN',
	                    'es-MX',
	                    'es-NI',
	                    'es-PA',
	                    'es-PE',
	                    'es-PR',
	                    'es-PY',
	                    'es-SV',
	                    'es-US',
	                    'es-UY',
	                    'es-VE',
	                    'et-EE',
	                    'eu-ES',
	                    'fa-IR',
	                    'fi-FI',
	                    'fil-PH',
	                    'fo-FO',
	                    'fr-BE',
	                    'fr-CA',
	                    'fr-CH',
	                    'fr-FR',
	                    'fr-LU',
	                    'fr-MC',
	                    'fy-NL',
	                    'ga-IE',
	                    'gd-GB',
	                    'gl-ES',
	                    'gsw-FR',
	                    'gu-IN',
	                    'ha-Latn-NG',
	                    'he-IL',
	                    'hi-IN',
	                    'hr-BA',
	                    'hr-HR',
	                    'hsb-DE',
	                    'hu-HU',
	                    'hy-AM',
	                    'id-ID',
	                    'ig-NG',
	                    'ii-CN',
	                    'is-IS',
	                    'it-CH',
	                    'it-IT',
	                    'iu-Cans-CA',
	                    'iu-Latn-CA',
	                    'ja-JP',
	                    'ka-GE',
	                    'kk-KZ',
	                    'kl-GL',
	                    'km-KH',
	                    'kn-IN',
	                    'kok-IN',
	                    'ko-KR',
	                    'ky-KG',
	                    'lb-LU',
	                    'lo-LA',
	                    'lt-LT',
	                    'lv-LV',
	                    'mi-NZ',
	                    'mk-MK',
	                    'ml-IN',
	                    'mn-MN',
	                    'mn-Mong-CN',
	                    'moh-CA',
	                    'mr-IN',
	                    'ms-BN',
	                    'ms-MY',
	                    'mt-MT',
	                    'nb-NO',
	                    'ne-NP',
	                    'nl-BE',
	                    'nl-NL',
	                    'nn-NO',
	                    'nso-ZA',
	                    'oc-FR',
	                    'or-IN',
	                    'pa-IN',
	                    'pl-PL',
	                    'prs-AF',
	                    'ps-AF',
	                    'pt-BR',
	                    'pt-PT',
	                    'qut-GT',
	                    'quz-BO',
	                    'quz-EC',
	                    'quz-PE',
	                    'rm-CH',
	                    'ro-RO',
	                    'ru-RU',
	                    'rw-RW',
	                    'sah-RU',
	                    'sa-IN',
	                    'se-FI',
	                    'se-NO',
	                    'se-SE',
	                    'si-LK',
	                    'sk-SK',
	                    'sl-SI',
	                    'sma-NO',
	                    'sma-SE',
	                    'smj-NO',
	                    'smj-SE',
	                    'smn-FI',
	                    'sms-FI',
	                    'sq-AL',
	                    'sr-Cyrl-BA',
	                    'sr-Cyrl-CS',
	                    'sr-Cyrl-ME',
	                    'sr-Cyrl-RS',
	                    'sr-Latn-BA',
	                    'sr-Latn-CS',
	                    'sr-Latn-ME',
	                    'sr-Latn-RS',
	                    'sv-FI',
	                    'sv-SE',
	                    'sw-KE',
	                    'syr-SY',
	                    'ta-IN',
	                    'te-IN',
	                    'tg-Cyrl-TJ',
	                    'th-TH',
	                    'tk-TM',
	                    'tn-ZA',
	                    'tr-TR',
	                    'tt-RU',
	                    'tzm-Latn-DZ',
	                    'ug-CN',
	                    'uk-UA',
	                    'ur-PK',
	                    'uz-Cyrl-UZ',
	                    'uz-Latn-UZ',
	                    'vi-VN',
	                    'wo-SN',
	                    'xh-ZA',
	                    'yo-NG',
	                    'zh-CN',
	                    'zh-HK',
	                    'zh-MO',
	                    'zh-SG',
	                    'zh-TW',
	                    'zu-ZA',);

	    foreach ($locales as $locale)
	    {
	        $locale_region = locale_get_region($locale);
	        $locale_language = locale_get_primary_language($locale);
	        $locale_array = array('language' => $locale_language,
	                             'region' => $locale_region);

	        if (strtoupper($country_code) == $locale_region &&
	            $language_code == '')
	        {
	            return locale_compose($locale_array);
	        }
	        elseif (strtoupper($country_code) == $locale_region &&
	                strtolower($language_code) == $locale_language)
	        {
	            return locale_compose($locale_array);
	        }
	    }

	    return null;
	}

}
