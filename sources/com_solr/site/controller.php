<?php
/*
 * This file is part of the Joomla Solr Extension.
*
* (c) John Verwoerd <john@xlab.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
jimport( 'joomla.application.component.helper' );
 
class SolrController extends JController
{
	private $params = null;
	
	public function __construct()
	{	
		parent::__construct();
	}  
}