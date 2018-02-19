<?php

/**
 * @file
 * commerce.php
 */

/**
 * Implements hook_form_FORM_ID_alter().
 */
function slow_form_commerce_cart_add_to_cart_form_alter(&$form, &$form_state, $form_id) {
  
  $node = menu_get_object();
  if ($node && $node->type == 'product'){
    if (isset($node->field_ref_cat['und'][0]['tid'])){
      $tid = $node->field_ref_cat['und'][0]['tid'];
      $model = taxonomy_term_load($tid);
      if (isset($model->field_size_guide['und'][0]['uri'])){
        $uri = $model->field_size_guide['und'][0]['uri'];
        $url = file_create_url($uri);

        $opt = array(
          'attributes' => array(
            'target' => '_blank',
          ),
          'html' => TRUE,
        );

        $form['line_item_fields']['size_guide'] = array(
          '#prefix' => '<div class="size-guide"><p class="text-bold">',
          '#suffix' => '</p></div>',
          '#markup' => l('<i class="fa fa-arrow-circle-right"></i> Guida alle taglie', $url, $opt),
          '#weight' => 6,
        );
      }
    }
  }
  // Inserire qui i link per i colori in modo che siano ordinati?

  //if (isset($form['product_id']['#value'])){
  //  dpm($form['product_id']['#value']);
  //}
  //if (isset($form['attributes']['field_ref_color']['#options'])){
  //  dpm($form);
  //  $colors = $form['attributes']['field_ref_color']['#options'];
  //  $selected = $form['attributes']['field_ref_color']['#default_value'];
  //  $ajax = $form['attributes']['field_ref_color']['#ajax'];
  //  $form['field_ref_colors'] = array(
  //    '#title' => 'Colore',
  //    '#type' => 'radios',
  //    '#options' => $colors,
  //    '#default_value' => $selected,
  //    '#ajax' => $ajax
  //  );
  //}  
}

/** 
 * implements hook_process_button()
 */
function slow_process_button(&$vars){
  // Cambio colore al bottone dell'aggiunta al carrello
  // Deve essere fatto qui perché bisogna vincere su bootstrap
  if (isset($vars['element']['#value']) && $vars['element']['#value'] == 'Aggiungi al carrello'){
    $vars['element']['#attributes']['class'] = array(
      'btn', 'btn-info',
    );  
  }

  if (isset($vars['element']['#value']) && $vars['element']['#value'] == 'Vai alla cassa'){
    $vars['element']['#attributes']['class'] = array(
      'btn', 'btn-primary',
    );  
  }
}

// ** CART **
// ----------

/**
 * Sostituisco il contenuto del carrello vuoto (blocco)
 */
function slow_commerce_cart_empty_block() {
  $text = '<i class="fa fa-shopping-cart fa-lg"></i> (0)';
  $opt = array(
    'html' => TRUE,
    'attributes' => array(
      'class' => array('a-cart'),
    ),
  );
  return l($text, 'cart', $opt);
}

/**
 * Themes an empty shopping cart page (pagina)
 */
function slow_commerce_cart_empty_page() {
  return '<div class="cart-empty-page text-center margin-b-4">' . t('Your shopping cart is empty.') . '</div>';
}

function slow_form_views_form_commerce_cart_form_default_alter(&$form, &$form_state, $form_id){
  foreach ($form['edit_delete'] as $row_id => $row) {
    if(isset($form['edit_delete'][$row_id]['#value'])){
      $form['edit_delete'][$row_id]['#attributes']['class'][] = 'btn-primary';
      $form['edit_delete'][$row_id]['#attributes']['value'] = t('Delete');
      $form['edit_delete'][$row_id]['#value'] = '&times;';
    }
  }
}

function slow_form_commerce_checkout_form_checkout_alter(&$form, $form_state, $form_id){

  //dpm($form);

  // Spedizione
  if (isset($form['customer_profile_shipping']['#title'])){
    //$form['customer_profile_shipping']['#title'] = t($form['customer_profile_shipping']['#title']);
    $form['customer_profile_shipping']['#type'] = 'container';
    $form['customer_profile_shipping']['#attributes']['class'][] = 'margin-b-2';
    $form['title'] = array(
      '#prefix' => '<h3 class="margin-t-0">',
      '#suffix' => '</h3><hr>',
      '#markup' => t($form['customer_profile_shipping']['#title']),
      '#weight' => -1,
    );
  }

  // Fatturazione
  if (isset($form['customer_profile_billing']['#title'])){
    $form['customer_profile_billing']['#title'] = t($form['customer_profile_billing']['#title']);
  }

  // Sposto e-mail dentro spedizione
  // if (isset($form['account'])){
  //   $form['customer_profile_shipping']['account'] = $form['account'];
  //   unset($form['account']);
  // }

  // Coupon
  // dpm($form);
  // if (isset($form['commerce_coupon']['#type'])){
  //   $form['commerce_coupon']['#type'] = 'container';
  // }
}

