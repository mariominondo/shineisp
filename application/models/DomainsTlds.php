<?php

/**
 * DomainsTlds
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class DomainsTlds extends BaseDomainsTlds
{
	
	/**
	 * grid
	 * create the configuration of the grid
	 */
	public static function grid($rowNum = 10) {
		
		$translator = Zend_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 'dt.tld_id', 'alias' => 'tld_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'ID' ), 'field' => 'dt.tld_id', 'alias' => 'tld_id', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'TLD' ), 'field' => 'ws.tld', 'alias' => 'tld', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Registration price' ), 'field' => 'dt.new', 'alias' => 'new', 'sortable' => true, 'searchable' => true, 'type' => 'integer' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Transfer price' ), 'field' => 'dt.transfer', 'alias' => 'transfer', 'sortable' => true, 'searchable' => true, 'type' => 'integer' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Renewal price' ), 'field' => 'dt.renew', 'alias' => 'renew', 'sortable' => true, 'searchable' => true, 'type' => 'integer' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Whois' ), 'field' => 'ws.server', 'alias' => 'whois', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		
		$config ['datagrid'] ['fields'] =  "dt.tld_id, ws.server as whois, ws.tld as tld, dt.registration_price as new, dt.transfer_price as transfer, dt.renewal_price as renew";
		
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()
																->select ( $config ['datagrid'] ['fields'] )
																->from ( 'DomainsTlds dt' )
																->leftJoin ( 'dt.WhoisServers ws' )
																->leftJoin('dt.DomainsTldsData dtd')
																->orderBy('ws.tld');
		
		$config ['datagrid'] ['rownum'] = $rowNum;
		$config ['datagrid'] ['basepath'] = "/admin/domainstlds/";
		$config ['datagrid'] ['index'] = "tld_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/domainstlds/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/domainstlds/delete/id/%d";
		
		$config ['datagrid'] ['massactions'] = array ('massdelete' => 'Delete');
		
		return $config;
	}
	
	/**
	 * Save all the data
	 * 
	 * 
	 * @param array $params
	 */
	public static function saveAll(array $params, $locale){
		
		if(!empty($params['tld_id'])){
			$tld = self::find($params['tld_id']);
		}else{
			$tld = new DomainsTlds();
		}
		
		$tld['server_id']          = $params['server_id'];
		$tld['registration_price'] = $params['registration_price'];
		$tld['renewal_price']      = $params['renewal_price'];
		$tld['transfer_price']     = $params['transfer_price'];
		$tld['registration_cost']  = $params['registration_cost'];
		$tld['renewal_cost']       = $params['renewal_cost'];
		$tld['transfer_cost']      = $params['transfer_cost'];
		$tld['registrars_id']      = $params['registrars_id'];
		$tld['ishighlighted']      = $params['ishighlighted'];
		$tld['isrefundable']       = (isset($params['isrefundable'])) ? intval($params['isrefundable']) : 0;
  		$tld['tax_id']             = $params['tax_id']; 
  		$tld['isp_id']             = Zend_Registry::get('ISP')->isp_id;     
		
		if($tld->trySave()){
			if(is_numeric($tld['tld_id'])){
				$record = self::getTranslation($tld['tld_id'], $locale);
				if($record === false){
					$record = new DomainsTldsData();
				}
				
				// Save the page translation references
				$record->name        = $params['name'];
				$record->description = $params['description'];
				$record->tags        = $params['tags'];
				$record->tld_id      = $tld['tld_id'];
				$record->language_id = $locale;
				$record->save();
				
			}
		}
	
		return $tld['tld_id'];
	}
	
	/**
	 * get the translations 
	 * 
	 * 
	 * @param integer $id
	 * @param integer $locale
	 * @return doctrine record
	 */
	public static function getTranslation($id, $locale) {
		
		$record = Doctrine_Query::create ()
                    ->from ( 'DomainsTldsData dtd' )
                    ->where ( "dtd.tld_id = ?", $id )
                    ->addWhere ( "dtd.language_id = ?", $locale )
					->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
                    ->execute( array (), Doctrine_Core::HYDRATE_ARRAY );
        
        if(!empty($record[0])){
        	return Doctrine::getTable('DomainsTldsData')->find($record[0]['data_id']);
        }else{
        	return false;
        }
        
	}
	
	/**
	 * find the record by id 
	 * @param integer
	 * @return doctrine record
	 */
	public static function find($id) {
		return Doctrine::getTable ( 'DomainsTlds' )->find ( $id );
	}
		
	/**
	 * massdelete
	 * delete the customer selected 
	 * @param array
	 * @return Boolean
	 */
	public static function massdelete($items) {
		$retval = Doctrine_Query::create ()->delete ()->from ( 'DomainsTlds d' )->whereIn ( 'd.tld_id', $items )->execute ();
		return $retval;
	}
	
	/**
     * Get all data with the Tld ID
     * 
     * 
     * @param $id
     * @return Doctrine Record / Array
     */
    public static function getAllInfo($id, $locale=1) {
        
        try {
            $record = Doctrine_Query::create ()
                    ->from ( 'DomainsTlds dt' )
                    ->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
                    ->leftJoin('dt.WhoisServers ws')
                    ->leftJoin('dt.Registrars r')
                    ->leftJoin('dt.Taxes t')
                    ->where ( "dt.tld_id = ?", $id )
					->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
                    ->limit ( 1 )
                    ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        
           
            if(!empty($record[0]['DomainsTldsData'][0])){
				$record[0]['name'] = $record[0]['DomainsTldsData'][0]['name'];
				$record[0]['description'] = $record[0]['DomainsTldsData'][0]['description'];
				$record[0]['tags'] = $record[0]['DomainsTldsData'][0]['tags'];
				$record[0]['language_id'] = $record[0]['DomainsTldsData'][0]['language_id'];
			}
			
            return !empty($record[0]) ? $record[0] : array();   
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }	
    
	/**
     * Get a tld information starting from the tld name
     * 
     * 
     * @param $tld
     * @return  Array
     */
    public static function getbyTld($tld, $locale=1) {
        
        try {
            $record = Doctrine_Query::create ()
                    ->from ( 'DomainsTlds dt' )
                    ->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
                    ->leftJoin('dt.Registrars r')
                    ->leftJoin('dt.Taxes t')
                    ->where ( "dtd.name = ?", $tld )
					->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
                    ->limit ( 1 )
                    ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
            
            return !empty($record) ? $record[0] : array();   
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }	
    
	/**
     * Get a tld information starting from the ID
     * 
     * 
     * @param $tld_id
     * @return  Array
     */
    public static function getbyID($tld_id, $locale=1) {
        
        try {
            $record = Doctrine_Query::create ()
                    ->from ( 'DomainsTlds dt' )
                    ->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
                    ->leftJoin('dt.Registrars r')
                    ->leftJoin('dt.WhoisServers ws')
                    ->leftJoin('dt.Taxes t')
                    ->where ( "tld_id = ?", $tld_id )
					->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
                    ->limit ( 1 )
                    ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
            
            return !empty($record) ? $record[0] : array();   
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }	
    
	/**
     * Get all records
     * 
     * 
     * @return Array
     */
    public static function getAll($locale=1) {
        
        try {
            return Doctrine_Query::create ()->from ( 'DomainsTlds dt' )
            								->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
            								->leftJoin('dt.Registrars r')
            								->leftJoin('dt.Taxes t')
											->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
            								->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }
    
	/**
     * Get all highligted tlds records
     * 
     * 
     * @return Array
     */
    public static function getHighlighted($locale=1) {
        
        try {
            return Doctrine_Query::create ()->from ( 'DomainsTlds dt' )
            								->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
            								->leftJoin('dt.Registrars r')
            								->leftJoin('dt.Taxes t')
            								->where('dt.ishighlighted = ?', true)
											->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
            								->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }
    
	/**
     * Check if a domain is highlighted
     * 
     * @param integer $id
     * @return boolean
     */
    public static function isHighlighted($id) {
        
        try {
            return Doctrine_Query::create ()->from ( 'DomainsTlds dt' )
            								->where('dt.ishighlighted = ?', true)
            								->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
            								->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }

	/**
     * Get all refundables tlds records
     * 
     * 
     * @return Array
     */
    public static function getRefundables($locale=1) {
        
        try {
            return Doctrine_Query::create ()->from ( 'DomainsTlds dt' )
            								->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
            								->leftJoin('dt.Registrars r')
            								->leftJoin('dt.Taxes t')
            								->where('dt.isrefundable = ?', true)
											->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
            								->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }
    
	/**
     * Check if a domain is refundable
     * 
     * @param integer $id
     * @return boolean
     */
    public static function isRefundable($id) {
        
        try {
            return Doctrine_Query::create ()->from ( 'DomainsTlds dt' )
            								->where('dt.isrefundable = ?', true)
            								->addWhere('dt.tld_id = ?', $id)
											->addWhere('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
            								->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }

	/**
	 * getList
	 * Get the list of records for the select object
	 */
	public static function getList($addpointprefix = false, $locale=1) {
		$items = array ();
		$records = Doctrine_Query::create ()
					->select("tld_id, dtd.data_id, dtd.name")
                    ->from ( 'DomainsTlds dt' )
                    ->leftJoin("dt.DomainsTldsData dtd WITH dtd.language_id = $locale")
                    ->leftJoin('dt.Registrars r')
                    ->leftJoin('dt.Taxes t')
					->where('dt.isp_id = ?',Zend_Registry::get('ISP')->isp_id)
                    ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		
		foreach ( $records as $c ) {
			if(!empty($c ['DomainsTldsData'][0]['name'])){
				if($addpointprefix){
					$items [$c ['tld_id']] = "." . $c ['DomainsTldsData'][0]['name'];
				}else{
					$items [$c ['tld_id']] = $c ['DomainsTldsData'][0]['name'];
				}
			}
		}
		
		return $items;
	}    
	
	
	/**
	 * Get autosetup value for this TLD
	 * Honore global preference and override it with each settings in TLD
	 */
	public static function getAutosetup($id) {
		$DomainTld = self::find($id);
		$autosetup = (isset($DomainTld) && !empty($DomainTld->autosetup) ) ? $DomainTld->autosetup : '';

		// autosetup configured for this tld
		if ( !empty($autosetup) ) {
			return $autosetup;
		}
		
		// i'm here, so there is no autosetup set for this TLD. Fallback to global preference
		$domains_autosetup = Settings::findbyParam('domains_autosetup');
		return $domains_autosetup ? intval($domains_autosetup) : 0;
	}	
	
	
	/**
	 * Get the domain auto setup creation values
	 */
	public static function getAutoSetupValues() {
		return array(
			 '0' => 'Do not automatically register or transfer domains'
		  	,'1' => 'Automatically register or transfer domains as soon as an order is placed'
		  	,'2' => 'Automatically register or transfer domains as soon as the first payment is received'
		  	,'3' => 'Automatically register or transfer domains when you manually accept a pending order'
		  	,'4' => 'Automatically register or transfer domains as soon as the payment is complete'
		);
	}
	
	
	
	
	
	
}