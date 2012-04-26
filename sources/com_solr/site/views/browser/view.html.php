<?php
/*
 * This file is part of the Joomla Solr Extension.
*
* (c) John Verwoerd <john@xlab.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Solr search component
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_solr
 *	
 * @since 2.5
 */
class SolrViewBrowser extends JView
{
	protected $pagination;
		
	function display($tpl = null)
	{
		$this->state = $this->get('State');

		// Load the parameters.
		$app = JFactory::getApplication();
		$params = $app->getParams();
		
		//Initialise variables
		$app	= JFactory::getApplication();
		$pathway = $app->getPathway();
		$uri	= JFactory::getURI();
		
		$model = &$this->getModel();
		
		$highlighting = JRequest::getInt('highlighting');
		$this->params	= $this->state->get('params');
		
		$this->dateFrom = $this->state->get('date_from');
		$this->dateEnd = $this->state->get('date_end');
		
		$model->setState('solrConfig', '');
		
		$this->results = $this->get('data');
		$total		= $this->get('total');
		$pagination	= $this->get('pagination');
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
	
}