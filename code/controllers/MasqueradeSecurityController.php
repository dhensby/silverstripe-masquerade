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

    /**
     * Returns the SS_HTTPResponse object that this controller is building up.
     * Can be used to set the status code and headers
     */
    public function getResponse() {
        if (!$this->response) {
            $this->setResponse(new SS_HTTPResponse());
        }
        return $this->response;
    }

    /**
     * Sets the SS_HTTPResponse object that this controller is building up.
     *
     * @param SS_HTTPResponse $response
     * @return Controller
     */
    public function setResponse(SS_HTTPResponse $response) {
        $this->response = $response;
        return $this;
    }

}
