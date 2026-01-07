<?php

class JP_SeriesButtons_Block_Buttons extends Mage_Core_Block_Template
{
    const ATTR_SERIES_NAME = 'jp_series_name';
    const ATTR_VALUE_1     = 'jp_series_value_1';
    const ATTR_VALUE_2     = 'jp_series_value_2';
    const ATTR_VALUE_3     = 'jp_series_value_3';

    public function getCurrentProduct()
    {
        return Mage::registry('current_product');
    }

    public function getSeriesName()
    {
        $product = $this->getCurrentProduct();
        if (!$product) {
            return '';
        }

        return trim((string)$product->getData(self::ATTR_SERIES_NAME));
    }

    public function canShow()
    {
        return (bool)$this->getSeriesName();
    }

    public function getRowValueAttributeCode($rowNumber)
    {
        $map = array(
            1 => self::ATTR_VALUE_1,
            2 => self::ATTR_VALUE_2,
            3 => self::ATTR_VALUE_3,
        );

        return isset($map[$rowNumber]) ? $map[$rowNumber] : '';
    }

    public function getSeriesProducts()
    {
        $seriesName = $this->getSeriesName();
        $product = $this->getCurrentProduct();

        if (!$seriesName || !$product) {
            return array();
        }

        $storeId = Mage::app()->getStore()->getId();

        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->setStoreId($storeId);
        $collection->addStoreFilter($storeId);

        $collection->addAttributeToSelect(array(
            'name',
            'url_key',
            'url_path',
            self::ATTR_SERIES_NAME,
            self::ATTR_VALUE_1,
            self::ATTR_VALUE_2,
            self::ATTR_VALUE_3,
        ));

        $collection->addAttributeToFilter(self::ATTR_SERIES_NAME, array('eq' => $seriesName));

        $collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $collection->addAttributeToFilter('visibility', array('in' => array(
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
        )));

        $collection->addAttributeToSort('name', 'ASC');

        return $collection->getItems();
    }

    public function getCurrentRowValue($rowNumber)
    {
        $product = $this->getCurrentProduct();
        if (!$product) {
            return '';
        }

        $attr = $this->getRowValueAttributeCode($rowNumber);
        if (!$attr) {
            return '';
        }

        return trim((string)$product->getData($attr));
    }

    public function getUniqueValuesForRow($rowNumber, array $products)
    {
        $attr = $this->getRowValueAttributeCode($rowNumber);
        if (!$attr) {
            return array();
        }

        $values = array();

        foreach ($products as $p) {
            $val = trim((string)$p->getData($attr));
            if ($val === '') {
                continue;
            }

            $key = mb_strtolower($val);
            if (!isset($values[$key])) {
                $values[$key] = $val;
            }
        }

        return array_values($values);
    }

    public function getBestMatchUrl($rowNumber, $clickedValue, array $products)
    {
        $clickedValue = trim((string)$clickedValue);
        if ($clickedValue === '') {
            return $this->getCurrentProduct() ? $this->getCurrentProduct()->getProductUrl() : '#';
        }

        $wanted = array(
            1 => $this->getCurrentRowValue(1),
            2 => $this->getCurrentRowValue(2),
            3 => $this->getCurrentRowValue(3),
        );

        $wanted[$rowNumber] = $clickedValue;

        $prioritySets = array(
            array(1, 2, 3),
            array(1, 2),
            array(1, 3),
            array(2, 3),
            array($rowNumber),
        );

        foreach ($prioritySets as $set) {
            $match = $this->findFirstMatchingProduct($products, $wanted, $set);
            if ($match) {
                return $match->getProductUrl();
            }
        }

        return $this->getCurrentProduct() ? $this->getCurrentProduct()->getProductUrl() : '#';
    }

    protected function findFirstMatchingProduct(array $products, array $wanted, array $matchRows)
    {
        foreach ($products as $p) {
            $ok = true;

            foreach ($matchRows as $row) {
                $attr = $this->getRowValueAttributeCode($row);
                if (!$attr) {
                    continue;
                }

                $productVal = trim((string)$p->getData($attr));
                $wantedVal  = trim((string)$wanted[$row]);

                if ($wantedVal === '') {
                    continue;
                }

                if (mb_strtolower($productVal) !== mb_strtolower($wantedVal)) {
                    $ok = false;
                    break;
                }
            }

            if ($ok) {
                return $p;
            }
        }

        return null;
    }

    public function isActiveValueForRow($rowNumber, $value)
    {
        $currentVal = $this->getCurrentRowValue($rowNumber);
        if ($currentVal === '') {
            return false;
        }

        return mb_strtolower($currentVal) === mb_strtolower(trim((string)$value));
    }
}
