<?php 

require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN'
];

const cols_editable = [
    "code" => [
        "label"       => "Authority Code",
        "type"        => "text",
        "constraints" => 'maxlength="10" required'
    ],
    "fname" => [
        "label"       => "Detailed Name",
        "type"        => "text",
        "constraints" => 'maxlength="63" required'
    ],
    "expl" => [ 
        "label"       => "Description",
        "type"        => "text",
        "constraints" => 'maxlength="255" value="N/A"'
    ]
];

function do_page()
{
    \eecs647\print_html_opener('Add New Authority');
    
    print '<section>';
    print '<h1>Create A New Authority</h1>';
    print '<p><a href="./edit_auths.php">Edit an existing Authority record instead &raquo;</a></p>';

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
    print '<form action="#" method="POST">';
    print '<input type="hidden" name="handler" value="void"/>';
    foreach (cols_editable as $field => $traits )
    {
        print '<div class="fl-pair">';
        $tag = $field;
        $label = '<label for="'.$tag.'">'.$traits["label"].'</label>';
        $idnty = 'id="'.$tag.'" name="'.$tag.'"';
        $constraints = $traits["constraints"];
        
        print $label;
        print '<input '.$idnty.
                ' type="'.$traits["type"].'"'.
                ' value=""'.
                ' '.$constraints.'>';
        print '</div>';
    }
    print '<input type="submit"/>';
    print '</form>';
}

function handle_form()
{
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare(
        "INSERT INTO eecs647.authnam(code, fname, expl) " .
        "VALUES (?, ?, ?)"
    );
    $stmt->bindParam(
        1,
        $_POST['code']
    );
    $stmt->bindParam(
        2,
        $_POST['fname']
    );
    $stmt->bindParam(
        3,
        $_POST['expl']
    );
    @$stmt->execute();
    $errs[] = $stmt->errorInfo();
    if (sizeof($errs) > 0)
    {
        if ( isset( $errs[0][0] ) && $errs[0][0] != 0 )
        {
            $erref = $errs;
            $errs = [];
            
            if ($erref[0][1] == 1062)
                $errs[] = "Authority '" . $args['code'] . "' already exists.";
            else 
                $errs[] = "Unknown SQL error(s) occurred.<pre>" . print_r($erref) . '</pre>';
        } else {
            $errs = [];
            $errs[] = "Authority created successfully.";
        }
    }

    while (sizeof($errs) > 0)
    {
        $err = array_pop($errs);
        if (is_array($err))
            $err = implode('<br/>', $err);
        print '<p>';
        print $err;
        print '</p>';
    }
    return;
}

function print_err_privs()
{
    print '<p><b>Error:</b><i> User is not logged in or is not privileged to this page.</i></p>';
}

do_page();