<?php
//use DB;

//$chatMsgLimit = 5;

function chatMsgLimit(){
	return 2;
}

function pr($data=array()){
    echo '<pre>';
    print_r($data);
    echo '</pre>';

}
function prd($data=array()){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;

}
function echo_die($string=array()){
    echo $string; die;

}

function generateToken($num){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $num; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/* Common function */


function randomNumber($qtd){
	$Caracteres = '0123456789';
	$QuantidadeCaracteres = strlen($Caracteres);
	$QuantidadeCaracteres--;

	$ransom_num=NULL;
	for($x=1;$x<=$qtd;$x++){
		$Posicao = rand(0,$QuantidadeCaracteres);
		$ransom_num .= substr($Caracteres,$Posicao,1);
	}

	return $ransom_num;
}


function dateTimeInterval($data){
	$result = '';
	$datetime1 = new DateTime($data);
		$datetime2 = new DateTime();
		$interval = $datetime1->diff($datetime2);

		//prd($interval);

		$year = $interval->format('%y');
		$month = $interval->format('%m');
		$days = $interval->format('%d');
		$hours = $interval->format('%h');
		$minutes = $interval->format('%i');
		$seconds = $interval->format('%s');


		if($year > 0){
			$result = $year.' Year';
			if($year > 1){ $result = $result.'s'; }
		}
		elseif($month > 0){
			$result = $month.' Month';
			if($month > 1){ $result = $result.'s'; }
		}
		elseif($days > 0){
			$result = $days.' Day';
			if($days > 1){ $result = $result.'s'; }
		}
		elseif($hours > 0){
			$result = $hours.' Hour';
			if($hours > 1){ $result = $result.'s'; }
		}
		elseif($minutes > 0){
			$result = $minutes.' Minute';
			if($result > 1){ $result = $result.'s'; }
		}
		elseif($seconds > 0){
			$result = $seconds.' Second';
			if($seconds > 1){ $result = $result.'s'; }
		}

		return $result;
	}

    function slugify($text)
    {
        // echo $text; die;
        // replace non letter or digits by -
        $text = preg_replace ( '~[^\pL\d]+~u', '-', $text );

        // transliterate
        $text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );

        // remove unwanted characters
        $text = preg_replace ( '~[^-\w]+~', '', $text );

        // trim
        $text = trim ( $text, '-' );

        // remove duplicate -
        $text = preg_replace ( '~-+~', '-', $text );

        // lowercase
        $text = strtolower ( $text );
        // echo $text; die;
        if (empty ( $text )) {
            // return 'n-a';
        }
        // echo $text; die;
        return $text;
    }

    function formShortCode($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$cms_short_code = \App\Helpers\CustomHelper::form_short_code($params_data);
    	return $cms_short_code;
    }

    function packageSection($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::packageSection($params_data);
    	return $section_code;
    }

    function themeCategoriesSection($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::themeCategoriesSection($params_data);
    	return $section_code;
    }

    function testimonialSection($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::testimonialSection($params_data);
    	return $section_code;
    }

    function accommodationSection($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::accommodationSection($params_data);
    	return $section_code;
    }

    function blogSection($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::blogSection($params_data);
    	return $section_code;
    }

    function teamSection($params_data = '') {
    	if (empty($params_data)) {
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::teamSection($params_data);
    	return $section_code;
    }

    function staticSection($params_data = '') {
    	if(empty($params_data)){
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::staticSection($params_data);
    	return $section_code;
    }

    function destinationSection($params_data = '') {
    	if(empty($params_data)){
    		$params_data = [];
    	}
    	$section_code = \App\Helpers\CustomHelper::destinationSection($params_data);
    	return $section_code;
    }

    function cmsSection($params_data = '') {
        if(empty($params_data)){
            $params_data = [];
        }
        $section_code = \App\Helpers\CustomHelper::cmsSection($params_data);
        return $section_code;
    }


/* End - Common function */

?>
