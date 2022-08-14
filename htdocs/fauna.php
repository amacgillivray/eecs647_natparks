<?php
require_once(dirname(__FILE__, 2) . "/lib.php");

use function \eecs647\print_html_opener;
use function \eecs647\print_html_closer;
use function \eecs647\determine_average_image_color;
use function \eecs647\determine_readable_text_color_for_background;

// phpinfo();
try {
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    throw $e;
}
// $stmt = $conn->prepare("select * from eecs647.fauna order by class, `order`;");
$stmt = $conn->prepare(
    "SELECT * 
    FROM eecs647.fauna 
    LEFT JOIN eecs647.image ON fauna.code = image.lfauna
    GROUP BY fauna.code
    ORDER BY class, `order`, family, genus"
);
// $stmt = $conn->prepare(
//     "SELECT DISTINCT * 
//     FROM eecs647.fauna 
//     LEFT JOIN eecs647.imgfauna ON fauna.code = imgfauna.code
//     GROUP BY fauna.code, imgfauna.code, imgfauna.fpath 
//     ORDER BY fauna.class, fauna.`order`;"
// );
$stmt->execute();

// var_dump($stmt);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($rows);

print_html_opener('Parks');
print '<section id="fauna">';


foreach ( $rows as $fauna )
{
    if ($fauna["fpath"] !== null) {
        $color = determine_average_image_color('_img/' . $fauna['fpath']);
        $text = determine_readable_text_color_for_background( $color );
        print '<article class="fauna" style="background-color:'. $color . ';color:'.$text.' !important">';

        print '<div class="cited-img">';
        print '<img src="/_img/' . $fauna["fpath"] . '">';
        
        // print '<div class="ifp">';
        // print '<i>Author</i>';
        // print '<strong>' . $fauna["author"] . '</strong>';
        // print '</div>';
        // print '</div>';
        print '<p class="photinfo">';
        print '<strong>' . $fauna["author"] . '</strong><br/>';
        print $fauna["license"];
        print '</p>';
        
        print '</div>';
    } else {

        print '<article class="fauna">';

    }

    print '<h2>' . $fauna["name"] . '</h2>';

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

        '</div>' .
    '</div>';
    print '<p style="padding-top:15px;"><a style="color:inherit" href="/singleitem.php?type=fauna&query=' . $fauna['code'] . '">View Animal Details -&gt;</a></p>' .
    '</article>';
}
print '</section>';

print_html_closer();

?>
