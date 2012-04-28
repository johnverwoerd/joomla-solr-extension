<?php
class JoomlaSolr
{

	private $options = null;

	private $client = null;
	
	private $document = null;
	
	private $facets = null;
	
	private $response = null;
	
	private $results = null;
	
	private $query = null;
	
	private $full_query = null;
	
	private $requestUrl = null;

	public function __construct()
	{
		//TODO: Add username,password, port from joomla
		$this->options = array
		(
			'hostname' => 'localhost',
			//'login'    => SOLR_SERVER_USERNAME,
			//'password' => SOLR_SERVER_PASSWORD,
			'port'     => 8983,
			'path'     => 'solr',
			//'wt'       => SOLR_PHP_NATIVE_RESPONSE_WRITER,
		);
		$this->getClient();
		$this->query = new SolrQuery();
		

		
		
		
	}
	
	public function addFacets()
	{
		//TODO: change this
		//$this->query->setFacet(true);
		//$this->query->addFacetField('genre');
		//$this->query->addFacetField('animetype');
		//$this->query->getFacetFields();

	}
	
	public function getAllFacets()
	{
		return $this->facets;
	}
	
	public function getResponse( $enableFacets = false )
	{
		if ( $enableFacets ) $this->addFacets();
		$response = $this->client->query($this->query);
		$this->requestUrl = $response->getRequestUrl();
		$this->response = $response->getResponse();
		if ($enableFacets && $this->response->facet_counts) $this->facets = $this->response->facet_counts;
		//print_r($this->response);
		
		return $this->response;
	}
	
	public function getRequestUrl()
	{
		return $this->requestUrl;
	}
	
	
	
	public function getQuery()
	{
		return $this->query;
	}
	
	public function getClient()
	{
		if ($this->options == null) throw new Exception('Set the Solr options first!');
		
		try
		{
			$this->client = new SolrClient($this->options);
			if ( ! $this->client->ping() ) 
			{
				throw new Exception('Solr server not responding');
			}
			else
			{
				return $this->client;
			}
		} catch(Exception $e)
		{
			//error
		}
	}
	
	public function getFullQueryString()
	{
		parse_str($this->query, $output);
		$this->full_query = $output;
		return $this->full_query;
	}
	
	public function createDocument($name = null)
	{
		$this->document = new SolrInputDocument();
	}
	
	public function addField($name, $value)
	{
		if ($this->document == null ) 
		{
			$this->document = new SolrInputDocument();
			if ($this->document == null) return false;
		}
		return $this->document->addField($name, $value);
	}
	
	public function saveDocument()
	{
			if ($this->document == null || $this->client == null) return false;
			$this->client->addDocument($this->document);
			$this->client->commit();
			$this->client->optimize();
			$this->document = null;
			return true;
	}
	public function commit()
	{
	}

	
	

}
