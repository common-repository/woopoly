<style type='text/css'>
    .wpi-social-column{
        position: relative;
        float: left;
        padding-left: 1%;
    }
</style>

<script>
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script>
    !function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
        if (!d.getElementById(id)) {
            js = d.createElement(s);
            js.id = id;
            js.src = p + '://platform.twitter.com/widgets.js';
            fjs.parentNode.insertBefore(js, fjs);
        }
    }(document, 'script', 'twitter-wjs');
</script>
<div class="wrap" >
    <div class="wpi-social-column">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://wordpress.org/plugins/woopoly/" data-text="WooPoly wordpress plugin, makes it easy to run multilingual WooCommerce online stores." data-via="decarvalhoaa">Tweet</a>
    </div>
    <div class="wpi-social-column">
        <div id="fb-root"></div>
        <div class="fb-share-button" data-href="https://wordpress.org/plugins/woopoly/" data-layout="button_count"></div>
    </div>
    <div class="wpi-social-column">
        <iframe src="https://ghbtns.com/github-btn.html?user=decarvalhoaa&repo=woopoly&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>
    </div>
</div>
<div style="clear: both"></div>
