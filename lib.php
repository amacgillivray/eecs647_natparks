<?php 

namespace eecs647 {

    const dsn = "eecs647";

    const pages = [
        "National Parks Database" => [
           "Home" => "index.php",
           "Parks" => "parks.php",
           "Fauna" => "fauna.php",
        //    "Flora" => "flora.php"
        ], 
        "User Actions" => [
            "Log In" => "user/login.php",
            "Log Out" => "user/logout.php"
        ],
        "Admin" => [
            "Security" => [
                "Authorities" => "priv/edit_auths",
                "Users" => "priv/edit_users.php"
            ], 
            "Park Data" => [
                "Parks" => "priv/edit_parks.php",
                "Fauna" => "priv/edit_fauna.php",
                "Park Fauna" => "priv/edit_parkfauna.php",
                // "Flora" => "priv/edit_flora.php",
                // "Park Flora" => "priv/edit_parkflora.php"
            ]
        ]
    ];

    function print_logout_link() : void 
    {

    }

    function print_header_menu() : void 
    {
        print '<nav id="hdrnav">';
        print '<a class="titlelink" href="/index.php">National Parks Database</a>';
        foreach ( array_slice(pages["National Parks Database"],1) as $title => $link )
        {
            $current = ($link === substr($_SERVER['REQUEST_URI'], 1));
            print '<a ';
            print ($current) ? 'class="current" ' : '';
            print 'href="/' . $link . '">';
            print $title . '</a>';
        }
        print '</nav>';
        print '<div id="hdrnavclr"></div>';
    }

    function print_footer_menu() : void
    {
        print '<nav id="ftrnav">';
        foreach ( pages as $entry => $val )
        {
            print_footer_menu_entry([$entry => $val]);
        }
        print '</nav>';
    }

    function print_footer_menu_entry( array $arr, int $depth = 0 ) : void
    {
        foreach ( $arr as $key => $value )
        {
            if (is_array($value))
            {
                print "<div>";
                print "<h" . ($depth+1) . ">" . $key . "</h" . ($depth+1) . ">";
                foreach ($value as $entry => $val )
                {
                    print_footer_menu_entry([$entry => $val], $depth+1);
                }
                print "</div>";
            } else {
                $current = ($value === substr($_SERVER['REQUEST_URI'], 1));
                print '<a ';
                print ($current) ? 'class="current" ' : '';
                print 'href="/' . $value . '">';
                print ($current) ?  "&raquo;&nbsp;" : '';
                print $key . '</a>';
            }
        }
    }

    function print_html_opener(
        string $title
    ) : void
    {
        print 
        '<!DOCTYPE html>' .
        '<html>' .   
        '<head>' .
            '<meta charset="utf-8">' . 
            '<title>' . $title . '</title>' .
            '<link rel="stylesheet" href="https://use.typekit.net/civ8eix.css">' .
            '<link rel="stylesheet" href="/style.css">' .
        '</head>' .
        '<body>';
        print_header_menu();
    } 

    function print_html_closer(
        string $custom_footer_content = "",
        array  $footer_scripts = []
    ) {
        print '<footer>' . $custom_footer_content;
        print_footer_menu();
        foreach ($footer_scripts as $script)
        {
            print $script;
        }
        print '</footer></body></html>' . "\n";
    }

    /**
     * @brief Prints part of a form for editing SQL data
     */
    function print_form_values_for_table(
        array $cols,
        array $row,
        string $row_id = "",
        string $wrap_open = "",
        string $wrap_close = ""
    ) : void {
        print $wrap_open;
        foreach ( $cols as $fieldname => $fieldmeta )
        {
            print '<div class="fl-pair fl-pair-' .$fieldmeta["type"] .'">';
            $tag = $row_id . "_" . $fieldname;
            $value = $row[$fieldname];
            
            $label = '<label for="'.$tag.'">'.$fieldmeta["label"].'</label>';
            $idnty = 'id="'.$tag.'" name="'.$tag.'"';
            $constraints = $fieldmeta["constraints"];

            if ( $fieldmeta["type"] === "checkbox" )
            {
                $comp = '1';
                print '<input '.$idnty.' type="checkbox" ' . $fieldmeta["constraints"];
                print ($value == $comp) ? ' checked>' : '' . '>';
                print $label;
            } else if ( $fieldmeta["type"] === "textarea" ) {
                print $label;
                print '<textarea '.$idnty.' '.$constraints.'>';
                print $value;
                print '</textarea>';
            } else {
                print $label;
                print '<input '.$idnty.
                      ' type="'.$fieldmeta["type"].'"'.
                      ' value="'.$value.'"'.
                      ' '.$constraints.'>';
            }
            print '</div>';
        }
        print $wrap_close;
        return;   
    }

    /**
     * 
     */
    function odbc_query(
        string $query,
        string $cb,
        array  $cbparms = []
    ) {
        try {
            $conn = new PDO( dsn );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e)
        {
            throw $e;
        }
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $cb( $stmt, ...$cbparms);
    }

    function fauna_get_endangered_level( $level ) : string
    {
        $endangered = [
            "least concern",
            "near threatened",
            "vulnerable",
            "endangered",
            "critically endangered",
            "extinct in the wild",
            "extinct"       
        ];
        return $endangered[$level];
    }

    function fauna_get_eats( $level ) : string
    {
        $eats = [
            "herbivore",
            "carnivore",
            "omnivore"
        ];
        return $eats[$level];
    }

} 
