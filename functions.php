<?php
function add_comment_rating_field() {
    // Enqueue the stylesheet and script files
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'comment-rating-style', 'https://myaccentway.com/rating/comment-rating.css' );
    wp_enqueue_script( 'comment-rating-script', 'https://myaccentway.com/rating/comment-rating.js', array('jquery'), '', true );

    echo '<p class="comment-form-rating">
          <label for="rating">' . __('Rating') . '</label>
          <span class="star-rating">
            <input type="radio" name="rating" value="5"><span class="dashicons dashicons-star-filled"></span>
            <input type="radio" name="rating" value="4"><span class="dashicons dashicons-star-filled"></span>
            <input type="radio" name="rating" value="3"><span class="dashicons dashicons-star-filled"></span>
            <input type="radio" name="rating" value="2"><span class="dashicons dashicons-star-filled"></span>
            <input type="radio" name="rating" value="1"><span class="dashicons dashicons-star-filled"></span>
          </span>
        </p>';
}

add_action( 'comment_form_logged_in_after', 'add_comment_rating_field' );
add_action( 'comment_form_after_fields', 'add_comment_rating_field' );



//Save the star rating field value:

function save_comment_rating( $comment_id ) {
    $rating = intval( $_POST['rating'] );
    add_comment_meta( $comment_id, 'rating', $rating );
}
add_action( 'comment_post', 'save_comment_rating' );



//Display the star rating value in the comments loop:

function display_comment_rating( $comment_text, $comment ) {
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    if ( $rating ) {
        $stars = '';
        for ( $i = 1; $i <= $rating; $i++ ) {
            $stars .= '<i class="dashicons dashicons-star-filled"></i>';
        }
        $comment_text .= '<div class="comment-rating">' . $stars . '</div>';
    }
    return $comment_text;
}

add_filter( 'comment_text', 'display_comment_rating', 10, 2 );

	
add_filter( 'comment_form_default_fields', 'add_review_city_field' );

function add_review_city_field( $fields ) {
    $fields['city'] = '<p class="comment-form-city"><label for="city">' . __( 'City', 'woocommerce' ) . '</label> <input id="city" name="city" type="text" size="30" /></p>';
    return $fields;
}


add_action( 'woocommerce_comment_after_save_comment', 'save_review_city_field' );

function save_review_city_field( $comment ) {
    if ( isset( $_POST['city'] ) ) {
        $city = sanitize_text_field( $_POST['city'] );
        add_comment_meta( $comment->comment_ID, 'City', $city );
    }
}


add_action( 'woocommerce_review_before_comment_meta', 'display_review_city_meta' );

function display_review_city_meta( $comment ) {
    $city = get_comment_meta( $comment->comment_ID, 'City', true );
    if ( $city ) {
        echo '<p><strong>' . __( 'City:', 'woocommerce' ) . '</strong> ' . $city . '</p>';
    }
}


add_action('admin_head', 'my_custom_notification_new_name');
function my_custom_notification_new_name() {
  echo '<style>
  
 .notice.notice-info.th_email_customizer_for_woocommerce_pro_admin_notice.thpl_license_notice{
	display:none !important;
}

  </style>';
}




add_filter('acf/format_value/type=textarea', 'wp_kses_post', 10, 3);




function add_checkout_text() {
  echo '<p class="instructions"> <span style="color: red;">CLICK THE BUTTON ONLY ONCE.</span> <br>
  Processing your order may take a few moments. <br>
  <span style="color: red;">Do NOT</span> refresh your browser or use your back button as duplicate orders may result</p>';
}
add_action( 'woocommerce_checkout_after_terms_and_conditions', 'add_checkout_text' );









add_action( 'woocommerce_thankyou', 'redirect_after_purchase' );
function redirect_after_purchase( $order_id ) {
    // get the order object
    $order = wc_get_order( $order_id );
    
    // check if the order contains a specific product
    $product_id = 1067; // change to the ID of the product you want to check
    $has_product = false;
    foreach( $order->get_items() as $item_id => $item ) {
        if ( $item->get_product_id() === $product_id ) {
            $has_product = true;
            break;
        }
    }
    
    // redirect to a specific page if the order contains the specific product
    if ( $has_product ) {
        wp_redirect( 'https://myaccentway.com/thank-you-for-purchase/' );
        exit;
    }
}



