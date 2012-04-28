<?php
/*
 * This file is part of the Joomla Solr Extension.
*
* (c) John Verwoerd <john@xlab.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

defined('_JEXEC') or die;

jimport('solr.joomlasolr');

// Register dependent classes.
//JLoader::register('FinderIndexer', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/indexer/indexer.php');

/**
 * Indexer controller class for Finder.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_solr
 * @since       2.5
 */
class SolrControllerIndexer extends JController
{


	public function total()
	{
		//TODO: add security here
		$db = JFactory::getDBO();
		$query = "SELECT count(*) as totals FROM #__content";
		$db->setQuery($query);
		
		echo json_encode(array('total' => $db->loadResult() ) );
		die();
	}
	
	public function createindex()
	{
		//TODO: add security here!
		jimport('solrsearch.joomlasolr');
		
		$db = JFactory::getDBO();
		$query = "SELECT count(*) as totals FROM #__content";
		
		$db->setQuery($query);
		
		$total_values = $db->loadResult();
		
		//echo $total_values . '<-- ' ;
		
		$rows_per_update = 100;
		
		
		$current = JRequest::getInt('current');
		
		//echo $current . ' <-- <br />'; 
		
		set_time_limit(120);
		
		$current_rows = ($total_values / 100) * $current;
		
		//echo $current_rows . ' <-- current rows <br />';
		
		$current_percentage =  ( $current_rows + $rows_per_update  )  / ( $total_values  / 100 )  ;
		
		//echo $current_percentage . ' <-- current percentage <br />';
		
		
		
		/*
		3365 RIJEN
		200 PER KEER
		3365 / 100 33.65
		
		
		200 / 33.65 =
		*/
		$solr = new JoomlaSolr();
		
		//TODO: change this	
		$articlesQuery = "SELECT a.id as articleid, a.`alias`, a.* FROM #__content as a LIMIT " . (int)$current_rows . "," . $rows_per_update;
		
		$db->setQuery( $articlesQuery );
		
		
		
		$articles = $db->loadObjectList();
		
		
			foreach($articles as $articleMass)
			{
				//print_r($animeMass);
				//die($animeMass->rating . ' <--');

			    
				$solr->createDocument();
				
				
				//Update anime
				$solr->addField('id', $articleMass->articleid);
				$solr->addField('catid', $articleMass->articleid);
				
				$solr->addField('title', $articleMass->title);
				
				$solr->addField('alias', $articleMass->alias);
				
				$solr->addField('introtext', strip_tags($articleMass->introtext));
				
				$solr->saveDocument();
			}
		if ( $current >= 0 )
		{
			header('Content-Type: application/json');
			header("Connection: Keep-Alive"); 
			header("Keep-Alive: timeout=130"); 
			echo json_encode(array('percentage' => (int)$current_percentage ) );
		}
		else
		{
			echo json_encode(array('percentage' => 100 ) );
		}
		
		
		die();
	}
}
