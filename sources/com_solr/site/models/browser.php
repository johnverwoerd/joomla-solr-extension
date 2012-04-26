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

jimport('joomla.application.component.model');
jimport('solrsearch.joomlasolr');
/**
 * Search Component Search Model
 *
 * @package		Joomla.Site
 * @subpackage	com_solr
 * @since 2.5
 */
class SolrModelBrowser extends JModel
{
	/**
	 * Sezrch data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Search total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Search areas
	 *
	 * @var integer
	 */
	var $_areas = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;
	
	private $solr = null;
	
	private $params = null;
	
	
	/**
	 * Constructor
	 *
	 * @since 2.5
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

		$app = JFactory::getApplication();
		$params = $app->getParams();
		
		$this->params = $params;
		
		$this->solr = new JoomlaSolr();
			
		
		//Get configuration
		$app	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$ordering = JRequest::getWord('ordering', 'title');

		
		//Get sort values
		
		// Get the pagination request variables
		$value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);
		$this->setState('limit', $app->getUserStateFromRequest('com_solr.limit', 'limit', $config->get('list_limit'), 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
		$filterFields = isset($_GET['filters']) ?  $_GET['filters'] : null;
		
		$this->setState('date_from', JRequest::getVar('date_from'));
		$this->setState('date_end', JRequest::getVar('date_end'));
		$this->setState('filterFields', $filterFields);
		
		
		
		// Set the search parameters
		$keyword		= urldecode(JRequest::getString('searchword'));
		$match			= JRequest::getWord('searchphrase', 'all');
		$this->setSearch($keyword, $match, $ordering);

		//Set the search areas
		$areas = JRequest::getVar('areas');
		$this->setAreas($areas);
	}

	/**
	 * Method to set the search parameters
	 *
	 * @access	public
	 * @param string search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 */
	function setSearch($keyword, $match = 'all', $ordering = 'date_from')
	{
		if (isset($keyword)) {
			$this->setState('origkeyword', $keyword);
			if($match !== 'exact') {
				$keyword 		= preg_replace('#\xE3\x80\x80#s', ' ', $keyword);
			}
			$this->setState('keyword', $keyword);
		}

		if (isset($match)) {
			$this->setState('match', $match);
		}

		if (isset($ordering)) {
			$this->setState('ordering', $ordering);
		}
	}

	/**
	 * Method to set the search areas
	 *
	 * @access	public
	 * @param	array	Active areas
	 * @param	array	Search areas
	 */
	function setAreas($active = array(), $search = array())
	{
		$this->_areas['active'] = $active;
		$this->_areas['search'] = $search;
	}

	/**
	 * Method to get weblink item data for the category
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			//JPluginHelper::importPlugin('search');
			//$dispatcher = JDispatcher::getInstance();
			//$results = $dispatcher->trigger('onContentSearch', array(
				//$this->getState('keyword'),
				//$this->getState('match'),
				//$areas['active'])
			//);
			
			
			

			$keyword = $this->getState('keyword');
			$query = $this->solr->getQuery();

			//Ordering
			$ordering = $this->getState('ordering');
			if (!empty($ordering))
			{
				//TODO: make this good
				//$query->addSortField( $ordering, SolrQuery::ORDER_DESC );	
			}
			
			//AllowedFilters
			//TODO: adapt this
			$allowedFilters = array( 'genre' );
			
			//Add filtering to the search
			$filterFields = $this->getState('filterFields');
			
			if ( isset($filterFields) && count($filterFields) > 0 )
			{
				foreach( $filterFields as $filterKey => $filter )
				{
					foreach ( $filter as $filterCat => $filterVal)
					{
						if (in_array($filterCat, $allowedFilters))
						{
							$query->addFilterQuery( $filterCat . ':' . $filterVal );			
						}
					}
				}
			}
			
			//Date filters
			$dateFrom = $this->getState('date_from');
			$dateEnd = $this->getState('date_end');
			if(!empty( $dateFrom ) || !empty($dateEnd)  )
			{
				$query->addFilterQuery('date_from:['. ( !empty( $dateFrom ) ? date("Y-m-d\TG:i:s\Z", strtotime($dateFrom)) : '1800-03-06T23:59:59.999Z' ) . ' TO '.( !empty( $dateEnd ) ? date("Y-m-d\TG:i:s\Z", strtotime($dateEnd)) : '*' ).']' );
					
			}
			
			//Set the current row and limits
			$query->setStart($this->getState('list.start'));
			$query->setRows($this->getState('limit'));
			

			//Add keyword to the search
			if ( !empty($keyword) )
			{
			    
				$query->setQuery($this->getState('keyword') . '*');

				//Set highlighting 
				if ($this->params->get('highlighting') == 1) 
				{
					$query->setShowDebugInfo(true);
					$query->addHighlightField( 'title' );
					$query->addHighlightField( 'introtext' );
					$query->setHighlight(true);
				}

				$query->addField('fulltext');
				
			}
			else
			{
				$query->setQuery('*');
				
			}
			
			$query->addField('title');
			$query->addField('introtext');
			$query->addField('alias');

			$results = $this->solr->getResponse(true);	

			
			$responseHeader = $results->responseHeader;
			
			
			$this->_total = $results->response->numFound;
		
			if ($this->getTotal() > 0)
			{
				$this->_data = $results->response->docs;
			}
			if ($this->params->get('highlighting') == 1) $this->_data->highlightresults  = $results->highlighting;

		}
		$this->_data['current_query'] = $this->solr->getQuery();
		$this->_data['facets'] = $this->solr->getAllFacets();
		$this->_data['total_results'] = $this->_total;
		$this->_data['full_query'] = $this->solr->getFullQueryString();
		return $this->_data;
	}
	
	
	
	/**
	 * Method to get the total number of solr items 
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Method to get a pagination object of the weblink items for the category
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}
	
	
	/**
	 * Method to get the search areas
	 *
	 * @since 2.5
	 */
	function getAreas()
	{
		// Load the Category data
		if (empty($this->_areas['search']))
		{
			$areas = array();

			//JPluginHelper::importPlugin('search');
			$dispatcher = JDispatcher::getInstance();
			$searchareas = $dispatcher->trigger('onContentSearchAreas');

			foreach ($searchareas as $area) {
				if (is_array($area)) {
					$areas = array_merge($areas, $area);
				}
			}

			$this->_areas['search'] = $areas;
		}

		return $this->_areas;
	}
}