add_filter( 'woocommerce_email_styles', 'bbloomer_add_css_to_completed_order_email', 9999, 2 );

function bbloomer_add_css_to_completed_order_email( $css, $email ) {
   if ( $email->id == 'customer_completed_order' ) {
      $css .= '
         tr td:nth-child(2) {
            border-image: linear-gradient(#BB286F, #fff) 5 !important;
    border-width: 4px !important;
    border-style: solid !important;
         }
      ';
   }
   return $css;
}







//add reviews count to single session 
//
function product_reviews_count_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => '',
    ), $atts, 'product_reviews_count' );

    if ( empty( $atts['id'] ) ) {
        return '';
    }

    $product = wc_get_product( $atts['id'] );

    if ( ! $product ) {
        return '';
    }

    $count = $product->get_review_count();

    return $count;
}
add_shortcode( 'product_reviews_count', 'product_reviews_count_shortcode' );
   




//change title description title of product tabs to consultation details
function change_product_description_tab_for_consultation_product( $tabs ) {
   global $product;
   $product_id = $product->get_id();
   if ( $product_id == 1067 ) { // replace 123 with the ID of the product you want to target
      $tabs['description']['title'] = __( 'Consultation Details', 'woocommerce' );
   }
   return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'change_product_description_tab_for_consultation_product', 98 );



function change_product_description_tab_for_session_product( $tabs ) {
   global $product;
   $product_id = $product->get_id();
   if ( $product_id == 7399 ) { // replace 123 with the ID of the product you want to target
      $tabs['description']['title'] = __( 'Session Details', 'woocommerce' );
   }
   return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'change_product_description_tab_for_session_product', 98 );




//////////////////////////////////////////////////////



// woocoomerce review display name
add_filter('get_comment_author', 'comments_filter_uprise', 10, 1);

function comments_filter_uprise( $author = '' ) {
    $comment = get_comment( $comment_author_email );

    if ( !empty($comment->comment_author_email) ) {
        if (!empty($comment->comment_author_email)){
            $user=get_user_by('email', $comment->comment_author_email);
			$author = trim($comment->comment_author);
			$last_name = (strpos($author, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $author);
			$first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $author ) );
			$add_last_name_first_letter = $first_name === '' ? '' : ' ' . esc_html(substr($last_name,0,1));
			$add_dot = (substr($last_name,0,1) === '' or $first_name === '') ? '' : '.';
			$add_S = $first_name === 'Dr. Alex'? esc_html( substr( "'s", 0, 2) ) : $add_last_name_first_letter . $add_dot;
			$author = $first_name === '' ? $last_name . $add_S : $first_name . $add_S;
        } else {
            $user = get_user_by( 'email', $comment->comment_author_email );
            $author = $user->first_name;
        }
    } else {
        $user=get_userdata($comment->user_id);
        $author=substr($user->first_name,0,1).' '. $user->last_name.'';
        $author = $comment->comment_author;
    }
    return $author;
}


//////////////////////////////////////////////////////




//add more fields to woocommerce review form

