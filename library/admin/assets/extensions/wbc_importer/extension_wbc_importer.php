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
if ( !class_exists( 'ReduxFramework_extension_wbc_importer' ) ) {

    class ReduxFramework_extension_wbc_importer {

        public static $instance;

        static $version = "1.0.1";

        protected $parent;

        private $filesystem = array();

        public $extension_url;

        public $extension_dir;

        public $demo_data_dir;

        public $wbc_import_files = array();

        public $active_import_id;

        public $active_import;


        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct( $parent ) {

            $this->parent = $parent;

            if ( !is_admin() ) return;

            //Hides importer section if anything but true returned. Way to abort :)
            if ( true !== apply_filters( 'wbc_importer_abort', true ) ) {
                return;
            }

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
                $this->demo_data_dir = apply_filters( "wbc_importer_dir_path", $this->extension_dir . 'demo-data/' );
            }

            //Delete saved options of imported demos, for dev/testing purpose
            // delete_option('wbc_imported_demos');

            $this->getImports();

            $this->field_name = 'wbc_importer';

            self::$instance = $this;

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
                    'overload_field_path'
                ) );

            add_action( 'wp_ajax_redux_wbc_importer', array(
                    $this,
                    'ajax_importer'
                ) );

            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/wbc_importer_files', array(
                    $this,
                    'addImportFiles'
                ) );

            //Adds Importer section to panel
            $this->add_importer_section();


        }


        public function getImports() {

            if ( !empty( $this->wbc_import_files ) ) {
                return $this->wbc_import_files;
            }

            $this->filesystem = $this->parent->filesystem->execute( 'object' );

            $imports = $this->filesystem->dirlist( $this->demo_data_dir, false, true );

            if($imports)
            {
               // echo '<pre>sd '.print_r($imports, TRUE).'</pre>';die();
            }

            $imported = get_option( 'wbc_imported_demos' );

            if ( !empty( $imports ) ) {
                $x = 1;
                foreach ( $imports as $import ) 
                {

                    if ( !isset( $import['files'] ) || empty( $import['files'] ) ) {
                        continue;
                    }

                    if ( $import['type'] == "d" && !empty( $import['name'] ) ) 
                    {
	                    
	                    // yunus edit - replace x with something unique for remote imports
	                    if(preg_match('/^cbt\-template\_/i', $import['name']))
	                    {
		                    $temp_x = $x;
		                    $x = $import['name'];
	                    }
	                    
                        $this->wbc_import_files['wbc-import-'.$x] = isset( $this->wbc_import_files['wbc-import-'.$x] ) ? $this->wbc_import_files['wbc-import-'.$x] : array();
                        $this->wbc_import_files['wbc-import-'.$x]['directory'] = $import['name'];

                        if ( !empty( $imported ) && is_array( $imported ) ) {
                            if ( array_key_exists( 'wbc-import-'.$x, $imported ) ) {
                                $this->wbc_import_files['wbc-import-'.$x]['imported'] = 'imported';
                            }
                        }

                        foreach ( $import['files'] as $file ) {
                            switch ( $file['name'] ) {
                            case 'content.xml':
                                $this->wbc_import_files['wbc-import-'.$x]['content_file'] = $file['name'];
                                break;

                            case 'theme-options.txt':
                            case 'theme-options.json':
                                $this->wbc_import_files['wbc-import-'.$x]['theme_options'] = $file['name'];
                                break;

                            case 'widgets.json':
                            case 'widgets.txt':
                                $this->wbc_import_files['wbc-import-'.$x]['widgets'] = $file['name'];
                                break;

                            case 'screen-image.png':
                            case 'screen-image.jpg':
                            case 'screen-image.gif':
                                $this->wbc_import_files['wbc-import-'.$x]['image'] = $file['name'];
                                break;
                            }

                        }

                        if ( !isset( $this->wbc_import_files['wbc-import-'.$x]['content_file'] ) ) {
                            unset( $this->wbc_import_files['wbc-import-'.$x] );
                            if ( $x > 1 ) $x--;
                        }

                    }
                    
                    // yunus edit - reset the x to what it was
                    if(isset($temp_x))
                    {
	                    $x = $temp_x;
	                    unset($temp_x);
                    }

                    $x++;
                }

            }

        }

        public function addImportFiles( $wbc_import_files ) {

            if ( !is_array( $wbc_import_files ) || empty( $wbc_import_files ) ) {
                $wbc_import_files = array();
            }

            $wbc_import_files = wp_parse_args( $wbc_import_files, $this->wbc_import_files );

            return $wbc_import_files;
        }

        public function ajax_importer() 
        {
            if ( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], "redux_{$this->parent->args['opt_name']}_wbc_importer" ) ) {
                die( 0 );
            }

            $this->start_importer();

            die();
        }


        public function start_importer() 
        {
            if ( isset( $_REQUEST['type'] ) && $_REQUEST['type'] == "import-demo-content" && array_key_exists( $_REQUEST['demo_import_id'], $this->wbc_import_files ) ) {

                $reimporting = false;

                if( isset( $_REQUEST['wbc_import'] ) && $_REQUEST['wbc_import'] == 're-importing'){
                    $reimporting = true;
                }

                $this->active_import_id = $_REQUEST['demo_import_id'];

                $import_parts         = $this->wbc_import_files[$this->active_import_id];

                $this->active_import = array( $this->active_import_id => $import_parts );

                $content_file        = $import_parts['directory'];
                $demo_data_loc       = $this->demo_data_dir.$content_file;


                if ( file_exists( $demo_data_loc.'/'.$import_parts['content_file'] ) && is_file( $demo_data_loc.'/'.$import_parts['content_file'] ) ) {

                    if ( !isset( $import_parts['imported'] ) || true === $reimporting ) {
                        include $this->extension_dir.'inc/init-installer.php';
                        $installer = new Radium_Theme_Demo_Data_Importer( $this, $this->parent );
                    }else {
                        echo esc_html__( "Content Already Imported", 'framework' );
                    }
                }
                

                die();
            }
            
            // ------------------------------------------------------
            //  yunus edit - remote import
            // ------------------------------------------------------
            elseif(isset( $_REQUEST['type'] ) && $_REQUEST['type'] == "import-demo-content" && preg_match('/\.zip/i', $_REQUEST['demo_import_id']))
            {
                // TEMP TEST - REMOVE BEFORE BETA CYCLE
                // $_REQUEST['type'] = 'import-demo-content';
                // $_REQUEST['demo_import_id'] = 'http://prostyler3:8888/wp-content/uploads/2017/05/test-template4.zip';
                // $_REQUEST['id'] = 'cbt-template_'.rand(10000,99999);
                
                cbt_apis::poll_start();
                $reimporting = false;
                
                global $wp_filesystem;
                
                $product = basename(get_template_directory());
                
                $key = trim(get_option('cbt_license_key'));

                $api = $this->api_url('');
                if($api == false)
                {
                    echo esc_html__( "Please activate your license first to access this feature", 'framework' );
                    die();
                }
                
                $url = $_REQUEST['demo_import_id'];

                if( isset($_REQUEST['takeaway']) && $_REQUEST['takeaway'] == 'true' )
                {
                    global $cbt_woocommerce_takeaway;
                    $cbt_woocommerce_takeaway->clone_importer($url);
                    die();
                }
                
                $new_name = $_REQUEST['id'];
                $temp_folder = $new_name.'_temp';
                $final_folder = $this->demo_data_dir.$new_name;
                $this->active_import_template_folder = $new_name;
                
                cbt_apis::poll('Downloading import file', 'important');
                $file = download_url($url, 1000);
                
                if ( is_wp_error( $file ) ) 
                {
                   $error_string = $file->get_error_message();
                   echo 'Error downloading file: '.$error_string;
                   die();
                }
                
                cbt_apis::poll('Unzipping import file', 'important');
                

                // delete existing dir
                $wp_filesystem->rmdir($this->demo_data_dir.$new_name, true);
                $wp_filesystem->rmdir($this->demo_data_dir.$new_name.'_temp', true);
                
                $unzip = unzip_file( $file, $this->demo_data_dir.$new_name.'_temp');
                if($unzip!==true)
                {
                    if ( is_wp_error( $unzip ) ) 
                    {
                        cbt_apis::poll('Trying alternative method for unzipping', 'important');
                        
                        add_filter('filesystem_method', array($this, '_return_direct'));
                        WP_Filesystem();
                        global $wp_filesystem;
                        $unzip = unzip_file( $file, $this->demo_data_dir.$new_name.'_temp');
                        
                        
                        if($unzip!==true)
                        {
                            if ( is_wp_error( $unzip ) ) 
                            {
                               $error_string = $unzip->get_error_message();
                               echo 'Error unzipping the file (code 2): '.$error_string;
                               die();
                            }
                            echo esc_html__( "Error unzipping the file (code 2)", 'framework' );
                            die();
                        }
                    }
                    else
                    {
                        echo esc_html__( "Error unzipping the file", 'framework' );
                        die();
                    }
                }

                $this->filesystem = $this->parent->filesystem->execute( 'object' );

                $folders = $this->filesystem->dirlist( $this->demo_data_dir.'/'.$temp_folder, false, false );
                
                // get first folder
                $folder_to_move = current(array_keys($folders));

                
                cbt_apis::poll('Moving import file');


                // move the folder outside of temp dir
                if(rename($this->demo_data_dir.$temp_folder.'/'.$folder_to_move, $final_folder)==false)
                {
                    echo esc_html__( "Error moving the file", 'framework' );
                    die();
                }


                
                // delete temp dir
                $wp_filesystem->rmdir($this->demo_data_dir.$new_name.'_temp', true);
                // delete temp downloaded file
                @unlink($file);
                
                $this->wbc_import_files = null;
                $this->getImports();
                

                $this->active_import_id = 'wbc-import-'.$new_name;
                

                $import_parts         = $this->wbc_import_files[$this->active_import_id];

                $this->active_import = array( $folder_to_move => $import_parts );
                
                //echo '<pre>'.print_r($this->active_import, TRUE).'</pre>';

                $content_file        = $import_parts['directory'];
                $demo_data_loc       = $this->demo_data_dir.$content_file;
                
                $_POST['imported_authors'] = array();
                $_POST['imported_authors'][0] = 'admin';
                $_POST['user_new'] = array();
                $_POST['user_new'][0] = sanitize_user(preg_replace('/\.zip/i','',basename($url)));


                if ( file_exists( $demo_data_loc.'/'.$import_parts['content_file'] ) && is_file( $demo_data_loc.'/'.$import_parts['content_file'] ) )
                {
                    global $cbt_importer;
                    $cbt_importer->set_import_id($this->active_import_id);
                    $cbt_importer->set_template_folder($this->active_import_template_folder);

                    include $this->extension_dir.'inc/init-installer.php';
                    $installer = new Radium_Theme_Demo_Data_Importer( $this, $this->parent );
                }
                else
                {
                    echo esc_html__( "File could not be found (".$demo_data_loc.'/'.$import_parts['content_file'].")", 'framework' );
                    die();
                }
    
                die();
            }
            else
            {
                    echo esc_html__( "Invalid package", 'framework' );
                    die();
            }

            die();
        }

        public static function get_instance() {
            return self::$instance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }

        function add_importer_section() {
            // Checks to see if section was set in config of redux.
            for ( $n = 0; $n < count( $this->parent->sections ); $n++ ) {
                if ( isset( $this->parent->sections[$n]['id'] ) && $this->parent->sections[$n]['id'] == 'wbc_importer_section' ) {
                    return;
                }
            }

            $wbc_importer_label = trim( esc_html( apply_filters( 'wbc_importer_label', __( 'Demo Importer', 'framework' ) ) ) );

            $wbc_importer_label = ( !empty( $wbc_importer_label ) ) ? $wbc_importer_label : __( 'Demo Importer', 'framework' );

            $this->parent->sections[] = array(
                'id'     => 'cbt_templates_v2_section',
                'title'  => 'Templates',
                'desc'   => '<p class="description">'. esc_html__( '', 'framework' ).'</p>',
                'class' => 'st-new-tag st-trigger-window-resize',
                'icon'   => 'fa fa-download',
                'fields' => array(
                    array(
                        'id'   => 'wbc_templates_importer_v2',
                        'remote'=>true,
                        'remote_api'=>$this->api_url('templates_v2'),
                        'type' => 'wbc_importer'
                    )
                )
            );

        }
        
        // ------------------------------------------------------
        //  yunus edit - api url
        // ------------------------------------------------------
        function api_url($api)
        {
			$product = basename(get_template_directory());
			
			$key = trim(get_option('cbt_license_key'));
			
			if(! $key)
			{
				return false;
			}
	
        	$url = 'http://members.prostylertheme.com/?'.$api.'_importer';
        	$url .= '&api_v=2';
            $url .= '&key='.$key;
        	$url .= '&product='.$product;
            $url .= '&v='.get_option('cbt_theme_version');
        	
        	return $url;
        }

    } // class
} // if

