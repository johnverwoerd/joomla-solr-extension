<?php
/**
 * @version		$Id: default.php 21576 2011-06-19 16:14:23Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath( JPATH_COMPONENT.'/helpers/html' );
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$doc = JFactory::getDocument();

$doc->addScript( JURI::root() . 'media/com_solr/js/jquery/jquery-1.7.2.min.js');
$doc->addScript( JURI::root() . 'media/com_solr/js/jquery/jquery-ui-1.8.19.custom.min.js');
$doc->addStyleSheet( JURI::root() . 'media/com_solr/css/jquery-ui-themes/redmond/jquery-ui-1.8.19.custom.css');
//$listOrder	= $this->escape($this->state->get('list.ordering'));
//$listDirn	= $this->escape($this->state->get('list.direction'));
$canDo		= SolrHelper::getActions();
?>
<style>
	.ui-progressbar-value { background-image: url(/media/com_solr/css/jquery-ui-themes/redmond/images/pbar-ani.gif); }
	</style>
<script type="text/javascript">
	jQuery('document').ready(function(){
	
		function getUrl( percentage)
		{
					jQuery.getJSON('index.php?option=com_solr&task=indexer.createindex&format=json&current='+parseInt(percentage), function(data) {
						if (data.percentage >= 100)
						{
							jQuery("#myProgressBar").progressbar({value:parseInt( 100 )});
						}
						else
						{
							jQuery("#myProgressBar").progressbar({value:parseInt( data.percentage )});
							getUrl(data.percentage);
						}
						
						
					});
		}
	
	
	
		jQuery('#reindex-solr').click(function(){

			
			
			jQuery("#myDialog").dialog({
				height: 100,
				width:500,
				modal: true,
				title:'Reindex articles',
				autoOpen:true
			});
			
				jQuery("#myProgressBar").progressbar({value:0 });
				getUrl(0);
			
		
		});
	});
</script>
<div id='myDialog'>
    <div id='myProgressBar'></div>
</div>

<?php
/**
* Joomla Dropbox component 1.0
* Copyright (c) 2011 Xlab, Xlab.nl All rights reserved.
* Author: John Verwoerd (xlab.nl)
* @license		GPLv2
* Visit http://www.xlab.nl for regular updates and information.
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');


?>
<table width="100%" border="0">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="#" id="reindex-solr" >
							<img src="/administrator/templates/bluestork/images/header/icon-48-clear.png" alt="Reindex">
							<span>Reindex
							</span>
						</a>
					</div>
				</div>				
			</div>
		</td>
		<td width="45%" valign="top">
			<table class="adminlist">
				<tr>
					<td>
						<div style="font-weight:700;">
							<?php echo JText::_('COM_SOLRSEARCH_WELCOME_MSG');?>
						</div>
						<p>
							Thanks for installing the Xlab Solr Search Joomla Extension 
							<a href="http://www.xlab.nl/" target="_blank">
							http://www.xlab.nl
							</a>
							Please vote for us to support us. 
						</p>
						<p>
							If you found any bugs, just drop me an email at john@xlab.nl or visit Github http://github.com/johnverwoerd
						</p>
						
					</td>
				</tr>
				<tr>
				<td style="background: black;">
					<img src="http://www.xlab.nl/templates/rt_modulus_j16/images/logo/dark/logo.png" />
				</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
