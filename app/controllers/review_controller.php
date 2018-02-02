<?php

// Olli Kärki

class ReviewController extends BaseController {

    public static function review() {

        $user = BaseController::get_user_logged_in();

        if (!$user) {
            Redirect::to('/games/' . $id);
        }

        $params = $_POST;

        $score = $params['score'];
        $content = $params['content'];
        $id = $params['id'];
        $userid = User::find($_SESSION['username'], $_SESSION['password'])->id;

        Review::saveorupdate($id, $userid, $score, $content);

        Redirect::to('/game/' . $id);
    }

    public static function deleteReview($gameid, $reviewid) {

        $admin = BaseController::get_admin_logged_in();

        if($admin) {
            Review::delete($reviewid);
        }

        Redirect::to('/game/' . $gameid);
    }

}
