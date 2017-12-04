
<?php
    defined('C5_EXECUTE') or die("Access Denied.");
    $a = $b->getBlockAreaObject();

    $container = $formatter->getLayoutContainerHtmlObject();
    //dd($columns);
    foreach($columns as $column) {
        $html = $column->getColumnHtmlObject();
        //var_dump($html);
        $container->appendChild($html);
    }
    //dd($container);
    print $container;
?>