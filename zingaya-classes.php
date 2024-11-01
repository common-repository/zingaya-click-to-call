<?php
class WidgetsTable extends WP_List_Table {

	var $data = array();

	function get_columns(){
		$columns = array(
			'cb'       	=> '<input type="checkbox" />',
			'name'	    	=> __('Name', 'zingaya'),
			'active'    	=> __('Active', 'zingaya'),
			'numbers'      	=> __('Numbers', 'zingaya'),
                        'shortcode'     => __(' ', 'zingaya')
		);
		if (check_feature('get_targeting')) $columns['countrycontrol'] = __('Country control', 'zingaya');
		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'name'  => array('name',false),
			'active' => array('active',false)
		);
		return $sortable_columns;
	}

	function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strcmp( $a[$orderby], $b[$orderby] );
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

	function prepare_items() {

		$this->process_bulk_action();

		$request = ZINGAYA_API_URL . '?cmd=GetWidgets&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', ''));		
		$result = wp_remote_get( $request, array("sslverify" => false) );

		if ( is_wp_error( $result ) ) {
			br_trigger_error( $result->get_error_message(), E_USER_ERROR);
		} else {
			$obj = json_decode($result["body"]);
			if (!isset($obj->error)) {
				foreach ($obj->result as $key => $value) {

					$callme_numbers = '';
					if (isset($value->callme_numbers) && is_array($value->callme_numbers)) {
						foreach ($value->callme_numbers as $k => $v) {
							$callme_numbers .= $v->callme_number . ' ';
						}
					}

					$this->data[] = array(
                                            'ID' => $value->widget_id, 
                                            'name' => $value->widget_name, 
                                            'active' => $value->active?'<div class="widget active"></div>':'<div class="widget inactive"></div>', 'numbers' => $callme_numbers , 'countrycontrol' => (isset($value->countries)?$value->countries:'<div class="widget inactive"></div>'),
                                            'callme_id' => $value->callme_id,
                                            'button_graphics' => ((isset($value->button_graphics))?$value->button_graphics:"")
                                        );
				}
				//die(print_r($obj->result));

			} else {
				// show error message
		    	admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
		    	exit;	
			}
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		usort( $this->data, array( &$this, 'usort_reorder' ) );

		$per_page = 10;
  		$current_page = $this->get_pagenum();
  		$total_items = count($this->data);

  		$this->found_data = array_slice($this->data,(($current_page-1)*$per_page),$per_page);

  		$this->set_pagination_args( array(
    		'total_items' => $total_items,                  //WE have to calculate the total number of items
    		'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  		) );

		$this->items = $this->found_data;
	}

	function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case 'name':
			case 'active':
			case 'numbers':
			case 'countrycontrol':
				return $item[ $column_name ];
                        case 'shortcode':
                            $widget_settings = explode(";", $item['button_graphics']);
                            $wt = "widget";
                            if ( is_array($widget_settings) && count($widget_settings) > 0 ) {
                                foreach($widget_settings as $ws){
                                    $param = explode(":", $ws);
                                    if ( !is_array($param) || count($param) < 2 ) continue;
                                    if ( $param[0] == "type" && $param[1] == "button" ) $wt = "button";
                                }
                            }
                            
                            $aw = intval(get_option('zingaya_active_widget', ''));
                            
                            if ( $wt == "button" )
                                return "<input value=\"[zingaya_widget widget_id={$item['ID']}]\" class=\"widgets-list-shortcode\" readonly />";
                            else 
                                return "<label><input type=\"checkbox\" data-widget=\"".$item['ID']."\" name=\"zingaya_active_widget\"".(($aw>0 && $aw==$item['ID'])?" checked":"")." />".__("Widget is active", "zingaya")."</label>";
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

	function column_name($item) {
            $actions = array(
                'view'      => sprintf('<a href="https://zingaya.com/widget/'.$item['callme_id'].'" onclick="window.open(this.href, \'_blank\', \'width=230,height=175,resizable=no,toolbar=no,menubar=no,location=no,status=no\'); return false">' . __('Test', 'zingaya') . '</a>',$_REQUEST['page'],'test_widget', $item['ID']),
                'edit'      => sprintf('<a href="?page=%s&action=%s&tab=widgets&widget=%s">' . __('Edit', 'zingaya') . '</a>',$_REQUEST['page'],'edit_widget', $item['ID']),
                'design'      => sprintf('<a href="?page=%s&action=%s&tab=widgets&widget=%s">' . __('Widget builder', 'zingaya') . '</a>',$_REQUEST['page'],'widget_designer', $item['ID']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&tab=widgets&widget=%s" onclick="return confirm(\'' . __('Widget', 'zingaya').' '.$item['name'].' ' . __('will be deleted. Are you sure?', 'zingaya') . '\')">' . __('Delete', 'zingaya') . '</a>',$_REQUEST['page'],'delete_widget', $item['ID'])
            );

            return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
	}

	function get_bulk_actions() {
            $actions = array(
                'delete_widgets'    =>  __('Delete', 'zingaya')
            );
            return $actions;
	}

	function process_bulk_action() {    

	    // security check!
            if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
                $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
                $action = 'bulk-' . $this->_args['plural'];
                if ( ! wp_verify_nonce( $nonce, $action ) ) wp_die( __('Nope! Security check failed!', 'zingaya') );
            }

	    if ( 'delete_widgets' === $this->current_action() ) {
	    	$errors = false;
	    	foreach ($_POST['widget'] as $key => $value) {
                    // delete widgets with id = value
                    $request = ZINGAYA_API_URL . '?cmd=DelWidget&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                            '&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
                            '&widget_id=' . urlencode($value);
                        
                    $result = wp_remote_get( $request, array("sslverify" => false) );

                    if ( is_wp_error( $result ) ) {
                        $errors = true;
                        br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                        break;
                    } else {
                        $obj = json_decode($result["body"]);
                        if (!isset($obj->error)) {
                            // go on	            		
                        } else {
                            $errors = true;
                            admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                            break;	
                        }
                    }
                }
                if (!$errors) admin_notice_message(__('Widgets were deleted successfully', 'zingaya'));    	
	    }
	}

	function column_cb($item) {
            return sprintf(
                '<input type="checkbox" name="widget[]" value="%s" />', $item['ID']
            );    
        }
}

class CallhistoryTable extends WP_List_Table {

	var $data = array();

	function get_columns(){
            $columns = array(
                'callstatus'	=> __('Call status', 'zingaya'),
                'date'          => __('Date', 'zingaya'),
                'number'      	=> __('Number', 'zingaya'),
                'duration'	=> __('Duration', 'zingaya'),
                'cost'		=> __('Cost', 'zingaya'),
                'country'	=> __('Country', 'zingaya'),
                'widget'	=> __('Widget', 'zingaya')
            );
            if (check_feature('call_recording')) $columns['record'] = __('Record', 'zingaya');
            return $columns;
	}

	function get_sortable_columns() {
            $sortable_columns = array(
                'date' => array('date',false)
            );
            return $sortable_columns;
	}

	function prepare_items() {

		// If no sort, default to date
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';
		// If no order, default to desc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';

		$request = ZINGAYA_API_URL . '?cmd=GetCallHistory&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
				'&show_total_count=true&count=20&offset='.(($this->get_pagenum()-1)*20).($order=='desc'?'&tail=true':'');	
		$result = wp_remote_get( $request, array("sslverify" => false) );

		if ( is_wp_error( $result ) ) {
			br_trigger_error( $result->get_error_message(), E_USER_ERROR);
		} else {
			$obj = json_decode($result["body"]);
			if (!isset($obj->error)) {
				//die(print_r($obj->result));
				foreach ($obj->result as $key => $value) {

					$this->data[] = array(
						'ID' 			=> $value->id,
						'callstatus'	=> ($value->successful?'<div class="call successful"></div>':'<div class="call unsuccessful"></div>'),
						'date'			=> $value->call_date,
						'number'		=> $value->phone,
						'duration'		=> $value->duration,
						'cost'			=> '$'.$value->price,
						'country'		=> $value->client_country,
						'widget'		=> $value->widget_name,
						'record'		=> ($value->record_url!=''?'<div class="record available"></div>':''),
						'record_url'            => $value->record_url
					);
				}

			} else {
				// show error message
		    	admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
		    	exit;	
			}
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		//usort( $this->data, array( &$this, 'usort_reorder' ) );

		$per_page = 20;
  		$current_page = $this->get_pagenum();
  		$total_items = $obj->total_calls_count;

  		$this->set_pagination_args( array(
    		'total_items' => $total_items,                  //WE have to calculate the total number of items
    		'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  		) );

		$this->items = $this->data;
	}

	function column_default( $item, $column_name ) {

		switch( $column_name ) { 
			case 'callstatus':
			case 'date':
			case 'number':
			case 'duration':
			case 'cost':
			case 'country':
			case 'widget':
			case 'record':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

	function column_record($item) {
		if ($item["record_url"] != "") {
			$actions = array(
                            'view'  => sprintf('<a href="%1$s">' . __('Download', 'zingaya') . '</a>', $item["record_url"]),
                            'edit'      => sprintf('<a href="http://zingaya.com:8080/CallmeAdmin/mediaplayer.jsp?file=%1$s" target="_blank">' . __('Play', 'zingaya') . '</a>', $item["record_url"]),
	        );

	  		return sprintf('%1$s %2$s', $item['record'], $this->row_actions($actions) );
  		}
	}

}


class VoicemailTable extends WP_List_Table {

	var $data = array();

	function get_columns(){
		$columns = array(
			'date'    		=> __('Date', 'zingaya'),
			'duration'		=> __('Duration', 'zingaya'),
			'widget'		=> __('Widget', 'zingaya'),
			'listened'		=> __('Listened', 'zingaya'),
			'record'		=> __('Record', 'zingaya')
		);

		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'date'		  	=> array('date',false)
		);
		return $sortable_columns;
	}

	function prepare_items() {

		// If no sort, default to date
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';
		// If no order, default to desc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';

		$request = ZINGAYA_API_URL . '?cmd=GetVoicemails&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
				'&show_total_count=true&count=20&offset='.(($this->get_pagenum()-1)*20).($order=='desc'?'&tail=true':'');			
		$result = wp_remote_get( $request, array("sslverify" => false) );
		//print_r($result);

		if ( is_wp_error( $result ) ) {
			br_trigger_error( $result->get_error_message(), E_USER_ERROR);
		} else {
			$obj = json_decode($result["body"]);
			if (!isset($obj->error)) {
				//die(print_r($obj->result));
				foreach ($obj->result as $key => $value) {

					$this->data[] = array(
						'ID' 			=> $value->voicemail_id,
						'date'			=> $value->date,
						'duration'		=> $value->duration,
						'widget'		=> $value->widget_id, // TODO: get widget name
						'listened'		=> ($value->listened?'<div class="record listened"></div>':''),
						'record'		=> ($value->record_url!=''?'<div class="record available"></div>':''),
						'record_url'	=> $value->record_url
					);
				}

			} else {
				// show error message
		    	admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
		    	exit;	
			}
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		//usort( $this->data, array( &$this, 'usort_reorder' ) );

		$per_page = 20;
  		$current_page = $this->get_pagenum();
  		$total_items = $obj->total_calls_count;

  		$this->set_pagination_args( array(
    		'total_items' => $total_items,                  //WE have to calculate the total number of items
    		'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  		) );

		$this->items = $this->data;
	}

	function column_default( $item, $column_name ) {

		switch( $column_name ) { 
			case 'date':						
			case 'widget':
			case 'record':
			case 'listened':
				return $item[ $column_name ];			
			case 'duration':
				return gmdate($item[ $column_name ] > 3600?'H:i:s':'i:s', $item[ $column_name ]);
			break;
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

	function column_record($item) {
		if ($item["record_url"] != "") {
			$actions = array(
                            'view'  => sprintf('<a href="%1$s">' . __('Download', 'zingaya') . '</a>', $item["record_url"]),
                            'edit'  => sprintf('<a href="http://zingaya.com:8080/CallmeAdmin/mediaplayer.jsp?file=%1$s" target="_blank">' . __('Play', 'zingaya') . '</a>', $item["record_url"]),
                            'delete'    => sprintf('<a href="?page=%s&action=%s&voicemail_id=%s&tab=features&feature=voicemail" onclick="return confirm(\'' . __('Voicemail will be deleted. Are you sure?', 'zingaya') . '\')">' . __('Delete', 'zingaya') . '</a>',$_REQUEST['page'],'delete_voicemail', $item['ID'])
                        );

	  		return sprintf('%1$s %2$s', $item['record'], $this->row_actions($actions) );
  		}
	}

}


class IPBlacklistTable extends WP_List_Table {

	var $data = array();

	function get_columns(){
		$columns = array(
			'ip' => __('IP address', 'zingaya'),
		);

		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	function prepare_items() {

		$request = ZINGAYA_API_URL . '?cmd=GetBlackList&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
				'&show_total_count=true&count=20&offset='.(($this->get_pagenum()-1)*20);			
		$result = wp_remote_get( $request, array("sslverify" => false) );

		if ( is_wp_error( $result ) ) {
			br_trigger_error( $result->get_error_message(), E_USER_ERROR);
		} else {
			$obj = json_decode($result["body"]);
			if (!isset($obj->error)) {
				//die(print_r($obj->result));
				foreach ($obj->result as $value) {

					$this->data[] = array(
						'ip' => $value,
					);
				}

			} else {
				// show error message
		    	admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
		    	exit;	
			}
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$per_page = 20;
  		$current_page = $this->get_pagenum();
  		$total_items = (isset($obj->total_calls_count))?$obj->total_calls_count:0;

  		$this->set_pagination_args( array(
    		'total_items' => $total_items,                  //WE have to calculate the total number of items
    		'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  		) );

		$this->items = $this->data;
	}

	function column_default( $item, $column_name ) {

		switch( $column_name ) { 
			case 'ip':						
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

	function column_ip($item) {
            $actions = array(		
                'delete'    => sprintf('<a href="?page=%s&action=%s&ip=%s&tab=features&feature=blacklist" onclick="return confirm(\'' . __('IP address', 'zingaya') . ' '.$item['ip'].' ' . __('will be deleted. Are you sure?', 'zingaya') . '\')">' . __('Delete', 'zingaya') . '</a>',$_REQUEST['page'],'delete_ip', $item['ip'])
            );
            return sprintf('%1$s %2$s', $item['ip'], $this->row_actions($actions) );
	}

}


?>