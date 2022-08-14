<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN',
    'ZOO'
];

const cols_editable = [
    "code" => [
        "label"       => "code",
        "type"        => "text",
        "constraints" => 'maxlength="10"'
    ],
    "name" => [
        "label"       => "Name",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ],
    "class" => [ 
        "label"       => "Description",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ],
    "order" => [
        "label"       => "2-Digit State Code",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ],
    "suborder" => [
        "label"       => "Date Founded",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ], 
    "family" => [
        "label"       => "Family",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ],
    "subfamily" => [
        "label"       => "Sub-Family",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ],
    "genus" => [
        "label"       => "Genus",
        "type"        => "text",
        "constraints" => 'maxlength="32"'
    ],
    "homerange_max" => [
        "label"       => "Max. Home Range (Sq. Mi)",
        "type"        => "number",
        "constraints" => 'min="0" max="9999" step="1"'
    ],
    "homerange_min"  => [ 
        "label"       => "Min. Home Range (Sq. Mi)",
        "type"        => "number",
        "constraints" => 'min="0" max="9999" step="1"'
    ],
    "weight_m"  => [
        "label"       => "Weight (Male)",
        "type"        => "number",
        "constraints" => 'min="0" max="9999" step="1"'
    ],
    "weight_f"  => [
        "label"       => "Weight (Female)",
        "type"        => "number",
        "constraints" => 'min="0" max="9999" step="1"'
    ],
    "height_cm"  => [
        "label"       => "Height (cm)",
        "type"        => "number",
        "constraints" => 'min="0" max="999" step="1"'
    ],
    "length_cm"  => [
        "label"       => "Length (cm)",
        "type"        => "number",
        "constraints" => 'min="0" max="999" step="1"'
    ],
    "lifespan"  => [
        "label"       => "Lifespan (Years)",
        "type"        => "number",
        "constraints" => 'min="0" max="999" step="1"'
    ],
    "endangered"  => [
        "label"       => "Endangered",
        "type"        => "number",
        "constraints" => 'min="0" max="5"'
    ],
    "fdesc" => [ 
        "label"       => "Description",
        "type"        => "textarea",
        "constraints" => 'rows="20" cols="85"'
    ]
];

function print_form_values_for_fauna_table(
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

try {
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    throw $e;
}
$stmt = $conn->prepare("select * from eecs647.fauna;");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
\eecs647\print_html_opener('Edit Fauna');

// Show the form to edit fauna
print '<section id="fauna">';
print '<h1>Editing Fauna</h1>';

if (!\eecs647\authorized_user(auths))
{
    \eecs647\print_err_privs();
    print '</section>';
    \eecs647\print_html_closer();
    exit();
}

print '<form>';
foreach ( $rows as $fauna )
{
    print_form_values_for_fauna_table(
        cols_editable,
        $fauna,
        "id".$fauna['code'],
        '<fieldset class="park"><legend>Animal: '.$fauna["code"].' (' . $fauna['name'] . ')</legend>',
        '</fieldset>'
    );
}
print '</form>';
print '</section>';

\eecs647\print_html_closer();