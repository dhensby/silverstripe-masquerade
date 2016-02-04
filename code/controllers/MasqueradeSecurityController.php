<?php

class MasqueradeSecurityController extends Security {

    private static $allowed_actions = array(
        'logout',
    );

    public function logout($redirect = true)
    {
        if (Session::get('Masquerade.Old.loggedInAs')) {
            $oldSession = Session::get('Masquerade.Old');
            Session::clear_all();
            foreach ($oldSession as $name => $val) {
                Session::set($name, $val);
            }
            if($redirect && (!$this->getResponse()->isFinished())) {
                $this->redirectBack();
            }
        }
        else {
            parent::logout($redirect);
        }
    }

}
