<?php

/** @var Mage_Catalog_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$attributes = array(
    'jp_series_name' => array(
        'label' => 'Series Name',
        'type' => 'varchar',
        'input' => 'text',
        'required' => 0,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group' => 'General',
        'sort_order' => 200,
        'visible_on_front' => 0,
        'used_in_product_listing' => 0,
        'user_defined' => 1,
    ),

    'jp_series_value_1' => array(
        'label' => 'Series Row 1 Value',
        'type' => 'varchar',
        'input' => 'text',
        'required' => 0,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group' => 'General',
        'sort_order' => 210,
        'visible_on_front' => 0,
        'used_in_product_listing' => 0,
        'user_defined' => 1,
    ),
    'jp_series_value_2' => array(
        'label' => 'Series Row 2 Value',
        'type' => 'varchar',
        'input' => 'text',
        'required' => 0,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group' => 'General',
        'sort_order' => 220,
        'visible_on_front' => 0,
        'used_in_product_listing' => 0,
        'user_defined' => 1,
    ),
    'jp_series_value_3' => array(
        'label' => 'Series Row 3 Value',
        'type' => 'varchar',
        'input' => 'text',
        'required' => 0,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group' => 'General',
        'sort_order' => 230,
        'visible_on_front' => 0,
        'used_in_product_listing' => 0,
        'user_defined' => 1,
    ),
);

foreach ($attributes as $code => $data) {
    if (!$installer->getAttributeId('catalog_product', $code)) {
        $installer->addAttribute('catalog_product', $code, $data);
    }
}

$installer->endSetup();
