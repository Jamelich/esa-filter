<?php

/**
 * Plugin Name: ESA filter
 * Description: Фильтрация товаров по категориям
 * Plugin URI:  https://wp-prog.ru
 * Version: 1.0.0
 * Author: Сергей Емельянов
 * Author URI: https://wp-prog.ru
 * Text Domain: esa-filter
 */

add_action('wp_enqueue_scripts', 'add_filter_script', 25);
function add_filter_script()
{
    wp_enqueue_script('filter', plugins_url('/esa-filter/assets/js/filter.js'), array());
    wp_enqueue_style(
        'style',
        plugins_url('/esa-filter/assets/css/style.css'),
        array()
    );
}

// фильтр - start
// wp_localize_script('filter', 'true_obj', array('ajaxurl' => admin_url('admin-ajax.php')));

add_action('wp_ajax_myfilter', 'true_filter_function');
add_action('wp_ajax_nopriv_myfilter', 'true_filter_function');

add_action('wp_ajax_outauto', 'outauto_function');
add_action('wp_ajax_nopriv_outauto', 'outauto_function');



function true_filter_function()
{

    $args = array(
        'orderby' => 'date', // сортировка по дате у нас будет в любом случае (но вы можете изменить/доработать это)
        'order'    => $_POST['date'] // ASC или DESC
    );

    // для таксономий
    if (isset($_POST['categoryfilter'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $_POST['categoryfilter'],
                // 'orderby' => 'date',
                // 'order' => 'ASC'
            ),
            // 'post_type' => 'product',
            // 'posts_per_page' => -1,
            // 'showposts' => '5',

        );
    }

    /*

    // создаём массив $args[ 'meta_query' ] если указана хотя бы одна цена или отмечен чекбокс
    if (
        isset($_POST['cena_min'])
        || isset($_POST['cena_max'])
        || isset($_POST['featured_image']) && 'on' == $_POST['featured_image']
    ) {
        $args['meta_query'] = array('relation' => 'AND');
    }

    // условие 1: цена больше $_POST[ 'cena_min' ]
    if (isset($_POST['cena_min'])) {
        $args['meta_query'][] = array(
            'key' => 'cena',
            'value' => $_POST['cena_min'],
            'type' => 'numeric',
            'compare' => '>'
        );
    }

    // условие 2: цена меньше $_POST[ 'cena_max' ]
    if (isset($_POST['cena_max'])) {
        $args['meta_query'][] = array(
            'key' => 'cena',
            'value' => $_POST['cena_max'],
            'type' => 'numeric',
            'compare' => '<'
        );
    }

    // условие 3: миниатюра имеется
    if (isset($_POST['featured_image']) && 'on' == $_POST['featured_image']) {
        $args['meta_query'][] = array(
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        );
    }
    */

    query_posts($args);
    // $args = array(
    //     'product_category_id' => array( 17, 23 ),
    // );
    // $products = wc_get_products( $args );

    echo '<div class="woocommerce columns-4">';
    woocommerce_product_loop_start();

    if (have_posts()) {
        $n = 0;
        while (have_posts()) : the_post();
            // тут вывод шаблона поста, например через get_template_part()
            // echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a><br>';
            // wc_get_template_part( 'content', 'product' );+
            $n++;
            do_action('woocommerce_shop_loop');
            wc_get_template_part('woocommerce/content-product');
        endwhile;
        echo '<br>' . $n;
    } else {
        do_action('woocommerce_no_products_found');
        echo 'Ничего не найдено';
    }
    woocommerce_product_loop_end();
    echo '</div>';
    die();
}


