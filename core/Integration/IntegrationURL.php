<?php


namespace rednaoeasycalculationforms\core\Integration;


use rednaoeasycalculationforms\core\db\core\OptionsManager;
use rednaoeasycalculationforms\core\Loader;
use WP_Query;

class IntegrationURL
{
    public static function PageURL($page)
    {
        return \admin_url('admin.php').'?page='.$page;
    }

    public static function AjaxURL()
    {
        return admin_url( 'admin-ajax.php' );
    }

    /**
     * @param $loader Loader
     */
    public static function PublicEntryURL($loader,$entryId,$reference)
    {
        $option=new OptionsManager();
        $pageId=$option->GetOption('entry_detail','');
        $url='';
        if($pageId==''||!($url=\get_permalink($pageId)))
        {
            $post = array(
                'post_content'   => '[rnentry]',
                'post_name'      => __('Entry Details'),
                'post_title'     => __('Details'),
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'ping_status'    => 'closed',
                'comment_status' => 'closed'
            );
            $pageId = wp_insert_post( $post );
            $option->SaveOptions('entry_detail',$pageId);
            $url=\get_permalink($pageId);
        }

        if(\strpos($url,'?')===false)
            $url.='?';
        else
            $url.='&';

        return $url.'ref='.$entryId.'__'.$reference;

    }


    public static function PreviewURL(){

        $query = new WP_Query(
            array(
                'post_type'              => 'rednao_forms_preview',
                'title'                  => 'AIO forms Preview',
                'post_status'            => 'all',
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'ignore_sticky_posts'    => true,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'orderby'                => 'post_date ID',
                'order'                  => 'ASC',
            )
        );

        if ( ! empty( $query->post ) ) {
            return get_permalink($query->post->ID);

        } else {

            $allposts = get_posts(array('post_type' => 'rednao_forms_preview', 'numberposts' => -1,'posts_per_page'=>999999,'post_status'=>'draft'));
            foreach ($allposts as $eachpost) {
                if($eachpost->post_type=='rednao_forms_preview')
                    wp_delete_post($eachpost->ID, true);
            }

             $post = array(
                'post_content' => '[rnformpreview]	',
                'post_name' => 'AIO forms Preview',
                'post_title' => 'AIO forms Preview',
                'post_status' => 'draft',
                'post_type' => 'rednao_forms_preview',
                'ping_status' => 'closed',
                'comment_status' => 'closed'
            );
            $page_id = wp_insert_post($post);


            $url = get_permalink($page_id);
            return $url;
        }


    }


}