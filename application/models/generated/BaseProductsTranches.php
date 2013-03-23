<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('ProductsTranches', 'doctrine');

/**
 * BaseProductsTranches
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $tranche_id
 * @property integer $quantity
 * @property float $price
 * @property integer $product_id
 * @property integer $billing_cycle_id
 * @property string $measurement
 * @property boolean $selected
 * @property Products $Products
 * @property BillingCycle $BillingCycle
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductsTranches extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('products_tranches');
        $this->hasColumn('tranche_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('quantity', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('price', 'float', 10, array(
             'type' => 'float',
             'notnull' => true,
             'length' => '10',
             ));
        $this->hasColumn('product_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => '4',
             ));
        $this->hasColumn('billing_cycle_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => '4',
             ));
        $this->hasColumn('measurement', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('selected', 'boolean', 25, array(
             'type' => 'boolean',
             'length' => '25',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Products', array(
             'local' => 'product_id',
             'foreign' => 'product_id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('BillingCycle', array(
             'local' => 'billing_cycle_id',
             'foreign' => 'billing_cycle_id'));
    }
}