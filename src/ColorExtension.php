<?php
declare(strict_types=1);

namespace MikeAlmond\TwigColorExtension;

use MikeAlmond\Color\Color;
use MikeAlmond\Color\CssGenerator;
use MikeAlmond\Color\PaletteGenerator;
use MikeAlmond\Color\Validator;
use Twig_Extension as TwigExtension;
use Twig_Filter as TwigFilter;
use Twig_SimpleTest as TwigTest;

/**
 * Class ColorExtension
 * @package MikeAlmond\TwigColorExtension
 */
class ColorExtension extends TwigExtension
{

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('color_valid', [$this, 'isValid']),
            new TwigTest('color_readable', [$this, 'isReadable']),
            new TwigTest('color_low_contrast', [$this, 'isLowContrast']),
            new TwigTest('color_dark', [$this, 'isDark']),
            new TwigTest('color_light', [$this, 'isLight']),
            new TwigTest('color_has_name', [$this, 'hasColorName']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('color_darken', [$this, 'darken']),
            new TwigFilter('color_lighten', [$this, 'lighten']),
            new TwigFilter('color_adjust_hue', [$this, 'adjustHue']),
            new TwigFilter('color_text_color', [$this, 'getMatchingTextColor']),
            new TwigFilter('color_complementary', [$this, 'getComplementary']),
            new TwigFilter('color_css_rgb', [$this, 'getCssRgb']),
            new TwigFilter('color_css_rgba', [$this, 'getCssRgba']),
            new TwigFilter('color_css_hsl', [$this, 'getCssHsl']),
            new TwigFilter('color_css_hsla', [$this, 'getCssHsla']),
            new TwigFilter('color_css_hex', [$this, 'getCssHex']),
            new TwigFilter('color_css_name', [$this, 'getCssColorName']),
        ];
    }

    /**
     * Extension name.
     *
     * @return string
     **/
    public function getName() : string
    {
        return 'mikealmond/twig-color-extension';
    }

    /**
     * @param string|Color $color
     *
     * @return bool
     */
    public function isValid($color) : bool
    {
        return $color instanceof Color || Validator::isValidHex($color);
    }

    /**
     * @param string|Color $color
     * @param string|Color $backgroundColor
     * @param string       $level
     * @param int          $fontSize
     *
     * @return bool
     */
    public function isReadable($color, $backgroundColor, $level = 'AA', int $fontSize = 12) : bool
    {
        if (!$this->isValid($color) || !$this->isValid($backgroundColor)) {
            return false;
        }

        return $this->parseColor($color)
                    ->isReadable(
                        $this->parseColor($backgroundColor),
                        $level,
                        $fontSize
                    );
    }

    /**
     * @param string|Color $color
     * @param string|Color $backgroundColor
     *
     * @return bool
     */
    public function isLowContrast($color, $backgroundColor = 'FFFFFF') : bool
    {
        if (!$this->isValid($color) || !$this->isValid($backgroundColor)) {
            return false;
        }

        return $this->parseColor($color)->luminosityContrast($this->parseColor($backgroundColor)) < 4.5;
    }

    /**
     * @param string|Color $color
     *
     * @return bool
     */
    public function isDark($color) : bool
    {
        if (!$this->isValid($color)) {
            return false;
        }

        return $this->parseColor($color)->isDark();
    }

    /**
     * @param string|Color $color
     *
     * @return bool
     */
    public function isLight($color) : bool
    {
        if (!$this->isValid($color)) {
            return false;
        }

        return !$this->parseColor($color)->isDark();
    }

    /**
     * @param string|Color $color
     *
     * @return bool
     */
    public function hasColorName($color) : bool
    {
        if (!$this->isValid($color)) {
            return false;
        }

        return CssGenerator::hasName($this->parseColor($color));
    }

    /**
     * @param string|Color $color
     * @param float        $percentage
     *
     * @return string
     */
    public function darken($color, float $percentage) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return $this->parseColor($color)->darken($percentage)->getHex();
    }

    /**
     * @param string|Color $color
     * @param float        $percentage
     *
     * @return string
     */
    public function lighten($color, float $percentage) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return $this->parseColor($color)->lighten($percentage)->getHex();
    }

    /**
     * @param string|Color $color
     * @param float        $degrees
     *
     * @return string
     */
    public function adjustHue($color, float $degrees = 30) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return $this->parseColor($color)->adjustHue($degrees)->getHex();
    }

    /**
     * @param string|Color $color
     * @param string       $default
     *
     * @return string
     */
    public function getMatchingTextColor($color, string $default = 'CCCCCC') : string
    {
        if (!$this->isValid($color)) {
            return $default;
        }

        return $this->parseColor($color)->getMatchingTextColor($default)->getHex();
    }

    /**
     * @param string|Color $color
     * @param int          $distance
     *
     * @return string
     */
    public function getComplementary($color, int $distance = 45) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        $generator = new PaletteGenerator($this->parseColor($color));
        $palette   = $generator->triad($distance);

        return $palette[1]->getHex();
    }

    /**
     * @param string|Color $color
     *
     * @return string
     */
    public function getCssColorName($color) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        $color = $this->parseColor($color);

        if (!CssGenerator::hasName($color)) {
            return CssGenerator::hex($color);
        }

        return CssGenerator::name($color);
    }

    /**
     * @param string|Color $color
     *
     * @return string
     */
    public function getCssRgb($color) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return CssGenerator::rgb($this->parseColor($color));
    }

    /**
     * @param string|Color $color
     * @param float        $alpha
     *
     * @return string
     */
    public function getCssRgba($color, float $alpha = 1.0) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return CssGenerator::rgba($this->parseColor($color), $alpha);
    }

    /**
     * @param string|Color $color
     *
     * @return string
     */
    public function getCssHsl($color) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return CssGenerator::hsl($this->parseColor($color));
    }

    /**
     * @param string|Color $color
     * @param float        $alpha
     *
     * @return string
     */
    public function getCssHsla($color, float $alpha = 1.0) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return CssGenerator::hsla($this->parseColor($color), $alpha);
    }

    /**
     * @param string|Color $color
     *
     * @return string
     */
    public function getCssHex($color) : string
    {
        if (!$this->isValid($color)) {
            return $color;
        }

        return CssGenerator::hex($this->parseColor($color));
    }

    /**
     * @param $color
     *
     * @return Color
     */
    private function parseColor($color) : Color
    {
        return $color instanceof Color ? $color : Color::fromHex($color);
    }
}
