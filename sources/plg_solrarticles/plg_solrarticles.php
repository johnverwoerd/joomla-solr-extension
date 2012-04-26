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

jimport('joomla.plugin.plugin');

//TODO: Needs work to use for articles
class plgContentSolrarticles extends JPlugin
{

	public function onContentAfterSave($context, $anime, $isNew)
	{
	
		if ($anime instanceof JTableAnime)
		{
		
			
			jimport('solrsearch.joomlasolr');
			$solr = new JoomlaSolr();
			$db = JFactory::getDBO();

			//TODO: change query etc.
			if ( isset($anime->id) && $anime->id > 0 )
			{
				$genresQuery = "SELECT `name` FROM #__anime_genres as ag LEFT JOIN #__genres as g on g.id = ag.genre_id  WHERE  anime_id = " . $anime->id ;
				$db->setQuery( $genresQuery );
				$genres = $db->loadObjectList();
				//Update anime
				$solr->addField('id', $anime->id);
				$solr->addField('title', $anime->title);
				$solr->addField('introtext', strip_tags($anime->introtext));
				foreach( $genres as $genre )
				{
					$solr->addField('genre', $genre->name);
				}
			}
			else
			{
				//Save new anime
				$solr->addField('id', $anime->id);
				$solr->addField('title', $article->anime);
				$solr->addField('introtext', strip_tags($anime->introtext));
			}
			if ( $solr->saveDocument() )
			{
				JFactory::getApplication()->enqueueMessage( JText::_('COM_SOLRSEARCH_SAVED') );
			}
			else
			{
					JFactory::getApplication()->enqueueMessage( JText::_('COM_SOLRSEARCH_NOT_SAVED') );
			}
			

		}
		
		return true;
	}

	/**
	 * Don't allow categories to be deleted if they contain items or subcategories with items
	 *
	 * @param	string	The context for the content passed to the plugin.
	 * @param	object	The data relating to the content that was deleted.
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentBeforeDelete($context, $data)
	{
		// Skip plugin if we are deleting something other than categories
		if ($context != 'com_categories.category') {
			return true;
		}

		// Check if this function is enabled.
		if (!$this->params->def('check_categories', 1)) {
			return true;
		}

		$extension = JRequest::getString('extension');

		// Default to true if not a core extension
		$result = true;

		$tableInfo = array (
			'com_banners' => array('table_name' => '#__banners'),
			'com_contact' => array('table_name' => '#__contact_details'),
			'com_content' => array('table_name' => '#__content'),
			'com_newsfeeds' => array('table_name' => '#__newsfeeds'),
			'com_weblinks' => array('table_name' => '#__weblinks')
		);

		// Now check to see if this is a known core extension
		if (isset($tableInfo[$extension]))
		{
			// Get table name for known core extensions
			$table = $tableInfo[$extension]['table_name'];
			// See if this category has any content items
			$count = $this->_countItemsInCategory($table, $data->get('id'));
			// Return false if db error
			if ($count === false)
			{
				$result = false;
			}
			else
			{
				// Show error if items are found in the category
				if ($count > 0 ) {
					$msg = JText::sprintf('COM_CATEGORIES_DELETE_NOT_ALLOWED', $data->get('title')) .
					JText::plural('COM_CATEGORIES_N_ITEMS_ASSIGNED', $count);
					JError::raiseWarning(403, $msg);
					$result = false;
				}
				// Check for items in any child categories (if it is a leaf, there are no child categories)
				if (!$data->isLeaf()) {
					$count = $this->_countItemsInChildren($table, $data->get('id'), $data);
					if ($count === false)
					{
						$result = false;
					}
					elseif ($count > 0)
					{
						$msg = JText::sprintf('COM_CATEGORIES_DELETE_NOT_ALLOWED', $data->get('title')) .
						JText::plural('COM_CATEGORIES_HAS_SUBCATEGORY_ITEMS', $count);
						JError::raiseWarning(403, $msg);
						$result = false;
					}
				}
			}

			return $result;
		}
	}


}