add_filter( 'comment_form_defaults', 'render_pros_cons_fields_for_anonymous_users', 10, 1 );
add_action( 'comment_form_top', 'render_pros_cons_fields_for_authorized_users' );
function get_pros_cons_fields_html() {
	ob_start();
	?>

<div class="pcf-container">
		<h3 class="your-information">Your Information</h3>
		<div class="pcf-field-content">
			<div class="pcf-field-container">
<!-- 				<img src="https://myaccentway.com/staging/wp-content/uploads/2023/03/Group-1000002136.png" alt="image"> -->
				<input placeholder="First Name" id="first-name" name="first-name" type="text" class="name-field">
			</div>

			<div class="pcf-field-container">
				<input placeholder="Last Name" id="last-name" name="last-name" type="text" class="name-field">
			</div>
		</div>

		<div class="pcf-field-container">
			<input placeholder="Email" id="email" name="email" type="email" class="email-field">
		</div>

		<div class="pcf-field-content">
			<div class="pcf-field-container ct">
				<input placeholder="City" id="city" name="city" type="text">
			</div>

			<div class="pcf-field-container ct">
				<select id="country" placeholder="Country" name="country" style="color: #B0B0B0;">
                <option value=" ">Country</option>
                <option value="Afghanistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cabo Verde">Cabo Verde</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
<option value="Congo, Republic of the">Congo, Republic of the</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote d'Ivoire">Cote d'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Eswatini">Eswatini</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Greece">Greece</option>
<option value="Grenada">Grenada</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Honduras">Honduras</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Kosovo">Kosovo</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia">Micronesia</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montenegro">Montenegro</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherlands">Netherlands</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="North Korea">North Korea</option>
<option value="North Macedonia">North Macedonia</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Qatar">Qatar</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option value="Saint Lucia">Saint Lucia</option>
<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="South Korea">South Korea</option>
<option value="South Sudan">South Sudan</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Timor-Leste">Timor-Leste</option>
<option value="Togo">Togo</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City">Vatican City</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Yemen">Yemen</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>



					<!-- add more countries as needed -->
				</select>
			</div>
		</div>
		
		<div class="pcf-field-content">
				<div class="pcf-field-container">
					<input placeholder="State" id="state" name="state" type="text">
				</div>

				<div class="pcf-field-container">
					<input placeholder="Zip Code" id="zip-code" name="zip-code" type="text">
				</div>			
		</div>		
	</div>

		<div class="esl">
			<div class="pcf-field-container">
				<label>
					<input id="esl" type="checkbox" name="esl" value="ESL (English as a Second Language) Classes"> ESL (English as a Second Language) Classes
				</label>
			</div>

			<div class="pcf-field-container">
				<label>
					<input id="accent-reduction" type="checkbox" name="accent-reduction" value="American Accent Training"> American Accent Training
				</label>
			</div>
		</div>
	<?php
	return ob_get_clean();
}

function render_pros_cons_fields_for_authorized_users() {
	if ( ! is_product() || ! is_user_logged_in() ) {
		return;
	}
	
	echo get_pros_cons_fields_html();
}

function render_pros_cons_fields_for_anonymous_users( $defaults ) {
	if ( ! is_product() || is_user_logged_in() ) {
		return;
	}
	
	$defaults['comment_notes_before'] .= get_pros_cons_fields_html();
	
	return $defaults;
}

