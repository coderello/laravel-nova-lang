<?php

namespace Coderello\LaravelNovaLang\Commands;

use Illuminate\Support\Collection;
use SplFileInfo;

class NovaLangCountry extends AbstractDevCommand
{
    protected const SAVED_COUNTRY_NAMES = 'Country names for "%s" locale have been added to [%s].';
    protected const RUN_MISSING_COMMAND = 'Additionally, country names were not found for %d keys. These were not added to the file. Run the command `php nova-lang missing` to add them.';
    protected const COUNTRY_NAMES_NOT_FOUND = 'Country names could not be found for "%s" locale. The file was not updated.';

    protected const CLDR_URL = 'https://github.com/unicode-org/cldr-json/raw/main/cldr-json/cldr-localenames-modern/main/%s/territories.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'country
                            {locales? : Comma-separated list of languages}
                            {--all : Output all languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download country names from the Unicode CLDR.';

    /**
     * Handle the command for a given locale.
     *
     * @param string $locale
     * @return void
     */
    protected function handleLocale(string $locale): void
    {
        if (!$this->availableLocales->contains($locale)) {
            $this->warn(sprintf(static::LOCALE_FILE_DOES_NOT_EXIST, $locale));

            if (!$this->confirm(sprintf(static::WANT_TO_CREATE_FILE, $locale))) {
                exit;
            }
        }

        [$cldrKeys, $untranslated] = $this->downloadCldr($locale);

        $inputFile = $this->directoryFrom("$locale.json");

        $localeTranslations = $this->loadJson($inputFile);

        $outputKeys = [];

        foreach ($this->sourceKeys as $sourceKey) {
            if ($translation = $cldrKeys[$sourceKey] ?? $localeTranslations[$sourceKey] ?? null) {
                $outputKeys[$sourceKey] = $translation;
            }
        }

        $outputKeys = array_filter($outputKeys, fn ($value) => $value != null);

        $outputFile = $inputFile;

        if (count($cldrKeys)) {
            $this->saveJson($outputFile, $outputKeys);

            $this->info(sprintf(static::SAVED_COUNTRY_NAMES, $locale, $outputFile));

            if ($untranslated > 0) {
                $this->warn(sprintf(static::RUN_MISSING_COMMAND, $untranslated, $outputFile));
            }

        } else {
            $this->warn(sprintf(static::COUNTRY_NAMES_NOT_FOUND, $locale));
        }
    }

    protected function getAvailableLocales(): Collection
    {
        return collect($this->filesystem->files($this->directoryFrom()))
            ->map(function (SplFileInfo $splFileInfo) {
                return str_replace('.' . $splFileInfo->getExtension(), '', $splFileInfo->getFilename());
            })->values();
    }

    protected function downloadCldr(string $locale): array
    {
        $options = $this->standardizeLocale($locale);

        $found = false;
        $json = null;

        foreach ($options as $option) {
            $json = @file_get_contents(sprintf(static::CLDR_URL, $option));

            if ($json) {
                $found = true;
                break;
            }
        }

        if ($found) {
            $json = json_decode($json, true);
            $json = $json['main'][$option]['localeDisplayNames']['territories'];

            $names = [];
            $countryKeys = $this->getCountryKeys();

            $untranslated = 0;

            foreach ($countryKeys as $code => $key) {
                if (isset($json[$code])) {
                    $names[$key] = $json[$code];
                }
                else {
                    $untranslated++;
                }
            }

            return [$names, $untranslated];
        }

        return [[], count($this->getCountryKeys())];
    }

    protected function standardizeLocale(string $locale): array
    {
        switch ($locale) {
            case 'zh-CN':
                return ['zh-Hans'];
            case 'zh-TW':
                return ['zh-Hant'];
            case 'pt-BR':
                return ['pt'];
            case 'pt':
                return ['pt-PT'];
            case 'uz-Cyrl':
                return ['uz'];
        }

        if (strpos($locale, '-') === false) {
            return [$locale];
        }

        if (preg_match('/^(?<language>[a-z]{2,3})(?:-(?<script>[A-Z][a-z]{3}))?(?:-(?<region>[A-Z]{2}|[0-9]{3}))?$/', $locale, $matches)) {
            $parsed = array_filter($matches);
        } else {
            $this->error(sprintf('Could not parse [%s] as a valid locale.', $locale));
            die;
        }

        $locale = [];

        if (isset($parsed['script']) && isset($parsed['region'])) {
            $locale[] = $parsed['language'] . '-' . $parsed['script'] . '-' . $parsed['region'];
        }

        if (isset($parsed['script'])) {
            $locale[] = $parsed['language'] . '-' . $parsed['script'];
        }

        if (isset($parsed['region'])) {
            $locale[] = $parsed['language'] . '-' . $parsed['region'];
        }

        $locale[] = $parsed['language'];

        return $locale;
    }

    protected function getCountryKeys(): array
    {
        return static::COUNTRY_KEYS;
    }

    public const COUNTRY_KEYS = [
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
        'BQ' => 'Bonaire, Sint Eustatius and Saba',
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
        'CW' => 'Curaçao',
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
        'KP' => 'Korea, Democratic People\'s Republic of',
        'KR' => 'Korea',
        'XK' => 'Kosovo',
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
        // 'AN' => 'Netherlands Antilles', // Removed in Nova v2.7.0
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
        'SX' => 'Sint Maarten (Dutch part)',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'SS' => 'South Sudan',
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
