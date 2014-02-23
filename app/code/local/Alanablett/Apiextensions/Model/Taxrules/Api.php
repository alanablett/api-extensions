<?php

class Alanablett_Apiextensions_Model_TaxRules_Api {
    
    /**
     * Get tax rule data based on a tax rule name
     * @param  String $ruleName 
     * @return Array
     */
    public function getTaxRuleDataByName($ruleName)
    {
        $rulesCollection = Mage::getResourceModel('tax/calculation_rule_collection')
            ->addFieldToFilter('code', $ruleName);
        
        if ($rulesCollection->count() === 0) return array();
        
        return $this->getTaxRuleData($rulesCollection->getFirstItem());
    }
    
    /**
     * Gets the data of an individual tax rule
     * @param  Mage_Tax_Model_Calculation_Rule $taxRule 
     * @return Array 
     */
    private function getTaxRuleData($taxRule)
    {
        $taxRule = Mage::getModel('tax/calculation_rule')->load($taxRule->getId());
        return $this->getTaxRates($taxRule);
    }
    
    /**
     * Get the tax rates for the rule
     * @param  Mage_Tax_Model_Calculation_Rule $taxRule
     * @return Array
     */
    public function getTaxRates($taxRule)
    {
        $data = array();
        
        $rateIds = $taxRule->getRates();
        $ratesCollection = Mage::getResourceModel('tax/calculation_rate_collection')
            ->addFieldToFilter('tax_calculation_rate_id', $rateIds);
        
        foreach($ratesCollection as $rate)
        {
            $data[] = array(
                'code' => $rate->getCode(),
                'taxCountryId' => $rate->getTaxCountryId()
            );
        }
        
        return $data;
    }
}