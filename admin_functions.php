<?php
function mdx_admin_function(){
    add_menu_page(__('MDx主题', 'mdx'), __('MDx主题', 'mdx'), 'edit_themes', 'mdx_admin','mdx_display_sub_function','dashicons-hammer');
}
function mdx_add_admin(){
    add_submenu_page('mdx_admin', __('MDx主题 - 样式', 'mdx'), __('样式', 'mdx'), 'edit_themes', 'mdx_styles', 'mdx_display_sub_function_one');
    add_submenu_page('mdx_admin', __('MDx主题 - 功能', 'mdx'), __('功能', 'mdx'), 'edit_themes', 'mdx_functions', 'mdx_display_sub_function_two');
    add_submenu_page('mdx_admin', __('MDx主题 - 关于', 'mdx'), __('关于', 'mdx'), 'edit_themes', 'mdx_about', 'mdx_display_sub_function_three');
}
function mdx_display_sub_function(){
    echo '<h1>'.__('MDx主题', 'mdx').'</h1>';
}
function mdx_display_sub_function_one(){
    require_once('admin_style.php');
}
function mdx_display_sub_function_two(){
    require_once('admin_fn.php');
}
function mdx_display_sub_function_three(){
    if(function_exists('file_get_contents')){
        $opt1 = array(
            'http'=>array('method'=>"GET",'header'=>"User-Agent: MDxThemeinWordPress\r\n")
        );
        $contexts1 = stream_context_create($opt1);
        $mdx_data = json_decode(file_get_contents('https://update.dlij.site/mdx/info.json', false, $contexts1));
        $mdx_now_version = $mdx_data->version;
    }else{
        $mdx_now_version = '版本号获取失败';
    }

echo '<div class="wrap">
<h1>'.__('MDx主题 - 关于', 'mdx').'</h1>
<br>
<h2 style="font-size:19px;">'.__('感谢使用MDx主题', 'mdx').'</h2>
<p style="font-size:15px;">'.__('我是Axton Yao，这个主题由我开发。我的网站是', 'mdx').'<a href="https://flyhigher.top" target="_blank">flyhigher.top</a></p>
<p style="font-size:15px;">'.__('对主题有任何疑问，建议先查阅 ', 'mdx').'<a href="https://flyhigher.top/mdx-docs-cn" target="_blank">'.__('主题文档', 'mdx').'</a></p>
<p style="font-size:15px;">'.__('这个主题的诞生离不开MDUI，这是一个优秀的前端框架项目，你可以在他们的官方网站上了解更多：', 'mdx').'<a href="https://mdui.org" target="_blank">mdui.org</a></p>
<br>
<p style="font-size:12px;">'.__('当前版本 v', 'mdx').get_option('mdx_version').'</p>
<p style="font-size:12px;">'.__('最新版本 v', 'mdx').$mdx_now_version.'</p>
<br>
<p style="font-size:17px;"><strong>'.__('这款主题献给Demi Zhou', 'mdx').'</strong></p>
</div>';
}
function remove_submenu() {
    remove_submenu_page('mdx_admin', 'mdx_admin');
}
add_action('admin_menu', 'mdx_admin_function');
add_action('admin_menu', 'mdx_add_admin');
add_action('admin_menu','remove_submenu');

//初始化主题设置，只有第一次激活主题时调用
function mdx_init_theme(){
    if(!get_option('mdx_first_init')){
        //用途仅为统计安装量 mdx_key为发送请求时间戳的md5值 mdx_first_init不会在除此外的任何地方被调用 请保持克制不要恶意访问接口
        if(function_exists('file_get_contents')){
            $opt = array(
                'http'=>array('method'=>"GET",'header'=>"User-Agent: MDxThemeinWordPress\r\n")
            );
            $contexts = stream_context_create($opt);
            $mdx_token = file_get_contents('https://mdxupdate.flyhigher.top/mdx/gettoken/', false, $contexts);
            $mdx_key = file_get_contents('https://mdxupdate.flyhigher.top/mdx/getkey/index.php?hostname='.$_SERVER['HTTP_HOST'].'&token='.md5($mdx_token), false, $contexts);
            update_option('mdx_first_init', md5($mdx_key));
        }else{
            add_action('admin_notices', 'mdx_cant_notice');
            update_option('mdx_first_init', 'false');
        }

        update_option('mdx_version', '1.3');
        
        update_option('mdx_night_style', 'true');
        update_option('mdx_auto_night_style', 'true');
        update_option('mdx_notice', '');
        update_option('mdx_open_side', 'true');
        update_option('mdx_img_box', 'true');
        update_option('mdx_read_pro', 'true');
        update_option('mdx_auto_scroll', 'false');
        update_option('mdx_load_pro', 'true');
        update_option('mdx_real_search', 'false');
        update_option('mdx_seo_key', '');
        update_option('mdx_auto_des', 'true');
        update_option('mdx_seo_des', '');

        update_option('mdx_styles', 'indigo');
        update_option('mdx_styles_hex', '#3f51b5');
        update_option('mdx_styles_act', 'pink');
        update_option('mdx_act_hex', '#ff4081');
        update_option('mdx_chrome_color', 'true');
        update_option('mdx_title_bar', 'false');
        update_option('mdx_default_style', '1');
        update_option('mdx_post_style', '0');
        update_option('mdx_index_img', get_bloginfo("template_url").'/img/def_index.jpg');
        update_option('mdx_side_img', get_bloginfo("template_url").'/img/def_side.jpg');
        update_option('mdx_side_info', 'false');
        update_option('mdx_side_head', '');
        update_option('mdx_side_name', '');
        update_option('mdx_side_more', '');
        update_option('mdx_index_say', 'Hello, MDx!');
        update_option('mdx_logo', '');
        update_option('mdx_safari', 'false');
        update_option('mdx_svg', '');
        update_option('mdx_svg_color', '');
        update_option('mdx_footer_say', 'Hello, MDx!');
        update_option('mdx_footer', '');
    }
    add_action('admin_notices', 'mdx_custom_admin_notice');
}
add_action('after_switch_theme', 'mdx_init_theme');
function mdx_custom_admin_notice() {?>
       <div class="notice notice-success is-dismissible">
           <p><?php _e('MDx主题现已激活，你可以前往<a href="admin.php?page=mdx_styles">主题设置页面</a>对主题进行个性化定制。', 'mdx'); ?></p>
       </div>
<?php }
function mdx_cant_notice() {?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('由于你的PHP配置问题，MDx未能成功获取密钥，但这不影响你正常使用MDx主题。', 'mdx'); ?></p>
    </div>
<?php }
?>