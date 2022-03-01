<?php
$gaCode = \Cake\Core\Configure::read('google-analytics.tracker-code');
if ($gaCode) {
    $googleAnalytics = <<<EOD

<script async src="https://www.googletagmanager.com/gtag/js?id=$gaCode"></script>

<script type="text/javascript">

    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '$gaCode');
    
</script>
EOD;
    echo $googleAnalytics;
}
?>
