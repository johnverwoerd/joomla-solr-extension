<?php
/*
 * This file is part of the Joomla Solr Extension.
*
* (c) John Verwoerd <john@xlab.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SolrViewConfiguration extends JView
{
	protected $enabled;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		//$this->items		= $this->get('Items');
		//$this->pagination	= $this->get('Pagination');
		//$this->state		= $this->get('State');
		//$this->enabled		= $this->state->params->get('enabled');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$canDo	= SolrHelper::getActions();

		JToolBarHelper::title(JText::_('COM_SOLRSEARCH_CONFIGURATION'), 'cpanel.png');

	JSubMenuHelper::addEntry(
			JText::_('COM_SOLRSEARCH_DASHBOARD'),
			'index.php?option=com_solrsearch&view=solr',
			$vName == 'solr'
		);	
		
		if ($canDo->get('core.edit.state')) {
			//JToolBarHelper::custom('searches.reset', 'refresh.png', 'refresh_f2.png', 'JSEARCH_RESET', false);
			
			//JToolBarHelper::customX('searches.reindex','refresh.png', 'refresh_f2.png', 'REINDEX', false);
		}
		//JToolBarHelper::divider();
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_search');
		}
		//JToolBarHelper::divider();
		//JToolBarHelper::help('JHELP_COMPONENTS_SEARCH');
	}
}