function slow_form_commerce_checkout_form_review_alter(&$form, $form_state, $form_id){
  
  //dpm($form);
  unset($form['help']);
  // Formatto l'help della review
  // $form['help'] = array(
  //   '#prefix' => '<div class="wrapper-help-review margin-b-1"><p class="small">',
  //   '#suffix' => '</p></div>',
  //   '#markup' => t('Review your order before continuing.'),
  // );

  // Tricks - modifico la renderizzazione da 'fieldset' a 'container'
  if (isset($form['cart_contents']['#type'])){
    $form['cart_contents']['#type'] = 'container';
    $form['cart_contents']['title'] = array(
      '#prefix' => '<h3 class="margin-t-0">',
      '#suffix' => '</h3><hr>',
      '#markup' => t('Order', array(), array('context' => 'a drupal commerce order')),
      '#weight' => -1,
    );
  }

  // Miglioro la visualizzazione del riepilogo
  if (isset($form['checkout_review']['review']['#data']['customer_profile_shipping']['data'])){
    $data = $form['checkout_review']['review']['#data']['customer_profile_shipping']['data'];
    $form['checkout_review']['review'] = array(
      'title' => array(
        '#prefix' => '<h3>',
        '#suffix' => '</h3><hr>',
        '#markup' => t('Shipping information'),  
      ),
      'data' => array(
        '#prefix' => '<div class="wrapper-review-data margin-b-1">',
        '#suffix' => '</div>',  
        '#markup' => $data,
      ),
    );
  }

  // Miglioro la visualizzazione delle modalità di pagamento
  if (isset($form['commerce_payment'])){
    $form['commerce_payment']['#type'] = 'container';
    $form['commerce_payment']['#attributes']['class'][] = 'margin-b-2';
    $form['commerce_payment']['title'] = array(
      '#prefix' => '<h3 class="margin-t-0">',
      '#suffix' => '</h3><hr>',
      '#markup' => t('Payment method'),
      '#weight' => -1,
    );
  }
}

function slow_form_commerce_checkout_form_complete_alter(&$form, $form_state, $form_id){
  $form['checkout_completion_message']['#prefix'] = '<div class="text-max-width text-center">';
  $form['checkout_completion_message']['#suffix'] = '</div>';

  $form['counter']['#prefix'] = '<div class="margin-t-2">';
  $form['counter']['#suffix'] = '</div>';
  $form['counter']['data'] = _countdown_create_big_counter();
  $form['counter']['#weight'] = 10;

  $order_id = arg(1);
  if ($order_id){
    $order = commerce_order_load_by_number($order_id);

    if ($order && isset($order->commerce_line_items['und'][0])){

      $data = _slow_check_items_types($order);

      // Se ci sono buoni regalo, ma non ci sono prodotti
      // Nascondo il conto alla rovescia
      if (!empty($data['gifts']) && empty($data['products'])){
        unset($form['counter']);
      }

      // Ci sono buoni sconto
      if (!empty($data['gifts'])){
        $form['gifts'] = _slow_create_gifts_content($order, $data['gifts']);
      }

      // Ci sono dei prodotti? magari possiamo poi anche elencarli
      if (!empty($data['products'])){
        $form['products_desc'] = array(
          '#prefix' => '<div class="wrapper-products-desc text-max-width">',
          '#suffix' => '</div>',
          'data' => _slow_get_taxonomy_text(204),
          '#weight' => 8,
        );
      }
    }
  }
  
  if (user_access('administer checkout')){
    $path = 'admin/commerce/config/checkout/form/pane/checkout_completion_message';
    $opt = array(
      'attributes' => array(
        'class' => array('btn', 'btn-default'),
      ),
    );
    $form['admin_link'] = array(
      '#prefix' => '<div class="text-center margin-t-1 margin-b-2">',
      '#suffix' => '</div>', 
      '#markup' => l('Modifica', $path, $opt),
    );
  }
}

function _slow_check_items_types($order){
  $data = array(
    'gifts' => array(),
    'products' => array(),
  );

  $items_id = $order->commerce_line_items['und'];
  $ids = array();
  foreach ($items_id as $key => $value) {
    $ids[$key] = $value['line_item_id'];
  }

  // Controllo le righe degli ordini per vedere se ci sono buoni regalo
  $products = array();
  $gift = array();
  $items = commerce_line_item_load_multiple($ids);
  if ($items){
    foreach ($items as $key => $item) {
      if (isset($item->commerce_product['und'][0]['product_id'])){
        $product_id = $item->commerce_product['und'][0]['product_id'];
        $product = commerce_product_load($product_id);
        if ($product->type == 'gift'){
          $data['gifts'][$product_id] = array(
            'entity' => $product,
            'quantity' => $item->quantity,
          );
        } else{
          $data['products'][$product_id] = array(
            'entity' => $product,
            'quantity' => $item->quantity,
          );
        }
      }
    }  
  }

  return $data;
}

