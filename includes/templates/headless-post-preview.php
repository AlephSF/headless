<?php
/**
 * Template Name: Listening Post Preview
 */
 $nonce = wp_create_nonce( 'wp_rest' );
 $base_rest_url = home_url('/') . 'wp-json/wp/v2/';
 $rev_url = $base_rest_url . $_GET['ptype'] . 's/' . $_GET['post_id'] . "/revisions/" . $_GET['preview_id'];
 $thumb_url = (int) $_GET['_thumbnail_id'] > 0 ? $base_rest_url . 'media/' . $_GET['_thumbnail_id'] : 'false';
 //
 if(defined('HEADLESS_POST_PREVIEW_DEST')){
	 echo '<h2>Building preview data, please be patient...</h2>';
   // $path = parse_url(get_permalink($_GET['post_id']), PHP_URL_PATH);
	 // $preview_url = HEADLESS_POST_PREVIEW_DEST . $path;
 } else {
	 echo '<h2>You must set the constant HEADLESS_POST_PREVIEW_DEST to use this feature!</h2>';
	 wp_die();
 }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	var $ = window.jQuery,
		postJson = null;
	function redirectPost(url, data) {
	    var form = document.createElement('form');
	    document.body.appendChild(form);
	    form.method = 'post';
	    form.action = url;
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'postdata';
			input.value = JSON.stringify(data);
			form.appendChild(input);
	    form.submit();
	}
	$(function(){
		var postData = {},
      previewUrl = '<?php echo HEADLESS_POST_PREVIEW_DEST; ?>';
		$.ajax( {
	    url: '<?php echo $rev_url; ?>',
	    method: 'GET',
	    beforeSend: function ( xhr ) {
	        xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo $nonce; ?>' );
	    }
		} ).done( function ( response ) {
				postData = response;
        previewUrl = previewUrl + '/' + postData.slug;
				$.ajax( {
					url: '<?php echo $base_rest_url; ?>users/' + postData.author,
					method: 'GET'
				} ).done( function ( response ) {
					postData._embedded = {
						'author': [response]
					};
          if( '<?php echo $thumb_url; ?>' !== 'false' ) {
  					$.ajax( {
  					url: '<?php echo $thumb_url; ?>',
  					method: 'GET'
  					} ).done( function ( response ) {
  						postData._embedded['wp:featuredmedia'] = [response];
  						redirectPost(previewUrl, postData);
  					});
          } else {
            redirectPost(previewUrl, postData);
          }
  			});
			});
	});
</script>
