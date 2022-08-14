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

function print_form()
{
    $entries = get_entries();

    print <<<'EPF_FORM_OPEN'
    <article>
    <h3>Edit Existing Relationships</h3>
    <p>You can edit and delete existing Park-Fauna relationships using the fields below and the submit button at the bottom of the page.</p>
    <form id="editparkfauna" action="#" method="POST">
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

    print '</form></article>';
}

\eecs647\print_html_opener('Edit Images');

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

// Show the form to edit parks
print <<<'PAGEOPENER'
<section id="pf">
<h1>Editing Park-Fauna Relationships</h1>
<p>Each Park -> Species Relationship indicates that, within the designated park, the specified species can be found.</p>
<article>
    <form id="new_pf" action="#" method="POST">
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