function esa_filter_add_shortcode($atts)
{
    $esa_html = '';
    // $esa_html .= '<form action="" method="POST" id="filter">';
    // if ($terms = get_terms(array('taxonomy' => 'product_cat', 'orderby' => 'name'))) {
    //     $esa_html .= '<select name="categoryfilter"><option>Выберите категорию...</option>';
    //     foreach ($terms as $term) {
    //         $esa_html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
    //     }
    //     $esa_html .= '</select>';
    // }
    // $esa_html .= '<button id="esafilter_button">Применить фильтр</button><input type="hidden" name="action" value="myfilter">';
    // $esa_html .= '</form>';

    // if ($terms = get_terms(array('taxonomy' => 'product_cat', 'orderby' => 'date', 'order' => 'DESC'))) {
    // if ($terms = get_terms(array('taxonomy' => 'product_cat', 'orderby' => 'term_id', 'order' => 'DESC'))) {
    if ($terms = get_terms(array('taxonomy' => 'product_cat', 'include' => array(45, 33, 21, 35, 38, 39, 34, 37, 41, 42, 43, 40, 44), 'orderby' => 'include'))) {
        $esa_html .= '<div class="wrap_esa_filter">';
        $esa_html .= '<ul class="list_filter">';
        foreach ($terms as $term) {
            // $esa_html .= '<li onclick="get_product(' . $term->term_id . ')">' . $term->name . '</li>';
            $esa_html .= '<li class="req_prod" data-id="' . $term->term_id . '">' . $term->name . '</li>';
        }
        $esa_html .= '</ul>';
    }

    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => 45,
        ),
        // 'posts_per_page' => -1
        // 'showposts' => '5',


    );
    query_posts($args);
    $output = '';
    if (have_posts()) {
        ob_start();
        // $n = 0;
        echo '<div class="woocommerce columns-4">';
        woocommerce_product_loop_start();
        while (have_posts()) : the_post();
            // $n++;
            do_action('woocommerce_shop_loop');
            wc_get_template_part('woocommerce/content-product');
        endwhile;
        woocommerce_product_loop_end();
        echo '</div>';
        // echo $n;
        $output = ob_get_contents(); // всё, что вывели, окажется внутри $output
        ob_end_clean();
    } else {
        do_action('woocommerce_no_products_found');
        echo 'Ничего не найдено';
    }

    $esa_html .= '<div id="response">' . $output . '</div>';
    $esa_html .= '</div>';
    return $esa_html;
}

add_shortcode('esa-filter', 'esa_filter_add_shortcode');