//save data
add_action( 'comment_post', 'save_additional_fields', 10, 3 );
function save_additional_fields( $comment_id, $approved, $commentdata ) {
	// The fields are not required, so we have to check if they're not empty
	$first_name = isset( $_POST['first-name'] ) ? $_POST['first-name'] : '';
	$last_name = isset( $_POST['last-name'] ) ? $_POST['last-name'] : '';
	$email = isset( $_POST['email'] ) ? $_POST['email'] : '';
	$city = isset( $_POST['city'] ) ? $_POST['city'] : '';
	$country = isset( $_POST['country'] ) ? $_POST['country'] : '';
	$state = isset( $_POST['state'] ) ? $_POST['state'] : '';
	$zip_code = isset( $_POST['zip-code'] ) ? $_POST['zip-code'] : '';
	$esl_english = isset( $_POST['esl'] ) ? $_POST['esl'] : '';
	$accent_reduction = isset( $_POST['accent-reduction'] ) ? $_POST['accent-reduction'] : '';
	
	
	// Spammers and hackers love to use comments to do XSS attacks.
	// Don't forget to escape the variables
	update_comment_meta( $comment_id, 'first-name', esc_html( $first_name ) );
	update_comment_meta( $comment_id, 'last-name', esc_html( $last_name ) );
	update_comment_meta( $comment_id, 'email', esc_html( $email ) );
	update_comment_meta( $comment_id, 'city', esc_html( $city ) );
	update_comment_meta( $comment_id, 'country', esc_html( $country ) );
	update_comment_meta( $comment_id, 'state', esc_html( $state ) );
	update_comment_meta( $comment_id, 'zip-code', esc_html( $zip_code ) );
	update_comment_meta( $comment_id, 'esl', esc_html( $esl_english ) );
	update_comment_meta( $comment_id, 'accent-reduction', esc_html( $accent_reduction ) );
}





	//display data
	add_filter( 'comment_text', 'display_additional_comment_fields', 10, 1 );
	function display_additional_comment_fields( $text ) {
		// We don't want to modify a comment's text in the admin, and we don't need to modify the text of blog post commets
		if ( is_admin() || ! is_product() ) {
			return $text;
		}
		
		$first_name = get_comment_meta( get_comment_ID(), 'first-name', true );
		$last_name = get_comment_meta( get_comment_ID(), 'last-name', true );
		$email = get_comment_meta( get_comment_ID(), 'email', true );
		$city = get_comment_meta( get_comment_ID(), 'city', true );
		$country = get_comment_meta( get_comment_ID(), 'country', true );
		$state = get_comment_meta( get_comment_ID(), 'state', true );
		$zip_code = get_comment_meta( get_comment_ID(), 'zip-code', true );
		$esl_english = get_comment_meta( get_comment_ID(), 'esl', true);
		$accent_reduction = get_comment_meta( get_comment_ID(), 'accent-reduction', true);
		
// 		$first_name_html = '<div class="acf-row"><b>First Name: </b>' . esc_html( $first_name ) . '</div>';
// 		$last_name_html = '<div class="acf-row"><b>Last Name: </b>' . esc_html( $last_name ) . '</div>';	
// 		$email_html = '<div class="acf-row"><b>Email: </b>' . esc_html( $email ) . '</div>';	
		$city_html = '<div class="acf-row">' . esc_html( $city ) . ',</div>';
		$state_html = '<div class="acf-row">' . esc_html( $state ) . ',</div>';
		$country_html = '<div class="acf-row">' . esc_html( $country ) . '</div>';		
		$zip_code_html = '<div class="acf-row">' . esc_html( $zip_code ) . "<br />" . '</div>';
		$esl_code_html = '<div class="acf-row">' . esc_html( $esl_english ) . "<br />" . '</div>';
		$accent_reduction_html = '<div class="acf-row">' . esc_html( $accent_reduction ) . "<br />" . '</div>';
		
		$updated_text = '<p class="author_name">' . 'Feedback' . '</p>' . '<div class="description-acf">' . $first_name_html . $last_name_html . $email_html . $city_html . $state_html . $country_html  . $zip_code_html . '</div>' . '<div class="reduction-acf">' . $esl_code_html . $accent_reduction_html . '</div>' . $text;
		
		return $updated_text;
	}
	



add_filter( 'woocommerce_product_review_comment_form_args', 'custom_review_comment_form_args' );

