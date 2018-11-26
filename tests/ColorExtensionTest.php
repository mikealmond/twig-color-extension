<?php
declare(strict_types=1);

namespace MikeAlmond\TwigColorExtension;

use PHPUnit\Framework\TestCase;
use Twig_Error_Syntax;

/**
 * Class ColorExtensionTest
 * @package MikeAlmond\TwigColorExtension
 */
class ColorExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function testExtensionName()
    {
        self::assertEquals('mikealmond/twig-color-extension', $this->getExtension()->getName());
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testCssDisplay()
    {
        $this->assertRender('#0099FF', "{{ '0099FF'|color_css_hex }}");
        $this->assertRender('rgb(0, 153, 255)', "{{ '0099FF'|color_css_rgb }}");
        $this->assertRender('rgba(0, 153, 255, 1)', "{{ '0099FF'|color_css_rgba }}");
        $this->assertRender('rgba(0, 153, 255, 0.5)', "{{ '0099FF'|color_css_rgba(0.5) }}");
        $this->assertRender('hsl(204, 100%, 50%)', "{{ '0099FF'|color_css_hsl }}");
        $this->assertRender('hsla(204, 100%, 50%, 1)', "{{ '0099FF'|color_css_hsla }}");
        $this->assertRender('hsla(204, 100%, 50%, 0.5)', "{{ '0099FF'|color_css_hsla(0.5) }}");

        $this->assertRender('#0099FF', "{{ '0099FF'|color_css_name }}");
        $this->assertRender('RebeccaPurple', "{{ '663399'|color_css_name }}");
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testCssDisplayWithInvalidColors()
    {
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_hex }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_rgb }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_rgba }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_rgba(0.5) }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_hsl }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_hsla }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_hsla(0.5) }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_name }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_css_name }}");
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testColorFilters()
    {
        $this->assertRender('FF0019', "{{ '0099FF'|color_complementary(30) }}");
        $this->assertRender('A4A4A4', "{{ 'CCCCCC'|color_darken(20) }}");
        $this->assertRender('CCCCCC', "{{ 'FFFFFF'|color_darken(20) }}");
        $this->assertRender('000000', "{{ 'FFFFFF'|color_darken(100) }}");
        $this->assertRender('F5F5F5', "{{ 'CCCCCC'|color_lighten(20) }}");
        $this->assertRender('000000', "{{ '000000'|color_lighten(100) }}");

        $this->assertRender('FFFFFF', "{{ 'C91414'|color_text_color }}");
        $this->assertRender('000000', "{{ '5CF081'|color_text_color }}");
        $this->assertRender('FF6600', "{{ '0099FF'|color_adjust_hue(180) }}");
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testColorFiltersWithInvalidColors()
    {
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_complementary(30) }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_darken(30) }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_lighten(30) }}");
        $this->assertRender('333333', "{{ 'N0TACOL0R'|color_text_color }}");
        $this->assertRender('N0TACOL0R', "{{ 'N0TACOL0R'|color_adjust_hue(180) }}");
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testInvalidFilters()
    {
        $this->expectException(Twig_Error_Syntax::class);
        $this->assertRender('hsla(204, 100%, 50%, 0.5)', "{{ '0099FF'|colour_css_hsla(0.5) }}");
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testTwigTests()
    {
        $this->assertRender('true', "{{ '0099FF' is color_valid ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ '0099FFF' is color_valid ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ '0099FFF' is color_dark ? 'true' : 'false' }}");
        $this->assertRender('true', "{{ '000000' is color_dark ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ '000000' is color_light ? 'true' : 'false' }}");
        $this->assertRender('true', "{{ '000000' is color_readable('FFFFFF') ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ '000000' is color_readable('000000') ? 'true' : 'false' }}");
        $this->assertRender('true', "{{ '663399' is color_has_name ? 'true' : 'false' }}");
        $this->assertRender('true', "{{ '000000' is color_has_name('000000') ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ '000000' is color_low_contrast('FFFFFF') ? 'true' : 'false' }}");
        $this->assertRender('true', "{{ '000000' is color_low_contrast('111111') ? 'true' : 'false' }}");
    }

    /**
     * @test
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testTwigTestsWithInvalidColors()
    {
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_valid ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_valid ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_dark ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_dark ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_light ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_readable('FFFFFF') ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_readable('000000') ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_has_name ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_has_name('000000') ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_low_contrast('FFFFFF') ? 'true' : 'false' }}");
        $this->assertRender('false', "{{ 'N0TACOL0R' is color_low_contrast('111111') ? 'true' : 'false' }}");
    }

    /**
     * @return \MikeAlmond\TwigColorExtension\ColorExtension
     */
    protected function getExtension()
    {
        return new ColorExtension();
    }

    /**
     * Build the Twig environment for the template
     *
     * @param string $template
     *
     * @return \Twig_Environment
     */
    protected function buildEnv($template)
    {
        $loader = new \Twig_Loader_Array([
            'template' => $template,
        ]);
        $twig   = new \Twig_Environment($loader);

        $twig->addExtension($this->getExtension());

        return $twig;
    }

    /**
     * Render template
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render($template, array $data = [])
    {
        $twig   = $this->buildEnv($template);
        $result = $twig->render('template', $data);

        return $result;
    }

    /**
     * Render template and assert equals
     *
     * @param string $expected
     * @param string $template
     * @param array  $data
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function assertRender($expected, $template, array $data = [])
    {
        $result = $this->render($template, $data);

        $this->assertEquals($expected, (string)$result);
    }
}
