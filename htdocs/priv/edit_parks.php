<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN',
    'RGR'
];

const parkcols_editable = [
    "pname" => [
        "label"       => "Name",
        "type"        => "text",
        "constraints" => 'maxlength="128"'
    ],
    "pdesc" => [ 
        "label"       => "Description",
        "type"        => "textarea",
        "constraints" => 'rows="8" cols="85"'
    ],
    "pstat" => [
        "label"       => "2-Digit State Code",
        "type"        => "text",
        "constraints" => 'maxlength="2"'
    ],
    "fnded" => [
        "label"       => "Date Founded",
        "type"        => "date",
        "constraints" => 'min="1776-07-04"'
    ], 
    "sqrmi" => [
        "label"       => "Area (Square Miles)",
        "type"        => "number",
        "constraints" => 'min="0" max="99999.99" step="0.01"'
    ],
    "vslfy" => [
        "label"       => "Visitors, Last Fiscal Year",
        "type"        => "number",
        "constraints" => 'min="0" max="9999999" step="1"'
    ],
    "t_prc" => [
        "label"       => "Ticket Price (USD)",
        "type"        => "number",
        "constraints" => 'min="0" max="999.99" step=".01"'
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
    \eecs647\print_html_opener('Edit Parks');
    
    print '<section id="parks">';
    print '<h1>Editing Parks</h1>';
    print '<p><a href="./add_parks.php">Add a new Park record instead &raquo;</a></p>';

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
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Show the form to edit parks
    print '<form action="#" method="POST">';
    print '<input type="hidden" name="handler" value="void"/>';
    foreach ( $rows as $park )
    {
        \eecs647\print_form_values_for_table(
            parkcols_editable,
            $park,
            $park["id"],
            '<fieldset class="park"><legend>Park ID#'.$park["id"].'</legend>' 
            . '<input type="hidden" name="'.$park["id"].'_id" value="'.$park["id"].'"/>',
            '<p><label><b>CHECK BOX TO APPLY CHANGES:</b></label>' .
            '<input name="'.$park["id"].'_apply" type="checkbox" />' .
            '</p></fieldset>'
        );
    }
    print '<input type="submit"/>';
    print '</form>';
    print '</section>';   
}

function handle_form()
{
    array_shift($_POST);

    // var_dump($_POST);

    $filter = [];
    $crawl = array_keys($_POST);
    for ($i = 0; $i < sizeof($crawl); $i++)
    {
        $park = substr(
            $crawl[$i],
            0, 
            strpos($crawl[$i], '_')
        );
        $field = substr(
            $crawl[$i],
            strpos($crawl[$i], '_')+1
        );

        $filter[$park][$field] = $_POST[$crawl[$i]]; 
    }

    for ($i = 0; $i < sizeof($filter); $i++)
    {
        $park =& $filter[array_keys($filter)[$i]];
        foreach (['guns', 'alch', 'camp'] as $field)
        {
            if (!isset($park[$field]))
                $park[$field] = '0';
            else 
                $park[$field] = '1';
        }
    }

    for ($i = sizeof($filter)-1; $i >= 0; $i--)
        if (!isset($filter[array_keys($filter)[$i]]['apply']))
            unset($filter[array_keys($filter)[$i]]);
    
    if (sizeof ($filter) === 0) 
    {
        print '<p>No changes were made. Please ensure you checked the box on any parks you wished to update.</p>';
        print '<p><b>If you click the browser\'s back button, changes you had made but not applied may be preserved.</b></p>';
        return;
    }

    try {
        $conn = new PDO( 'odbc:eecs647' );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        throw $e;
    }

    # Apply changes to modified rows 
    for ($i = 0; $i < sizeof($filter); $i++)
    {
        $park =& $filter[array_keys($filter)[$i]];

        $stmt = $conn->prepare(
            "UPDATE eecs647.parks " . 
            "SET " .
                "pname = ?, " .
                "pdesc = ?, " .
                "pstat = ?, " .
                "fnded = ?, " .
                "sqrmi = ?, " .
                "vslfy = ?, " . 
                "t_prc = ?, " .
                "alch = ?, " . 
                "camp = ?, " .
                "guns = ? " . 
            "WHERE id = ?;"
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

        print '<p>Park \''.$park['pname'].'\' was successfully modified.</p>';
    }

    print '<p><a href="./edit_parks.php">&laquo;Go Back</p>';
}

do_page();