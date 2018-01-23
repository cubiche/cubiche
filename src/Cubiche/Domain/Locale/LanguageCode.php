<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Locale;

use Cubiche\Domain\System\Enum;

/**
 * LanguageCode.
 *
 * @method LanguageCode AS()
 * @method LanguageCode OR()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LanguageCode extends Enum
{
    const AF = 'af';
    const SQ = 'sq';
    const AM = 'am';
    const AR = 'ar';
    const HY = 'hy';
    const AZ = 'az';
    const EU = 'eu';
    const BE = 'be';
    const BN = 'bn';
    const BS = 'bs';
    const BG = 'bg';
    const MY = 'my';
    const CA = 'ca';
    const ZH = 'zh';
    const HR = 'hr';
    const CS = 'cs';
    const DA = 'da';
    const DV = 'dv';
    const NL = 'nl';
    const EN = 'en';
    const ET = 'et';
    const FO = 'fo';
    const FA = 'fa';
    const FI = 'fi';
    const FR = 'fr';
    const MK = 'mk';
    const GA = 'ga';
    const GD = 'gd';
    const GL = 'gl';
    const KA = 'ka';
    const DE = 'de';
    const EL = 'el';
    const GN = 'gn';
    const GU = 'gu';
    const HE = 'he';
    const HI = 'hi';
    const HU = 'hu';
    const IS = 'is';
    const ID = 'id';
    const IT = 'it';
    const JA = 'ja';
    const KN = 'kn';
    const KS = 'ks';
    const KK = 'kk';
    const KM = 'km';
    const KO = 'ko';
    const LO = 'lo';
    const LA = 'la';
    const LV = 'lv';
    const LT = 'lt';
    const MS = 'ms';
    const ML = 'ml';
    const MT = 'mt';
    const MI = 'mi';
    const MR = 'mr';
    const MN = 'mn';
    const NE = 'ne';
    const NB = 'nb';
    const NN = 'nn';
    const PL = 'pl';
    const PT = 'pt';
    const PA = 'pa';
    const RM = 'rm';
    const RO = 'ro';
    const RU = 'ru';
    const SA = 'sa';
    const SR = 'sr';
    const TN = 'tn';
    const SD = 'sd';
    const SI = 'si';
    const SK = 'sk';
    const SL = 'sl';
    const SO = 'so';
    const SB = 'sb';
    const ES = 'es';
    const SW = 'sw';
    const SV = 'sv';
    const TG = 'tg';
    const TA = 'ta';
    const TT = 'tt';
    const TE = 'te';
    const TH = 'th';
    const BO = 'bo';
    const TS = 'ts';
    const TR = 'tr';
    const TK = 'tk';
    const UK = 'uk';
    const UR = 'ur';
    const UZ = 'uz';
    const VI = 'vi';
    const CY = 'cy';
    const XH = 'xh';
    const YI = 'yi';
    const ZU = 'zu';

    /**
     * @var array
     */
    protected static $aliases = array(
        'AS' => 'as',
        'OR' => 'or',
    );

    /**
     * {@inheritdoc}
     */
    public function __construct($value)
    {
        parent::__construct(strtolower($value));
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        return array_merge(static::$aliases, parent::toArray());
    }
}
