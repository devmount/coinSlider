<?php

/**
 * moziloCMS Plugin: coinSlider
 *
 * Generates a slideshow with multiple effects with images of a moziloCMS gallery
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_MoziloPlugins
 * @author   DEVMOUNT <mail@devmount.de>
 * @license  GPL v3+
 * @version  GIT: v1.0.2013-09-19
 * @link     https://github.com/devmount-mozilo/coinSlider
 * @link     http://devmount.de/Develop/moziloCMS/Plugins/coinSlider.html
 * @see      The fear of the LORD is the beginning of knowledge:
 *           but fools despise wisdom and instruction.
 *            - The Bible
 *
 * Plugin created by DEVMOUNT
 * www.devmount.de
 *
 */

// only allow moziloCMS environment
if (!defined('IS_CMS')) {
    die();
}

/**
 * coinSlider Class
 *
 * @category PHP
 * @package  PHP_MoziloPlugins
 * @author   DEVMOUNT <mail@devmount.de>
 * @license  GPL v3+
 * @link     https://github.com/devmount-mozilo/coinSlider
 */
class coinSlider extends Plugin
{
    private $_admin_lang;
    private $_cms_lang;
    var $GalleryClass;

    // plugin information
    const PLUGIN_AUTHOR  = 'DEVMOUNT';
    const PLUGIN_TITLE   = 'coinSlider';
    const PLUGIN_VERSION = 'v1.0.2013-09-19';
    const MOZILO_VERSION = '2.0';
    const PLUGIN_DOCU
        = 'http://devmount.de/Develop/moziloCMS/Plugins/coinSlider.html';

    private $_plugin_tags = array(
        'full' => '{coinSlider|name|width|height|spw|sph|delay|sDelay|opacity|titleSpeed|effect|navigation|links|hoverPause}',
        'min'  => '{coinSlider|name|width|height||||||||||}',
    );

    const LOGO_URL = 'http://media.devmount.de/logo_pluginconf.png';

    /**
     * creates plugin content
     *
     * @param string $value Parameter divided by '|'
     *
     * @return string HTML output
     */
    function getContent($value)
    {
        global $CMS_CONF;
        global $syntax;

        $this->_cms_lang = new Language(
            $this->PLUGIN_SELF_DIR
            . 'lang/cms_language_'
            . $CMS_CONF->get('cmslanguage')
            . '.txt'
        );

        // get params
        $values = explode('|', $value);
        // id for current coinslider = existing gallery name
        $param_id = rawurlencode(trim($values[0]));
        // build specific coinslider configuration
        $params = array(
            // width for current coinslider
            'width' => trim($values[1]),
            // height for current coinslider
            'height' => trim($values[2]),
            // squares per width
            'spw' => trim($values[3]),
            // squares per height
            'sph' => trim($values[4]),
            // delay between images in ms
            'delay' => trim($values[5]),
            // delay beetwen squares in ms
            'sDelay' => trim($values[6]),
            // opacity of title and navigation
            'opacity' => trim($values[7]),
            // speed of title appereance in ms
            'titleSpeed' => trim($values[8]),
            // effect
            'effect' => '"' . trim($values[9]) . '"',
            // show navigation
            'navigation' => trim($values[10]),
            // show links
            'links' => trim($values[11]),
            // hoverpause
            'hoverPause' => trim($values[12])
        );

        // get conf
        $conf_prev = trim($this->settings->get('prev'));
        $conf_next = trim($this->settings->get('next'));

        // initialize gallery
        include_once BASE_DIR . 'cms/GalleryClass.php';
        $this->GalleryClass = new GalleryClass();
        $this->GalleryClass->initial_Galleries(false, false, false, true);

        // if gallery exists read pictures, otherwise warn
        if (in_array($param_id, $this->GalleryClass->get_GalleriesArray())) {
            $pictures = $this->GalleryClass->get_GalleryImagesArray($param_id);
        } else {
            return $this->_cms_lang->getLanguageValue("error_nogallery", $param_id);
        }
        if (empty($pictures)) {
            return $this->_cms_lang->getLanguageValue("error_nopictures", $param_id);
        }

        // initialize return content
        $content = '';

        // include jquery and coinslider javascript
        $syntax->insert_jquery_in_head('jquery');
        $content .= '
            <script
                language="JavaScript"
                src="' . $this->PLUGIN_SELF_URL . 'js/coin-slider.js">
            </script>
        ';
        // html for container
        $content .= '
            <div style="width:' . $params['width'] . 'px;">
                <div id="' . str_replace(' ', '_', rawurldecode($param_id)) . '">
            ';
        foreach ($pictures as $picture) {
            $content .= '
                <a href="'
                . $this->GalleryClass->get_ImageSrc($param_id, $picture, false)
                . '" target="_blank">
                    <img src="'
                    . $this->GalleryClass->get_ImageSrc($param_id, $picture, false)
                    . '" />
                    <span>'
                    . $this->GalleryClass->get_ImageDescription(
                        $param_id,
                        $picture,
                        false
                    ) . '</span>
                </a>
            ';
        }
        $content .= '</div></div>';

        // call coinslider
        $content .= '
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#'
                    . str_replace(' ', '_', rawurldecode($param_id))
                    . '").coinslider({';
        foreach ($params as $label => $param) {
            if ($param != '') {
                $content .= $label . ': ' . $param . ',';
            }
        }

        if (isset($conf_prev) and $conf_prev != '') {
            $content .= 'prevText: "' . $conf_prev . '",';
        }
        if (isset($conf_next) and $conf_next != '') {
            $content .= 'nextText: "' . $conf_next . '",';
        }

        // remove last commata
        $content = substr($content, 0, -1);
        $content .= '});});</script>';

        // return coinSlider
        return $content;

    }

