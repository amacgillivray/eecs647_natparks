<?php

use function eecs647\determine_average_image_color;
use function eecs647\determine_readable_text_color_for_background;

require_once(dirname(__FILE__, 2) . "/lib.php");

function do_page()
{
    \eecs647\print_html_opener('Single Item Query');
    print '<section id="itemquery">';
    
    if (!isset($_GET['type']) || !isset($_GET['query']))
        print_bad_query();
    else if ($_GET['type'] == "park")
        query_for_park($_GET['query']);
    else if ($_GET['type'] == "fauna")
        query_for_fauna($_GET['query']);
    else
        print_bad_query();

    print '</section>';
    \eecs647\print_html_closer();
}

function query_for_park( $query )
{
    try {
        $conn = new PDO( 'odbc:eecs647' );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        throw $e;
    }

    // GET PARK
    $stmt = $conn->prepare(
        "SELECT * 
        FROM eecs647.parks
        WHERE parks.id = ?
        ORDER BY parks.sqrmi DESC"
    );
    $stmt->bindParam(
        1,
        $query
    );
    $stmt->execute();
    $park = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

    // GET FAUNA
    $stmt = $conn->prepare(
        "SELECT * 
        FROM eecs647.parkfauna
        LEFT JOIN eecs647.fauna on parkfauna.code = fauna.code
        WHERE parkfauna.id = ?
        ORDER BY fauna.class, fauna.order, fauna.family, fauna.genus ASC"
    );
    $stmt->bindParam(
        1,
        $query
    );
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach (["camp", "alch", "guns"] as $trait)
    {
        $park[$trait] = ($park[$trait] === '1') ? "Allowed" : "Prohibited";
    }
    print '<h1 style="margin-bottom:0;">' . $park['pname'] . '<br/>';
    print '<span style="font-size:.65em;">' . $park["pstat"] . ', USA</span></h1>';
    print'<p>' . $park["pdesc"] . '</p>';

    print 
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
    '</div>';

    # GET PHOTOS
    $stmt = $conn->prepare(
        "SELECT * 
        FROM eecs647.image
        WHERE eecs647.image.lpark = ?
        ORDER BY image.dattkn"
    );
    $stmt->bindParam(
        1,
        $query
    );
    $stmt->execute([$park['id']]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    print '<h2>Photos of ' . $park['pname'] . '</h2>';
    if (sizeof($photos) === 0)
    {
//        print '<p>No photos available.</p>';
    } else {
        print '<div class="photogrid">';
        for ($i = 0; $i < sizeof($photos); $i++) {
            $photo = $photos[$i];
            if ($photo["fpath"] !== null) {
                $color = determine_average_image_color('_img/' . $photo['fpath']);
                $text = determine_readable_text_color_for_background($color);
                print '<div class="park" style="background-color:' . $color . ';color:' . $text . ' !important">';
                print '<div class="cited-img">';
                print '<img src="/_img/' . $photo["fpath"] . '">';
                print '<p class="photinfo">';
                print '<strong>' . $photo["author"] . '</strong><br/>';
                print $photo["license"];
                print '</p>';

                print '<p><small><i>'.$photo['idesc'].'</i></small></p>';

                print '<small>' .
                    '<b>Camera Manufacturer:</b> ' . $photo['mfr'] . '<br/>' .
                    '<b>Camera Model:</b> ' . $photo['mod'] . '<br/>' .
                    '<b>Exposure Time:</b> ' . $photo['exposure'] . '<br/>' .
                    '<b>F Number:</b> ' . $photo['fnum'] . '<br/>' .
                    '<b>ISO:</b> ' . $photo['iso'] . '<br/>' .
                    '<b>Focal Length:</b> ' . $photo['foclen'] . '<br/>' .
                    '</small>';


                print '</div></div>';
            }
        }
        print '</div>';
    }


    # FAUNA
    print "<h2>Fauna in " . $park['pname'] . '</h2>';
    print '<p>Sorted alphabetically by taxonomy (class, order, etc.)</p>';
    foreach ( $rows as $fauna )
    {
        print '<div style="margin-bottom:25px;">';
        print '<h3>' . $fauna["name"] . '</h3>';
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
        '</div>';
        print '<p style="padding-top:8px;"><a style="color:inherit" href="/singleitem.php?type=fauna&query=' . $fauna['code'] . '">View Animal Details -&gt;</a></p>';

        print '</div>';
    }
    
}

function query_for_fauna( $query )
{
    try {
        $conn = new PDO( 'odbc:eecs647' );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        throw $e;
    }

    // GET FAUNA
    $stmt = $conn->prepare(
        "SELECT * 
        FROM eecs647.fauna
        WHERE fauna.code = ?"
    );
    $stmt->bindParam(
        1,
        $query
    );
    $stmt->execute();
    $fauna = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

    // GET PARKS
    $stmt = $conn->prepare(
        "SELECT * 
        FROM eecs647.parkfauna
        LEFT JOIN eecs647.parks on parkfauna.id = parks.id
        WHERE parkfauna.code = ?
        ORDER BY parks.id ASC"
    );
    $stmt->bindParam(
        1,
        $query
    );
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print '<h1>' . $fauna["name"] . '</h1>';
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

        '</div>';

    # GET PHOTOS
    $stmt = $conn->prepare(
        "SELECT * 
        FROM eecs647.image
        WHERE eecs647.image.lfauna = ?
        ORDER BY image.dattkn"
    );
    $stmt->bindParam(
        1,
        $query
    );
    $stmt->execute([$fauna['code']]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (sizeof($photos) > 0)
    {
        print '<div class="photogrid">';
        for ($i = 0; $i < sizeof($photos); $i++) {
            $photo = $photos[$i];
            if ($photo["fpath"] !== null) {
                $color = determine_average_image_color('_img/' . $photo['fpath']);
                $text = determine_readable_text_color_for_background($color);
                print '<div class="park" style="background-color:' . $color . ';color:' . $text . ' !important">';
                print '<div class="cited-img">';
                print '<img src="/_img/' . $photo["fpath"] . '">';
                print '<p class="photinfo">';
                print '<strong>' . $photo["author"] . '</strong><br/>';
                print $photo["license"];
                print '</p>';

                print '<p><small><i>'.$photo['idesc'].'</i></small></p>';

                print '<small>' .
                    '<b>Camera Manufacturer:</b> ' . $photo['mfr'] . '<br/>' .
                    '<b>Camera Model:</b> ' . $photo['mod'] . '<br/>' .
                    '<b>Exposure Time:</b> ' . $photo['exposure'] . '<br/>' .
                    '<b>F Number:</b> ' . $photo['fnum'] . '<br/>' .
                    '<b>ISO:</b> ' . $photo['iso'] . '<br/>' .
                    '<b>Focal Length:</b> ' . $photo['foclen'] . '<br/>' .
                    '</small>';


                print '</div></div>';
            }
        }
        print '</div>';
    }



    print '<h2>Parks where ' . $fauna['name'] . ' are found:</h2>';
    foreach ( $rows as $park )
    {
        foreach (["camp", "alch", "guns"] as $trait)
        {
            $park[$trait] = ($park[$trait] === '1') ? "Allowed" : "Prohibited";
        }
        print '<div style="width:85%;margin:auto;margin-bottom:25px;">';
        
        print '<h1 style="margin-bottom:0;">' . $park['pname'] . '<br/>';
        print '<span style="font-size:.65em;">' . $park["pstat"] . ', USA</span></h1>';
        print'<p>' . $park["pdesc"] . '</p>';
        print 
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
        '</div>';

        print '<p style="padding-top:15px;"><a style="color:inherit" href="/singleitem.php?type=park&query=' . $park['id'] . '">View This Park -&gt;</a></p>';
        print '</div>';
    }
    return;
}

function print_bad_query()
{
    print '<h1>Error</h1>';
    print '<p>No interpretable query has been provided. The request could not be understood.</p>';
    print '<p>This page should only be accessed by clicking a link to generate an appropriate query. Please go back and try again.</p>';    
}


do_page();