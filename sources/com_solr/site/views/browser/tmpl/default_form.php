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
$lang = JFactory::getLanguage();
//$upper_limit = $lang->getUpperLimitSearchWord();

?>

<!--<style type="text/css">-->
<!--	.facet-field-->
<!--	{-->
<!--		width: 250px;-->
<!--		border: solid 1px #CECECE;-->
<!--	}-->
<!--	.facet-field a-->
<!--	{-->
<!--		width: 100px;-->
<!--	}-->
<!--	.facet-field span-->
<!--	{-->
<!--		width: 20px;-->
<!--	}-->
<!--	.current-field-link-->
<!--	{-->
<!--		background: #2E3540;-->
<!--	}-->
<!--	.remove-filter-link-->
<!--	{-->
<!--		color: black;-->
<!--	}-->
<!--	-->
<!--</style>-->
<script type="text/javascript">
	jQuery('document').ready(function(){
		//alert('loaded');
		
		
		jQuery('.facet-field').each(function(i, e){
			
			
			var urlVars = getUrlVars('filters');
			//jQuery( e ).hide();
			
			facetField = jQuery(this).attr('id').split('-')[1];
			jQuery('#facet-'+facetField + ' .facet-field-link').each(function(a, w){
				
				currentLink = jQuery(this);
				facetFieldVal = currentLink.text();

				var i = 0;
				
				jQuery.each(urlVars, function(key, value)
				{
					if (typeof urlVars['filters[' + i + '][' + facetField + ']']  !== 'undefined' && urlVars['filters[' + i + '][' + facetField + ']'] ==  facetFieldVal )
					{
						jQuery( e ).show();
						//jQuery(currentLink).after('<a class="remove-filter-link" title="Remove filter" href="' + removeUrlVars(top.location.href, 'filters[' + i + '][' + facetField + ']=' + facetFieldVal + '&' ) +'">X</a>')
						
					}
					i++;
				});
			});
		});

		if( jQuery('#facet-genre a').length > 20 )
		{
				jQuery('#facet-genre').css('height','100px').css('overflow','hidden').parent('.search-field-block').append('<a style="color: #555;" onClick="javascript:sizeGenre(this);" href="#">View all &gt;&gt;</a>');
		}
		
		//Image buttons calendar
		jQuery( "#datepickerFrom" ).datepicker( {
			changeYear: true, 
			onClose: function(dateText, inst) { 
					addParam('date_from', jQuery("#datepickerFrom").val());
			} 
		});
		jQuery( "#datepickerTo" ).datepicker( {
			changeYear: true,
			onClose: function(dateText, inst) { 
				addParam('date_end', jQuery("#datepickerTo").val());
			} 
		});
		
		jQuery('#imageDatePickerFrom').click(function(el){
			jQuery( "#datepickerFrom" ).datepicker("show");
		});
		jQuery('#imageDatePickerTo').click(function(el){
			jQuery( "#datepickerTo" ).datepicker("show");
		});
		//End calendar functions

			
		jQuery('.facet-button').each(function(i, e){
			jQuery(e).click(function(){
				jQuery( '#facet-' + jQuery(this).val() ).toggle();
			
			});
		});
	
		console.log(getUrlVars());
		jQuery('.facet-field-link').each(function(i,e){
			
		});
		
	});

	function sizeGenre(el)
	{
		
		jQuery('#facet-genre').css('height','auto').css('overflow', 'visible');
		jQuery(el).remove();
	}
	
	function addParam(key, value)
	{
	    key = escape(key); value = escape(value);

	    var kvp = document.location.search.substr(1).split('&');

	    var i=kvp.length; var x; while(i--) 
	    {
	        x = kvp[i].split('=');

	        if (x[0]==key)
	        {
	                x[1] = value;
	                kvp[i] = x.join('=');
	                break;
	        }
	    }

	    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

	    //this will reload the page, it's likely better to store this until finished
	    document.location.search = kvp.join('&'); 
	}
	
function removeUrlVars(url_string, variable_name)
{
	var URL = String(url_string);
	URL = URL.replace(variable_name , '');
	/*
    var regex = new RegExp( "\\?" + variable_name + "=[^&]*&?", "gi");
    URL = URL.replace(regex,'?');
    regex = new RegExp( "\\&" + variable_name + "=[^&]*&?", "gi");
    URL = URL.replace(regex,'&');
    URL = URL.replace(/(\?|&)$/,'');
    regex = null;
	*/
	return URL;
}
	
function getUrlVars() {

    var vars = {};

    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {

        vars[key] = value;

    });

    return vars;

}

