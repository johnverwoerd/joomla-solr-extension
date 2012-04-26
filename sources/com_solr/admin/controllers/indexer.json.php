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
	
		$db = JFactory::getDBO();
		$query = "SELECT count(*) as totals FROM #__content";
		$db->setQuery($query);
		
		echo json_encode(array('total' => $db->loadResult() ) );
		die();
	}
	
	public function createindex()
	{
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
		$articlesQuery = "SELECT a.id as animeid, ROUND( v.rating_sum / v.rating_count,2 ) AS rating,  a.`alias`, a.*, atypes.* FROM #__animes as a LEFT JOIN #__anime_types as atypes on a.type_id = atypes.id LEFT JOIN #__anime_rating AS v ON a.id = v.anime_id LIMIT " . (int)$current_rows . "," . $rows_per_update;
		
		$db->setQuery( $articlesQuery );
		$animes = $db->loadObjectList();
			foreach($animes as $animeMass)
			{
				//print_r($animeMass);
				//die($animeMass->rating . ' <--');
				
				$solr->createDocument();
				//TODO: add joomla_content query here
				$genresQuery = "SELECT `name`, genre_id FROM #__animegenres as ag LEFT JOIN #__genres as g on g.id = ag.genre_id  WHERE  anime_id = " . $animeMass->animeid ;
				
				$db->setQuery( $genresQuery );
				$genres = $db->loadObjectList();
				//Update anime
				$solr->addField('id', $animeMass->animeid);
				$solr->addField('title', $animeMass->title);
				$solr->addField('date_from', date("Y-m-d\TG:i:s\Z", strtotime($animeMass->date_from)));
				$solr->addField('date_end', date("Y-m-d\TG:i:s\Z", strtotime($animeMass->date_end)));
				$solr->addField('alias', $animeMass->alias);
				$solr->addField('rating', (double)$animeMass->rating);
				$solr->addField('image_path', $animeMass->animeAfbeelding);
				$solr->addField('animetype', $animeMass->name);
				$solr->addField('introtext', strip_tags($animeMass->introtext));
				foreach( $genres as $genre )
				{
					$solr->addField('genre', $genre->name);
				}
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