// Фильтрация авто в зависимости от веса
function outauto_function()
{
    // echo $_POST['weight_auto'] . '<br>';
    $array_auto = cfs()->get('tracks', 129);


    if ($_POST['weight_auto'] <= 4) {
        $key = 1;
        $hours = array(9, 10, 11, 12, 13, 14, 15, 17, 18);
    }

    if ($_POST['weight_auto'] > 4 && $_POST['weight_auto'] <= 7) {
        $key = 2;
        $hours = array(9, 10, 11, 13, 15, 17, 18);
    }

    if ($_POST['weight_auto'] > 7 && $_POST['weight_auto'] <= 10) {
        $key = 3;
        $hours = array(9, 11, 13, 15, 17, 18);
    }

    if ($_POST['weight_auto'] > 10 && $_POST['weight_auto'] <= 15) {
        $key = 4;
        $hours = array(9, 11, 13, 15, 17, 18);
    }

    if ($_POST['weight_auto'] > 15 && $_POST['weight_auto'] <= 20) {
        $key = 5;
        $hours = array(9, 11, 13, 15, 17, 18);
    }

    if ($_POST['weight_auto'] > 20 && $_POST['weight_auto'] <= 30) {
        $key = 6;
        $hours = array(9, 11, 13, 15, 17, 18);
    }

    $id_auto = $array_auto[$key]['id_calendar'];

?>
    <div class="auto" id="a<?php echo $key; ?>">
        <input class="w_auto" type="hidden" value="<?php echo $array_auto[$key]['load_capacity']; ?>">
        <img class="image_car" src="<?php echo $array_auto[$key]['image_car']; ?>">
        <div class="info">
            <span>Грузоподъемность: <?php echo $array_auto[$key]['load_capacity']; ?> t</span>
            <span class="w_price price0"><?php echo $array_auto[$key]['price_0']; ?></span>
            <span class="w_price price1"><?php echo $array_auto[$key]['price_1']; ?></span>
            <span class="w_price price2"><?php echo $array_auto[$key]['price_2']; ?></span>
            <span class="w_price price3"><?php echo $array_auto[$key]['price_3']; ?></span>
            <span class="w_price price4"><?php echo $array_auto[$key]['price_4']; ?></span>
        </div>
        <p>Тут описание машины</p>
    </div>

    <?php

    $period = new DatePeriod(
        new DateTime(date('Y-m-d')),
        new DateInterval('P1D'),
        new DateTime(date("Y-m-d H:i:s", strtotime("+2 month")))
    );

    $dates = array();
    foreach ($period as $value) {
        if ($value->format('N') != 6 && $value->format('N') != 7)
            $dates[] = $value->format('Y-m-d');
    }

    // echo '<pre>';
    // print_r($dates);
    // echo '</pre>';

    // echo $id_auto . '<br>';

    global $wpdb;
    // формируем календарь. сканируем календарь. если записи нет - добавляем
    foreach ($dates as $esa_date) {
        foreach ($hours as $hour) {
            $dtime = date($hour . ':00:00');
            $results_item = $wpdb->get_results("SELECT * FROM esa_calendar where `id_auto`=$id_auto and `date_b`='$esa_date' and `time_b`='$dtime'");
            if (!$results_item) {
                $wpdb->insert(
                    'esa_calendar',
                    array(
                        'id_auto' => $id_auto,
                        'date_b' => $esa_date,
                        'time_b' => date($hour . ':00:00'),
                        'status' => 'opened'
                    ),
                );
            }
        }
    }

    // if (!$results) {
    //     foreach ($dates as $esa_date) {
    //         foreach ($hours as $hour) {
    //             $wpdb->insert(
    //                 'esa_calendar',
    //                 array(
    //                     'id_auto' => $id_auto,
    //                     'date_b' => $esa_date,
    //                     'time_b' => date($hour . ':00:00'),
    //                     'status' => 'opened'
    //                 ),
    //             );
    //         }
    //     }
    // }

    // выводим календарь
    $results = $wpdb->get_results("SELECT * FROM esa_calendar where id_auto=$id_auto"); ?>
    <div class="wrapper_calendar owl-carousel owl-theme">
        <?php foreach ($dates as $item_date) {
            if (date_create($item_date)->Format('N') != 6 && date_create($item_date)->Format('N') != 7) {
        ?>
                <div class="wrap_calendar_item">
                    <h3><?php echo $item_date; ?></h3>
                    <h3><?php echo __(date_create($item_date)->Format('l'), 'esa-filter'); ?></h3>
                    <div class="wrap_calendar_day">
                        <?php foreach ($results as $item) {
                            if ($item->date_b === $item_date) {
                                $esa_time = date_create($item->time_b)->Format('H:i'); ?>
                                <div class="wrap_time">
                                    <?php if ($item->status == 'opened') { ?>
                                        <input onclick="sel_time(this)" class="timeslot" type="radio" data-day="<?php echo $item->date_b; ?>" name="timeslot" data-calendar="<?php echo $id_auto; ?>" value="<?php echo $esa_time; ?>">
                                    <?php } else { ?>
                                        <input type="radio" value="<?php echo $esa_time; ?>" disabled="disabled">
                                    <?php } ?>
                                    <span><?php echo $esa_time; ?></span>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
        <?php }
        } ?>
    </div>

    <script>
        // jQuery(".owl-carousel").owlCarousel({
        //     nav: true,
        //     dots: true,
        //     items: 5,
        //     margin: 10
        // });
    </script>
    <!-- <script>
        jQuery(".owl-carousel").owlCarousel({
            // loop: true,
            nav: true,
            dots: true,
            items: 5,
            margin: 10
            // stagePadding: 100
            // autoWidth: true
            // autoHeight: true
        });
    </script> -->

<?php die();
}
