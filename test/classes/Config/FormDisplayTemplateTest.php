<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * tests for FormDisplayTemplate
 *
 * @package PhpMyAdmin-test
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests\Config;

use PhpMyAdmin\Config;
use PhpMyAdmin\Config\FormDisplayTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FormDisplayTemplate
 *
 * @package PhpMyAdmin-test
 */
class FormDisplayTemplateTest extends TestCase
{
    /**
     * @var FormDisplayTemplate
     */
    protected $formDisplayTemplate;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Setup tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->config = new Config();
        $this->formDisplayTemplate = new FormDisplayTemplate($this->config);
    }

    /**
     * Test for displayFormTop()
     *
     * @return void
     */
    public function testDisplayFormTop()
    {
        $_SERVER['REQUEST_URI'] = 'https://www.phpmyadmin.net';
        $GLOBALS['cfg']['ServerDefault'] = '';
        $result = $this->formDisplayTemplate->displayFormTop(null, 'posted', [1]);

        $this->assertContains(
            '<form method="get" action="https://www.phpmyadmin.net" ' .
            'class="config-form disableAjax">',
            $result
        );

        $this->assertContains(
            '<input type="hidden" name="tab_hash" value="">',
            $result
        );

        $this->assertContains(
            '<input type="hidden" name="lang" value="en">',
            $result
        );

        $this->assertContains(
            '<input type="hidden" name="token" value="token">',
            $result
        );

        $this->assertContains(
            '<input type="hidden" name="0" value="1">',
            $result
        );
    }

    /**
     * Test for displayTabsTop()
     *
     * @return void
     */
    public function testDisplayTabsTop()
    {
        $result = $this->formDisplayTemplate->displayTabsTop(['one', 'two']);

        $this->assertContains(
            '<ul class="tabs responsivetable"',
            $result
        );

        $this->assertContains(
            '<a href="#0"',
            $result
        );

        $this->assertContains(
            '<a href="#1"',
            $result
        );

        $this->assertContains(
            '<div class="tabs_contents"',
            $result
        );
    }

    /**
     * Test for displayFieldsetTop()
     *
     * @return void
     */
    public function testDisplayFieldsetTop()
    {
        $attributes = ['name' => 'attrname'];
        $errors = [
            'e1',
            'e2',
        ];

        $result = $this->formDisplayTemplate->displayFieldsetTop("TitleTest", "DescTest", $errors, $attributes);

        $this->assertContains(
            '<fieldset class="optbox" name="attrname">',
            $result
        );

        $this->assertContains(
            '<legend>',
            $result
        );

        $this->assertContains(
            '<p>',
            $result
        );

        $this->assertContains(
            '<dl class="errors">',
            $result
        );

        $this->assertContains(
            '<dd>',
            $result
        );

        $this->assertContains(
            '<table width="100%" cellspacing="0">',
            $result
        );
    }

    /**
     * Test for displayInput()
     *
     * @return void
     */
    public function testDisplayInput()
    {
        $opts = [];
        $opts['errors'] = ['e1'];
        $opts['userprefs_allow'] = false;
        $opts['setvalue'] = ':group';
        $opts['doc'] = "https://example.com/";
        $opts['comment'] = "testComment";
        $opts['comment_warning'] = true;
        $opts['show_restore_default'] = true;
        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'text',
            'val',
            'desc',
            false,
            $opts
        );

        $this->assertContains(
            '<tr class="group-header-field group-header-1 disabled-field">',
            $result
        );

        $this->assertContains(
            '<label for="test/path">',
            $result
        );

        $this->assertContains(
            '<a href="https://example.com/" target="documentation"',
            $result
        );

        $this->assertContains(
            '<img src="themes/dot.gif" title="Documentation" ' .
            'alt="Documentation" class="icon ic_b_help"',
            $result
        );

        $this->assertContains(
            '<span class="disabled-notice"',
            $result
        );

        $this->assertContains(
            '<small>',
            $result
        );

        $this->assertContains(
            '<input type="text" class="all85" name="test/path" id="test/path" ' .
            'class="custom field-error" value="val">',
            $result
        );

        $this->assertContains(
            '<span class="field-comment-mark field-comment-warning" '
            . 'title="testComment">',
            $result
        );

        $this->assertContains(
            '<a class="restore-default hide" href="#test/path"',
            $result
        );

        $this->assertContains(
            '<dl class="inline_errors"><dd>e1</dd></dl>',
            $result
        );

        // second case

        $this->config->set('is_setup', true);
        $opts = [];
        $opts['errors'] = [];
        $opts['setvalue'] = 'setVal';
        $opts['comment'] = "testComment";
        $opts['show_restore_default'] = true;
        $opts['userprefs_comment'] = 'userprefsComment';
        $opts['userprefs_allow'] = true;

        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'checkbox',
            'val',
            '',
            false,
            $opts
        );

        $this->assertContains(
            '<tr class="group-field group-field-1">',
            $result
        );

        $this->assertContains(
            '<input type="checkbox" name="test/path" id="test/path" ' .
            'checked="checked">',
            $result
        );

        $this->assertContains(
            '<a class="userprefs-comment" title="userprefsComment">',
            $result
        );

        $this->assertContains(
            '<td class="userprefs-allow" title="Allow users to customize ' .
            'this value">',
            $result
        );

        $this->assertContains(
            '<a class="set-value hide" href="#test/path=setVal" ' .
            'title="Set value: setVal">',
            $result
        );

        // short_text
        $opts = [];
        $opts['errors'] = [];

        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'short_text',
            'val',
            '',
            true,
            $opts
        );

        $this->assertContains(
            '<input type="text" size="25" name="test/path" id="test/path" ' .
            'value="val">',
            $result
        );

        // number_text
        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'number_text',
            'val',
            '',
            true,
            $opts
        );

        $this->assertContains(
            '<input type="number" name="test/path" ' .
            'id="test/path" value="val">',
            $result
        );

        // select case 1
        $opts['values_escaped'] = true;
        $opts['values_disabled'] = [
            1,
            2,
        ];
        $opts['values'] = [
            1 => 'test',
            'key1' => true,
            'key2' => false,
        ];
        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'select',
            true,
            '',
            true,
            $opts
        );
        $this->assertContains(
            '<select class="all85" name="test/path" id="test/path">',
            $result
        );

        $this->assertContains(
            '<option value="1" selected="selected" disabled="disabled">',
            $result
        );

        $this->assertContains(
            '<option value="key1">',
            $result
        );

        $this->assertContains(
            '<option value="key2">',
            $result
        );

        // select case 2
        $opts['values_escaped'] = false;
        $opts['values_disabled'] = [
            1,
            2,
        ];
        $opts['values'] = [
            'a<b' => 'c&d',
            'key1' => true,
            'key2' => false,
        ];
        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'select',
            false,
            '',
            true,
            $opts
        );

        $this->assertContains(
            '<select class="all85" name="test/path" id="test/path">',
            $result
        );

        // assertContains doesn't seem to work with htmlentities
        $this->assertContains(
            '<option value="a&lt;b">c&amp;d</option>',
            $result
        );

        // list
        $result = $this->formDisplayTemplate->displayInput(
            'test/path',
            'testName',
            'list',
            [
                'foo',
                'bar',
            ],
            '',
            true,
            $opts
        );

        $this->assertContains(
            '<textarea cols="35" rows="5" name="test/path" id="test/path">',
            $result
        );
    }

    /**
     * Test for displayGroupHeader()
     *
     * @return void
     */
    public function testDisplayGroupHeader()
    {
        $this->assertEquals(
            '',
            $this->formDisplayTemplate->displayGroupHeader('')
        );

        $this->formDisplayTemplate->group = 3;

        $this->config->set('is_setup', true);

        $result = $this->formDisplayTemplate->displayGroupHeader('headerText');

        $this->assertContains(
            '<tr class="group-header group-header-4">',
            $result
        );

        // without PMA_SETUP
        $this->config->set('is_setup', false);

        $this->formDisplayTemplate->group = 3;

        $result = $this->formDisplayTemplate->displayGroupHeader('headerText');

        $this->assertContains(
            '<tr class="group-header group-header-4">',
            $result
        );
    }

    /**
     * Test for displayGroupFooter()
     *
     * @return void
     */
    public function testDisplayGroupFooter()
    {
        $this->formDisplayTemplate->group = 3;
        $this->formDisplayTemplate->displayGroupFooter();
        $this->assertEquals(
            2,
            $this->formDisplayTemplate->group
        );
    }

    /**
     * Test for displayFieldsetBottom()
     *
     * @return void
     */
    public function testDisplayFieldsetBottom()
    {
        // with PMA_SETUP
        $this->config->set('is_setup', true);

        $result = $this->formDisplayTemplate->displayFieldsetBottom();

        $this->assertContains(
            '<td colspan="3" class="lastrow">',
            $result
        );

        $this->assertContains(
            '<input class="btn btn-primary green" type="submit" name="submit_save" value="Apply"',
            $result
        );

        $this->assertContains(
            '<input class="btn btn-secondary" type="button" name="submit_reset" value="Reset">',
            $result
        );

        $this->assertContains(
            '</fieldset>',
            $result
        );

        // without PMA_SETUP
        $this->config->set('is_setup', false);

        $result = $this->formDisplayTemplate->displayFieldsetBottom();

        $this->assertContains(
            '<td colspan="2" class="lastrow">',
            $result
        );
    }

    /**
     * Test for displayTabsBottom()
     *
     * @return void
     */
    public function testDisplayTabsBottom()
    {
        $result = $this->formDisplayTemplate->displayTabsBottom();
        $this->assertEquals(
            "</div>\n",
            $result
        );
    }

    /**
     * Test for displayFormBottom()
     *
     * @return void
     */
    public function testDisplayFormBottom()
    {
        $result = $this->formDisplayTemplate->displayFormBottom();
        $this->assertEquals(
            "</form>\n",
            $result
        );
    }

    /**
     * Test for addJsValidate()
     *
     * @return void
     */
    public function testAddJsValidate()
    {
        $validators = [
            'one' => [
                '\\\';',
                '\r\n\\\'<scrIpt></\' + \'script>',
            ],
            'two' => [],
        ];

        $js = [];

        $this->formDisplayTemplate->addJsValidate('testID', $validators, $js);

        $this->assertEquals(
            [
                'validateField(\'testID\', \'PMA_\\\';\', true, '
                    . '[\'\\\\r\\\\n\\\\\\\''
                    . '<scrIpt></\\\' + \\\'script>\'])',
                'validateField(\'testID\', \'PMA_\', true)',
            ],
            $js
        );
    }

    /**
     * Test for displayJavascript()
     *
     * @return void
     */
    public function testDisplayJavascript()
    {
        $this->assertEquals(
            '',
            $this->formDisplayTemplate->displayJavascript([])
        );

        $result = $this->formDisplayTemplate->displayJavascript(['var i = 1', 'i++']);

        $this->assertEquals(
            '<script type="text/javascript">' . "\n"
            . 'if (typeof configInlineParams === "undefined"'
            . ' || !Array.isArray(configInlineParams)) '
            . 'configInlineParams = [];' . "\n"
            . 'configInlineParams.push(function() {' . "\n"
            . 'var i = 1;' . "\n"
            . 'i++;' . "\n"
            . '});' . "\n"
            . 'if (typeof configScriptLoaded !== "undefined"'
            . ' && configInlineParams) loadInlineConfig();'
            . "\n" . '</script>' . "\n",
            $result
        );
    }

    /**
     * Test for displayErrors()
     *
     * @return void
     */
    public function testDisplayErrors()
    {
        $errors = [
            '<err1>',
            '&err2',
        ];

        $result = $this->formDisplayTemplate->displayErrors('err"Name1"', $errors);

        $this->assertContains('<dt>err&quot;Name1&quot;</dt>', $result);
        $this->assertContains('<dd>&lt;err1&gt;</dd>', $result);
        $this->assertContains('<dd>&amp;err2</dd>', $result);
    }
}
