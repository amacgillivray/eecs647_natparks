<?php
require_once(dirname(__FILE__, 2) . "/lib.php");

use function \eecs647\print_html_opener;
use function \eecs647\print_html_closer;
use function \eecs647\determine_average_image_color;
use function \eecs647\determine_readable_text_color_for_background;

try {
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    throw $e;
}
// $stmt = $conn->prepare("select * from eecs647.parks order by sqrmi desc;");
$stmt = $conn->prepare(
    "SELECT * 
    FROM eecs647.parks
    LEFT JOIN eecs647.image ON parks.id = image.lpark 
    GROUP BY parks.id, image.fpath
    ORDER BY sqrmi DESC"
);
$stmt->execute();

// var_dump($stmt);
$parks = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($rows);

print_html_opener('Parks');
print '<section id="parks">';

$printed_parks = [];

for ($i = 0; $i < sizeof($parks); $i++)
{
    $park = $parks[$i];
    if (in_array($park['id'],$printed_parks))
        continue;

//    if (!empty($park['lfauna']) && $parks[$i+1]['id'] == $park['id'] && empty($parks[$i+1]['lfauna']))
    if (!empty($park['lfauna']))
        continue;

    $printed_parks[] = $park['id'];

    foreach (["camp", "alch", "guns"] as $trait)
    {
        $park[$trait] = ($park[$trait] === '1') ? "Allowed" : "Prohibited";
    }

    if ($park["fpath"] !== null) {
        $color = determine_average_image_color('_img/' . $park['fpath']);
        $text = determine_readable_text_color_for_background( $color );
        print '<article class="park" style="background-color:'. $color . ';color:'.$text.' !important">';
        print '<div class="cited-img">';
        print '<img src="/_img/' . $park["fpath"] . '">';
        print '<p class="photinfo">';
        print '<strong>' . $park["author"] . '</strong><br/>';
        print $park["license"];
        print '</p>';
        
        print '</div>';
    } else {
        print '<article class="park">';
    }

    
    // print '<article class="park">';

    print
    '<div class="park-it">' . 
        '<div class="park-it-i">' .
        '</div>' .
        '<div class="park-it-t">' .
            '<h2>(' . $park["pstat"] . ') ' . $park["pname"] . '</h2>' .
            '<p>' . $park["pdesc"] . '</p>' . 
        '</div>' .
        '<div class="pinfo">' .

            // DATE FOUNDED
            '<div class="ifp">' . 
                '<i>Founded</i>' .
                '<strong>' . $park["fnded"] . '</strong>' .
            '</div>' .

            // SQUARE MILEAGE
            '<div class="ifp">' . 
                '<i>Square Mileage</i>' .
                '<strong>' . $park["sqrmi"] . '</strong>' .
            '</div>' .

            // VISITORS LAST YEAR
            '<div class="ifp">' . 
                '<i>Annual Visitors</i>' .
                '<strong>' . $park["vslfy"] . '</strong>' .
            '</div>' .

            // CAMPING
            '<div class="ifp">' . 
                '<i>Camping</i>' .
                '<strong>' . $park["camp"] . '</strong>' .
            '</div>' .

            // ALCOHOL
            '<div class="ifp">' . 
                '<i>Alcohol</i>' .
                '<strong>' . $park["alch"] . '</strong>' .
            '</div>' .

            // GUNS
            '<div class="ifp">' . 
                '<i>Firearms</i>' .
                '<strong>' . $park["guns"] . '</strong>' .
            '</div>' .

        '</div>' .
    '</div>';
    print '<p style="padding-top:15px;"><a style="color:inherit" href="/singleitem.php?type=park&query=' . $park['id'] . '">View All Relationships -&gt;</a></p>' .
    '</article>';
}
print '</section>';
print_html_closer();

// var_dump($rows);

?>