    /**
     * sets backend configuration elements and template
     *
     * @return Array configuration
     */
    function getConfig()
    {
        $config = array();

        // prev button
        $config['prev'] = array(
            'type' => 'text',
            'description' => $this->_admin_lang->getLanguageValue('config_prev'),
            'maxlength' => '40',
            'size' => '15'
        );

        // next button
        $config['next'] = array(
            'type' => 'text',
            'description' => $this->_admin_lang->getLanguageValue('config_next'),
            'maxlength' => '40',
            'size' => '15'
        );

        // Template CSS
        $css_admin_header = '
            margin: -0.4em -0.8em -5px -0.8em;
            padding: 10px;
            background-color: #234567;
            color: #fff;
            text-shadow: #000 0 1px 3px;
        ';
        $css_admin_header_span = '
            font-size:20px;
            vertical-align: top;
            padding-top: 3px;
            display: inline-block;
        ';
        $css_admin_subheader = '
            margin: -0.4em -0.8em 5px -0.8em;
            padding: 5px 9px;
            background-color: #ddd;
            color: #111;
            text-shadow: #fff 0 1px 2px;
        ';
        $css_admin_li = '
            background: #eee;
        ';
        $css_admin_default = '
            color: #aaa;
            padding-left: 6px;
        ';

        // build Template
        $config['--template~~'] = '
            <div style="' . $css_admin_header . '">
            <span style="' . $css_admin_header_span . '">'
                . $this->_admin_lang->getLanguageValue(
                    'admin_header',
                    self::PLUGIN_TITLE
                )
            . '</span>
            <a href="' . self::PLUGIN_DOCU . '" target="_blank">
            <img style="float:right;" src="' . self::LOGO_URL . '" />
            </a>
            </div>
        </li>
        <li class="mo-in-ul-li ui-widget-content" style="' . $css_admin_li . '">
            <div style="' . $css_admin_subheader . '">'
            . $this->_admin_lang->getLanguageValue('admin_label') . '</div>
            <div style="margin-bottom:5px;">
                {prev_text}
                {prev_description}
                <span style="' . $css_admin_default .'"> [Prev] </span>
            </div>
            <div style="margin-bottom:5px;">
                {next_text}
                {next_description}
                <span style="' . $css_admin_default .'"> [Next] </span>
        ';

        return $config;
    }

    /**
     * sets backend plugin information
     *
     * @return Array information
     */
    function getInfo()
    {
        global $ADMIN_CONF;
        $this->_admin_lang = new Language(
            $this->PLUGIN_SELF_DIR
            . 'lang/admin_language_'
            . $ADMIN_CONF->get('language')
            . '.txt'
        );

        // build plugin tags
        $tags = array();
        foreach ($this->_plugin_tags as $key => $tag) {
            $tags[$tag] = $this->_admin_lang->getLanguageValue('tag_' . $key);
        }

        $info = array(
            '<b>' . self::PLUGIN_TITLE . '</b> ' . self::PLUGIN_VERSION,
            self::MOZILO_VERSION,
            $this->_admin_lang->getLanguageValue(
                'description',
                htmlspecialchars($this->_plugin_tags['full'], ENT_COMPAT, 'UTF-8')
            ),
            self::PLUGIN_AUTHOR,
            array(
                self::PLUGIN_DOCU,
                self::PLUGIN_TITLE . ' '
                . $this->_admin_lang->getLanguageValue('on_devmount')
            ),
            $tags
        );

        return $info;
    }
}

?>