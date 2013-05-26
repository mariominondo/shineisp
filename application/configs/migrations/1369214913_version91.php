<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version91 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('oauth_clients', 'secret');
        $this->addColumn('oauth_clients', 'client_secret', 'string', '250', array(
             'notnull' => '1',
             ));
        $this->addColumn('orders', 'order_number', 'string', '50', array(
             ));
    }

    public function down()
    {
        $this->addColumn('oauth_clients', 'secret', 'string', '250', array(
             'notnull' => '1',
             ));
        $this->removeColumn('oauth_clients', 'client_secret');
        $this->removeColumn('orders', 'order_number');
    }
}