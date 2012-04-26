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

jimport('joomla.application.component.controller');


class SolrController extends JController
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'configuration';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/solr.php';

		// Load the submenu.
		//SearchHelper::addSubmenu(JRequest::getCmd('view', 'searches'));

		parent::display();
	}
}
