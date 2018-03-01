<?php

// Olli Kärki

class ReviewController extends BaseController {

    public static function review() {

        $params = $_POST;

        $score = $params['score'];
        $content = $params['content'];
        $id = $params['id'];

        self::check_logged_in();
        
        $userid = User::find($_SESSION['username'], $_SESSION['password'])->id;

        $error = Review::validate($content);
        
        if (count($error) == 0) {
            
            Review::saveorupdate($id, $userid, $score, $content);
            Redirect::to('/game/' . $id);
            
        } else {
            
            Redirect::to('/game/' . $id, array('error' => $error, 'previous' => $content, 'rScore' => $score));
            
        }
    }

    public static function deleteReview($gameid, $reviewid) {

        if(!ctype_digit(strval($gameid)) && !ctype_digit(strval($reviewid))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }

        $admin = BaseController::get_admin_logged_in();

        if ($admin) {
            Review::delete($reviewid);
        } else {
            if(BaseController::get_user_logged_in()){
                $user = User::find($_SESSION['username'], $_SESSION['password']);
                Review::deleteFromUser($reviewid, $user->id);
            }
            
        }
        
        Redirect::to('/game/' . $gameid);

    }

}