</script>
<?php 
	$absoluteURL = JURI::base();

	function removeCurrentFilterUrl($currentURL, $key, $filterVal )
	{
		$newUrl = preg_replace('/filters\[\]\['.$key.'\]='.$filterVal.'\&/','', $currentURL);
		return preg_replace('/filters\[\]\['.$key.'\]='.$filterVal.'/','', $newUrl);
	}
	
	function removeDateFilter($currentURL, $date, $cur)
	{
		return preg_replace('/date_'.$cur.'=[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]/','', $currentURL);
	}
	
 ?>
	<?php 
	
	$get_query = $_GET;
	$allowed_get = array('filters', 'searchword', 'searchphrase', 'start', 'date_from', 'date_end');
	foreach( $get_query as $key => $fval)
	{
		if (!in_array(trim($key), $allowed_get))
		{
			unset($get_query[$key]);
		}
		else 
		{
			//$this->pagination->setAdditionalUrlParam($key, $fval);
		}
	}
	
	//Remove invalid date_from
	if( isset($get_query['date_from']) && strlen($get_query['date_from']) == 10 && substr($get_query['date_from'], 2, 1) == '/' && substr($get_query['date_from'], 5, 1) == '/' )
	{
		//
	}
	else {
		unset($get_query['date_from']);
	}
	
	//Remove invalid date_from
	if( isset($get_query['date_end']) && strlen($get_query['date_end']) == 10 && substr($get_query['date_end'], 2, 1) == '/' && substr($get_query['date_end'], 5, 1) == '/' )
	{
		//
	}
	else {
		unset($get_query['date_end']);
	}	
	
	$valid_get_query  = urldecode(http_build_query(  $get_query ));
	$valid_get_query = preg_replace('/filters\[[0-9]\]/','filters[]', $valid_get_query);
	
	
	
	$current_filter_fields  = isset($get_query['filters']) ? $get_query['filters'] : null;
	$current_filter_fields_count  = isset($get_query['filters']) ? count($get_query['filters']) : null;
	
	$current_query = $this->results['full_query']; 
	function getCurrentFacetQuery($facetCat, $facet)
	{		
		$return = '';
		if (isset($_GET['filters']))
		{
			$get_query = $_GET['filters'];
			foreach ($get_query as $num => $filter)
			{
				foreach($filter as $key => $cur)
				{
					if ($key == $facetCat && $cur == $facet) $return =  '';
					else $return .='&filters[]['.$facetCat.']='.$facet;
				}
			}
			
			
		}
		else
		{
			$return .='&filters[]['.$facetCat.']='.$facet;
		}
		return $return;
	}
	?>
	
	
	<?php //echo $this->results['current_query'];?>
	<?php //var_dump($this->results['full_query']); ?>
	
	
<div class="anime-filter">

	<fieldset class="word">
		<div class="search-field-block">
			<div class="search-fields">
				<input type="text" name="searchword" id="search-searchword" size="10" maxlength="10" value="" class="inputbox" />
				<button name="Search" onclick="this.form.submit()" class="button"><img src="/templates/rt_tachyon_j16/images/body/rokajaxsearch-icon.png" /></button>
				<?php if (true == false && isset($_GET['searchword'])):?>
					<span>showing results for <?php echo htmlspecialchars($_GET['searchword']);?>...</span>
					<?php endif;?>
					<input type="hidden" name="task" value="search" />
				<div class="clear-left"></div>
			</div>
		</div>
		<?php if (isset($this->results['facets'])): ?>
		<div id="facets">
			<?php $facets = $this->results['facets']; ?>
			<?php if ($facets->facet_fields): ?>
				<?php foreach(  $facets->facet_fields as $key => $facetFieldCat  ): ?>
					<div class="search-field-block">
						<input type="button" class="facet-button" value="Filter by <?php echo $key ?>" />
						<div id="facet-<?php echo $key;?>" class="facet-field">
							<?php foreach($facetFieldCat as $keyF => $facetFieldCount): ?>
								<?php if ($facetFieldCount > 0): ?> 
									<?php if($current_filter_fields != null && in_array( array($key => $keyF), $current_filter_fields )) : ?>
										<a class="current-field-link bliep facet-field-link" href="<?php echo $absoluteURL . 'anime?'.$valid_get_query;?>"><?php echo $keyF ?></a><span>(<?php echo $facetFieldCount ?>)</span><a class="remove-filter-link" href="<?php echo $absoluteURL . 'anime?' . removeCurrentFilterUrl($valid_get_query, $key , $keyF ); ?>"><img src="/templates/rt_tachyon_j16/images/body/remove-filter-field.png" /></a>
									<?php elseif( $facetFieldCount != $this->results['total_results'] ): ?>
										<a class="facet-field-link" href="<?php echo $absoluteURL . 'anime?&filters[]['.$key. ']='.$keyF.'&' .$valid_get_query;?>"><?php echo $keyF ?></a><span>(<?php echo $facetFieldCount ?>)</span>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</fieldset>
	<fieldset class="dates">
		<div class="search-field-block">
			<?php if(isset($get_query['date_from'])):?>
				<label>From:</label> <span><?php echo $get_query['date_from']?></span><a class="remove-filter-link" href="<?php echo $absoluteURL . 'anime?' . removeDateFilter($valid_get_query,  $get_query['date_from'], 'from'); ?>"><img src="/templates/rt_tachyon_j16/images/body/remove-filter-field.png" /></a>
			<?php else:?>
				<label>From:</label> <input id="datepickerFrom" name="date_from"> <img alt="Select date from" src="/templates/rt_tachyon_j16/images/body/calendar.png" id="imageDatePickerFrom" />
			<?php endif;?>
			<div class="clear-left"></div>
			<?php if(isset($get_query['date_end'])):?>
				<label>To:</label> <span><?php echo $get_query['date_end']?></span><a class="remove-filter-link" href="<?php echo $absoluteURL . 'anime?' . removeDateFilter($valid_get_query,  $get_query['date_end'], 'end'); ?>"><img src="/templates/rt_tachyon_j16/images/body/remove-filter-field.png" /></a>
			<?php else:?>
				<label>To:</label> <input id="datepickerTo" name="date_end" /> <img alt="Select date to" src="/templates/rt_tachyon_j16/images/body/calendar.png" id="imageDatePickerTo" />
			<?php endif;?>
			<div class="clear-left"></div>
		</div>
	</fieldset>
</div>














