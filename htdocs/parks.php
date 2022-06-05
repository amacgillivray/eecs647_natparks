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
$stmt = $conn->prepare("select * from eecs647.parks;");
$stmt->execute();

// var_dump($stmt);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($rows);

\eecs647\print_html_opener('Parks');
print '<section id="parks">';

foreach ( $rows as $park )
{
    foreach (["camp", "alch", "guns"] as $trait)
    {
        $park[$trait] = ($park[$trait] === '1') ? "Allowed" : "Prohibited";
    }

    print 
    '<article class="park">' .
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
    '</div>' . 
    '</article>';
}
print '</section>';

\eecs647\print_html_closer();

// var_dump($rows);

?>
