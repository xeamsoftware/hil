<?php

if(!function_exists('getMyLimitedNotifications'))
{
	function getMyLimitedNotifications($staticPic,$profilePicPath,$userId,$limit = false)
	{
		$notifications = DB::table('notifications as n')
                    ->join("users as u",'n.sender_id','=','u.id')
                    ->where(['n.receiver_id'=>$userId,'n.status'=>'1'])
                    ->select('n.id','n.read_status',"u.first_name","u.last_name","u.middle_name",'n.label','n.message','n.created_at',DB::raw('CASE WHEN u.profile_pic = "" OR u.profile_pic IS NULL THEN "'.$staticPic.'" ELSE CONCAT("'.$profilePicPath.'",u.profile_pic) END AS profile_pic'));

    if(empty($limit)){
    	$data = $notifications->orderBy('n.created_at','desc')->get();
    }else{
    	$data = $notifications->orderBy('n.created_at','desc')->limit($limit)->get();
    }

    return $data;
	}
    function checkLastAuthorityForUser($user_id, $supervisor_id){
        $data = DB::table('leave_approval_authorities')
                    ->select('user_id', 'supervisor_id')
                    ->where('user_id', $user_id)
                    ->orderBy('priority', 'desc')
                    ->limit(1)
                    ->get();

        if(isset($data[0])){
            if($data[0]->supervisor_id == $supervisor_id){
                return true;
            }
        }
        return false;
    }
}



?>
