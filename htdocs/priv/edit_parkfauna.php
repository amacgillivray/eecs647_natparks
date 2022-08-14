<?php 
require_once '../../lib.php';

const auths = [
    'ADMIN',
    'ZOO',
    'RGR'
];

function get_entries() : array
{
    $query = <<< 'PF_FETCH'
    SELECT eecs647.parks.pname, 
           eecs647.fauna.name,
           eecs647.parkfauna.id, 
           eecs647.parkfauna.code 
    FROM eecs647.parkfauna
    LEFT JOIN eecs647.parks ON eecs647.parks.id = eecs647.parkfauna.id
    LEFT JOIN eecs647.fauna ON eecs647.fauna.code = eecs647.parkfauna.code
    GROUP BY eecs647.parks.pname, eecs647.fauna.name, eecs647.parkfauna.id, eecs647.parkfauna.code
    ORDER BY parks.pname, fauna.name
    PF_FETCH;

    $conn = new PDO('odbc:eecs647');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $entries;
}

function handle_edits()
{
    $query = <<<'PF_EDIT'
    UPDATE eecs647.parkfauna
    SET id = ?, code = ?
    WHERE id = ? AND code = ?
    PF_EDIT;

    $delete = <<<'PF_DELETE'
    DELETE FROM eecs647.parkfauna WHERE id = ? AND code = ?
    PF_DELETE;


    $keys = array_filter(
        array_keys($_POST),
        function($field){
            return (strpos($field,'p_') !== false);
        });
    $keys = array_values($keys);
    $fields = [];
    $edits = 0;

    $conn = new PDO('odbc:eecs647');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();
    try {
        for ($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $tgt = $_POST[$key];
            $e = strrpos($key, '_');
            $fields[substr($key, 0, $e)][] = $tgt;
        }

        $keys = array_keys($fields);
        for ($i = 0; $i < sizeof($fields); $i++)
        {
            $old_park = substr(
                $keys[$i],
                2,
                strpos($keys[$i], '_s_')-2
            );

            $old_fauna = substr(
                $keys[$i],
                strpos($keys[$i], '_s_')+3
            );

            $fieldset = $fields[$keys[$i]];

            # Skip values that have not been changed
            if ($old_park === $fieldset[0] && $old_fauna === $fieldset[1])
                continue;

            // TODO - DELETION
//            if (!isset($fieldset[2]))
            $stmt = $conn->prepare($query);
            if ($stmt->execute([$fieldset[0], $fieldset[1], $old_park, $old_fauna]))
                $edits++;
        }

    } catch (Exception $e) {
        $conn->rollBack();
        setcookie('pgmsg', $e->getMessage(), time()+30, '/');
        header('Location: /priv/edit_parkfauna.php');
    }
    $conn->commit();
    setcookie('pgmsg', "Successfully modified $edits rows.", time()+30, '/');
    header('Location: /priv/edit_parkfauna.php');
}

function handle_add()
{
//    $query =

//    $conn = new PDO('odbc:eecs647');
//    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $stmt = $conn->prepare($query);
//    $stmt->execute();
}

function print_form()
{
    $entries = get_entries();

    print <<<'EPF_FORM_OPEN'
    <article>
    <h3>Edit Existing Relationships</h3>
    <p>You can edit and delete existing Park-Fauna relationships using the fields below and the submit button at the bottom of the page.</p>
    <form id="editparkfauna" action="#" method="POST">
        <input type="hidden" name="edit_handler" value="void"/>
    EPF_FORM_OPEN;


    print '<form id="editparkfauna" action="#" method="POST">';
    for ($i=0;$i<sizeof($entries);$i++) {
        $row = $entries[$i];
        $park_name = $row['pname'];
        $fauna_name = $row['name'];
        $park = $row['id'];
        $spec = $row['code'];
        $token = "p_${park}_s_${spec}";

        print <<<LoopInputs1
        <fieldset id="$token" class="parkfauna">
            <legend>$park_name -> $fauna_name</legend>
            <div class="fl-pair fl-pair-text">
                <label for="${token}_park">Park ID</label>
                <input id="${token}_park" name="${token}_park" type="text" maxlength="10" value="$park">
            </div>
            <div class="fl-pair fl-pair-text">
                <label for="${token}_spec">Fauna Code</label>
                <input id="${token}_spec" name="${token}_spec" type="text" maxlength="10" value="$spec">
            </div>
            <div class="fl-pair fl-pair-checkbox"> <input id="${token}_del" name="${token}_del" type="checkbox">
                <label for="${token}_del">Delete Relationship</label></div>
        </fieldset>
        LoopInputs1;
    }
    print '<input type="Submit" value="Apply Changes &raquo;">';
    print '</form></article>';
}

function do_page()
{
//    var_dump($_POST);

    if (isset($_POST['add_handler']))
    {
        handle_add();
    }

    if (isset($_POST['edit_handler']))
    {
        handle_edits();
    }

    \eecs647\print_html_opener('Edit Park-Fauna Relationships');

    // Check Privileges
    if (!\eecs647\authorized_user(auths))
    {
        print '<section id="pf">';
        \eecs647\print_err_privs();
        print '</section>';
        \eecs647\print_html_closer();
        exit();
    }

    # Todo - Check for POST arguments and handle form submission

    $msg = (isset($_COOKIE['pgmsg'])) ? '<p><b style="color:coral">OPERATION HAD EFFECT:</b><br/>'.$_COOKIE['pgmsg'].'</p>' : '';

    // Show the form to edit parks
    print <<<PAGEOPENER
    <section id="pf">
    <h1>Editing Park-Fauna Relationships</h1>
    <p>Each Park -> Species Relationship indicates that, within the designated park, the specified species can be found.</p>
    $msg
    <article>
        <form id="new_pf" action="#" method="POST">
            <input type="hidden" name="add_handler" value="void"/>
            <h3>Add New Relationship</h3>
            <p>You can add a new Park-Fauna relationship using these fields and the submit button below. Note that submitting will lose any unsaved changes 
                the row-editor form below the submit button.</p>
            <div class="fl-pair fl-pair-text">
                <label for="newpf_park">Park ID</label>
                <input id="newpf_park" name="newpf_park" type="text" maxlength="10" value="">
            </div>
            <div class="fl-pair fl-pair-text">
                <label for="newpf_spec">Fauna Code</label>
                <input id="newpf_spec" name="newpf_spec" type="text" maxlength="10" value="">
            </div>
            <input type="Submit" value="Add New Relationship &raquo;">
        </fieldset>
    </article>
    PAGEOPENER;
    print_form();
    print '</section>';

    \eecs647\print_html_closer();
}

do_page();
