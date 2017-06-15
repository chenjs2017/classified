<?php
function pf_itemgrid_func( $atts ) {
  extract( shortcode_atts( array(
    'listingtype' => '',
	'itemtype' => '',
	'conditions' => '',
	'locationtype' => '',
	'posts_in' => '',
	'sortby' => 'ASC',
	'orderby' => 'title',
	'items' => 8,
	'cols' => 4,
	'features'=>array(),
	'filters' => 'true',
	'itemboxbg' => '',
	'grid_layout_mode' => 'fitRows',
	'featureditems'=>'',
	'featureditemshide'=>'',
	'authormode'=>0,
	'agentmode'=>0,
	'author'=>'',
	'related'=> 0,
	'relatedcpi'=> 0,
	'listingtypefilters'=>'',
	'itemtypefilters'=>'',
	'locationfilters'=>''
  ), $atts ) );
  			if (empty($itemboxbg)) {
				$itemboxbg = PFSAIssetControl('setup22_searchresults_background2','','');
			}
 
			$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
			$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
			$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

			$gridrandno_orj = PF_generate_random_string_ig();
			$gridrandno = 'pf_'.$gridrandno_orj;

			$listingtype_x = PFEX_extract_type_ig($listingtype);
			$itemtype_x = ($setup3_pointposttype_pt4_check == 1) ? PFEX_extract_type_ig($itemtype) : '' ;
			$conditions_x = ($setup3_pointposttype_pt4_check == 1) ? PFEX_extract_type_ig($conditions) : '' ;
			$locationtype_x = ($setup3_pointposttype_pt5_check == 1) ? PFEX_extract_type_ig($locationtype) : '' ;
			$features_x = ($setup3_pointposttype_pt6_check == 1) ? PFEX_extract_type_ig($features) : '' ;
			$pfajaxurl = get_template_directory_uri().'/admin/core/pfajaxhandler.php';
			$pfnonce = wp_create_nonce('pfget_listitems');

			$wpflistdata = "<div class='pflistgridview".$gridrandno_orj."-container pflistgridviewgr-container'></div> ";
			
			ob_start();
			?> 
			
			<script type="text/javascript">
			(function($) {
			"use strict"
			$.gdt_data<?php echo $gridrandno_orj;?> = {};
			$.gdt_data<?php echo $gridrandno_orj;?>['sortby'] = '<?php echo $sortby;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['orderby'] = '<?php echo $orderby;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['items'] = '<?php echo $items;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['cols'] = '<?php echo $cols;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['posts_in'] = '<?php echo $posts_in;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['filters'] = '<?php echo $filters;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['itemboxbg'] = '<?php echo $itemboxbg;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['grid_layout_mode'] = '<?php echo $grid_layout_mode;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['listingtype'] = '<?php echo $listingtype_x;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['itemtype'] = '<?php echo $itemtype_x;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['conditions'] = '<?php echo $conditions_x;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['locationtype'] = '<?php echo $locationtype_x;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['features'] = '<?php echo $features_x;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['featureditems'] = '<?php echo $featureditems;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['featureditemshide'] = '<?php echo $featureditemshide;?>';

			$.gdt_data<?php echo $gridrandno_orj;?>['authormode'] = '<?php echo $authormode;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['agentmode'] = '<?php echo $agentmode;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['author'] = '<?php echo $author;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['related'] = '<?php echo $related;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['relatedcpi'] = '<?php echo $relatedcpi;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['listingtypefilters'] = '<?php echo $listingtypefilters;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['itemtypefilters'] = '<?php echo $itemtypefilters;?>';
			$.gdt_data<?php echo $gridrandno_orj;?>['locationfilters'] = '<?php echo $locationfilters;?>';


				$.fn.<?php echo $gridrandno;?> = function( options ) {
			    
			        var settings = $.extend({
			            sgdt:$.gdt_data<?php echo $gridrandno_orj;?>,
			            show : 1,
			            grid : '',
			            pfg_orderby : '',
			            pfg_order : '',
			            pfg_number : '',
			            pfsearch_filter_ltype : '',
			            pfsearch_filter_itype : '',
			            pfsearch_filter_location : '',
			            page : '',
			            cl:'<?php echo PF_current_language();?>',
			            pfcontainerdiv : 'pflistgridview<?php echo $gridrandno_orj;?>',
            			pfcontainershow : 'pflistgridviewshow<?php echo $gridrandno_orj;?>',
            			pfex : 'alist'
			        }, options );
			 
			        var pfremovebyresults = function(){
						if($('.pflistgridviewshow<?php echo $gridrandno_orj;?>').length>0){
							$('.pflistgridviewshow<?php echo $gridrandno_orj;?>').remove();
						};
						

						$(".pflistgridviewshow<?php echo $gridrandno_orj;?>").hide("fade",{direction: "up" },300)
						
					};

					var pfscrolltoresults = function(){
						$.smoothScroll({
							scrollTarget: ".pflistgridviewshow<?php echo $gridrandno_orj;?>",
							offset: -110
						});
					};

					var pfgridloadingtoggle = function(status){
						
						if(status == "hide"){
							if($(".pflistgridviewshow<?php echo $gridrandno_orj;?> .pfsearchresults-loading").length>0){
								$(".pflistgridviewshow<?php echo $gridrandno_orj;?>").remove();
								$(".pflistgridviewshow<?php echo $gridrandno_orj;?>").hide("fade",{direction: "up" },300)
							};
						}else{
							$(".pflistgridview<?php echo $gridrandno_orj;?>-container").append("<div class= 'pfsearchresults pflistgridviewshow<?php echo $gridrandno_orj;?> pfsearchgridview'><div class='pfsearchresults-loading'><div class='pfsresloading pfloadingimg'></div></div></div>");
							$(".pflistgridviewshow<?php echo $gridrandno_orj;?>").show("fade",{direction: "up" },300)
						}
					}
					
					var pfmakeitperfect = function() {
										
						var layout_modes = {
			            fitrows: 'fitRows',
			            masonry: 'masonry'
				        }
				        $('.pflistgridview<?php echo $gridrandno_orj;?>-content').each(function(){
				            var $container = $(this);
				            var $thumbs = $container.find('.pfitemlists-content-elements');
				            var layout_mode = $thumbs.attr('data-layout-mode');
				            $thumbs.isotope({
				                itemSelector : '.isotope-item',
				                layoutMode : (layout_modes[layout_mode]==undefined ? 'fitRows' : layout_modes[layout_mode])
				            });
				           
				        });
					
						
					};

					var pfscrolltotop = function(){ $.smoothScroll(); };

					if($('.pflistgridviewshow<?php echo $gridrandno_orj;?>').length <= 0){
						
						$.ajax({
							beforeSend:function(){pfgridloadingtoggle('show');},
							type: 'POST',
							cache:false,
							dataType: 'html',
							url: '<?php echo $pfajaxurl;?>',
							data: {
								'action': 'pfget_listitems',
								'gdt': settings.sgdt,
								'grid': settings.grid,
								'pfg_orderby': settings.pfg_orderby,
								'pfg_order': settings.pfg_order,
								'pfg_number': settings.pfg_number,
								'pfsearch_filter_ltype' : settings.pfsearch_filter_ltype,
					            'pfsearch_filter_itype' : settings.pfsearch_filter_itype,
					            'pfsearch_filter_location' : settings.pfsearch_filter_location,
								'page': settings.page,
								'pfcontainerdiv': 'pflistgridview<?php echo $gridrandno_orj;?>',
								'pfcontainershow': 'pflistgridviewshow<?php echo $gridrandno_orj;?>',
								'security': '<?php echo $pfnonce;?>',
								'pfex' : 'alist',
								'cl':'<?php echo PF_current_language();?>',
							},
							success:function(data){
								pfgridloadingtoggle('hide');
								setTimeout(function() {
									pfmakeitperfect();
								}, 300);
								setTimeout(function() {
									pfmakeitperfect();
								}, 500);
								setTimeout(function() {
									pfmakeitperfect();
								}, 700);

								setTimeout(function() {
									pfmakeitperfect();
								}, 3000);

								setTimeout(function() {
									pfmakeitperfect();
								}, 5000);

								if($.isEmptyObject($.pfsortformvars_<?php echo $gridrandno_orj;?>)){
									$.pfsortformvars_<?php echo $gridrandno_orj;?> = {};
								};
								
								if(settings.page == '' || settings.page == null || settings.page <= 0){ $.pfsortformvars_<?php echo $gridrandno_orj;?>.page = 1; }
								
						
								$('.pflistgridview<?php echo $gridrandno_orj;?>-container').append(data);
								
								$('.pflistgridviewshow<?php echo $gridrandno_orj;?>').show('fade',{direction: 'up' },300)
								
								
								$('.pflistgridview<?php echo $gridrandno_orj;?>-filters .pfgridlist6').click(function(){
									pfremovebyresults();
								});

								
								$('.pfajax_paginate a').click(function(){
									if($(this).hasClass('prev')){
										$.pfsortformvars_<?php echo $gridrandno_orj;?>.page--;
									}else if($(this).hasClass('next')){
										$.pfsortformvars_<?php echo $gridrandno_orj;?>.page++;
									}else{
										$.pfsortformvars_<?php echo $gridrandno_orj;?>.page = $(this).text();
									}
									
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_orderby = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_order = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-order').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_number = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-number').val();

									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_ltype = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-ltype').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_itype = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-itype').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_location = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-location').val();

									if($.isEmptyObject($.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid)){ $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid = ''; }
									
									if(!$.isEmptyObject($.pfsortformvars_<?php echo $gridrandno_orj;?>)){

										pfremovebyresults();

										$.fn.<?php echo $gridrandno;?>({
											gdt: settings.sgdt,
											grid : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid,
											pfg_orderby : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_orderby,
											pfg_order : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_order,
											pfg_number : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_number,
											pfsearch_filter_ltype : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_ltype,
											pfsearch_filter_itype : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_itype,
											pfsearch_filter_location : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_location,
											page : $.pfsortformvars_<?php echo $gridrandno_orj;?>.page,
											pfcontainerdiv : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_griddiv,
											pfcontainershow : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_gridshow
										});


									};
									
									return false;
								})
								
								$('.pflistgridview<?php echo $gridrandno_orj;?>-filters-right .pfgridlistit').click(function(){
									
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_orderby = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_order = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-order').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_number = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-number').val();
									

									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_ltype = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-ltype').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_itype = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-itype').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_location = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-location').val();

									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid = $(this).attr('data-pf-grid');
									
									pfremovebyresults();

									
									$.fn.<?php echo $gridrandno;?>({
										grid : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid,
										gdt: settings.sgdt,
										pfg_orderby : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_orderby,
										pfg_order : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_order,
										pfg_number : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_number,
										pfsearch_filter_ltype : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_ltype,
										pfsearch_filter_itype : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_itype,
										pfsearch_filter_location : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_location,
										page : $.pfsortformvars_<?php echo $gridrandno_orj;?>.page,
									});

								});
								
								
								$('.pflistgridview<?php echo $gridrandno_orj;?>-filters-left > li > label > select').change(function(){

									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_orderby = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_order = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-order').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_number = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-number').val();

									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_ltype = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-ltype').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_itype = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-itype').val();
									$.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_location = $('.pflistgridviewshow<?php echo $gridrandno_orj;?>').find('.pfsearch-filter-location').val();

									if($.isEmptyObject($.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid)){ $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid = ''; }

									
									pfremovebyresults();



									$.fn.<?php echo $gridrandno;?>({
										gdt: settings.sgdt,
										grid : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_grid,
										pfg_orderby : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_orderby,
										pfg_order : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_order,
										pfg_number : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfg_number,
										pfsearch_filter_ltype : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_ltype,
										pfsearch_filter_itype : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_itype,
										pfsearch_filter_location : $.pfsortformvars_<?php echo $gridrandno_orj;?>.pfsearch_filter_location,
										page : $.pfsortformvars_<?php echo $gridrandno_orj;?>.page,
									});
								});
								
								$('.pfButtons a').click(function() {
									if($(this).attr('data-pf-link')){
										$.prettyPhoto.open($(this).attr('data-pf-link'));
									}
								});

								
							},
							error: function (request, status, error) {
								pfgridloadingtoggle('hide')
								$('.pflistgridview<?php echo $gridrandno_orj;?>-container').append('<div class= "pflistgridview<?php echo $gridrandno_orj;?>"><div class="pfsearchresults-loading" style="text-align:center"><strong>An error occured!</strong></div></div>');
							},
							complete: function(){
								
							},
						});
					}else{
						$(settings.pfcontainershow).show('fade',{direction: 'up' },300)
					}
			        
			 
			    };
				
				$(document).ready(function() {
					$.fn.<?php echo $gridrandno;?>()
				});
			})(jQuery);
			</script> 
<?php
$wpflistdata .= ob_get_contents();
ob_end_clean();
return $wpflistdata;
}
add_shortcode( 'pf_itemgrid', 'pf_itemgrid_func' ); 

?>
