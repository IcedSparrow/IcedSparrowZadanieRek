<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class IcedSparrow extends Module
{
    private $moduleHooks;

    public function __construct()
    {
        $this->name = 'icedsparrow';
        $this->tab = 'front_office_features';
        $this->author = 'Dawid Wróbel';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->displayName = $this->l('IcedSparrow');
        $this->description = $this->l('Zadanie Rekrutacyjne PrestaShop Dawid Wróbel');

        $this->setModuleHooks();

        parent::__construct();
    }

    public function install()
    {
        return parent::install() &&
            $this->installModuleHooks() &&
            Configuration::updateValue('icedsparrow_homecol', '0') &&
            Configuration::updateValue('icedsparrow_body', 'enter the code here') &&
            Configuration::updateValue('icedsparrow_ssl', '0');
    }

    public function getContent()
    {
        $output = "";
        if (Tools::isSubmit('submitSettingsIcedSparrow')) {
            $body = trim(Tools::getValue('icedsparrow_body'));
            $body = $this->smartClean($body);

            Configuration::updateValue('icedsparrow_body', $body, true);
            Configuration::updateValue('icedsparrow_home', Tools::getValue('icedsparrow_home'));
            Configuration::updateValue('icedsparrow_hook', Tools::getValue('icedsparrow_hook'));
            $output .= '<div class="alert alert-success">' . $this->l('Settings updated') . '</div>';
        }

        return $output . $this->displayForm();
    }

    public function installModuleHooks()
    {
        $this->setModuleHooks();
        foreach ($this->moduleHooks as $moduleHook => $value) {
            if ($this->isHookRequired($value)) {
                if (!$this->registerHook($moduleHook)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function setModuleHooks()
    {
        $this->moduleHooks = [
            'home' => ['ps15' => 1, 'ps16' => 1, 'ps17' => 1, 'ps80' => 0],
            'displayHome' => ['ps15' => 0, 'ps16' => 0, 'ps17' => 0, 'ps80' => 1]
        ];
    }

    public function isHookRequired($hookSettings)
    {
        $psVersion = $this->psVersion();
        switch ($psVersion) {
            case 4:
            case 5:
                return $hookSettings['ps15'] == 1;
            case 6:
                return $hookSettings['ps16'] == 1;
            case 7:
                return $hookSettings['ps17'] == 1;
            default:
                return $hookSettings['ps80'] == 1;
        }
    }

    public function displayForm()
    {
        $form = '';

        if (Configuration::get('icedsparrow_disabletiny') != 1) {
            $iso = Language::getIsoById((int) ($this->context->language->id));
            $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js')) ? $iso : 'en';
            $ad = dirname($_SERVER["PHP_SELF"]);

            if (Configuration::get('icedsparrow_origtiny') == 1) {
                $form .= '<script type="text/javascript" src="../js/admin/tinymce.inc.js"></script>';
            } else {
                $form .= '<script type="text/javascript" src="../modules/icedsparrow/tinymce16.inc.js"></script>';
            }

            $form .= '
                <script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tiny_mce/tiny_mce.js"></script>
                <script type="text/javascript">
                    var iso = \'' . $isoTinyMCE . '\' ;
                    var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
                    var ad = \'' . $ad . '\' ;
                </script>';
            if (Configuration::get('icedsparrow_origtiny') == 1) {
                $form .= '<script>$(document).ready(function(){tinySetup();});</script>';
            }
        }

        $selectOptions = '';
        if ($this->moduleHooks) {
            foreach ($this->moduleHooks as $moduleHook => $value) {
                if ($value['ps17'] == 1) {
                    $selectOptions .= "<option value=\"$moduleHook\" " . (Configuration::get('icedsparrow_hook') == $moduleHook ? 'selected="yes"' : '') . ">" . $moduleHook . "</option>";
                }
            }
        }

        $form .= '
        <div class="panel nobootstrap" style="margin-left:0px; ">
        <form name="icedsparrowform" id="icedsparrowform" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <div style="display:block; margin:auto; vertical-align:top;">
                <label>' . $this->l('Gdzie ma być wyświetlane pole zawartości?') . '</label>
                <div class="margin-form">
                    <select name="icedsparrow_hook">
                        ' . $selectOptions . '
                    </select>
                </div>
                <br/>
            </div>
            <fieldset style="margin-bottom:20px; display:block; vertical-align:top; clear:both;">
                <div class="bootstrap"></div>
                <textarea class="rte rtepro" style="margin-bottom:10px; width:99%; height:300px;" id="icedsparrow_body" name="icedsparrow_body">' . Configuration::get('icedsparrow_body') . '</textarea>
                <input type="hidden" name="submitSettingsIcedSparrow" value="1" />
            </fieldset>
            <div class="panel-footer"><button type="submit" name="submit_settings" class="button btn btn-default pull-right"/><i class="process-icon-save"></i>' . $this->l('Save') . '</button></div>
        </form>
        </div>
        ';

        return $form;
    }

    public function hookHome($params)
    {
        if (Configuration::get('icedsparrow_hook') == "home") {
            $this->prepareDatas();

            $this->registerStylesheet('icedsparrow-style', 'modules/' . $this->name . '/css/style.css', ['media' => 'all']);

            $content = stripslashes($this->prepareBody(Configuration::get('icedsparrow_body')));

            $content = '<div id="icedsparrow-container">' . $content . '</div>';

            $tpl_content = $this->display(__FILE__, 'icedsparrow.tpl');

            $final_content = $content . $tpl_content;

            return $final_content;
        }
    }

    public static function removeDoubleWhitespace($s = null)
    {
        return preg_replace('/([\s])\1+/', ' ', $s);
    }

    public function smartClean($s = null)
    {
        return trim(self::removeDoubleWhitespace($s));
    }

    public function psVersion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp = explode(".", $version);
        return $exp[$part];
    }

    public function prepareBody($body)
    {
        return str_replace(["\r\n", "\r", "\n"], ' ', $body);
    }

    public function prepareDatas()
    {
        $this->context->smarty->assign('page_name', Tools::getValue('controller'));
        $this->context->smarty->assign('logged', $this->context->customer->isLogged());
        $this->context->smarty->assign(['icedsparrowbody' => nl2br(stripslashes($this->prepareBody(Configuration::get('icedsparrow_body'))))]);
        $this->context->smarty->assign(['is_https_icedsparrow' => (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == "on" ? 1 : 0)]);
        $this->context->smarty->assign(['icedsparrow_ssl' => Configuration::get('icedsparrow_ssl')]);
        $this->context->smarty->assign(['icedsparrow_home' => Configuration::get('icedsparrow_home')]);
    }

    public function registerStylesheet($name, $path, $options = [])
    {
        $this->context->controller->registerStylesheet($name, $path, $options);
    }
}
?>