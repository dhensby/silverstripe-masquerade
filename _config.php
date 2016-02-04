<?php

//turn off member tracking when masquerading
if (Session::get('Masquerade.Old.loggedInAs')) {
    Member::config()->log_last_visited = false;
}
