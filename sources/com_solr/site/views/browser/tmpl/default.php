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
$document = JFactory::getDocument();

$document->addScript('/media/com_anime/js/jquery-ui-1.8.17.custom.min.js');
$document->addStyleSheet('/media/com_anime/css/redmond/jquery-ui-1.8.17.custom.css');

?>
<style type="text/css">
			.solr-filter 
			{
				width: 200px;
			
			}
			.search-fields span
			{
				
				text-indent: -9999px;		
				white-space: nowrap;
			}
			.clear-left
			{
				clear: left;	
			}
			.search-fields #search-searchword
			{
				float: left;
				width: 150px !important;	
			}
				
			#rt-main #rt-mainbody #searchForm .search-fields button.button
			{
				float: left;
				width: 40px;
				background: none;
				padding: 0px;
				margin: 0px;	
			}
			.remove-filter-link img
			{
				width: 12px;
				height: 12px; 	
			}
			#facets a, #facets span
			{
				margin-right: 5px;	
			}
			.search-field-block 
			{
				padding-top: 5px;
				padding-bottom: 5px;
				background: #F8F8F8;
				border-top: 1px solid #E6E6E6;
				border-bottom: 1px solid #E6E6E6;
				margin-bottom: 10px;	
			}
			.solr-filter button, #submit-filter-search, #facets .facet-button
			{
				background: black;
				color: white;
				border: none;
				cursor: pointer;
				font-weight: 700;
				border: solid 1px #FFF;
				text-transform: uppercase; 
			}
			
			#facet a 
			{
				width: 100px;	
			}
			#facets .facet-button
			{
				background: #2E3540;
				width: 100%;
				text-transform: lowercase;
				height: 25px;	
			}
			
			#solr-overview table th
			{
				background: #2E3540;
				color: white;	
			}
			#solr-overview table .image
			{
				text-align: center; 
				width: 125px;
			}
			#solr-overview table
			{
				width: 100%;	
			}
			#solr-overview table .title-row
			{
				width: 150px;	
			}
			#solr-overview table .image img
			{
				max-height: 50px;	
			}
			#solr-overview table .even
			{
				background: #E0E0E0;	
			}
			#solr-overview div.pagination ul
			{
				margin-top: 0px;
				padding-top: 0px;	
			}
			
			#solr-overview
			{
				padding: 5px;
				padding-top: 0px;	
			}
			#solr-overview div.pagination
			{
				width: 100%;
				background: #2E3540;
				display: block;
				color: white;
			}
			#solr-overview .pagination a
			{
				color: white;	
			}
			#solr-overview .pagination ul li
			{
				list-style-type: none;
				display: table-cell;
				width: auto;
				padding-right: 10px;
			}
			#facets 
			{
				margin-top: 5px;	
			}
			.search-animes
			{
				width: 100%;	
			}
			#datepickerFrom, #datepickerTo, #imageDatePickerFrom, #imageDatePickerTo
			{
				float: left;

			}
			#imageDatePickerFrom, #imageDatePickerTo
			{
				cursor: pointer; 	
			}
			#datepickerFrom, #datepickerTo
			{				
							width: 80px;
							margin-right: 5px;	
			}
			fieldset.dates label
			{
				display: block;
				float: left;
				width: 30px;
				margin-right: 5px;	
			}
</style>
<div class="solrsearch">
	<h1>Solr search</h1>
	<form id="searchForm" action="" method="post">
		<table class="search-solr">
		<tr>
			<td colspan="2">
				
					
			</td>
		<tr>
		<tr>
		<td valign="top">
		<?php echo $this->loadTemplate('form'); ?>
		</td>
		<td valign="top">
		<?php if (count($this->results) > 0) :
			echo $this->loadTemplate('results');
		else :
			echo $this->loadTemplate('error');
		endif; ?>
		</td>
		</tr>
		</table>
	</form>
</div>
