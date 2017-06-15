<?php
function pf_itemgrid2_func( $atts ) {
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
	'featureditemshide' => '',
	'authormode'=>0,
	'agentmode'=>0,
	'author'=>'',
	'manualargs' => '',
	'hidden_output' => '',
	'ne' => '',
	'ne2' => '',
	'sw' => '',
	'sw2' => '',
	'listingtypefilters'=>'',
	'itemtypefilters'=>'',
	'locationfilters'=>'',
	'tag' => '',
	'infinite_scroll' => 0,
	'infinite_scroll_lm' => 0
  ), $atts ) );
  
  	$template_directory_uri = get_template_directory_uri();
  	$pfgrid = $pfg_ltype = $pfg_itype = $pfg_lotype = $pfitemboxbg = $pf1colfix = $pf1colfix2 ='';

  	/* Get admin values */
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

		$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
		$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
		$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
		$setup3_pt14_check = PFSAIssetControl('setup3_pt14_check','',0);
		

		$setup22_searchresults_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
		$setup22_searchresults_defaultsortbytype = PFSAIssetControl('setup22_searchresults_defaultsortbytype','','ID');
		$setup22_searchresults_defaultsorttype = PFSAIssetControl('setup22_searchresults_defaultsorttype','','ASC');

		$general_retinasupport = PFSAIssetControl('general_retinasupport','','0');
		$setup3_pt14_check = PFSAIssetControl('setup3_pt14_check','',0);

		$setupsizelimitconf_general_gridsize1_width = PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','width',440);
		$setupsizelimitconf_general_gridsize1_height = PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','height',330);

		$review_system_statuscheck = PFREVSIssetControl('setup11_reviewsystem_check','','0');

		$gridrandno_orj = PF_generate_random_string_ig();
		$gridrandno = 'pf_'.$gridrandno_orj;

		$listingtype_x = PFEX_extract_type_ig($listingtype);
		$itemtype_x = ($setup3_pointposttype_pt4_check == 1) ? PFEX_extract_type_ig($itemtype) : '' ;
		$conditions_x = ($setup3_pt14_check == 1) ? PFEX_extract_type_ig($conditions) : '' ;
		$locationtype_x = ($setup3_pointposttype_pt5_check == 1) ? PFEX_extract_type_ig($locationtype) : '' ;
		$features_x = ($setup3_pointposttype_pt6_check == 1) ? PFEX_extract_type_ig($features) : '' ;

		$user_loggedin_check = is_user_logged_in();
		$favtitle_text = esc_html__('Add to Favorites','pointfindert2d');

	$wpflistdata = "<div class='pflistgridview".$gridrandno_orj."-container pflistgridviewgr-container'>";

		/*Container & show check*/
		$pfcontainerdiv = 'pflistgridview'.$gridrandno_orj.'';
		$pfcontainershow = 'pflistgridviewshow'.$gridrandno_orj.'';

		
		$pfheaderfilters = $filters;		

		$pfgetdata = array();
		$pfgetdata['sortby'] = $sortby;
		$pfgetdata['orderby'] = $orderby;
		$pfgetdata['posts_in'] = $posts_in;
		$pfgetdata['items'] = $items;
		$pfgetdata['cols'] = $cols;
		$pfgetdata['filters'] = $filters;
		$pfgetdata['itemboxbg'] = $itemboxbg;
		$pfgetdata['grid_layout_mode'] = $grid_layout_mode;
		$pfgetdata['listingtype'] = $listingtype_x;
		$pfgetdata['itemtype'] = $itemtype_x;
		$pfgetdata['conditions'] = $conditions_x;
		$pfgetdata['locationtype'] = $locationtype_x;
		$pfgetdata['features'] = $features_x;	
		$pfgetdata['featureditems'] = $featureditems;
		$pfgetdata['featureditemshide'] = $featureditemshide;
		$pfgetdata['authormode'] = $authormode;
		$pfgetdata['agentmode'] = $agentmode;
		$pfgetdata['author'] = $author;
		$pfgetdata['listingtypefilters'] = $listingtypefilters;
		$pfgetdata['itemtypefilters'] = $itemtypefilters;
		$pfgetdata['locationfilters'] = $locationfilters;
		$pfgetdata['tag'] = $tag;
		$pfgetdata['manual_args'] = (!empty($manualargs))? maybe_unserialize(base64_decode($manualargs)): '';
		$pfgetdata['hidden_output'] = (!empty($hidden_output))? maybe_unserialize(base64_decode($hidden_output)): '';

		if($pfgetdata['cols'] != ''){$pfgrid = 'grid'.$pfgetdata['cols'];}

		/*Get if sort/order/number values exist*/
		if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){$pfg_orderby = esc_attr($_GET['pfsearch-filter']);}else{$pfg_orderby = '';}
		if(isset($_GET['pfsearch-filter-order']) && $_GET['pfsearch-filter-order']!=''){$pfg_order = esc_attr($_GET['pfsearch-filter-order']);}else{$pfg_order = '';}
		if(isset($_GET['pfsearch-filter-number']) && $_GET['pfsearch-filter-number']!=''){$pfg_number = esc_attr($_GET['pfsearch-filter-number']);}else{$pfg_number = '';}
		if(isset($_GET['pfsearch-filter-col']) && $_GET['pfsearch-filter-col']!=''){$pfgrid = esc_attr($_GET['pfsearch-filter-col']);}

		if(isset($_GET['pfsearch-filter-ltype']) && !empty($_GET['pfsearch-filter-ltype'])){$pfg_ltype = esc_attr($_GET['pfsearch-filter-ltype']);}
		if(isset($_GET['pfsearch-filter-itype']) && !empty($_GET['pfsearch-filter-itype'])){$pfg_itype = esc_attr($_GET['pfsearch-filter-itype']);}
		if(isset($_GET['pfsearch-filter-location']) && !empty($_GET['pfsearch-filter-location'])){$pfg_lotype = esc_attr($_GET['pfsearch-filter-location']);}

		
		
		if ( is_front_page() ) {
	        $pfg_paged = (esc_sql(get_query_var('page'))) ? esc_sql(get_query_var('page')) : 1;   
	    } else {
	        $pfg_paged = (esc_sql(get_query_var('paged'))) ? esc_sql(get_query_var('paged')) : 1; 
	    }

		$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');
		
		if($pfgetdata['posts_in']!=''){
			$args['post__in'] = pfstring2BasicArray($pfgetdata['posts_in']);

		}


		if($pfgetdata['tag']!=''){
			$args['tag_id'] = $pfgetdata['tag'];

		}


		if($pfgetdata['authormode'] != 0){
			if (!empty($pfgetdata['author'])) {
				$args['author'] = $pfgetdata['author'];
			}
		}


		$st22srlinknw = PFSAIssetControl('st22srlinknw','','0');
		$targetforitem = '';
		if ($st22srlinknw == 1) {
			$targetforitem = ' target="_blank"';
		}
		

		$grid_layout_mode = $pfgetdata['grid_layout_mode'];


		if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
			$args['meta_query'] = array();
		}	

		if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
			$args['tax_query'] = array();
		}


		
		if(is_array($pfgetdata)){

			/* listing type*/
			if($pfgetdata['listingtype'] != ''){
				$pfvalue_arr_lt = PFGetArrayValues_ld($pfgetdata['listingtype']);
				$fieldtaxname_lt = 'pointfinderltypes';
				$args['tax_query'][]=array(
					'taxonomy' => $fieldtaxname_lt,
					'field' => 'id',
					'terms' => $pfvalue_arr_lt,
					'operator' => 'IN'
				);
			}

			if($setup3_pointposttype_pt5_check == 1){
				/* location type*/
				if($pfgetdata['locationtype'] != ''){
					$pfvalue_arr_loc = PFGetArrayValues_ld($pfgetdata['locationtype']);
					$fieldtaxname_loc = 'pointfinderlocations';
					$args['tax_query'][]=array(
						'taxonomy' => $fieldtaxname_loc,
						'field' => 'id',
						'terms' => $pfvalue_arr_loc,
						'operator' => 'IN'
					);
					
				}
			}

			if($setup3_pointposttype_pt4_check == 1){
				/* item type*/
				if($pfgetdata['itemtype'] != ''){
					$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['itemtype']);
					$fieldtaxname_it = 'pointfinderitypes';
					$args['tax_query'][]=array(
						'taxonomy' => $fieldtaxname_it,
						'field' => 'id',
						'terms' => $pfvalue_arr_it,
						'operator' => 'IN'
					);
				}
			}

			/* Condition */
				$setup3_pt14_check = PFSAIssetControl('setup3_pt14_check','',0);
				if($setup3_pt14_check == 1){
					if($pfgetdata['conditions'] != ''){
						$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['conditions']);
						$fieldtaxname_it = 'pointfinderconditions';
						$args['tax_query'][] = array(
							'taxonomy' => $fieldtaxname_it,
							'field' => 'id',
							'terms' => $pfvalue_arr_it,
							'operator' => 'IN'
						);
					}
				}

			if($setup3_pointposttype_pt6_check == 1){
				/* features type*/
				if($pfgetdata['features'] != ''){
					$pfvalue_arr_fe = PFGetArrayValues_ld($pfgetdata['features']);
					$fieldtaxname_fe = 'pointfinderfeatures';
					$args['tax_query'][]=array(
						'taxonomy' => $fieldtaxname_fe,
						'field' => 'id',
						'terms' => $pfvalue_arr_fe,
						'operator' => 'IN'
					);
				}
			}

			if (empty($pfgetdata['itemboxbg'])) {
				$pfgetdata['itemboxbg'] = PFSAIssetControl('setup22_searchresults_background2','','');
			}
			
			$pfitemboxbg = ' style="background-color:'.$pfgetdata['itemboxbg'].';"';
			$pfheaderfilters = ($pfgetdata['filters']=='true') ? '' : 'false' ;

			
			$meta_key_featured = 'webbupointfinder_item_featuredmarker';
			
			if ( !empty($pfg_ltype)) {
				$fieldtaxname_lt = 'pointfinderltypes';
				$args['tax_query'][]=array(
					'taxonomy' => $fieldtaxname_lt,
					'field' => 'id',
					'terms' => $pfg_ltype,
					'operator' => 'IN'
				);

			}


			if ( !empty($pfg_itype) && $setup3_pointposttype_pt4_check == 1) {
				$fieldtaxname_it = 'pointfinderitypes';
				$args['tax_query'][]=array(
					'taxonomy' => $fieldtaxname_it,
					'field' => 'id',
					'terms' => $pfg_itype,
					'operator' => 'IN'
				);
			}

			if ( !empty($pfg_lotype) && $setup3_pointposttype_pt5_check == 1) {
				$fieldtaxname_loc = 'pointfinderlocations';
				$args['tax_query'][]=array(
					'taxonomy' => $fieldtaxname_loc,
					'field' => 'id',
					'terms' => $pfg_lotype,
					'operator' => 'IN'
				);
			}


			if($pfg_orderby != ''){
				if($pfg_orderby == 'date' || $pfg_orderby == 'title'){
					
					$args['orderby'] = array('meta_value_num' => 'DESC' , $pfg_orderby => $pfg_order);
					$args['meta_key'] = $meta_key_featured;
					
					if (!empty($pfgetdata['manual_args'])) {
						$args['meta_key'] = $meta_key_featured;
						$pfgetdata['manual_args']['orderby'] = array('meta_value_num' => 'DESC' , $pfg_orderby => $pfg_order);
					}

				}else{
					
					$args['meta_key']='webbupointfinder_item_'.$pfg_orderby;
					
					if(PFIF_CheckFieldisNumeric_ld($pfg_orderby) == false){
						$args['orderby']= array('meta_value' => $pfg_order);
					}else{
						$args['orderby']= array('meta_value_num' => $pfg_order);
					}
					
					if (!empty($pfgetdata['manual_args'])) {
						$pfgetdata['manual_args']['meta_key']='webbupointfinder_item_'.$pfg_orderby;
						if(PFIF_CheckFieldisNumeric_ld($pfg_orderby) == false){
							$pfgetdata['manual_args']['orderby'] = array('meta_value' => $pfg_order);
						}else{
							$pfgetdata['manual_args']['orderby'] = array('meta_value_num' => $pfg_order);
						}
					}
					
				}
			}else{
				
				if($pfgetdata['orderby'] != ''){
					$args['meta_key'] = $meta_key_featured;
					$args['orderby'] = array('meta_value_num' => 'DESC' , $pfgetdata['orderby'] => $pfgetdata['sortby']);
				}else{
					$args['meta_key'] = $meta_key_featured;
					$args['orderby'] = array('meta_value_num' => 'DESC' , $setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
				}
			}
			
			
			if($pfg_number != ''){
				$args['posts_per_page'] = $pfg_number;
				if (!empty($pfgetdata['manual_args'])) {
					$pfgetdata['manual_args']['posts_per_page'] = $pfg_number;
				}
			}else{
				if($pfgetdata['items'] != ''){
					$args['posts_per_page'] = $pfgetdata['items'];
				}else{
					$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
				}
			}
			
			if($pfg_paged != ''){
				$args['paged'] = $pfg_paged;
				if (!empty($pfgetdata['manual_args'])) {
					$pfgetdata['manual_args']['paged'] = $pfg_paged;
				}
			}	

			/*Featured items filter*/
			if($pfgetdata['featureditems'] == 'yes' && $pfgetdata['featureditemshide'] != 'yes'){
				
				$args['meta_query'][] = array(
					'key' => 'webbupointfinder_item_featuredmarker',
					'value' => 1,
					'compare' => '=',
					'type' => 'NUMERIC'
				);
			}

			if ($pfgetdata['featureditemshide'] == 'yes') {
				$args['meta_query'][] = array(
					'key' => 'webbupointfinder_item_featuredmarker',
					'value' => 1,
					'compare' => '!=',
					'type' => 'NUMERIC'
				);
			}

			if($pfgetdata['agentmode'] != 0){
				if (!empty($pfgetdata['author'])) {
					$args['meta_query'][] = array(
						'key' => 'webbupointfinder_item_agents',
						'value' => $pfgetdata['author'],
						'compare' => '=',
						'type' => 'NUMERIC'
					);
				}
			}				
		}
		

		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}
			

		$featured_image_width = $setupsizelimitconf_general_gridsize1_width*$pf_retnumber;
		$featured_image_height = $setupsizelimitconf_general_gridsize1_height*$pf_retnumber;

		switch($pfgrid){
			case 'grid1':
				$pfgrid_output = 'pf1col';
				$pfgridcol_output = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
				break;
			case 'grid2':
				$pfgrid_output = 'pf2col';
				$pfgridcol_output = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
				break;
			case 'grid3':
				$pfgrid_output = 'pf3col';
				$pfgridcol_output = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
				break;
			case 'grid4':
				$pfgrid_output = 'pf4col';
				$pfgridcol_output = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
				break;
			default:
				$pfgrid_output = 'pf4col';
				$pfgridcol_output = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
				break;
		}
		

		/* On/Off filter for items */
			/*$args['meta_query'][] = array('relation' => 'OR',
				array(
					'key' => 'pointfinder_item_onoffstatus',
					'compare' => 'NOT EXISTS'
					
				),
				array(
	                    'key'=>'pointfinder_item_onoffstatus',
	                    'value'=> 0,
	                    'compare'=>'=',
	                    'type' => 'NUMERIC'
                )
                
			);*/

		/* If point is visible */
			/*$args['meta_query'][] = array(
				'key' => 'webbupointfinder_item_point_visibility',
				'compare' => 'NOT EXISTS'
			);*/

			

		/* Start: Coordinate Filter */
			if (empty($pfgetdata['manual_args'])) {
				$loop = new WP_Query( $args );
			}else{

			    //echo print_r($pfgetdata['manual_args']);

				$loop = new WP_Query( $pfgetdata['manual_args'] );
			}
		/* End: Coordinate Filter */

		


		/* Start: Image Settings and hover elements */
			$setup22_searchresults_animation_image  = PFSAIssetControl('setup22_searchresults_animation_image','','WhiteSquare');
			$setup22_searchresults_hover_image  = PFSAIssetControl('setup22_searchresults_hover_image','','0');
			$setup22_searchresults_hover_video  = PFSAIssetControl('setup22_searchresults_hover_video','','0');
			$setup22_searchresults_hide_address  = PFSAIssetControl('setup22_searchresults_hide_address','','0');
			
			$pfbuttonstyletext = 'pfHoverButtonStyle ';
			
			switch($setup22_searchresults_animation_image){
				case 'WhiteRounded':
					$pfbuttonstyletext .= 'pfHoverButtonWhite pfHoverButtonRounded';
					break;
				case 'BlackRounded':
					$pfbuttonstyletext .= 'pfHoverButtonBlack pfHoverButtonRounded';
					break;
				case 'WhiteSquare':
					$pfbuttonstyletext .= 'pfHoverButtonWhite pfHoverButtonSquare';
					break;
				case 'BlackSquare':
					$pfbuttonstyletext .= 'pfHoverButtonBlack pfHoverButtonSquare';
					break;
				
			} 

			
			$pfboptx1 = PFSAIssetControl('setup22_searchresults_hide_excerpt','1','0');
			$pfboptx2 = PFSAIssetControl('setup22_searchresults_hide_excerpt','2','0');
			$pfboptx3 = PFSAIssetControl('setup22_searchresults_hide_excerpt','3','0');
			$pfboptx4 = PFSAIssetControl('setup22_searchresults_hide_excerpt','4','0');
			
			if($pfboptx1 != 1){$pfboptx1_text = 'style="display:none"';}else{$pfboptx1_text = '';}
			if($pfboptx2 != 1){$pfboptx2_text = 'style="display:none"';}else{$pfboptx2_text = '';}
			if($pfboptx3 != 1){$pfboptx3_text = 'style="display:none"';}else{$pfboptx3_text = '';}
			if($pfboptx4 != 1){$pfboptx4_text = 'style="display:none"';}else{$pfboptx4_text = '';}
			
			switch($pfgrid_output){case 'pf1col':$pfboptx_text = $pfboptx1_text;break;case 'pf2col':$pfboptx_text = $pfboptx2_text;break;case 'pf3col':$pfboptx_text = $pfboptx3_text;break;case 'pf4col':$pfboptx_text = $pfboptx4_text;break;}		
			
			if (is_user_logged_in()) {
				$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );
				if (!empty($user_favorites_arr)) {
					$user_favorites_arr = json_decode($user_favorites_arr,true);
				}else{
					$user_favorites_arr = array();
				}
			}						
			
			$setup16_featureditemribbon_hide = PFSAIssetControl('setup16_featureditemribbon_hide','','1');
			$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
			$setup22_searchresults_hide_re = PFREVSIssetControl('setup22_searchresults_hide_re','','1');
			$setup22_searchresults_hide_excerpt_rl = PFSAIssetControl('setup22_searchresults_hide_excerpt_rl','','2');
			$setup16_reviewstars_nrtext = PFREVSIssetControl('setup16_reviewstars_nrtext','','0');
		/* End: Image Settings and hover elements */


		/* Start: Favorites check */
			if ($user_loggedin_check) {
				$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );
				if (!empty($user_favorites_arr)) {
					$user_favorites_arr = json_decode($user_favorites_arr,true);
				}else{
					$user_favorites_arr = array();
				}
			}
		/* End: Favorites check */

		/* Start: Size Limits */
			switch($pfgrid){
				case 'grid1':
					$pf1colfix = ' hidden-lg hidden-md';
					$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid1address','',120);
					$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid1title','',120);
					break;				
				case 'grid2':
					$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid2address','',96);
					$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid2title','',96);
					break;
				case 'grid3':
					$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid3address','',32);
					$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid3title','',32);
					break;
				case 'grid4':
					$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4address','',32);
					$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4title','',32);
					break;
				default:
					$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4address','',32);
					$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4title','',32);
					break;
			}
		/* End: Size Limits */

		

		
		//print_r($loop->query).PHP_EOL;
		//echo $loop->request.PHP_EOL;
		

		

		if (!empty($pfgetdata['manual_args'])) {
			if($loop->post_count == 1) {
				$pf_found_text = $loop->found_posts.' '.esc_html__('筆','pointfindert2d');
			}elseif($loop->post_count > 1) {
				$pf_found_text = $loop->found_posts.' '.esc_html__('筆','pointfindert2d');
			}

			if ($loop->post_count == 0) {
				$wpflistdata .= do_shortcode('[pftext_separator title="'.esc_html__('No matching listings','pointfindert2d').'" title_align="separator_align_left"]');
			} else {
				$wpflistdata .= do_shortcode('[pftext_separator title="'.esc_html__('搜索結果 : ','pointfindert2d').' '.$pf_found_text.'" title_align="separator_align_left"]');
			}
			
		}

		$setup22_searchresults_showmapfeature = PFSAIssetControl('setup22_searchresults_showmapfeature','','1');
		$setup42_searchpagemap_headeritem = PFSAIssetControl('setup42_searchpagemap_headeritem','','1');
		$pflang = PF_current_language();

		/* Start: Grid (HTML) */
			$wpflistdata .= '<div class="pfsearchresults '.$pfcontainershow.' pflistgridview pflistgridview-static">
            <form action="" method="GET" name="'.$pfcontainershow.'-form" id="'.$pfcontainershow.'-form">';/*List Data Begin . Form Begi*/

            
			/* Start: Header Area for filters (HTML) */		
            	if($pfheaderfilters == ''){
            		$wpflistdata .= '<div class="'.$pfcontainerdiv.'-header pflistcommonview-header">'; 

						/*
                        * Start: Left Filter Area
                        */
							$wpflistdata .= '<ul class="'.$pfcontainerdiv.'-filters-left '.$pfcontainerdiv.'-filters searchformcontainer-filters searchformcontainer-filters-left golden-forms clearfix col-lg-9 col-md-9 col-sm-9 col-xs-12">';

								/*
	                            * Start: SORT BY Section
	                            */	
								    $wpflistdata .= '<li>';
								    	$wpflistdata .= '<label for="pfsearch-filter" class="lbl-ui select pfsortby">';
		                            		$wpflistdata .= '<select class="pfsearch-filter" name="pfsearch-filter" id="pfsearch-filter">';
											
												if($args['orderby'] == 'ID' && $args['orderby'] != 'meta_value_num' && $args['orderby'] != 'meta_value'){
														$wpflistdata .= '<option value="" selected>'.esc_html__('SORT BY','pointfindert2d').'</option>';
												}else{
													$wpflistdata .= '<option value="">'.esc_html__('SORT BY','pointfindert2d').'</option>';
												}

												$pfgform_values3 = array('title','date');
												$pfgform_values3_texts = array('title'=>esc_html__('Title','pointfindert2d'),'date'=>esc_html__('Date','pointfindert2d'));
											
												if ($review_system_statuscheck == 1) {
													array_push($pfgform_values3, 'reviewcount');
													$pfgform_values3_texts['reviewcount'] = esc_html__('Review','pointfindert2d');
												}
											
											
												foreach($pfgform_values3 as $pfgform_value3){

												    if(isset($pfg_orderby)){

													   if(strcmp($pfgform_value3, $pfg_orderby) == 0){
														   $wpflistdata .= '<option value="'.$pfgform_value3.'" selected>'.$pfgform_values3_texts[$pfgform_value3].'</option>';
													   }else{
														   $wpflistdata .= '<option value="'.$pfgform_value3.'">'.$pfgform_values3_texts[$pfgform_value3].'</option>';
													   }

													}else{

													   if(strcmp($pfgform_value3, $setup22_searchresults_defaultsortbytype)){
														   $wpflistdata .= '<option value="'.$pfgform_value3.'" selected>'.$pfgform_values3_texts[$pfgform_value3].'</option>';
													   }else{
														   $wpflistdata .= '<option value="'.$pfgform_value3.'">'.$pfgform_values3_texts[$pfgform_value3].'</option>';
													   }

													}

												}

												if(!isset($pfg_orderby)){
													$wpflistdata .= PFIF_SortFields_sg($pfgetdata);
												}else{
													$wpflistdata .= PFIF_SortFields_sg($pfgetdata,$pfg_orderby);
												}

											$wpflistdata .='</select>';
										$wpflistdata .= '</label>';
									$wpflistdata .= '</li>';
								/*
		                        * End: SORT BY Section
		                        */

		                        /*
	                            * Start: ASC/DESC Section
	                            */
									$wpflistdata .= '
		                            <li>
		                                <label for="pfsearch-filter-order" class="lbl-ui select pforderby">
		                            	<select class="pfsearch-filter-order" name="pfsearch-filter-order" id="pfsearch-filter-order" >';
										$pfgform_values2 = array('ASC','DESC');
										$pfgform_values2_texts = array('ASC'=>esc_html__('ASC','pointfindert2d'),'DESC'=>esc_html__('DESC','pointfindert2d'));
										foreach($pfgform_values2 as $pfgform_value2){
											if(isset($pfg_order)){
												if(strcmp($pfgform_value2,$pfg_order) == 0){
												   $wpflistdata .= '<option value="'.$pfgform_value2.'" selected>'.$pfgform_values2_texts[$pfgform_value2].'</option>';
												}else{
													$wpflistdata .= '<option value="'.$pfgform_value2.'">'.$pfgform_values2_texts[$pfgform_value2].'</option>';
												}
											}else{
												if(strcmp($pfgform_value2,$setup22_searchresults_defaultsorttype) == 0){
												   $wpflistdata .= '<option value="'.$pfgform_value2.'" selected>'.$pfgform_values2_texts[$pfgform_value2].'</option>';
												}else{
													$wpflistdata .= '<option value="'.$pfgform_value2.'">'.$pfgform_values2_texts[$pfgform_value2].'</option>';
												}
											}
										}
										$wpflistdata .= '</select>
		                                </label>
		                            </li>
									';
								/*
	                            * End: ASC/DESC Section
	                            */

								/*
	                            * Start: Number Section
	                            */
									if($pfgetdata['authormode'] == 0 && $pfgetdata['agentmode'] == 0){
									$wpflistdata .='
		                            <li>
		                                <label for="pfsearch-filter-number" class="lbl-ui select pfnumberby">
		                            	<select class="pfsearch-filter-number" name="pfsearch-filter-number" id="pfsearch-filter-number" >';
										$pfgform_values = PFIFPageNumbers();
										if($args['posts_per_page'] != ''){
											$pagevalforn = $args['posts_per_page'];
										}else{
											$pagevalforn = $pfgetdata['items'];
										}
										foreach($pfgform_values as $pfgform_value){
		                                   if(strcmp($pfgform_value,$pagevalforn) == 0){
										  	   $wpflistdata .= '<option value="'.$pfgform_value.'" selected>'.$pfgform_value.'</option>';
										   }else{
											   $wpflistdata .= '<option value="'.$pfgform_value.'">'.$pfgform_value.'</option>';
										   }
										}
										$wpflistdata .= '</select>
		                                </label>
		                            </li>';
		                        	}
	                        	/*
	                            * End: Number Section
	                            */

		                        /*
	                            * Start: Category Filters
	                            */
		                            /*
		                            * Start: Listing Type Filter
		                            */   
			                        	if (isset($pfgetdata['listingtypefilters'])) {
				                        	if($pfgetdata['listingtypefilters'] == 'yes'){
												$wpflistdata .= '
					                            <li class="pfltypebyli">
					                                <label for="pfsearch-filter-ltype" class="lbl-ui select pfltypeby">';
					                                ob_start();
													$pfltypeby_args = array(
														'show_option_all'    => '',
														'show_option_none'   => esc_html__('Listing Types','pointfindert2d'),
														'option_none_value'  => '0',
														'orderby'            => 'ID', 
														'order'              => 'ASC',
														'show_count'         => PFASSIssetControl('setup_gridsettings_ltype_filter_c','',0),
														'hide_empty'         => PFASSIssetControl('setup_gridsettings_ltype_filter_h','',0), 
														'child_of'           => 0,
														'exclude'            => '',
														'echo'               => 1,
														'selected'           => (!empty($pfg_ltype))?$pfg_ltype:0,
														'hierarchical'       => 1, 
														'name'               => 'pfsearch-filter-ltype',
														'id'                 => 'pfsearch-filter-ltype',
														'class'              => 'pfsearch-filter-ltype',
														'depth'              => 0,
														'tab_index'          => 0,
														'taxonomy'           => 'pointfinderltypes',
														'hide_if_empty'      => false,
														'value_field'	     => 'term_id',
														'pointfinder'		=> 'directorylist'
													);
													wp_dropdown_categories($pfltypeby_args);
													$wpflistdata .= ob_get_contents();
													ob_end_clean();
												$wpflistdata .= '
					                                </label>
					                            </li>
												';
											}
										}
									/*
		                            * End: Listing Type Filter
		                            */


		                            /*
		                            * Start: Item Type Filter
		                            */
										if (isset($pfgetdata['itemtypefilters'])) {
											if($pfgetdata['itemtypefilters'] == 'yes' && $setup3_pointposttype_pt4_check == 1){
												$wpflistdata .= '
					                            <li class="pfitypebyli">
					                                <label for="pfsearch-filter-itype" class="lbl-ui select pfitypeby">';
												ob_start();
													$pfitypeby_args = array(
														'show_option_all'    => '',
														'show_option_none'   => esc_html__('Item Types','pointfindert2d'),
														'option_none_value'  => '0',
														'orderby'            => 'ID', 
														'order'              => 'ASC',
														'show_count'         => PFASSIssetControl('setup_gridsettings_itype_filter_c','',0),
														'hide_empty'         => PFASSIssetControl('setup_gridsettings_itype_filter_h','',0), 
														'child_of'           => 0,
														'exclude'            => '',
														'echo'               => 1,
														'selected'           => (!empty($pfg_itype))?$pfg_itype:0,
														'hierarchical'       => 1, 
														'name'               => 'pfsearch-filter-itype',
														'id'                 => 'pfsearch-filter-itype',
														'class'              => 'pfsearch-filter-itype',
														'depth'              => 0,
														'tab_index'          => 0,
														'taxonomy'           => 'pointfinderitypes',
														'hide_if_empty'      => false,
														'value_field'	     => 'term_id',	
													);
													wp_dropdown_categories($pfitypeby_args);
													$wpflistdata .= ob_get_contents();
													ob_end_clean();
												$wpflistdata .= '
					                                </label>
					                            </li>
												';
											}
										}
									/*
		                            * End: Item Type Filter
		                            */


									/*
		                            * Start: Location Type Filter
		                            */
										if (isset($pfgetdata['locationfilters'])) {
				                        	if($pfgetdata['locationfilters'] == 'yes' && $setup3_pointposttype_pt5_check == 1){
												$wpflistdata .= '
					                            <li class="pflocationbyli">
					                                <label for="pfsearch-filter-location" class="lbl-ui select pflocationby">';
												ob_start();
													$pflocationby_args = array(
														'show_option_all'    => '',
														'show_option_none'   => esc_html__('Locations','pointfindert2d'),
														'option_none_value'  => '0',
														'orderby'            => 'ID', 
														'order'              => 'ASC',
														'show_count'         => PFASSIssetControl('setup_gridsettings_location_filter_c','',0),
														'hide_empty'         => PFASSIssetControl('setup_gridsettings_location_filter_h','',0), 
														'child_of'           => 0,
														'exclude'            => '',
														'echo'               => 1,
														'selected'           => (!empty($pfg_lotype))?$pfg_lotype:0,
														'hierarchical'       => 1, 
														'name'               => 'pfsearch-filter-location',
														'id'                 => 'pfsearch-filter-location',
														'class'              => 'pfsearch-filter-location',
														'depth'              => 0,
														'tab_index'          => 0,
														'taxonomy'           => 'pointfinderlocations',
														'hide_if_empty'      => false,
														'value_field'	     => 'term_id',	
													);
													wp_dropdown_categories($pflocationby_args);
													$wpflistdata .= ob_get_contents();
													ob_end_clean();
												$wpflistdata .= '
					                                </label>
					                            </li>
												';
											}
										}
									/*
		                            * End: Location Type Filter
		                            */
		                        /*
	                            * End: Category Filters
	                            */

							$wpflistdata .='</ul>';
	                    /*
	                    * End: Left Filter Area
	                    */

	                    /*
                        * Start: Right Filter Area
                        */
	                        if($pfgetdata['authormode'] == 0 && $pfgetdata['agentmode'] == 0){
	                        $wpflistdata .= '
	                        <ul class="'.$pfcontainerdiv.'-filters-right '.$pfcontainerdiv.'-filters searchformcontainer-filters searchformcontainer-filters-right clearfix col-lg-3 col-md-3 col-sm-3 col-xs-12">
								';
								$setup22_searchresults_status_2col = PFSAIssetControl('setup22_searchresults_status_2col','','0');
								$setup22_searchresults_status_3col = PFSAIssetControl('setup22_searchresults_status_3col','','0');
								$setup22_searchresults_status_4col = PFSAIssetControl('setup22_searchresults_status_4col','','0');
								$setup22_searchresults_status_2colh = PFSAIssetControl('setup22_searchresults_status_2colh','','0');
								if($setup22_searchresults_status_2col == 0){$wpflistdata .= '<li class="pfgridlist2 pfgridlistit" data-pf-grid="grid2" ></li>';}
                                if($setup22_searchresults_status_3col == 0){$wpflistdata .= '<li class="pfgridlist3 pfgridlistit" data-pf-grid="grid3" ></li>';}
                                if($setup22_searchresults_status_4col == 0){$wpflistdata .= '<li class="pfgridlist4 pfgridlistit" data-pf-grid="grid4" ></li>';}
                                if($setup22_searchresults_status_2colh == 0){$wpflistdata .= '<li class="pfgridlist5 pfgridlistit" data-pf-grid="grid1" ></li>';}
								$wpflistdata .= '
								<li class="pfgridlist6"></li>                                
							</ul>
							';
							}
						/*
                        * End: Right Filter Area
                        */

					$wpflistdata .= '</div>';
				}
			/* End: Header Area for filters (HTML) */
                           
            
                $wpflistdata .=
                '<div class="'.$pfcontainerdiv.'-content pflistcommonview-content">';/*List Content begin*/
                
                    $wpflistdata .='<ul class="pfitemlists-content-elements '.$pfgrid_output.'" data-layout-mode="'.$grid_layout_mode.'">';
		
					$wpflistdata_output = '';	
					
					if($loop->post_count > 0){
				
						while ( $loop->have_posts() ) : $loop->the_post();
						
						$post_id = get_the_id();

							
								/* Start: Prepare Item Elements */				
									$ItemDetailArr = array();
									
									/* Get Item's WPML ID */
									
									if (!empty($pflang)) {$pfitemid = PFLangCategoryID_ld($post_id,$pflang);}else{$pfitemid = $post_id;}

									/* Start: Setup Featured Image */

										$ItemDetailArr['if_title'] = get_the_title($pfitemid);
										$ItemDetailArr['if_excerpt'] = get_the_excerpt();
										$ItemDetailArr['if_link'] = get_permalink($pfitemid);;
										$ItemDetailArr['if_address'] = esc_html(get_post_meta( $pfitemid, 'webbupointfinder_items_address', true ));
										$ItemDetailArr['featured_video'] =  get_post_meta( $pfitemid, 'webbupointfinder_item_video', true );
									/* End: Setup Featured Image */

									/* Start: Setup Details */

										$output_data = PFIF_DetailText_ld($pfitemid);
										if (is_array($output_data)) {
											if (!empty($output_data['ltypes'])) {
												$output_data_ltypes = $output_data['ltypes'];
											} else {
												$output_data_ltypes = '';
											}
											if (!empty($output_data['content'])) {
												$output_data_content = $output_data['content'];
											} else {
												$output_data_content = '';
											}
											if (!empty($output_data['priceval'])) {
												$output_data_priceval = $output_data['priceval'];
											} else {
												$output_data_priceval = '';
											}
										} else {
											$output_data_priceval = '';
											$output_data_content = '';
											$output_data_ltypes = '';
										}
									/* End: Setup Details */
								/* End: Prepare Item Elements */
								
								/* Start: Item Box */
									$fav_check = 'false';

									$wpflistdata_output .= '<li class="'.$pfgridcol_output.' wpfitemlistdata isotope-item">';
										$wpflistdata_output .= '<div class="pflist-item"'.$pfitemboxbg.'>';
											$wpflistdata_output .= '<div class="pflist-item-inner">';

										
											
												/* Start: Detail Texts */
													$titlecount = strlen($ItemDetailArr['if_title']);
													$titlecount = (strlen($ItemDetailArr['if_title'])<=$limit_chr_title ) ? '' : '...' ;
													$title_text = mb_substr($ItemDetailArr['if_title'], 0, $limit_chr_title ,'UTF-8').$titlecount;

													$addresscount = strlen($ItemDetailArr['if_address']);
													$addresscount = (strlen($ItemDetailArr['if_address'])<=$limit_chr ) ? '' : '...' ;
													$address_text = mb_substr($ItemDetailArr['if_address'], 0, $limit_chr ,'UTF-8').$addresscount;

													$excerpt_text = mb_substr($ItemDetailArr['if_excerpt'], 0, ($limit_chr*$setup22_searchresults_hide_excerpt_rl),'UTF-8').$addresscount;
													if (strlen($ItemDetailArr['if_excerpt']) > ($limit_chr*$setup22_searchresults_hide_excerpt_rl)) {
														$excerpt_text .= '...';
													}
													
													/* Title and address area */

														$wpflistdata_output .= '
														<div class="pflist-detailcontainer pflist-subitem">
															<ul class="pflist-itemdetails">
																<li class="pflist-itemtitle"><a href="'.$ItemDetailArr['if_link'].'"'.$targetforitem.'>'.$title_text.'</a></li>
																';

																/* Start: Review Stars */
											                        if ($review_system_statuscheck == 1) {
											                        	if ($setup22_searchresults_hide_re == 0) {

											                        		$reviews = pfcalculate_total_review($pfitemid);

											                        		if (!empty($reviews['totalresult'])) {
											                        			$wpflistdata_output .= '<li class="pflist-reviewstars">';
											                        			$rev_total_res = round($reviews['totalresult']);
											                        			$wpflistdata_output .= '<div class="pfrevstars-wrapper-review">';
											                        			$wpflistdata_output .= ' <div class="pfrevstars-review">';
											                        				for ($ri=0; $ri < $rev_total_res; $ri++) { 
											                        					$wpflistdata_output .= '<i class="pfadmicon-glyph-377"></i>';
											                        				}
											                        				for ($ki=0; $ki < (5-$rev_total_res); $ki++) { 
											                        					$wpflistdata_output .= '<i class="pfadmicon-glyph-378"></i>';
											                        				}

											                        			$wpflistdata_output .= '</div></div>';
											                        			$wpflistdata_output .= '</li>';
											                        		}else{
											                        			if($setup16_reviewstars_nrtext == 0){
											                        				$wpflistdata_output .= '<li class="pflist-reviewstars">';
												                        			$wpflistdata_output .= '<div class="pfrevstars-wrapper-review">';
												                        			$wpflistdata_output .= '<div class="pfrevstars-review pfrevstars-reviewbl">
												                        			<i class="pfadmicon-glyph-378"></i><i class="pfadmicon-glyph-378"></i><i class="pfadmicon-glyph-378"></i><i class="pfadmicon-glyph-378"></i><i class="pfadmicon-glyph-378"></i></div></div>';
												                        			$wpflistdata_output .= '</li>';
											                        			}
											                        		}
											                        	}
											                        }
												                /* End: Review Stars */

																if($setup22_searchresults_hide_address == 0){
																$wpflistdata_output .= '
																<li class="pflist-address"><i class="pfadmicon-glyph-109"></i> '.$address_text.'</li>
																';
																}

																if (!empty($output_data_ltypes) && $pfgrid == 'grid1') {
																	
																	$wpflistdata_output .= '<li class="pflist-category visible-lg visible-md"><i class="pfadmicon-glyph-534"></i>';
																		$output_data_ltypes_f1col = str_replace("<div class=\"pflistingitem-subelement pf-price\">", "", $output_data_ltypes);
																		$output_data_ltypes_f1col = str_replace("</div>", "", $output_data_ltypes_f1col);
																		$output_data_ltypes_f1col = str_replace("<ul class=\"pointfinderpflisttermsgr\">", "", $output_data_ltypes_f1col);
																		$output_data_ltypes_f1col = str_replace("</ul>", "", $output_data_ltypes_f1col);
																		$output_data_ltypes_f1col = str_replace("<li>", "", $output_data_ltypes_f1col);
																		$output_data_ltypes_f1col = str_replace("</li>", "", $output_data_ltypes_f1col);
																		$wpflistdata_output .= $output_data_ltypes_f1col;
																	
																	$wpflistdata_output .= '</li>';
																}
																$wpflistdata_output .= '
															</ul>
															';
															if($pfboptx_text != 'style="display:none"' && $pfgrid == 'grid1'){
															$wpflistdata_output .= '
																<div class="pflist-excerpt pflist-subitem" '.$pfboptx_text.'>'.$excerpt_text.'</div>
															';
															}
															$wpflistdata_output .= '
														</div>
														';

														if($pfboptx_text != 'style="display:none"' && $pfgrid != 'grid1'){
														$wpflistdata_output .= '
															<div class="pflist-excerpt pflist-subitem" '.$pfboptx_text.'>'.$excerpt_text.'</div>
														';
														}

														if ((!empty($output_data_content) || !empty($output_data_priceval))) {
															if (!empty($pf1colfix)) {
																$pf1colfix2 = '<div class="pflist-customfield-price">'.$output_data_priceval.'</div>';
															}
															
															$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem">'.$pf1colfix2.'
															<div class="pflist-customfields">'.$output_data_content.'</div>
															</div>';
														}

														/* Show on map text for search results and search page */
														if (!empty($pfgetdata['manual_args'])) {
															if ($setup22_searchresults_showmapfeature == 1 && $setup42_searchpagemap_headeritem == 1) {
																//$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem"><a data-pfitemid="'.$pfitemid.'" class="pfshowmaplink"><i class="pfadmicon-glyph-372"></i> '.esc_html__('SHOW ON MAP','pointfindert2d').'</a></div>';
															}
														}
												/* End: Detail Texts */
														
											$wpflistdata_output .= '</div>';
										$wpflistdata_output .= '</div>';
									$wpflistdata_output .= '</li>';

								/* End: Item Box */
							
						
							
							
						endwhile;
						
						$wpflistdata .= $wpflistdata_output;               
			            $wpflistdata .= '</ul>';
					}else{
						$setup3_modulessetup_authornrf = PFSAIssetControl('setup3_modulessetup_authornrf','','0');
						$wpflistdata .= $wpflistdata_output;               
			            $wpflistdata .= '</ul>';

			            if($setup3_modulessetup_authornrf == 1 && ($pfgetdata['authormode'] == 1 || $pfgetdata['agentmode'] == 1)){
				            $wpflistdata .= '<div class="golden-forms">';
				            $wpflistdata .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
							$wpflistdata .= '<strong>'.esc_html__('No record found!','pointfindert2d').'</strong></p>';
							$wpflistdata .= '</div>';
							$wpflistdata .= '</div>';
						}elseif ($pfgetdata['authormode'] == 0 && $pfgetdata['agentmode'] == 0) {
							$wpflistdata .= '<div class="golden-forms">';
				            $wpflistdata .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
							$wpflistdata .= '<strong>'.esc_html__('No record found!','pointfindert2d').'</strong></p>';
							$wpflistdata .= '</div>';
							$wpflistdata .= '</div>';
						}
						
					}
		           
					$wpflistdata .= '<div class="pfstatic_paginate" >';
					
					$big = 999999999;
					$maxpages = $loop->max_num_pages;
					$wpflistdata .= paginate_links(array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' => '?page=%#%',
						'current' => max(1, $pfg_paged),
						'total' => $maxpages,
						'type' => 'list',
					));
					
					wp_reset_postdata();

					if ($infinite_scroll == 1) {
						if ($infinite_scroll_lm == 1) {
							$wpflistdata .= "<a class='pointfinder-infinite-scroll-loadbutton'>".sprintf(esc_html__("更多 %s","pointfindert2d")," (<span class='pointfinder-infinite-scroll-loadbutton-num'>".($pfg_paged+1)."</span>/".$maxpages.")")."</a>";
						}
							$wpflistdata .= "<div class='pointfinder-infinite-scroll-loading'></div>";
					}

					$wpflistdata .= '</div></div>';/*List Content End*/
					$wpflistdata .= "<input type='hidden' value='".$pfgrid."' name='pfsearch-filter-col'>";
					$wpflistdata .= $pfgetdata['hidden_output'];
					$wpflistdata .= "</form></div></div> ";/*Form End . List Data End*/

					
					if ($infinite_scroll == 1) {
						$wpflistdata .='<script>
						(function($) {
						"use strict"
						$(function(){
							$(".pflistgridview'.$gridrandno_orj.'-content .pfstatic_paginate .page-numbers").hide();

							$(".pflistgridview'.$gridrandno_orj.'-content > ul.pfitemlists-content-elements").infinitescroll({
							  nextSelector: ".pflistgridview'.$gridrandno_orj.'-content .pfstatic_paginate a.next.page-numbers:last",
							  navSelector: ".pflistgridview'.$gridrandno_orj.'-content .pfstatic_paginate ul.page-numbers",
							  extraScrollPx: 150,
							  itemSelector: "li.wpfitemlistdata",
							  animate: true,
							  dataType: "html",
							  bufferPx: 40,
							  errorCallback: function () {
								$(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loading").hide();
							  },
							  maxPage: '.$maxpages.',
							  debug : false,
							  ';
						  	  if (is_page()) {
						  	  $wpflistdata .= '
							  pathParse: function (path, currentPage) {
							  	return ["'.get_permalink().'page/",""];
							  	
							  },';
							  }
							  $wpflistdata .= '
							  loading: {
								selector: $(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loading"),
								selector2:".pflistgridview'.$gridrandno_orj.'-content",
								msgText: "<em>'.esc_html__("Loading please wait...","pointfindert2d" ).'</em>",
								speed: "fast",
								finishedMsg: "<em>'.esc_html__("All pages loaded.","pointfindert2d").'</em>",
                				img:"'.$template_directory_uri.'/images/info-loading.gif",
                				finished:function(){
                					$(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loading").hide();
                					$(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loadbutton").show();
                				},
                				startCallback:function(){
                					$(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loading").show();
                					$(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loadbutton").hide();
                				}
							  }
							},function( newElements ) {
					          $(".pflistgridview'.$gridrandno_orj.'-content > ul.pfitemlists-content-elements").isotope( "appended", $( newElements ) );
					        });


						';

						if ($infinite_scroll_lm == 1) {
							$wpflistdata .= '
								$(".pflistgridview'.$gridrandno_orj.'-content > ul.pfitemlists-content-elements").infinitescroll("unbind");
								$(".pflistgridview'.$gridrandno_orj.'-content .pointfinder-infinite-scroll-loadbutton").on("click touchstart",function(){
									$(".pflistgridview'.$gridrandno_orj.'-content > ul.pfitemlists-content-elements").infinitescroll("retrieve");
								});
							';
						}

						$wpflistdata .= '
						});
						})(jQuery);
						</script>';
					}

					$wpflistdata .= "
					<script type='text/javascript'>
					(function($) {
						'use strict'
						$('.pflistgridview{$gridrandno_orj}-filters-right .pfgridlistit').click(function(){
							$('input[name=pfsearch-filter-col]').val($(this).attr('data-pf-grid'));
							$('#{$pfcontainershow}-form').submit();	
						});
						
						$('.pflistgridview{$gridrandno_orj}-filters-left > li > label > select').change(function(){
							$('#{$pfcontainershow}-form').submit();
						});
						
						$(function() {
							var intervaltime = 0;
							var makeitperfextpf = setInterval(function() {
								
							
								
								var layout_modes = {
						        fitrows: 'fitRows',
						        masonry: 'masonry'
						        }
						        $('.pflistgridview{$gridrandno_orj}-content').each(function(){
						            var \$container = $(this);
						            var \$thumbs = \$container.find('.pfitemlists-content-elements');
						            var layout_mode = \$thumbs.attr('data-layout-mode');
						            \$thumbs.isotope({
						                itemSelector : '.isotope-item',
						                layoutMode : (layout_modes[layout_mode]==undefined ? 'fitRows' : layout_modes[layout_mode])
						            });
						            
						        });
								
								
								intervaltime++;
								if (intervaltime == 5) {
									clearInterval(makeitperfextpf);
								}
							}, 1000);
						});

						$('.pfButtons a').click(function() {
							if($(this).attr('data-pf-link')){
								$.prettyPhoto.open($(this).attr('data-pf-link'));
							}
						});

					})(jQuery);
					</script>
";
return $wpflistdata;
}
add_shortcode( 'pf_itemgrid2', 'pf_itemgrid2_func' ); 

?>