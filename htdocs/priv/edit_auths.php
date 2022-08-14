<?php 

require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN'
];

const cols_editable = [
    "code" => [
        "label"       => "Authority Code",
        "type"        => "text",
        "constraints" => 'maxlength="10" readonly="readonly"'
    ],
    "fname" => [
        "label"       => "Detailed Name",
        "type"        => "text",
        "constraints" => 'maxlength="63"'
    ],
    "expl" => [ 
        "label"       => "Description",
        "type"        => "text",
        "constraints" => 'maxlen="255"'
    ]
];

function do_page()
{
    \eecs647\print_html_opener('Edit Authorities');
    
    print '<section>';
    print '<h1>Editing Authorities</h1>';
    print '<p><a href="./add_auths.php">Add a new Authority record instead &raquo;</a></p>';

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
    $stmt = $conn->prepare("select * from eecs647.authnam;");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i = 0;
 
    print '<form action="#" method="POST">';
    print '<p><b>To apply changes to a record, please check the box in its fieldset labeled "MODIFY".</b></p>';
    print '<input type="hidden" name="handler" value="void"/>';
    foreach ( $rows as $auth_idx => $auth )
    {
        print '<fieldset>';
        print '<legend>' . $auth['code'] . '</legend>';


        print '<label for="'.$i.'_mod">Modify</label>';
        print '<input type="checkbox" id="'.$i.'_mod" name="'.$i.'_mod">';

        foreach (cols_editable as $field => $traits )
        {
            print '<div class="fl-pair">';
            $tag = $auth_idx . "_" . $field;
            $value = $auth[$field];
            $label = '<label for="'.$tag.'">'.$traits["label"].'</label>';
            $idnty = 'id="'.$tag.'" name="'.$tag.'"';
            $constraints = $traits["constraints"];
            
            print $label;
            print '<input '.$idnty.
                  ' type="'.$traits["type"].'"'.
                  ' value="'.$value.'"'.
                  ' '.$constraints.'>';
            print '</div>';
        }
        print '</fieldset>';
        $i++;
    }
    print '<input type="hidden" name="count" value="'.$i.'"/>';
    print '<input type="submit"/>';
    print '</form>';
}

function handle_form()
{
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $e = 0;

    for ($i = 0; $i < intval($_POST['count']); $i++)
    {
        if (isset($_POST[$i.'_mod'])) {

            $stmt = $conn->prepare(
                "UPDATE eecs647.authnam " . 
                "SET code = ?, fname = ?, expl = ? " .
                "WHERE code = ?;"
            );
            $stmt->bindParam(
                1,
                $_POST[$i . '_code']
            );
            $stmt->bindParam(
                2,
                $_POST[$i . '_fname']
            );
            $stmt->bindParam(
                3,
                $_POST[$i . '_expl']
            );
            $stmt->bindParam(
                4,
                $_POST[$i . '_code']
            );
            $stmt->execute();
            $errs[] = $stmt->errorInfo();
            if (sizeof($errs) > 0)
            {
                if ( isset( $errs[0][0] ) && $errs[0][0] != 0 )
                {
                    $erref = $errs;
                    $errs = [];
                    $errs[] = "Unknown SQL error(s) occurred.<pre>" . print_r($erref) . '</pre>';
                } else {
                    $errs = [];
                    $errs[] = "Authority edited successfully.";
                }
            }

            print '<h3>'. $_POST[$i . '_fname'] .' (' . $_POST[$i . '_code'] . '): </h3>';
            print '<p>';
            while (sizeof($errs) > 0)
            {
                $err = array_pop($errs);
                if (is_array($err))
                    $err = implode('<br/>', $err);
                print $err;
                print '<br/>';
            }
            print '</p>';
            $e++;
        }
    }
    if ($e === 0)
    {
        print '<p>NOTICE: No entries were modified.</p>';
    }
    print '<p><a href="/priv/edit_auths.php">Refresh &raquo;</a></p>';
    return;
}


function print_err_privs()
{
    print '<p><b>Error:</b><i> User is not logged in or is not privileged to this page.</i></p>';
}


// var_dump($_POST);
do_page();