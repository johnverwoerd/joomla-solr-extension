<?php
/*
 * This file is part of the Joomla Solr Extension.
*
* (c) John Verwoerd <john@xlab.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

// no direct access
defined('_JEXEC') or die;

//Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_solr')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Solr');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