function custom_review_comment_form_args( $comment_form_args ) {
	
				if ( wc_review_ratings_enabled() ) {
					$comment_form_args['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
					</select></div>';
				}
    $comment_form_args['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Your review', 'woocommerce' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __( 'Your Review', 'woocommerce' ) . '"></textarea></p>';
    return $comment_form_args;
}













add_filter( 'woocommerce_checkout_fields', 'custom_checkout_fields' );
function custom_checkout_fields( $fields ) {
    // Get the current cart
    $cart = WC()->cart->get_cart();
    
    // Check if a specific product is in the cart
    $product_found = false;
    foreach ( $cart as $cart_item_key => $cart_item ) {
        if ( $cart_item['product_id'] == 7399 ) { // replace 123 with the ID of your product
            $product_found = true;
            break;
        }
    }
    
    // Modify the checkout fields array for the specific product
    if ( $product_found ) {
        $fields['order']['order_comments']['type'] = 'select';
        $fields['order']['order_comments']['options'] = array(
            '' => 'Select a session',
            'Session 1. American Unvoiced [t] & Voiced [d] Phonemes + Voicing [t̬], like in “Water”' => 'Session 1. American Unvoiced [t] & Voiced [d] Phonemes + Voicing [t̬], like in “Water”',
            'Session 2. American Voiced [r] & Voiced [w] Phonemes' => 'Session 2. American Voiced [r] & Voiced [w] Phonemes',
            'Session 3. American Unvoiced [s] & Voiced [z] Phonemes' => 'Session 3. American Unvoiced [s] & Voiced [z] Phonemes',
            'Session 4. American Unvoiced [th] Phoneme like in “Think” & Voiced [t͟h] Phoneme like in “The”' => 'Session 4. American Unvoiced [th] Phoneme like in “Think” & Voiced [t͟h] Phoneme like in “The”',
            'Session 5. American Unvoiced [sh] Phoneme, like in “She” & Voiced [zh] Phoneme like in “Vision”' => 'Session 5. American Unvoiced [sh] Phoneme, like in “She” & Voiced [zh] Phoneme like in “Vision”',
            'Session 6. American Unvoiced [ch] Phoneme, like in “Lunch” & Voiced [j] Phoneme, like in “Junk”' => 'Session 6. American Unvoiced [ch] Phoneme, like in “Lunch” & Voiced [j] Phoneme, like in “Junk”',
            'Session 7. American Unvoiced [h] Phoneme' => 'Session 7. American Unvoiced [h] Phoneme',
            'Session 8. American Unvoiced [p] & Voiced [b] Phonemes' => 'Session 8. American Unvoiced [p] & Voiced [b] Phonemes',
            'Session 9. American Unvoiced [f] & Voiced [v] Phonemes' => 'Session 9. American Unvoiced [f] & Voiced [v] Phonemes',
            'Session 10. American Unvoiced consonant [k] & Voiced consonant [g] Sounds' => 'Session 10. American Unvoiced consonant [k] & Voiced consonant [g] Sounds',
            
            'Session 11. American Lateral Voiced consonant [l] Sound' => 'Session 11. American Lateral Voiced consonant [l] Sound',
            'Session 12. American Voiced Sonorants [m], [n], & [ŋ] like in “Eating”' => 'Session 12. American Voiced Sonorants [m], [n], & [ŋ] like in “Eating”',
            'Session 13. Word Endings; ED -ending [t], [d], or [id]' => 'Session 13. Word Endings; ED -ending [t], [d], or [id]',
           
        );
    }
    
    return $fields;
}

add_action( 'woocommerce_checkout_create_order', 'save_custom_checkout_field_value' );
function save_custom_checkout_field_value( $order ) {
    if ( ! empty( $_POST['order_comments'] ) ) {
        $order->set_customer_note( sanitize_text_field( $_POST['order_comments'] ) );
    }
}








add_filter( 'woocommerce_checkout_fields' , 'custom_checkout_fields_label' );
function custom_checkout_fields_label( $fields ) {
    $product_id = 7399; // Replace with the ID of the product you want to modify
    $label_text = 'Your new note label'; // Replace with the new label text
    $fields['order']['order_comments']['label'] = $label_text;
    return $fields;
}




/*
  Make author name and email not required in WooCommerce review form

function remove_required_fields_from_review_form( $fields ) {
    $fields['author']['required'] = false;
    $fields['email']['required']  = false;
    return $fields;
}
add_filter( 'woocommerce_product_review_comment_form_args', 'remove_required_fields_from_review_form' ); */





// this is for direct checkout of the free pdf product

// Redirect to checkout for a specific product
function redirect_to_checkout_for_specific_product() {
    // Change the product ID below to the ID of the product you want to redirect to checkout
    $product_id = 9931;
    // Check if the product is in the cart
    if (WC()->cart->get_cart_contents_count() == 1 && WC()->cart->get_cart_contents()[0]['product_id'] == $product_id) {
      // Redirect to checkout page
      wp_redirect(wc_get_checkout_url());
      exit;
    }
  }
  add_action('template_redirect', 'redirect_to_checkout_for_specific_product');
  
