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
?>
<!--<pre>
	<?php print_r($this->results);?>
</pre>
-->
<div id="solr-overview">
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<div style="float: left;"><?php echo $this->results['total_results'];?></div> 
	</div>
	<div class="clear-left"></div>
	<table>
		<thead>
			<tr>
				<th>
					Image
				</th>
				<th class="title-row">
					Title <!--<a href="<?php echo JURI::current();?>?ordering=title&sortdir=<?php echo (isset($this->anime_sortField, $this->anime_sortDir) && $this->anime_sortField == 'title' && $this->anime_sortDir == 'ASC' ? 'desc' : 'asc')?>" title="Sort by title"><img src="media/com_anime/images/arrow.gif" /></a>-->
				</th>
				<th>
					Type
				</th>
				<th>
					Rating<!--<a href="<?php echo JURI::current()?>?ordering=rating&sortdir=<?php echo (isset($this->anime_sortField, $this->anime_sortDir) && $this->anime_sortField == 'rating' && $this->anime_sortDir == 'ASC' ? 'desc' : 'asc')?>" title="Sort by rating"><img src="media/com_anime/images/arrow.gif" /></a>-->
				</th>
				<th>
					Aired<!--<a href="<?php echo JURI::current()?>?ordering=date_from&sortdir=<?php echo (isset($this->anime_sortField, $this->anime_sortDir) && $this->anime_sortField == 'date_from' && $this->anime_sortDir == 'ASC' ? 'desc' : 'asc')?>" title="Sort by Air date"><img src="media/com_anime/images/arrow.gif" /></a>-->
				</th>
				<th>
					Ended<!--<a href="<?php echo JURI::current();?>?ordering=date_end&sortdir=<?php echo (isset($this->anime_sortField, $this->anime_sortDir) && $this->anime_sortField == 'date_end' && $this->anime_sortDir == 'ASC' ? 'desc' : 'asc')?>" title="Sort by End date"><img src="media/com_anime/images/arrow.gif" /></a>-->
				</th>
				<th>
					Actions
				</th>
			</tr>
		</thead>
		<tbody>
			<?php $counter = 0;?>
			<?php foreach($this->results as $key => $result) : ?>
				<?php if ( isset($result->alias ) ):?>
				<tr class="<?php echo ($counter%2 == 0 ? 'even' : 'odd');?>" >
					<td class="image">
						<?php if(isset($result->image_path) && !empty($result->image_path)):?>
							img overview
						<?php else:?>
							img
						<?php endif;?>
					</td>
					<td class="title-row">
						<a title="<?php echo $result->title ?>" href="/url_jroute/<?php echo $result->alias;?>"><?php echo $result->title;?></a>
					</td>
					<td>
						data
					</td>
					<td>
						<?php if($result->rating > 0) echo  $result->rating;?>
					</td>
					<td>
						<?php if(!empty($result->date_from)) echo date( 'd m Y', strtotime($result->date_from));?>
					</td>
					<td>
						<?php if(!empty($result->date_end)) echo date( 'd m Y', strtotime($result->date_end));?>
					</td>
					<td>
						data 
					</td>
				</tr>
				<?php endif;?>
				<?php $counter++;?>
				
			<?php endforeach;?>
		</tbody>
	
		
	</table><!--	
	
	<dl class="search-results">
	<?php foreach($this->results as $key => $result) : ?>
		<?php if (is_numeric($key)): ?>
		<dt class="result-title">
			<?php //echo $this->pagination->limitstart + $result->count.'. ';?>
			<?php if (true == false && $result->href) :?>
				<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>>
					<?php echo strip_tags($result->title, '<em>');?>
				</a>
			<?php else:?>
				<?php echo $result->title;?>
			<?php endif; ?>
		</dt>
		<dd class="result-score">
		</dd>
		<dd class="result-text">
			<?php echo $result->introtext;?>
		</dd>
		<?php endif;?>
	<?php endforeach; ?>
	</dl>
	
	--><div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
</div>
