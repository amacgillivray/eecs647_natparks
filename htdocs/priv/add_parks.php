<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN',
    'RGR'
];

const cols_editable = [
    "id" => [
        "label"       => "Short ID",
        "type"        => "Text",
        "constraints" => 'maxlength="10" required'
    ],
    "pname" => [
        "label"       => "Name",
        "type"        => "text",
        "constraints" => 'maxlength="128" required'
    ],
    "pdesc" => [ 
        "label"       => "Description",
        "type"        => "textarea",
        "constraints" => 'rows="8" cols="85"'
    ],
    "pstat" => [
        "label"       => "2-Digit State Code",
        "type"        => "text",
        "constraints" => 'maxlength="2" required'
    ],
    "fnded" => [
        "label"       => "Date Founded",
        "type"        => "date",
        "constraints" => 'min="1776-07-04" required'
    ], 
    "sqrmi" => [
        "label"       => "Area (Square Miles)",
        "type"        => "number",
        "constraints" => 'min="0" max="99999.99" step="0.01" required'
    ],
    "vslfy" => [
        "label"       => "Visitors, Last Fiscal Year",
        "type"        => "number",
        "constraints" => 'min="0" max="9999999" step="1" required'
    ],
    "t_prc" => [
        "label"       => "Ticket Price (USD)",
        "type"        => "number",
        "constraints" => 'min="0" max="999.99" step=".01" required'
    ],
    "alch"  => [ 
        "label"       => "Allows Alcohol",
        "type"        => "checkbox",
        "constraints" => ''
    ],
    "camp"  => [
        "label"       => "Allows Camping",
        "type"        => "checkbox",
        "constraints" => ''
    ],
    "guns"  => [
        "label"       => "Allows Firearms",
        "type"        => "checkbox",
        "constraints" => ''
    ]
];

function do_page()
{
    \eecs647\print_html_opener('Add Park');
    
    print '<section id="parks">';
    print '<h1>Adding Park</h1>';
    print '<p><a href="./edit_parks.php">Edit an existing Park record instead &raquo;</a></p>';

    if (!\eecs647\authorized_user(auths))
        print_err_privs();
    else if (isset($_POST['handler']))
        handle_form();
    else 
        print_form();
    
    print '</section>';
    \eecs647\print_html_closer();
}

function print_form()
{   
    // Show the form to edit parks
    print '<form action="#" method="POST">';
    print '<input type="hidden" name="handler" value="void"/>';
    print '<fieldset class="park"><legend>New Park</legend>';

    foreach (cols_editable as $fieldname => $fieldmeta)
    {
        print '<div class="fl-pair fl-pair-' .$fieldmeta["type"] .'">';
        $tag = $fieldname;
        $value = "";
        
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

    print '</fieldset>';
    print '<input type="submit"/>';
    print '</form>';
    print '</section>';   
}

function handle_form()
{
    array_shift($_POST);

    var_dump($_POST);
    
    $park = & $_POST;
    foreach (['guns', 'alch', 'camp'] as $field)
    {
        if (!isset($park[$field]))
            $park[$field] = '0';
        else 
            $park[$field] = '1';
    }

    try {
        $conn = new PDO( 'odbc:eecs647' );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        throw $e;
    }

    $stmt = $conn->prepare(
        "INSERT INTO eecs647.parks(pname, pdesc, pstat, fnded, sqrmi, vslfy, t_prc, alch, camp, guns, id) " . 
        "VALUES ( " .
            "?, " .
            "?, " .
            "?, " .
            "?, " .
            "?, " .
            "?, " . 
            "?, " .
            "?, " . 
            "?, " .
            "?, " . 
        "? );"
    );
    $stmt->bindParam(
        1,
        $park['pname']
    );
    $stmt->bindParam(
        2,
        $park['pdesc']
    );
    $stmt->bindParam(
        3,
        $park['pstat']
    );
    $stmt->bindParam(
        4,
        $park['fnded']
    );
    $stmt->bindParam(
        5,
        $park['sqrmi']
    );
    $stmt->bindParam(
        6,
        $park['vslfy']
    );
    $stmt->bindParam(
        7,
        $park['t_prc']
    );
    $stmt->bindParam(
        8,
        $park['alch']
    );
    $stmt->bindParam(
        9,
        $park['camp']
    );
    $stmt->bindParam(
        10,
        $park['guns']
    );
    $stmt->bindParam(
        11,
        $park['id']
    );

    $stmt->execute();

    print '<p>Park \''.$park['pname'].'\' was successfully created.</p>';

    print '<p><a href="./add_parks.php">&laquo;Go Back</p>';
}

do_page();