function _slow_create_gifts_content($order, $gifts){
  $data = array(
    '#prefix' => '<div class="wrapper-gifts-desc text-max-width margin-b-2">',
    '#suffix' => '</div>',
    '#weight' => 5,
  );
  $data['desc'] = _slow_get_taxonomy_text(205);
  $data['list'] = _slow_get_gifts_list($order, $gifts);
  return $data;
}

function _slow_get_gifts_list($order, $gifts){  
  $data = array(
    '#prefix' => '<div class="coupon-list margin-v-1">',
    '#suffix' => '</div>',
    '#weight' => 2,
  );

  $cids = array();
  if (isset($order->field_ref_coupons['und'][0])){
    // I buoni esistono già, bisogna solo visualizzarli
    $coupons = $order->field_ref_coupons['und'];
    foreach ($coupons as $key => $coupon) {
      $cids[] = $coupon['target_id'];
    }
  } else {
    // I coupon non esistono ancora, bisogna generarli e linkarli all'ordine
    // Questa funziona ritorna i cid dentro un array
    $cids_t = _slow_create_coupons($order, $gifts);
    foreach ($cids_t as $key => $value) {
      $cids[] = $value['target_id'];
    }
  }

  $coupons = commerce_coupon_load_multiple($cids);

  $n = 0;
  foreach ($coupons as $key => $coupon) {

    $n++;

    $did = $coupon->commerce_discount_reference['und'][0]['target_id'];
    $discount = entity_load('commerce_discount', array($did));
    $first = array_keys($discount);
    $f = $first[0];
    $discount = $discount[$f];

    $opt = array(
      'query' => array(
        'code' => $coupon->code,
        'did' => $did,
      ),
    );

    $optb = $opt;

    $opt['html'] = TRUE;
    $opt['attributes'] = array(
      'class' => array(
        'coupon-link',
      ),
      'target' => '_blank',
    );

    $optb['attributes'] = array(
      'class' => array(
        'btn', 'btn-primary',
      ),
      'target' => '_blank',
    );

    //$text = '<span class="number">' . $n . '</span>' . $discount->label;
    $text = $discount->label;

    $data[$key] = array(
      '#prefix' => '<div class="coupon">',
      '#suffix' => '</div>',
      'title' => array(
        '#markup' => l($text, 'code/print', $opt),
      ),
      'downlad' => array(
        '#markup' => l('Scarica', '/code/print', $optb),
      ),
    );
  }
  return $data;
}

function _slow_create_coupons($order, $gifts){

  $slow_reference = array(
  //  25.00 € => ID Discount,
     '2500' => '20',
     '5000' => '21',
     '7500' => '22',
    '10000' => '22',
  );

  // Esempio di coupon corretto
  // $test = commerce_coupon_load('64');
  //dpm($test, 'esempio');

  $n = 0;

  // Per ogni buono regalo, creo un coupon relazionato allo sconto corretto
  // dopo di che devo aggiornare le referenze ai coupon nell'ordine
  foreach ($gifts as $key => $gift) {

    $entity = $gift['entity'];
    $quantity = $gift['quantity'];
    $price = $entity->commerce_price['und'][0]['amount'];

    $currency_code = $entity->commerce_price['und'][0]['currency_code'];

    for ($i=1; $i <= $quantity ; $i++) { 
      // Create the coupon
      $coupon = commerce_coupon_create('discount_coupon');
      $code = commerce_coupon_batch_generate_coupon_code('discount_coupon', 8);

      $coupon->code = $code;
      $coupon->uid = 1;

      // Collego il coupon allo sconto
      $target_id = $slow_reference[$price];
      $coupon->commerce_discount_reference['und'][0]['target_id'] = $target_id;

      // Set to single use
      $coupon->commerce_coupon_conditions['und'][0] = array(
        'condition_name' => 'commerce_coupon_usage_evaluate_usage',
        'condition_settings' => array(
          'max_usage' => '1',
          'condition_logic_operator' => NULL,
        ),
        'condition_negate' => 0
      );

      // save the coupon
      $result = commerce_coupon_save($coupon);

      // get the coupon code
      // Ora l'id del coupon c'è
      $cids[$n]['target_id'] = $coupon->coupon_id;

      $n++;
    }

    // Aggiorno l'ordine con le referenze appena generate
    $order->field_ref_coupons['und'] = $cids;
    commerce_order_save($order) ;
  }

  return $cids;
}

function _slow_get_taxonomy_text($tid){
  $term = taxonomy_term_load($tid);
  $description = field_view_field('taxonomy_term', $term, 'description_field', 'default');
  return $description;  
}

function slow_form_commerce_checkout_form_payment_alter(&$form, $form_state, $form_id){
  $form['spin'] = array(
    '#prefix' => '<div class="text-center margin-v-1">',
    '#suffix' => '</div>',
    '#markup' => '<i class="fa fa-refresh fa-2x fa-spin"></i>',
  );
}