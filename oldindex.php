<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="https://use.typekit.net/civ8eix.css">

    <style>
html, body {
    margin: 0;
    padding: 0;
    font-family: "Nimbus Sans", "Helvetica", "Arial", sans-serif;
}

p {
    font-family: "IBM Plex Sans", sans-serif;
    margin: 8px 0;
}

pre {
    font-family: "IBM Plex Mono", monospace;
}

section {
    display: flex; 
    flex-direction: column;
    max-width: 68.2%;
    width: auto;
    margin: 0 auto;
    background-color: #f8fafe;
}

section > * {
    box-sizing: border-box;
    padding: 0 16.4%;
}
    </style>
    </head>
    <body>
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
                Bob        123abc     Botanist
                Eve        1a2b3c     Zoologist
                Walter     a1b2c3     Ranger
            </pre>
            <p>The admin user can create new users, delete existing users, and manage user privileges, in addition to modifying any/all types of data.</p>
            <p>The botanist can view and edit entries relating to flora.</p>
            <p>The zoologist can view and edit entries relating to fauna.</p>
            <p>The ranger can view and edit entries relating to parks.</p>

            <p><b>To view any subpages, you'll need to <a href="user/login">log in.</a></b></p>
        </section>
        <footer></footer>
    </body>
</html>
