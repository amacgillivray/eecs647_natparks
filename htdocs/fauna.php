<?php
require_once(dirname(__FILE__, 2) . "/lib.php");

// phpinfo();
try {
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    throw $e;
}
$stmt = $conn->prepare("select * from eecs647.fauna order by class, `order`;");
$stmt->execute();

// var_dump($stmt);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($rows);

\eecs647\print_html_opener('Parks');
print '<section id="fauna">';

foreach ( $rows as $fauna )
{
    print 
    '<article class="fauna">' .
        '<h2>' . $fauna["name"] . '</h2>';

    print '<strong>';
    foreach (["class", "order", "suborder", "family", "subfamily", "genus"] as $tax)
    {
        if ($fauna[$tax] !== "N/A")
        {
            if ($tax != "class")
                print " -> ";
            print $fauna[$tax];
        }
    }
    print '</strong>';

    print 
        '<p>' . $fauna["fdesc"] . '</p>' . 
        '</div>' .
        '<div class="pinfo">' .

            // LIFESPAN
            '<div class="ifp">' . 
                '<i>Avg. Lifespan</i>' .
                '<strong>' . $fauna["lifespan"] . ' years</strong>' .
            '</div>' .

            // HABITAT SIZE
            '<div class="ifp">' . 
                '<i>Home Range</i>' .
                '<strong>' . $fauna["homerange_min"] . '-' . $fauna["homerange_max"] . ' sq-mi</strong>' .
            '</div>' .

            // WEIGHT
            '<div class="ifp">' . 
                '<i>Max Weight M/F</i>' .
                '<strong>' . $fauna["weight_m"] .' / ' . $fauna["weight_f"] . ' lbs</strong>' .
            '</div>' .

            // HEIGHT
            '<div class="ifp">' . 
                '<i>Shoulder Height</i>' .
                '<strong>' . $fauna["height_cm"] . ' cm</strong>' .
            '</div>' .

            // LENGTH
            '<div class="ifp">' . 
                '<i>Length</i>' .
                '<strong>' . $fauna["length_cm"] . ' cm</strong>' .
            '</div>' .

            // // GUNS
            // '<div class="ifp">' . 
            //     '<i>Firearms</i>' .
            //     '<strong>' . $fauna["guns"] . '</strong>' .
            // '</div>' .

        '</div>' .
    '</div>' . 
    '</article>';
}
print '</section>';

\eecs647\print_html_closer();

// var_dump($rows);

?>
