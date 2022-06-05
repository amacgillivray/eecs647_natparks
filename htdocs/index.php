<?php 
require_once(dirname(__FILE__, 2) . "/lib.php");
\eecs647\print_html_opener('American Parks');
?>

        <section>
            <h1>American Park Information System</h1>
            <p>This website provides information about three national parks located in the continental united states. While some of the information provided is accurate, the legitimacy of the information was not the focus of the project and
               this site should not be quoted as a legitimate source.</p>
            <h2>Accounts / Roles</h2>
            <p>By default, the following accounts exist with the described privileges:</p>
            <pre>
                Username   Password   Role
                --------   --------   ----
                Alice      abc123     Admin
                Bob        123abc     Ranger
                Eve        1a2b3c     Zoologist
            </pre>
            <p>The admin user can create new users, delete existing users, and manage user privileges, in addition to modifying any/all types of data.</p>
            <p>The ranger can view and edit entries relating to parks.</p>
            <p>The zoologist can view and edit entries relating to fauna.</p>

            <p><b>To use admin pages, you'll need to <a href="user/login">log in.</a></b></p>
        </section>

<?php 
\eecs647\print_html_closer();
