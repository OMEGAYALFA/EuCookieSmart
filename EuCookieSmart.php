<?php
/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class EuCookieSmart extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'EuCookieSmart';
        $this->tab = 'front_office_features';
        $this->version = '0.0.1';
        $this->author = 'Yuri Blanc';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('EuCookieSmart');
        $this->description = $this->l('Cookie law banner with ajax features');

        $this->confirmUninstall = $this->l('Are you sure to uninstall this module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        

        
        
        

        return
            Configuration::updateValue('EUCOOKIESMART_MESSAGE', 'We use cookies to track usage and preferences.') &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_ACCEPT', true) &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_ACCEPT_TEXT', 'I Understand') &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_DECLINE', true) &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_DECLINE_TEXT', 'Disable cookies') &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_POLICY', true) &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_POLICY_TEXT', 'Privacy policy') &&
            Configuration::updateValue('EUCOOKIESMART_BUTTON_POLICY_ARTICLE', null) &&
            Configuration::updateValue('EUCOOKIESMART_ACCEPT_CONTINUE', false) &&
            Configuration::updateValue('EUCOOKIESMART_ACCEPT_SCROLL', false) &&
            Configuration::updateValue('EUCOOKIESMART_RENEW_VISIT', true) &&
            Configuration::updateValue('EUCOOKIESMART_FIXED', false) &&
            Configuration::updateValue('EUCOOKIESMART_BOTTOM', false) &&
            Configuration::updateValue('EUCOOKIESMART_EFFECT', 'slide') &&
            Configuration::updateValue('EUCOOKIESMART_EXPIRE_DAYS', 30) &&
            parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHeader');
    }

    public function uninstall()
    {

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitEuCookieSmartModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperFormCore();
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang =  Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $language->id;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEuCookieSmartModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-gears"></i>',
                        'desc' => $this->l('Enter the message to display'),
                        'name' => 'EUCOOKIESMART_MESSAGE',
                        'label' => $this->l('Text message'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display accept button'),
                        'name' => 'EUCOOKIESMART_BUTTON_ACCEPT',
                        'is_bool' => true,
                        'desc' => $this->l('Show or not show accept button'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_BUTTON_ACCEPT_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_BUTTON_ACCEPT_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-gears"></i>',
                        'desc' => $this->l('Enter the text for the decline button'),
                        'name' => 'EUCOOKIESMART_BUTTON_ACCEPT_TEXT',
                        'label' => $this->l('Accept button text'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display decline button'),
                        'name' => 'EUCOOKIESMART_BUTTON_DECLINE',
                        'is_bool' => true,
                        'desc' => $this->l('Show or not show decline button'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_BUTTON_DECLINE_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_BUTTON_DECLINE_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-gears"></i>',
                        'desc' => $this->l('Enter the text for the decline button'),
                        'name' => 'EUCOOKIESMART_BUTTON_DECLINE_TEXT',
                        'label' => $this->l('Decline button text'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display policy button'),
                        'name' => 'EUCOOKIESMART_BUTTON_POLICY',
                        'is_bool' => true,
                        'desc' => $this->l('Show or not show policy button'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_BUTTON_POLICY_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_BUTTON_POLICY_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-gears"></i>',
                        'desc' => $this->l('Enter the text for the policy button'),
                        'name' => 'EUCOOKIESMART_BUTTON_POLICY_TEXT',
                        'label' => $this->l('Policy button text'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-gears"></i>',
                        'desc' => $this->l('Enter the id of the article'),
                        'name' => 'EUCOOKIESMART_BUTTON_POLICY_ARTICLE',
                        'label' => $this->l('Policy CMS article'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Accept on continue'),
                        'name' => 'EUCOOKIESMART_ACCEPT_CONTINUE',
                        'is_bool' => true,
                        'desc' => $this->l('Show or not show policy button'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_ACCEPT_CONTINUE_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_ACCEPT_CONTINUE_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Accept on scroll'),
                        'name' => 'EUCOOKIESMART_ACCEPT_SCROLL',
                        'is_bool' => true,
                        'desc' => $this->l('Show or not show policy button'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_ACCEPT_SCROLL_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_ACCEPT_SCROLL_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Accept on scroll'),
                        'name' => 'EUCOOKIESMART_RENEW_VISIT',
                        'is_bool' => true,
                        'desc' => $this->l('Renew each visits'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_RENEW_VISIT_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_RENEW_VISIT_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Fixed position'),
                        'name' => 'EUCOOKIESMART_FIXED',
                        'is_bool' => true,
                        'desc' => $this->l('Use fixed position so the module will follow user scroll'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_FIXED_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_FIXED_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Bottom'),
                        'name' => 'EUCOOKIESMART_BOTTOM',
                        'is_bool' => true,
                        'desc' => $this->l('Display the module on the bottom of the page'),
                        'values' => array(
                            array(
                                'id' => 'EUCOOKIESMART_BOTTOM_ON',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'EUCOOKIESMART_BOTTOM_OFF',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),

                    array(
                        'type' => 'select',                              // This is a <select> tag.
                        'label' => $this->l('Effect'),         // The <label> for this <select> tag.
                        'desc' => $this->l('Choose an effect'),  // A help text, displayed right next to the <select> tag.
                        'name' => 'EUCOOKIESMART_EFFECT',                     // The content of the 'id' attribute of the <select> tag.
                        'required' => true,                              // If set to true, this option must be set.
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'slide',       // The value of the 'value' attribute of the <option> tag.
                                    'name' => 'Slide'    // The value of the text content of the  <option> tag.
                                ),
                                array(
                                    'id_option' => 'fade',
                                    'name' => 'Fade'
                                ),
                                array(
                                    'id_option' => 'hide',
                                    'name' => 'Hide'
                                ),
                            ),                                              // $options contains the data itself.
                            'id' => 'id_option',                           // The value of the 'id' key must be the same as the key for 'value' attribute of the <option> tag in each $options sub-array.
                            'name' => 'name'                               // The value of the 'name' key must be the same as the key for the text content of the <option> tag in each $options sub-array.
                        )
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-calendar"></i>',
                        'desc' => $this->l('Set when the confirmation will be considered expired'),
                        'name' => 'EUCOOKIESMART_EXPIRE_DAYS',
                        'label' => $this->l('Expire in days'),
                    ),

                    // endfields
                ),

                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $id_shop_group = Shop::getContextShopGroupID();
        $id_shop = Shop::getContextShopID();
        return array(
            'EUCOOKIESMART_MESSAGE' => Configuration::get('EUCOOKIESMART_MESSAGE', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_ACCEPT' => Configuration::get('EUCOOKIESMART_BUTTON_ACCEPT', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_ACCEPT_TEXT' => Configuration::get('EUCOOKIESMART_BUTTON_ACCEPT_TEXT', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_DECLINE' => Configuration::get('EUCOOKIESMART_BUTTON_DECLINE', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_DECLINE_TEXT' => Configuration::get('EUCOOKIESMART_BUTTON_DECLINE_TEXT', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_POLICY' => Configuration::get('EUCOOKIESMART_BUTTON_POLICY', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_POLICY_TEXT' => Configuration::get('EUCOOKIESMART_BUTTON_POLICY_TEXT', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BUTTON_POLICY_ARTICLE' => Configuration::get('EUCOOKIESMART_BUTTON_POLICY_ARTICLE', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_ACCEPT_CONTINUE' => Configuration::get('EUCOOKIESMART_ACCEPT_CONTINUE', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_ACCEPT_SCROLL' => Configuration::get('EUCOOKIESMART_ACCEPT_SCROLL', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_RENEW_VISIT' => Configuration::get('EUCOOKIESMART_RENEW_VISIT', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_FIXED' => Configuration::get('EUCOOKIESMART_FIXED', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_BOTTOM' => Configuration::get('EUCOOKIESMART_BOTTOM', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_EFFECT' => Configuration::get('EUCOOKIESMART_EFFECT', null, $id_shop_group, $id_shop),
            'EUCOOKIESMART_EXPIRE_DAYS' => Configuration::get('EUCOOKIESMART_EXPIRE_DAYS', null, $id_shop_group, $id_shop)
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function hookDisplayHeader()
    {
        $this->hookHeader();
        $this->context->smarty->assign(
            $this->getConfigFormValues()
        );

        return $this->display(__FILE__, 'euCookieSmart.tpl');

    }
}
