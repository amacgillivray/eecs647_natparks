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
            "Log Out" => "user/logout.php",
            "Sign Up" => "user/signup.php"
        ],
        "Admin" => [
            "Security" => [
                "Authorities" => "priv/edit_auths.php",
                "Users" => "priv/edit_users.php"
            ], 
            "Edit Data" => [
                "Parks" => "priv/edit_parks.php",
                "Fauna" => "priv/edit_fauna.php",
                "Park Fauna" => "priv/edit_parkfauna.php",
                "Images" => "priv/edit_images.php"
            ]
        ]
    ];

    function print_header_menu() : void 
    {   
        $uname = (isset($_COOKIE['lid'])) ?
            '<span style="display:block;font-style:italic">User: ' . decrypt_lid($_COOKIE['lid']) . '</span>' : 
            '<a style="align-self:flex-end;" href="/user/login.php">Log In</a>';


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
        print $uname;
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
    
    function determine_average_image_color ( $file_path ) : string
    { 
        $ftype = substr($file_path, strrpos($file_path, '.', 0));

        if ($ftype == ".jpg"  || $ftype == ".jpeg") {
            $i = imagecreatefromjpeg( $file_path );
        } else if ($ftype == ".png") {
            $i = imagecreatefrompng( $file_path );
        }

        // Create a 3x3 img, then get the color of the center pixel
        $avg = imagescale( $i, 3, 3, IMG_BILINEAR_FIXED );  
        // $idx = imagecolorat( $avg, 0, 01 );
        $idx = imagecolorat( $avg, 1, 1 );
        $colors = imagecolorsforindex( $avg, $idx ); 

        // Prevent extreme colors resulting from the selection
        // That is, if any of the three color bands is > 225
        // while the other two average less than 200, try another
        // cell
        $e = [0,0];
        $tt = false;
        while ( 1 )
        {
            $r = $colors['red'];
            $g = $colors['green'];
            $b = $colors['blue'];

            if (( $r > 225 && ($g + $b)/2 < 200 ) ||
                ( $g > 225 && ($r + $b)/2 < 200 ) ||
                ( $b > 225 && ($r + $g)/2 < 200 ))
            {
                $e[$tt]++;
                $idx = imagecolorat( $avg, $e[0], $e[1] );
                $colors = imagecolorsforindex( $avg, $idx ); 
                $tt = !$tt;
            } else if ( 
                ($e[0] >= 2 && !$tt) ||
                ($e[1] >= 2 &&  $tt) 
            ){
                break;
            } else {
                break;
            }
        }

        return sprintf(
            '#%02X%02X%02X', 
            $colors['red'], 
            $colors['green'], 
            $colors['blue']
        );
    }

    function determine_readable_text_color_for_background ( $bg ) : string
    {
    
        if (strlen($bg) === 7)
            $bg = substr($bg, 1);
    
        $r = hexdec( substr( $bg, 0, 2 ) );
        $g = hexdec( substr( $bg, 2, 2 ) );
        $b = hexdec( substr( $bg, 4, 2 ) );

        $contrast = sqrt(
            $r * $r * .241 +
            $g * $g * .691 +
            $b * $b * .068
        );

        // dark
        if($contrast > 130)
            return '#272421';

        // light
        return '#f8fafe';
    }

    function encrypt_lid( string $username ) : string 
    {
        return openssl_encrypt(
            $username,
            'AES-256-CBC',
            'thisismykeyIhopeyoulikeit',
            0,
            'thisSiteIsNotSec'
        );
    }

    function decrypt_lid( string $cookie ) : string
    {
        return openssl_decrypt(
            $cookie,
            'AES-256-CBC',
            'thisismykeyIhopeyoulikeit',
            0,
            'thisSiteIsNotSec'
        );
    }

    function authorized_user( array $auths ) : bool
    {
        if (!isset($_COOKIE['lid']))
            return false;
        
        $user = \eecs647\decrypt_lid($_COOKIE['lid']);
            
        try {
            $conn = new \PDO( 'odbc:eecs647' );
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e)
        {
            throw $e;
        }
        $stmt = $conn->prepare(
            "SELECT auth " .
            "FROM eecs647.userauth " . 
            "WHERE user = ?;"
        );
        $stmt->bindParam(
            1,
            $user
        );
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (sizeof($rows) <= 0)
            return false;

        for ($i = 0; $i < sizeof($rows); $i++)
        {
            if (in_array($rows[$i]['auth'], $auths))
                return true;
        }
        return false;
    }

    function print_err_privs()
    {
        print '<p><b>Error:</b><i> User is not logged in or is not privileged to this page.</i></p>';
    }

} 
