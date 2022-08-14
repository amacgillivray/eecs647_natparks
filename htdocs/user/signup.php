<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

use function \eecs647\print_html_opener;
use function \eecs647\print_html_closer;
use function \eecs647\determine_average_image_color;
use function \eecs647\determine_readable_text_color_for_background;

const cols_editable = [
    "uname" => [
        "label"       => "Username",
        "type"        => "text",
        "constraints" => 'maxlength="10" required'
    ],
    "up1" => [ 
        "label"       => "Password",
        "type"        => "password",
        "constraints" => 'maxlength="64" required'
    ],
    "up2" => [
        "label"       => "Password Again",
        "type"        => "password",
        "constraints" => 'maxlength="64" required'
    ]
];

function print_page( $post )
{    
    print '<section id="signup">';
    print '<h1>Sign Up</h1>';

    if (isset($_COOKIE['lid'])) {
        print '<p>You are already signed in. If you would like to create another profile, please log out.</p>';
    }
    else if (isset($post['uname']))
        handle_signup_form( $post );
    else 
        print_signup_form();

    print '</section>';
}

function print_signup_form( array $errs = [] )
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

function handle_signup_form( $args )
{
    $errs=[];
    if (!isset($args['uname']))
        $errs[] = "Username cannot be empty.";
    if (!isset($args['up1']))
        $errs[] = "Password field 1 cannot be empty.";
    if (!isset($args['up2']))
        $errs[] = "Password field 2 cannot be empty.";
    if ($args['up1'] != $args['up2'])
        $errs[] = "Passwords do not match.";
    
    if (sizeof($errs) > 0)
    {
        print_signup_form($errs);
        return;
    }

    $args['up1'] = password_hash( $args['up1'], PASSWORD_DEFAULT );
    
    try {
        $conn = new PDO( 'odbc:eecs647' );
        // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        throw $e;
    }
    $stmt = $conn->prepare("{CALL eecs647.crtusr(?,?)}");
    $stmt->bindParam(
        1,
        $args['uname']
    );
    $stmt->bindParam(
        2,
        $args['up1']
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
                $errs[] = "Username '" . $args['uname'] . "' already exists.";
            else 
                $errs[] = "Unknown SQL error(s) occurred.<pre>" . print_r($erref) . '</pre>';
            print_signup_form($errs);
            return;
        } else {
            $errs = [];
        }
    }

    setcookie(
        'lid',
        openssl_encrypt(
            $args['uname'],
            'AES-256-CBC',
            'thisismykeyIhopeyoulikeit',
            0,
            'thisSiteIsNotSec'
        ),
        0,
        '/'
    );

    print_success_msg();
    return;
}

function print_success_msg()
{
    print '<h1>Your profile has been created successfully.</h1>';
    print '<p>You are now logged in.<p>';
    print '<p><a href="/">&laquo; Go Home</a></p>';
}


print_html_opener('Sign Up');
print_page($_POST);
print_html_closer();