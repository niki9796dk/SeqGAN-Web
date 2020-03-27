<?php declare(strict_types = 1);

require_once LAYOUT . "/Dependencies.php";



class Layout
{
    public static function echoHead(array $styles = [], string $title = "SeqGAN") {
        $fav = rand(0, 100) != 0 ? "stonks" : "seblund";
        ?>
    <html lang="en">
        <head>
            <title><?=$title?></title>
            <?php foreach (Dependencies::getStylesheets() as $stylesheet): ?>
                <link rel="stylesheet" type="text/css" href="/<?=$stylesheet."?v=".filemtime(ROOT ."/$stylesheet")?>">
            <?php endforeach; ?>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/w/dt/dt-1.10.18/datatables.min.css"/>

            <!-- FAV -->
            <link rel="apple-touch-icon" sizes="57x57" href="/views/layout/fav/<?=$fav?>/apple-icon-57x57.png">
            <link rel="apple-touch-icon" sizes="60x60" href="/views/layout/fav/<?=$fav?>/apple-icon-60x60.png">
            <link rel="apple-touch-icon" sizes="72x72" href="/views/layout/fav/<?=$fav?>/apple-icon-72x72.png">
            <link rel="apple-touch-icon" sizes="76x76" href="/views/layout/fav/<?=$fav?>/apple-icon-76x76.png">
            <link rel="apple-touch-icon" sizes="114x114" href="/views/layout/fav/<?=$fav?>/apple-icon-114x114.png">
            <link rel="apple-touch-icon" sizes="120x120" href="/views/layout/fav/<?=$fav?>/apple-icon-120x120.png">
            <link rel="apple-touch-icon" sizes="144x144" href="/views/layout/fav/<?=$fav?>/apple-icon-144x144.png">
            <link rel="apple-touch-icon" sizes="152x152" href="/views/layout/fav/<?=$fav?>/apple-icon-152x152.png">
            <link rel="apple-touch-icon" sizes="180x180" href="/views/layout/fav/<?=$fav?>/apple-icon-180x180.png">
            <link rel="icon" type="image/png" sizes="192x192"  href="/views/layout/fav/<?=$fav?>/android-icon-192x192.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/views/layout/fav/<?=$fav?>/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="96x96" href="/views/layout/fav/<?=$fav?>/favicon-96x96.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/views/layout/fav/<?=$fav?>/favicon-16x16.png">
            <link rel="manifest" href="/views/layout/fav/<?=$fav?>/manifest.json">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="/views/layout/fav/<?=$fav?>/ms-icon-144x144.png">
            <meta name="theme-color" content="#ffffff">
            <!-- FAV -->
        </head>
        <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 py-3">
                    <a class="btn btn-primary" href="/">Front page</a>
                    <a class="btn btn-primary ml-3" href="/views/pages/compareRunning.php?cols=2">Compare running</a>
                    <a class="btn btn-primary ml-3" href="/views/pages/compareRecent.php?cols=2">Compare recent</a>
                    <a class="btn btn-primary ml-3" href="/views/pages/compareRecentGood.php?cols=2">Compare recent good</a>
                </div>
            </div>
        </div>
    <?php
    }

    public static function echoFooter(array $scripts = []) { ?>
        </body>
        <footer>
            <?php foreach (Dependencies::getScripts() as $script): ?>
                <script src="/<?=$script."?v=".filemtime(ROOT."/$script")?>"></script>
            <?php endforeach; ?>
            <script type="text/javascript" src="https://cdn.datatables.net/w/dt/dt-1.10.18/datatables.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        </footer>
    </html>
    <?php
    }
}