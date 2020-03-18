<?php declare(strict_types = 1);

require_once LAYOUT . "/Dependencies.php";

class Layout
{
    public static function echoHead(array $styles = [], string $title = "SeqGAN") { ?>
    <html lang="en">
        <head>
            <title><?=$title?></title>
            <?php foreach (Dependencies::getStylesheets() as $stylesheet): ?>
                <link rel="stylesheet" type="text/css" href="/<?=$stylesheet."?v=".filemtime(ROOT ."/$stylesheet")?>">
            <?php endforeach; ?>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/w/dt/dt-1.10.18/datatables.min.css"/>
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
        </footer>
    </html>
    <?php
    }
}