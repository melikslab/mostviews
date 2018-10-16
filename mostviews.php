<?php

/*
* @author    Joan Melis
* @copyright  Copyright (c) 2018 
* @license    You only can use module, nothing more!
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class mostviews extends Module
{
    
    public function __construct()
    {
        $this->name = 'mostviews';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Joan Melis';
        $this->need_instance = 0;
        //$this->is_configurable = 1;
        /*$this->ps_versions_compliancy['min'] = '1.5.3.1';
        $this->ps_versions_compliancy['max'] = '1.6.1.2';
        $this->secure_key = Tools::encrypt($this->name);*/

        $this->dependencies = array('statsdata');
        parent::__construct();

        $this->displayName = $this->l('Most viewed products');
        $this->description = $this->l('Widget to display most viewed products');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7.0.99');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
    }

    public function install()
    {

        // Hooks & Install
        return (parent::install() 
                && $this->prepareModuleSettings() 
                && $this->registerHook('displayHome') 
                && $this->registerHook('displayHeader'));
    }

    public function prepareModuleSettings()
    {
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    

    public function getValues()
    {

        $manufacturer = $this->getManufacturerMostView(3, 3);

        $product = $this->getProductMostView(3, 3); 

        $this->context->smarty->assign(array(
            'products' => $product,
            'manufacturers' => $manufacturer,
        ));
    }

    public function hookHeader($params)
    {
    
        $this->context->controller->addCSS(($this->_path).'css/mostviews.css');
    }

    
    public function hookDisplayHome($params)
    {
        $this->getValues();

        return $this->display(__FILE__, 'recent.tpl');
    }

    
   /**
    * Get all available products
    *
    * @param int $limit Number of products to return
    * @param int $id_lang Language id
    * @return array Products details
    */
    private function getProductMostView($limit, $id_lang) {

        $sql = 'SELECT SUM(pv.counter) AS total, pr.*, p.*,i.id_image, m.name AS manufacturer_name, pl.name FROM ps_page_viewed pv LEFT JOIN ps_date_range dr ON pv.id_date_range = dr.id_date_range LEFT JOIN ps_page p ON pv.id_page = p.id_page LEFT JOIN ps_page_type pt ON pt.id_page_type = p.id_page_type LEFT JOIN ps_product_lang pl ON (p.id_object = pl.id_product and pl.id_lang='.$id_lang.') LEFT JOIN ps_product pr ON pr.id_product = p.id_object LEFT JOIN ps_image i ON (pr.id_product = i.id_product and i.position=1) LEFT JOIN ps_manufacturer m ON (m.id_manufacturer = pr.id_manufacturer) where pt.name=\'product\' AND dr.time_start > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND dr.time_end < CONCAT(CURDATE(),\'23:59:59\') AND m.name is not null GROUP BY p.id_page order by total desc limit 0, '.$limit;


        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);


        return Product::getProductsProperties( $id_lang, $rq);
    }

    private function getManufacturerMostView($limit, $id_lang) {


        $sql = 'SELECT SUM(pv.counter) AS total, p.*,  m.id_manufacturer, m.name FROM ps_page_viewed pv LEFT JOIN ps_date_range dr ON pv.id_date_range = dr.id_date_range LEFT JOIN ps_page p ON pv.id_page = p.id_page LEFT JOIN ps_page_type pt ON pt.id_page_type = p.id_page_type LEFT JOIN ps_manufacturer m ON m.id_manufacturer = p.id_object where pt.name=\'manufacturer\' AND dr.time_start > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND dr.time_end < CONCAT(CURDATE(),\'23:59:59\') AND p.id_object!=0 GROUP BY p.id_page order by total desc limit 0, '.$limit;


        $manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($manufacturers === false) {
            return false;
        }
        $total_manufacturers = count($manufacturers);
        $rewrite_settings = (int)Configuration::get('PS_REWRITING_SETTINGS');
        for ($i = 0; $i < $total_manufacturers; $i++) {
            $manufacturers[$i]['link_rewrite'] = ($rewrite_settings ? Tools::link_rewrite($manufacturers[$i]['name']) : 0);
        }
        return $manufacturers;
    }

}
