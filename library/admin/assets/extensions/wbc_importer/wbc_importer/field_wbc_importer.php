<?php
/**
 * Extension-Boilerplate
 * @link https://github.com/ReduxFramework/extension-boilerplate
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_wbc_importer' ) ) {

    /**
     * Main ReduxFramework_wbc_importer class
     *
     * @since       1.0.0
     */
    class ReduxFramework_wbc_importer {

        /**
         * Field Constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            $class = ReduxFramework_extension_wbc_importer::get_instance();

            if ( !empty( $class->demo_data_dir ) ) {
                $this->demo_data_dir = $class->demo_data_dir;
                $this->demo_data_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->demo_data_dir ) );
            }

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }
        }
        
        
        function remote_render()
        {
	         echo '</fieldset></td></tr><tr><td colspan="2"><fieldset class="redux-field wbc_importer">';
	         echo '<div class="theme-browser"><div class="themes">';
         
	         ?>
	         <div class="st-marketplace-bonus-dialog-container hidden">
                 <div class="st-markplace-bonus-dialog">
			         <div class="st-markplace-bonus-dialog-form">
				         <h4>Unlock Bonus Layout</h4>
				         <input class="cbt-bonus-password" type="text" id="cbt-bonus-password" placeholder="Type password to unlock new layout" />
				         <button id="cbt-bonus-button" class="button-primary cbt-bonus-button">Unlock</button>
			         </div>
			         <div class="st-markplace-bonus-dialog-notice">
				         <h4>You can see a full list of currently available bonus layouts on our blog.</h4>
				         <a style="" href="http://bonus.prostylertheme.com" target="_blank" class="button-primary">Click Here</a>
			         </div>
			         <div class="clearfix"></div>
		         </div>
             </div>
	         <?php

	        //$data = $this->get_remote($this->field['remote_api']);
	        $nonce = wp_create_nonce( "redux_{$this->parent->args['opt_name']}_wbc_importer" );

            $category_mapping = array(
                'Market Place' => '1',
                'Template Club' => '2',
                'Bonus Templates' => '3',
            );

            ?>
            <div class="st-marketplace-filters hidden">
                <ul class="st-marketplace-categories"> 
                    <?php 
                    echo '<li class="st-filter-bar-btn" data-filter="all">All</li>';
                    foreach($category_mapping as $cat_name => $cat_id):

                        echo '<li class="st-filter-bar-btn '.(($cat_id=='1')?'active':'').'" data-filter="'.$cat_id.'">'.$cat_name.'</li>';

                    endforeach; 
                    echo '<li class="st-filter-bar-filter st-unlock-bonus-btn">Unlock Bonus</li>';
                    echo '<li class="st-filter-bar-filter st-only-purchased-btn" data-filter="999"><i class="fa fa-square-o" aria-hidden="true"></i> Show only Purchased templates</li>';
                    ?>
                </ul>
                <div class="st-marketplace-search">
                    <input class="" data-search="" name="filtr-search" placeholder="Search..." type="text" value="">
                    </input>
                </div>
            </div>
            <?php
	        
	        echo '<div class="cbt_api_request cbt_marketplace_container" data-api="'.urlencode($this->field['remote_api']).'" data-callback-event="cbt_importer-loaded" data-nonce="'.$nonce.'"><div class="cbt_loader"></div></div>';
	        
            
            //$data = preg_replace('/\{NONCE\}/', $nonce, $data);
			
			//$imported = get_option( 'wbc_imported_demos' );
			//echo '<pre>'.print_r($imported, TRUE).'</pre>';
			
			echo $data;

            echo '</div></div>';
            echo '</fieldset></td></tr>';
        }
        
        
        function get_remote($api)
        {
			if($api == false)
			{
				echo 'Please activate your license first to access this feature';
				return;
			}

			$data = @file_get_contents($api);
		
			if(! $data)
			{
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, $api);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    $data = curl_exec($ch);
			    curl_close($ch);
			}

			if(! $data) return 'Error connecting to API Server';
	

			return $data;
        }

        /**
         * Field Render Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() 
        {
	        // ------------------------------------------------------
	        //  yunus edit - added remote render
	        // ------------------------------------------------------
	        if(isset($this->field['remote']) && $this->field['remote'])
	        {
		        return $this->remote_render();
	        }

            echo '</fieldset></td></tr><tr><td colspan="2"><fieldset class="redux-field wbc_importer">';

            $nonce = wp_create_nonce( "redux_{$this->parent->args['opt_name']}_wbc_importer" );

            // No errors please
            $defaults = array(
                'id'        => '',
                'url'       => '',
                'width'     => '',
                'height'    => '',
                'thumbnail' => '',
            );

            $this->value = wp_parse_args( $this->value, $defaults );
            

            $imported = false;

            $this->field['wbc_demo_imports'] = apply_filters( "redux/{$this->parent->args['opt_name']}/field/wbc_importer_files", array() );

            echo '<div class="theme-browser"><div class="themes">';

            if ( !empty( $this->field['wbc_demo_imports'] ) ) {
                foreach ( $this->field['wbc_demo_imports'] as $section => $imports ) {

                    if ( empty( $imports ) ) {
                        continue;
                    }

                    if ( !array_key_exists( 'imported', $imports ) ) {
                        $extra_class = 'not-imported';
                        $imported = false;
                        $import_message = esc_html__( 'Import Demo', 'framework' );
                    }else {
                        $imported = true;
                        $extra_class = 'active imported';
                        $import_message = esc_html__( 'Demo Imported', 'framework' );
                    }
                    echo '<div class="wrap-importer theme '.$extra_class.'" data-demo-id="'.esc_attr( $section ).'"  data-nonce="' . $nonce . '" id="' . $this->field['id'] . '-custom_imports">';

                    echo '<div class="theme-screenshot">';

                    if ( isset( $imports['image'] ) ) {
                        echo '<img class="wbc_image" src="'.esc_attr( esc_url( $this->demo_data_url.$imports['directory'].'/'.$imports['image'] ) ).'"/>';

                    }
                    echo '</div>';

                    echo '<span class="more-details">'.$import_message.'</span>';
                    echo '<h3 class="theme-name">'. esc_html( apply_filters( 'wbc_importer_directory_title', $imports['directory'] ) ) .'</h3>';

                    echo '<div class="theme-actions">';
                    if ( false == $imported ) {
                        echo '<div class="wbc-importer-buttons"><span class="spinner" style="position: absolute;right: 100%;">'.esc_html__( 'Please Wait...', 'framework' ).'</span><span class="button-primary importer-button import-demo-data">' . __( 'Import Demo', 'framework' ) . '</span></div>';
                    }else {
                        echo '<div class="wbc-importer-buttons button-secondary importer-button">'.esc_html__( 'Imported', 'framework' ).'</div>';
                        echo '<span class="spinner" style="position: absolute;right: 100%;">'.esc_html__( 'Please Wait...', 'framework' ).'</span>';
                        echo '<div id="wbc-importer-reimport" class="wbc-importer-buttons button-primary import-demo-data importer-button">'.esc_html__( 'Re-Import', 'framework' ).'</div>';
                    }
                    echo '</div>';
                    echo '</div>';


                }

            } else {
                echo "<h5>".esc_html__( 'No Demo Data Provided', 'framework' )."</h5>";
            }

            echo '</div></div>';
            echo '</fieldset></td></tr>';

        }

        /**
         * Enqueue Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {


            wp_enqueue_script(
                'redux-field-wbc-importer-js',
                $this->extension_url . 'field_wbc_importer.js',
                array( 'jquery' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-wbc-importer-css',
                $this->extension_url . 'field_wbc_importer.css',
                time(),
                true
            );

        }
    }
}
