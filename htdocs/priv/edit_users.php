<?php 

require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN'
];

const cols_editable = [
    "user" => [
        "label"       => "Username",
        "type"        => "text",
        "constraints" => 'maxlength="10" required'
    ],
    "auth" => [
        "label"       => "Authority Code",
        "type"        => "text",
        "constraints" => 'maxlength="10" required'
    ]
];

function do_page()
{
    \eecs647\print_html_opener('Editing User Privileges');
    
    print '<section>';
    print '<h1>Manage User Privileges</h1>';
    print '<p>To add a user, use the sign-up link provided in the footer.</p>';

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

    // Get User List
    $stmt = $conn->prepare("select users.uname, userauth.auth from eecs647.users LEFT JOIN eecs647.userauth ON users.uname = userauth.user;");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get Auth List
    $stmt = $conn->prepare("select code from eecs647.authnam;");
    $stmt->execute();
    $auths = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $prepared = [];

    $temp = [];
    for ($i = 0; $i < sizeof($auths); $i++)
    {
        $temp[] = array_values($auths[$i])[0];
    }
    $auths = $temp;
    
    foreach ($users as $user)
    {
        $prepared[$user['uname']][] = $user['auth'];
    }
    $keys = array_keys($prepared);
    

    print '<form action="#" method="POST">';
    print '<input type="hidden" name="handler" value="void"/>';
    for ($i =0; $i<sizeof($prepared); $i++)
    {
        $has = $prepared[$keys[$i]];
        if (is_null($has[0]))
            $has = [];
        $lacks = array_diff($auths, $has);
        $lacks = array_values($lacks);

        print '<fieldset class="users">';
        print '<legend>' . $keys[$i]. '</legend>';
        print '<table class="userpriv">';
        print '<thead><tr>';
        print '<th>Has</th>';
        print '<th>Lacks</th>';
        print '</tr></thead>';
        print '<tbody>';

        for ($e = 0; $e < max(sizeof($has), sizeof($lacks)); $e++) 
        {
            print '<tr>';

            print '<td>';
            if (isset($has[$e]))
            {
                print '<input type="checkbox" id="'.$keys[$i].'_r_'.$has[$e].'" name="'.$keys[$i].'_r_'.$has[$e].'">';
                print '<label for="'.$keys[$i].'_r_'.$has[$e].'">Revoke '.$has[$e].'</label>';
            }
            print '</td>';

            print '<td>';
            if (isset($lacks[$e]))
            {
                print '<input type="checkbox" id="'.$keys[$i].'_g_'.$lacks[$e].'" name="'.$keys[$i].'_g_'.$lacks[$e].'">';
                print '<label for="'.$keys[$i].'_g_'.$lacks[$e].'">Grant '.$lacks[$e].'</label>';
            }
            print '</td>';

            print '</tr>';
        }

        print '</tbody></table>';
        print '</fieldset>';
    }
    print '<input type="submit"/>';

    print '</form>';
}

function handle_form()
{
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $grants  = [];
    $revokes = [];
    $crawl = array_keys($_POST);
    $g = '_g_';
    $gl = strlen($g);
    $r = '_r_';
    $rl = strlen($r);

    for ($i = 0; $i < sizeof($crawl); $i++)
    {
        # Get grants
        if (strpos($crawl[$i] , '_g_') !== false)
        {
            $grants[] = [
                'user' => substr($crawl[$i], 0, strpos($crawl[$i], '_g_')), 
                'auth' => substr($crawl[$i], strpos($crawl[$i], '_g_') + $gl)
            ];
        }

        # Get revokes
        if (strpos($crawl[$i] , '_r_') !== false)
        {
            $revokes[] = [
                'user' => substr($crawl[$i], 0, strpos($crawl[$i], '_r_')), 
                'auth' => substr($crawl[$i], strpos($crawl[$i], '_r_') + $rl)
            ];
        }
    }

    # STATEMENT 1 - GRANT
    for ($i = 0; $i < sizeof($grants); $i++)
    {
        $stmt = $conn->prepare(
            "INSERT INTO eecs647.userauth(user, auth) " .
            "VALUES (?, ?)"
        );
        $stmt->bindParam(
            1,
            $grants[$i]['user']
        );
        $stmt->bindParam(
            2,
            $grants[$i]['auth']
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
                    $errs[] = "User '" .$grants[$i]['user']. "' already has authority '" .$grants[$i]['auth']. "'.";
                else 
                    $errs[] = "Unknown SQL error(s) occurred.<pre>" . print_r($erref) . '</pre>';
            } else {
                $errs = [];
                $errs[] = "User '" .$grants[$i]['user']. "' was granted authority '" .$grants[$i]['auth']. "'";
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
    }

    # STATEMENT 2 - REVOKE
    for ($i = 0; $i < sizeof($revokes); $i++)
    {
        $stmt = $conn->prepare(
            "DELETE FROM eecs647.userauth " .
            "WHERE user = ? AND auth = ?"
        );
        $stmt->bindParam(
            1,
            $revokes[$i]['user']
        );
        $stmt->bindParam(
            2,
            $revokes[$i]['auth']
        );
        @$stmt->execute();
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
                $errs[] = "User '" . $revokes[$i]['user'] . "' no longer has authority '" .$revokes[$i]['auth']. "'";
            }
        }
    }

    print '<p><a href="./edit_users.php">&laquo; Go Back</a></p>';

    return;
}

function print_err_privs()
{
    print '<p><b>Error:</b><i> User is not logged in or is not privileged to this page.</i></p>';
}

do_page();