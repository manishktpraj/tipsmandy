<?php


namespace App\Library\Locale;

use App\Models\Currency;

/**
* Currencies Array
*/
class Currencies
{

	public static function database()
	{
		$currencies = Currency::get();

		return $currencies;
	}

	public static function paypal()
	{
		return [

			'AUD' => 'Australian Dollar',
			'BRL' => 'Brazilian Real',
			'CAD' => 'Canadian Dollar',
			'CZK' => 'Czech Koruna',
			'DKK' => 'Danish Krone',
			'EUR' => 'Euro',
			'HKD' => 'Hong Kong Dollar',
			'HUF' => 'Hungarian Forint',
			'JPY' => 'Japanese Yen',
			'MYR' => 'Malaysian Ringgit',
			'MXN' => 'Mexican Peso',
			'NOK' => 'Norwegian Krone',
			'NZD' => 'New Zealand Dollar',
			'PHP' => 'Philippine Peso',
			'PLN' => 'Polish Zloty',
			'GBP' => 'Pound Sterling',
			'RUB' => 'Russian Ruble',
			'SGD' => 'Singapore Dollar',
			'SEK' => 'Swedish Krona',
			'CHF' => 'Swiss Franc',
			'TWD' => 'Taiwan New Dollar',
			'THB' => 'Thai Baht',
			'USD' => 'U.S. Dollar',

		];
	}

	public static function TwoCheckout()
	{
		return [

			'AUD' => 'Australian Dollar',
			'AFN' => 'Afghan Afghani',
			'ALL' => 'Albanian Lek',
			'DZD' => 'Algerian Dinar',
			'ARS' => 'Argentine Peso',
			'AZN' => 'Azerbaijani Manat',
			'BSD' => 'Bahamian Dollar',
			'BDT' => 'Bangladeshi Taka',
			'BBD' => 'Barbadian Dollar',
			'BZD' => 'Belize Dollar',
			'BMD' => 'Bermudan Dollar',
			'BOB' => 'Bolivian Boliviano',
			'BWP' => 'Botswana Pula',
			'BRL' => 'Brazilian Real',
			'BND' => 'Brunei Dollar',
			'BGN' => 'Bulgarian Lev',
			'CLP' => 'Chilean Peso',
			'CNY' => 'Chinese Yuan',
			'COP' => 'Colombian Peso',
			'CRC' => 'Costa Rican Colon',
			'HRK' => 'Croatian Kuna',
			'CZK' => 'Czeh Koruna',
			'DKK' => 'Danish Krone',
			'DOP' => 'Dominican Peso',
			'XCD' => 'East Caribbean Dollar',
			'EGP' => 'Egyptian Pound',
			'EUR' => 'Euro',
			'FJD' => 'Fijian Dollar',
			'GTQ' => 'Guatemalan Quetzal',
			'HKD' => 'Hong Kong Dollar',
			'HNL' => 'Honduran Lempira',
			'HUF' => 'Hungarian Forint',
			'INR' => 'Indian Rupee',
			'IDR' => 'Indonesian Rupiah',
			'JMD' => 'Jamaican Dollar',
			'JPY' => 'Japanese Yen',
			'KZT' => 'Kazakhstani Tenge',
			'KES' => 'Kenyan Shilling',
			'LAK' => 'Lao Kip, Democratic Rep',
			'MMK' => 'Kyat, Myanmar',
			'LBP' => 'Lebanese Pound',
			'LRD' => 'Liberian Dollar',
			'MOP' => 'Macanese Pataca',
			'MYR' => 'Malaysian Ringgit',
			'MVR' => 'Maldivian Rufiyaa',
			'MRO' => 'Mauritanian Ouguiya',
			'MUR' => 'Mauritian Rupee',
			'MXN' => 'Mexican Peso',
			'MAD' => 'Moroccan Dirham',
			'NPR' => 'Nepalese Rupee',
			'TWD' => 'New Taiwan Dollar',
			'NZD' => 'New Zealand Dollar',
			'NIO' => 'Nicaraguan Cordoba Oro',
			'NOK' => 'Norwegian Krone',
			'PKR' => 'Pakistani Rupee',
			'PGK' => 'Papua New Guinean Kina',
			'PEN' => 'Peruvian Nuevo Sol',
			'PHP' => 'Philippine Peso',
			'PLN' => 'Polish Zloty',
			'QAR' => 'Qatari Riyal',
			'RON' => 'Romanian Leu',
			'RUB' => 'Russian Ruble',
			'WST' => 'Samoan Tala',
			'SAR' => 'Saudi Riyal',
			'SCR' => 'Seychellois Rupee',
			'SGD' => 'Singaporean Dollar',
			'SBD' => 'Solomon Islands Dollar',
			'ZAR' => 'South African Rand',
			'KRW' => 'South Korean Won',
			'LKR' => 'Sri Lankan Rupee',
			'SEK' => 'Swedish Krona',
			'CHF' => 'Swiss Franc',
			'THB' => 'Thai Baht',
			'TOP' => 'Tongan Pa’anga',
			'TTD' => 'Trinidad and Tobago Dollar',
			'TRY' => 'Turkish Lira',
			'UAH' => 'Ukrainian Hryvnia',
			'AED' => 'United Arab Emirates Dirham',
			'USD' => 'United States Dollar',
			'VUV' => 'Vanuatu Vatu',
			'VND' => 'Vietnamese Dong',
			'XOF' => 'West African CFA Franc',
			'YER' => 'Yemeni Ria',

		];
	}

	public static function paysafecard()
	{
		return [

            'SKK' => 'Slovakia Koruny',
            'TRY' => 'Turkey New Lira',
            'NOK' => 'Norway Kroner',
            'RON' => 'Romania New Lei',
            'GBP' => 'United Kingdom Pounds',
            'EUR' => 'Euro',
            'USD' => 'United States Dollars',

		];
	}

}
