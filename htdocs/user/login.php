<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

use function \eecs647\print_html_opener;
use function \eecs647\print_html_closer;
use function \eecs647\encrypt_lid;

const cols_editable = [
    "uname" => [
        "label"       => "Userame",
        "type"        => "text",
        "constraints" => 'maxlength="10" required'
    ],
    "up1" => [ 
        "label"       => "Password",
        "type"        => "password",
        "constraints" => 'maxlength="64" required'
    ]
];

function print_page( $post )
{    
    print '<section id="login">';
    print '<h1>Log In:</h1>';

    if (isset($_COOKIE['lid']))
    {
        print '<p>Error: User is already logged in. Please log out before logging in again.</p>';
        print '</section>';
        return;
    }

    if (isset($post['errs']))
        print_login_form(); 
    else if (isset($post['uname']))
        handle_login_form( $post );
    else 
        print_login_form();

    print '</section>';
}

function print_login_form( array $errs = [] )
{
    while (sizeof($errs) > 0)
    {
        $err = array_pop($errs);
        if (is_array($err))
            $err = implode('<br/>', $err);
        print '<p><b>ERROR:</b><i> ';
        print $err;
        print '</i></p>';
    }

    print '<form action="#" method="post">';
    foreach (cols_editable as $field => $traits)
    {
        print '<div class="fl-pair">';
        
        print '<label ';
        print 'for="'.$field.'">';
        print $traits['label']; 
        print '</label>';

        print '<input ';
        print 'id="';
        print $field;
        print '" ';
        print 'name="';
        print $field;
        print '" ';
        print 'type="';
        print $traits["type"];
        print '" ';
        print $traits['constraints'];
        print '</input>';

        print '</div>';
    }
    print '<input type="submit"/>';
    print '</form>';
}

function handle_login_form( $args )
{
    $errs=[];
    if (!isset($args['uname']) || empty($args['uname']))
        $errs[] = "Username cannot be empty.";
    if (!isset($args['up1']) || empty($args['up1']))
        $errs[] = "Password field 1 cannot be empty.";
    
    if (sizeof($errs) > 0)
    {
        print_login_form( $errs );
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

    $stmt = $conn->prepare("SELECT * FROM eecs647.users WHERE UPPER(users.uname) = UPPER(?) LIMIT 1;");
    $stmt->bindParam(
        1,
        $args['uname']
    );
    @$stmt->execute();

    $errs[] = $stmt->errorInfo();
    if (sizeof($errs) > 0)
    {
        if ($errs[0][0] != 0) {
            var_dump($errs);
            print_login_form( $errs );
            return;
        } else {
            $errs = [];
        }
    } 

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (sizeof($rows) < 1)
        $errs[] = "Incorrect username or password. ";
    if (isset($rows[0]) && !password_verify($args['up1'], $rows[0]['pwrd']))
        $errs[] = "Incorrect username or password. ";
    
    if (sizeof($errs) > 0)
    {
        print_login_form( $errs );
        return;
    }

    setcookie(
        'lid',
        encrypt_lid($args['uname']),
        0,
        '/'
    );
    header('Location: /');
}

print_html_opener('Sign Up');
print_page($_POST);
print_html_closer();