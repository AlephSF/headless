<?php
/**
 * Template Name: Listening Post Preview
 */
 $nonce = wp_create_nonce( 'wp_rest' );
 $base_rest_url = home_url('/') . 'wp-json/wp/v2/';
 $rev_url = $base_rest_url . $_GET['ptype'] . 's/' . $_GET['post_id'] . "/revisions/" . $_GET['preview_id'];
 $thumb_url = $base_rest_url . 'media/' . $_GET['_thumbnail_id'];
 if(defined('HEADLESS_POST_PREVIEW_DEST')){
	 echo '<h2>Building preview data, please be patient...</h2>';
	 $preview_url = HEADLESS_POST_PREVIEW_DEST;
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
		var postData = {};
		$.ajax( {
	    url: '<?php echo $rev_url; ?>',
	    method: 'GET',
	    beforeSend: function ( xhr ) {
	        xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo $nonce; ?>' );
	    }
		} ).done( function ( response ) {
				postData = response;

				$.ajax( {
					url: '<?php echo $base_rest_url; ?>users/' + postData.author,
					method: 'GET'
				} ).done( function ( response ) {
					postData._embedded = {
						'author': response
					};
					$.ajax( {
					url: '<?php echo $thumb_url; ?>',
					method: 'GET'
					} ).done( function ( response ) {
						postData._embedded['wp:featuredmedia'] = response;
						console.log(postData);
						redirectPost('<?php echo $preview_url ?>', postData);
					});
				});
			});
	});
</script>
